<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminTransactionManage;
use App\Models\AdminNotification;
use App\Models\Freelancer;
use App\Models\FreelancerOrder;
use App\Models\FreelancerOrderConversation;
use App\Models\FreelancerService;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FreelancerOrderController extends Controller
{

    public function all(Request $request)
    {

        $pageTitle = 'All Orders';
        $orders    = FreelancerOrder::paymentCompleted()->where('user_id', auth()->id());

        if ($request->search) {
            $search = $request->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('order_no', $search)->orWhereHas('freelancer', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $orders = $orders->with('freelancer', 'review', 'service')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.freelancer_order.list', compact('pageTitle', 'orders'));
    }

    public function detail($id)
    {
        $pageTitle = 'Order Detail';
        $order     = FreelancerOrder::where('user_id', auth()->id())->with('freelancer', 'service')->findOrFail($id);
        return view($this->activeTemplate . 'user.freelancer_order.detail', compact('pageTitle', 'order'));
    }

    public function order($id)
    {
        $service = FreelancerService::approved()
            ->whereHas('freelancer', function ($freelancer) {
                return $freelancer->active();
            })
            ->where('id', $id)
            ->firstOrFail();
        $pageTitle  = 'Order a Service';    
        $freelancer = $service->freelancer;
        return view($this->activeTemplate . 'user.freelancer_order.form', compact('pageTitle', 'service', 'freelancer'));
    }

    public function createOffer($freelancer){
        $app = app();
        $service = $app->make('stdClass');

        $service->id=0;
        $service->title='Custom Offer';
        $service->price=0;

        $pageTitle  = 'Order a Service';
        $freelancer = Freelancer::where('username',$freelancer)->first();
        return view($this->activeTemplate . 'user.freelancer_order.form', compact('pageTitle', 'service', 'freelancer'));
    }

    public function orderConfirm(Request $request, $freelancerId, $serviceId){
        if($serviceId == 0){ 
            $app = app();
            $service = $app->make('stdClass');
    
            $service->id=0;
            $service->price=$request->price;
        }else{
            $service = FreelancerService::approved()->where('id', $serviceId)->where('freelancer_id', $freelancerId)->firstOrFail();
        }

        $freelancer = Freelancer::active()->findOrFail($freelancerId);

        $request->validate([
            'title'         => 'required|string|max:255',
            'delivery_date' => 'required|date_format:Y-m-d|after:yesterday',
            'description'   => 'required|string',
            'payment_type'  => 'required|in:1,2',
        ]);

        $user = auth()->user();

        if ($request->payment_type == 1 && $service->price > $user->balance) {
            $notify[] = ['error', 'You have no sufficient balance'];
            return back()->withNotify($notify)->withInput();
        }

        $paymentStatus = $request->payment_type == 1 ? 1 : 0;
        $order = $this->saveOrderData($freelancer, $service, $paymentStatus);

        if ($request->payment_type == 1) {
            $this->payViaWallet($order, $service);
        } else {
            session()->put('payment_data', [
                'order_id' => $order->id,
                'amount' => $service->price,
            ]);

            session()->put('order_details', [
                'order_id' => $order->id,
                'amount' => $service->price,
                'order_type' => 'freelancer service order',
            ]);

            return redirect()->route('user.freelancer.payment');
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'A new order placed by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.order.detail', $order->id);
        $adminNotification->save();

        $general = gs();

        notify($freelancer, 'ORDER_PLACED', [
            'username'      => $user->username,
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
        ]);

        $notify[] = ['success', 'Your order submitted successfully'];
        return to_route('user.order.all')->withNotify($notify);
    }

    protected function saveOrderData($freelancer, $service, $paymentStatus)
    {
        $request = request();
        $user    = auth()->user();

        $order                 = new FreelancerOrder();
        $order->user_id        = $user->id;
        $order->freelancer_id  = $freelancer->id;
        $order->service_id     = $service->id;
        $order->title          = $request->title;
        $order->delivery_date  = $request->delivery_date;
        $order->amount         = $service->price;
        $order->description    = $request->description;
        $order->payment_status = $paymentStatus;
        $order->order_no       = getTrx();
        $order->save();

        return $order;
    }


    protected function payViaWallet($order, $service)
    {
        $user = auth()->user();
        $user->balance -= $service->price;
        $user->save();


        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $order->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '-';
        $transaction->trx          = getTrx();
        $transaction->details      = 'Balance deducted for ordering a service.';
        $transaction->remark       = 'order_payment';
        $transaction->save();
    }



    public function completeStatus($id)
    {
        $user          = auth()->user();
        $order         = FreelancerOrder::JobDone()->where('id', $id)->where('user_id', $user->id)->with('freelancer')->firstOrFail();
        $order->status = 1;
        $order->save();

        $freelancer = $order->freelancer;
        $general    = gs();

        $adminTransaction = AdminTransactionManage::where('order_id', $id)->firstOrFail();
        $adminTransaction->status = 3;
        $adminTransaction->save();

        $payble_amount = $adminTransaction->payble_amount;

        $freelancer->balance += $payble_amount;
        $freelancer->increment('completed_order');
        $freelancer->save();

        notify($freelancer, 'ORDER_COMPLETED_FREELANCER', [
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($payble_amount),
            'order_no'      => $order->order_no,
        ]);

        $transaction                = new Transaction();
        $transaction->freelancer_id = $freelancer->id;
        $transaction->amount        = $payble_amount;
        $transaction->post_balance  = $freelancer->balance;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Payment received for completing a new service order';
        $transaction->trx           = getTrx();
        $transaction->remark        = 'order_payment';
        $transaction->save();

        $notify[] = ['success', 'Order completed successfully'];
        return back()->withNotify($notify);
    }

    public function reportStatus(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $user          = auth()->user();
        $order         = FreelancerOrder::where('id', $id)->where('user_id', $user->id)->with('freelancer')->firstOrFail();
        $order->status = 4;
        $order->reason = $request->reason;
        $order->save();

        $freelancer = $order->freelancer;
        $general    = gs();

        notify($freelancer, 'ORDER_REPORTED', [
            'username'      => $user->username,
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
            'reason'        => $order->reason,
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'This order is reported by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.order.detail', $order->id);
        $adminNotification->save();

        $notify[] = ['success', 'You report submitted successfully. Admin will take action immediately.'];
        return back()->withNotify($notify);
    }

    public function conversation($id)
    {
        $pageTitle           = 'Order Conversation';
        $order               = FreelancerOrder::where('user_id', auth()->id())->with('orderMessage')->findOrFail($id);
        $freelancer          = Freelancer::where('id', $order->freelancer_id)->first();
        $conversationMessage = $order->orderMessage->take(10);
        return view($this->activeTemplate . 'user.freelancer_order.conversation', compact('pageTitle', 'conversationMessage', 'freelancer', 'order'));
    }

    public function conversationStore(Request $request, $id)
    {

        $order = FreelancerOrder::where('user_id', auth()->id())->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found']);
        }

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $freelancer = Freelancer::where('id', $order->freelancer_id)->first();

        $message                = new FreelancerOrderConversation();
        $message->order_id      = $order->id;
        $message->user_id       = auth()->id();
        $message->freelancer_id = $freelancer->id;
        $message->sender        = 'client';
        $message->message       = $request->message;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = fileUploader($file, getFilePath('conversation'));
                } catch (\Exception $exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }
            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();

        return view($this->activeTemplate . 'user.conversation.last_message', compact('message'));
    }

    public function conversationMessage(Request $request)
    {
        $conversationMessage = FreelancerOrderConversation::where('order_id', $request->order_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'user.conversation.message', compact('conversationMessage'));
    }
}
