<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class PayPalController extends Controller
{
    protected function baseUrl()
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken()
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        $response = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->ok()) {
            return $response->json()['access_token'] ?? null;
        }

        Log::error('PayPal token error', ['resp' => $response->body()]);
        return null;
    }

    public function createOrder(Request $request)
    {
        $accessToken = $this->getAccessToken();
        if (! $accessToken) {
            return response()->json(['error' => 'No access token'], 500);
        }

        // Calculate total from session cart
        $cart = Session::get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        }
        $total = number_format(max(0, $total), 2, '.', '');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => (string)$total,
                ]
            ]]
        ];

        $resp = Http::withToken($accessToken)
            ->post($this->baseUrl() . '/v2/checkout/orders', $payload);

        if ($resp->successful()) {
            return response()->json($resp->json());
        }

        Log::error('PayPal create order failed', ['body' => $resp->body()]);
        return response()->json(['error' => 'create_failed'], 500);
    }

    public function captureOrder(Request $request, $orderId)
    {
        $accessToken = $this->getAccessToken();
        if (! $accessToken) {
            return response()->json(['error' => 'No access token'], 500);
        }

        $resp = Http::withToken($accessToken)
            ->post($this->baseUrl() . "/v2/checkout/orders/{$orderId}/capture");

        if (! $resp->successful()) {
            Log::error('PayPal capture failed', ['body' => $resp->body()]);
            return response()->json(['error' => 'capture_failed'], 500);
        }

        $data = $resp->json();

        // Create our internal order and items
        DB::beginTransaction();
        try {
            $cart = Session::get('cart', []);
            if (empty($cart)) {
                // nothing to create
                DB::rollBack();
                return response()->json(['error' => 'cart_empty'], 400);
            }

            $order = Order::create([
                'order_number' => 'PP-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'status' => 'paid',
                'total_amount' => array_reduce($cart, fn($s, $i) => $s + ($i['quantity'] * $i['unit_price']), 0),
                'billing_first_name' => session('billing.first_name') ?? null,
                'billing_last_name' => session('billing.last_name') ?? null,
                'billing_email' => session('billing.email') ?? null,
                'billing_phone' => session('billing.phone') ?? null,
                'billing_address' => session('billing.address') ?? null,
                'billing_city' => session('billing.city') ?? null,
                'billing_state' => session('billing.state') ?? null,
                'billing_postal_code' => session('billing.postal_code') ?? null,
                'billing_country' => session('billing.country') ?? null,
                'shipping_first_name' => session('shipping.first_name') ?? null,
                'shipping_last_name' => session('shipping.last_name') ?? null,
                'shipping_address' => session('shipping.address') ?? null,
                'shipping_city' => session('shipping.city') ?? null,
                'shipping_state' => session('shipping.state') ?? null,
                'shipping_postal_code' => session('shipping.postal_code') ?? null,
                'shipping_country' => session('shipping.country') ?? null,
                'payment_method' => 'paypal',
                'payment_status' => 'paid',
                'payment_id' => $data['id'] ?? $orderId,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // Clear cart and billing/shipping session stubs
            Session::forget('cart');
            Session::forget('billing');
            Session::forget('shipping');

            DB::commit();

            return response()->json(['status' => 'success', 'redirect' => route('customer.order-confirmation', $order->id)]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating internal order after PayPal capture: ' . $e->getMessage());
            return response()->json(['error' => 'internal'], 500);
        }
    }
}
