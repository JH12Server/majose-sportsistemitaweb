<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Notification;
use App\Models\User;

class TestNotifications extends Command
{
    protected $signature = 'test:notifications';
    protected $description = 'Test notification system';

    public function handle()
    {
        $this->info('Testing notification system...');

        // Find a customer user
        $customer = User::where('role', 'customer')->first();
        
        if (!$customer) {
            $this->error('No customer user found');
            return 1;
        }

        $this->info("Using customer: {$customer->name} (ID: {$customer->id})");

        // Create a test order
        $order = Order::create([
            'order_number' => 'TEST-' . \Illuminate\Support\Str::random(8),
            'user_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 100.00,
        ]);

        $this->info("Created test order: {$order->order_number}");

        // Test notification creation
        try {
            $order->notifyOrderCreated();
            $this->info("✓ notifyOrderCreated() executed successfully");
        } catch (\Exception $e) {
            $this->error("✗ notifyOrderCreated() failed: " . $e->getMessage());
            return 1;
        }

        try {
            $order->notifyOrderPaid();
            $this->info("✓ notifyOrderPaid() executed successfully");
        } catch (\Exception $e) {
            $this->error("✗ notifyOrderPaid() failed: " . $e->getMessage());
            return 1;
        }

        // Check if notifications were created
        $notificationCount = Notification::where('order_id', $order->id)->count();
        $this->info("Total notifications created: {$notificationCount}");

        if ($notificationCount >= 2) {
            $this->info("✓ Notifications created successfully!");
            
            // Display created notifications
            $notifications = Notification::where('order_id', $order->id)->get();
            foreach ($notifications as $notif) {
                $this->line("  - {$notif->title}: {$notif->message}");
            }
            
            return 0;
        } else {
            $this->error("✗ Not enough notifications created (expected: 2, got: {$notificationCount})");
            return 1;
        }
    }
}
