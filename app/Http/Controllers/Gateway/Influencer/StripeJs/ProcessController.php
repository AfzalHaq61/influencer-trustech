<?php

namespace App\Http\Controllers\Gateway\Influencer\StripeJs;

use App\Models\Deposit;
use App\Http\Controllers\Gateway\Influencer\InfluencerToClientPaymentController;
use App\Http\Controllers\Gateway\Influencer\InfluencerToInfluencerPaymentController;
use App\Http\Controllers\Gateway\Influencer\InfluencerToFreelancerPaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;


class ProcessController extends Controller
{

    public static function process($deposit)
    {
        $StripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $val['key'] = $StripeJSAcc->publishable_key;
        $val['name'] = authInfluencer()->username;
        $val['description'] = "Payment with Stripe";
        $val['amount'] = $deposit->final_amo * 100;
        $val['currency'] = $deposit->method_currency;
        $send['val'] = $val;

        $alias = $deposit->gateway->alias;

        $send['src'] = "https://checkout.stripe.com/checkout.js";
        $send['view'] = 'influencer.payment.' . $alias;
        $send['method'] = 'post';
        $send['url'] = route('ipn.influencer.'.$deposit->gateway->alias);
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if ($deposit->status == 1) {
            $notify[] = ['error', 'Invalid request.'];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        $StripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);


        Stripe::setApiKey($StripeJSAcc->secret_key);

        Stripe::setApiVersion("2020-03-02");

        try {
            $customer =  Customer::create([
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken,
            ]);
        } catch (\Exception $e) {
            $notify[] = ['success', $e->getMessage()];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }

        try {
            $charge = Charge::create([
                'customer' => $customer->id,
                'description' => 'Payment with Stripe',
                'amount' => $deposit->final_amo * 100,
                'currency' => $deposit->method_currency,
            ]);
        } catch (\Exception $e) {
            $notify[] = ['success', $e->getMessage()];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }

        $userType = session('userType');
        
        if ($charge['status'] == 'succeeded'){
            if($userType == 'influencer'){
                InfluencerToInfluencerPaymentController::userDataUpdate($deposit);
            }elseif($userType == 'freelancer'){
                InfluencerToFreelancerPaymentController::userDataUpdate($deposit);
            }else{
                InfluencerToClientPaymentController::userDataUpdate($deposit);
            }
            
            $notify[] = ['success', 'Payment captured successfully'];
            return to_route('influencer.home')->withNotify($notify);
        }else{
            $notify[] = ['success', 'Failed to process'];
            return to_route('influencer.home')->withNotify($notify);
        }
    }
}
