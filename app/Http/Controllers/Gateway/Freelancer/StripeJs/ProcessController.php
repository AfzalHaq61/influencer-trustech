<?php

namespace App\Http\Controllers\Gateway\Freelancer\StripeJs;

use App\Http\Controllers\Gateway\Freelancer\FreelancerToInfluencerPaymentController;
use App\Http\Controllers\Gateway\Freelancer\FreelancerToFreelancerPaymentController;
use App\Http\Controllers\Gateway\Freelancer\FreelancerToClientPaymentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Stripe;
use Session;


class ProcessController extends Controller
{

    public static function process($deposit)
    {
        $StripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $val['key'] = $StripeJSAcc->publishable_key;
        $val['name'] = authFreelancer()->username;
        $val['description'] = "Payment with Stripe";
        $val['amount'] = $deposit->final_amo * 100;
        $val['currency'] = $deposit->method_currency;
        $send['val'] = $val;

        $alias = $deposit->gateway->alias;

        $send['src'] = "https://checkout.stripe.com/checkout.js";
        $send['view'] = 'freelancer.payment.' . $alias;
        $send['method'] = 'post';
        $send['url'] = route('ipn.freelancer.'.$deposit->gateway->alias);
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
                FreelancerToInfluencerPaymentController::userDataUpdate($deposit);
            }elseif($userType == 'freelancer'){
                FreelancerToFreelancerPaymentController::userDataUpdate($deposit);
            }else{
                FreelancerToClientPaymentController::userDataUpdate($deposit);
            }
            
            $notify[] = ['success', 'Payment captured successfully'];
            return to_route('freelancer.home')->withNotify($notify);
        }else{
            $notify[] = ['success', 'Failed to process'];
            return to_route('freelancer.home')->withNotify($notify);
        }
    }
}
