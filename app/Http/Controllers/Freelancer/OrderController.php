<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\AdminTransactionManage;
use App\Models\FreelancerOrder;
use App\Models\FreelancerOrderConversation;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function index() {
        $this->pageTitle = 'All Orders';
        return $this->filterOrder();
    }

    public function pending() {
        $this->pageTitle = 'Pending Orders';
        return $this->filterOrder('pending');
    }

    public function inprogress() {
        $this->pageTitle = 'Processing Orders';
        return $this->filterOrder('inprogress');
    }

    public function jobDone() {
        $this->pageTitle = 'Job Done Orders';
        return $this->filterOrder('JobDone');
    }

    public function completed() {
        $this->pageTitle = 'Completed Orders';
        return $this->filterOrder('completed');
    }

    public function reported() {
        $this->pageTitle = 'Reported Orders';
        return $this->filterOrder('reported');
    }

    public function cancelled() {
        $this->pageTitle = 'Cancelled Orders';
        return $this->filterOrder('cancelled');
    }

    protected function filterOrder($scope = null) {
        $freelancerId = authFreelancerId();
        $orders       = FreelancerOrder::query();

        if ($scope) {
            $orders = $orders->$scope();
        }

        $request = request();

        if ($request->search) {
            $search = request()->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('order_no', $search)->orWhereHas('user', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $orders = $orders->where('freelancer_id', $freelancerId)->with('user')->latest()->paginate(getPaginate());

        $pageTitle = $this->pageTitle;

        $pendingOrder = FreelancerOrder::pending()->where('freelancer_id', $freelancerId)->count();

        return view($this->activeTemplate . 'freelancer.order.list', compact('pageTitle', 'orders', 'pendingOrder'));
    }

    public function detail($id) {
        $pageTitle = 'Order Detail';
        $order     = FreelancerOrder::where('freelancer_id', authFreelancerId())->with('user', 'service','review')->findOrFail($id);
        return view($this->activeTemplate . 'freelancer.order.detail', compact('pageTitle', 'order'));
    }

    public function cancelOrder($id) {
        $freelancer    = authFreelancer();
        $order         = FreelancerOrder::where('id', $id)->where('freelancer_id', $freelancer->id)->with('user')->firstOrFail();
        $order->status = 5;
        $order->save();

        $user    = $order->user;
        $general = gs();

        $user->balance += $order->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $order->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Payment refunded for order cancellation';
        $transaction->trx          = getTrx();
        $transaction->remark       = 'order_payment';
        $transaction->save();

        notify($user, 'ORDER_CANCELLED', [
            'freelancer'    => $freelancer->username,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
            'post_balance'  => showAmount($user->balance),
            'title'         => $order->title,
        ]);

        $notify[] = ['success', 'Order canceled successfully'];
        return back()->withNotify($notify);
    }

    public function orderAccept($id) {
        
        $freelancer    = authFreelancer();
        $order         = FreelancerOrder::where('id', $id)->where('freelancer_id', $freelancer->id)->with('user', 'service')->firstOrFail();
        $order->status = 2;
        $order->save();

        $adminTransaction = AdminTransactionManage::where('order_id', $id)->firstOrFail();
        $adminTransaction->status = 1;
        $adminTransaction->save();

        $user    = $order->user;
        $general = gs();
        notify($user, 'ORDER_ACCEPT', [
            'freelancer'    => $freelancer->username,
            'site_currency' => $general->cur_text,
            'title'         => $order->title,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
        ]);

        $notify[] = ['success', 'Order accepted successfully'];
        return back()->withNotify($notify);
    }

    public function jobDoneStatus($id) {
        $freelancer    = authFreelancer();
        $order         = FreelancerOrder::where('id', $id)->where('freelancer_id', $freelancer->id)->with('user')->firstOrFail();
        $order->status = 3;
        $order->save();

        $adminTransaction = AdminTransactionManage::where('order_id', $id)->firstOrFail();
        $adminTransaction->status = 2;
        $adminTransaction->save();

        $user    = $order->user;
        $general = gs();
        notify($user, 'JOB_DONE', [
            'freelancer'    => $freelancer->username,
            'site_currency' => $general->cur_text,
            'title'         => $order->title,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
        ]);
        $notify[] = ['success', 'Order has been done successfully'];
        return back()->withNotify($notify);
    }

    public function conversation($id) {
        $pageTitle           = 'Order Conversation';
        $order               = FreelancerOrder::where('freelancer_id', authFreelancerId())->with('orderMessage')->findOrFail($id);
        $user                = User::where('id', $order->user_id)->first();
        $conversationMessage = $order->orderMessage->take(10);
        return view($this->activeTemplate . 'freelancer.order.conversation', compact('pageTitle', 'conversationMessage', 'user', 'order'));
    }

    public function conversationStore(Request $request, $id) {
        $order = FreelancerOrder::where('freelancer_id', authFreelancerId())->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found.']);
        }

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = User::find($order->user_id);

        $message                = new FreelancerOrderConversation();
        $message->order_id      = $order->id;
        $message->user_id       = $user->id;
        $message->freelancer_id = authFreelancerId();
        $message->sender        = 'freelancer';
        $message->message       = $request->message;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = fileUploader($file, getFilePath('conversation'));
                } catch (\Exception$exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }

            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();
        return view($this->activeTemplate . 'user.conversation.last_message', compact('message'));
    }

    public function conversationMessage(Request $request) {
        $conversationMessage = FreelancerOrderConversation::where('order_id', $request->order_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'freelancer.conversation.message', compact('conversationMessage'));
    }

}
