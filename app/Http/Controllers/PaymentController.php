<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
class PaymentController extends Controller
{
    private $apiContext;

    public function __construct() 
{
    $paypalConfig = Config::get('paypal');

    $this->apiContext = new ApiContext(
        new OAuthTokenCredential(
            $paypalConfig['client_id'],     // ClientID
            $paypalConfig['client_secret']      // ClientSecret
        )
);
}
public function payWithPayPal()                
{
    return '123456789 mi paypal';
}

}