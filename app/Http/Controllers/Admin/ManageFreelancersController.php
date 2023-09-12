<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hiring;
use App\Models\Freelancer;
use App\Models\NotificationLog;
use App\Models\FreelancerOrder;
use App\Models\Review;
use App\Models\FreelancerService;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManageFreelancersController extends Controller
{

    public function allFreelancers()
    {
        $pageTitle   = 'All Freelancers';
        $freelancers = $this->freelancerData();
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function activeFreelancers()
    {
        $pageTitle   = 'Active Freelancers';
        $freelancers = $this->freelancerData('active');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function bannedFreelancers()
    {
        $pageTitle   = 'Banned Freelancers';
        $freelancers = $this->freelancerData('banned');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function emailUnverifiedFreelancers()
    {
        $pageTitle   = 'Email Unverified Freelancers';
        $freelancers = $this->freelancerData('emailUnverified');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function kycUnverifiedFreelancers()
    {
        $pageTitle   = 'KYC Unverified Freelancers';
        $freelancers = $this->freelancerData('kycUnverified');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function kycPendingFreelancers()
    {
        $pageTitle   = 'KYC Pending Freelancers';
        $freelancers = $this->freelancerData('kycPending');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function emailVerifiedFreelancers()
    {
        $pageTitle   = 'Email Verified Freelancers';
        $freelancers = $this->freelancerData('emailVerified');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function mobileUnverifiedFreelancers()
    {
        $pageTitle   = 'Mobile Unverified Freelancers';
        $freelancers = $this->freelancerData('mobileUnverified');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function mobileVerifiedFreelancers()
    {
        $pageTitle   = 'Mobile Verified Freelancers';
        $freelancers = $this->freelancerData('mobileVerified');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    public function FreelancersWithBalance()
    {
        $pageTitle   = 'Freelancers with Balance';
        $freelancers = $this->freelancerData('withBalance');
        return view('admin.freelancers.list', compact('pageTitle', 'freelancers'));
    }

    protected function freelancerData($scope = null)
    {

        if ($scope) {
            $freelancers = Freelancer::$scope();
        } else {
            $freelancers = Freelancer::query();
        }

        //search
        $request = request();

        if ($request->search) {
            $search      = $request->search;
            $freelancers = $freelancers->where(function ($user) use ($search) {
                $user->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        return $freelancers->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $freelancer       = Freelancer::findOrFail($id);
        $pageTitle        = 'Information of ' . $freelancer->username;
        $totalService     = FreelancerService::where('freelancer_id', $freelancer->id)->where('status', 1)->count();
        $totalWithdrawals = Withdrawal::where('freelancer_id', $freelancer->id)->where('status', 1)->sum('amount');
        $totalTransaction = Transaction::where('freelancer_id', $freelancer->id)->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $data['pending_order']    = FreelancerOrder::pending()->where('freelancer_id', $id)->count();
        $data['inprogress_order'] = FreelancerOrder::inprogress()->where('freelancer_id', $id)->count();
        $data['job_done_order']   = FreelancerOrder::jobDone()->where('freelancer_id', $id)->count();
        $data['completed_order']  = FreelancerOrder::completed()->where('freelancer_id', $id)->count();
        $data['reported_order']   = FreelancerOrder::reported()->where('freelancer_id', $id)->count();
        $data['cancelled_order']  = FreelancerOrder::cancelled()->where('freelancer_id', $id)->count();

        $data['pending_hiring']    = Hiring::pending()->where('freelancer_id', $id)->count();
        $data['inprogress_hiring'] = Hiring::inprogress()->where('freelancer_id', $id)->count();
        $data['job_done_hiring']   = Hiring::jobDone()->where('freelancer_id', $id)->count();
        $data['completed_hiring']  = Hiring::completed()->where('freelancer_id', $id)->count();
        $data['reported_hiring']   = Hiring::reported()->where('freelancer_id', $id)->count();
        $data['cancelled_hiring']  = Hiring::cancelled()->where('freelancer_id', $id)->count();

        return view('admin.freelancers.detail', compact('pageTitle', 'freelancer', 'totalWithdrawals', 'totalTransaction', 'countries', 'totalService', 'data'));
    }

    public function kycDetails($id)
    {
        $pageTitle  = 'KYC Details';
        $freelancer = Freelancer::findOrFail($id);
        return view('admin.freelancers.kyc_detail', compact('pageTitle', 'freelancer'));
    }

    public function kycApprove($id)
    {
        $freelancer = Freelancer::findOrFail($id);
        $freelancer->kv = 1;
        $freelancer->save();

        notify($freelancer, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.freelancers.kyc.pending')->withNotify($notify);
    }

    public function kycReject($id)
    {
        $freelancer = Freelancer::findOrFail($id);

        foreach ($freelancer->kyc_data as $kycData) {

            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }

        $freelancer->kv       = 0;
        $freelancer->kyc_data = null;
        $freelancer->save();

        notify($freelancer, 'KYC_REJECT', []);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.freelancers.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $freelancer = Freelancer::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country     = $countryData->$countryCode->country;
        $dialCode    = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:users,email,' . $freelancer->id,
            'mobile'    => 'required|string|max:40|unique:freelancers,mobile,' . $freelancer->id,
            'country'   => 'required|in:' . $countries,
        ]);
        $freelancer->mobile       = $dialCode . $request->mobile;
        $freelancer->country_code = $countryCode;
        $freelancer->firstname    = $request->firstname;
        $freelancer->lastname     = $request->lastname;
        $freelancer->email        = $request->email;
        $freelancer->address      = [
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => @$country,
        ];
        $freelancer->ev = $request->ev ? 1 : 0;
        $freelancer->sv = $request->sv ? 1 : 0;
        $freelancer->ts = $request->ts ? 1 : 0;

        if (!$request->kv) {
            $freelancer->kv = 0;

            if ($freelancer->kyc_data) {

                foreach ($freelancer->kyc_data as $kycData) {

                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }

            $freelancer->kyc_data = null;
        } else {
            $freelancer->kv = 1;
        }

        $freelancer->save();

        $notify[] = ['success', 'Freelancer details updated successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act'    => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $freelancer = Freelancer::findOrFail($id);
        $amount     = $request->amount;
        $general    = gs();
        $trx        = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $freelancer->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', $general->cur_sym . $amount . ' added successfully'];
        } else {

            if ($amount > $freelancer->balance) {
                $notify[] = ['error', $freelancer->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $freelancer->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[]       = ['success', $general->cur_sym . $amount . ' subtracted successfully'];
        }

        $freelancer->save();

        $transaction->freelancer_id = $freelancer->id;
        $transaction->amount        = $amount;
        $transaction->post_balance  = $freelancer->balance;
        $transaction->charge        = 0;
        $transaction->trx           = $trx;
        $transaction->details       = $request->remark;
        $transaction->save();

        notify($freelancer, $notifyTemplate, [
            'trx'          => $trx,
            'amount'       => showAmount($amount),
            'remark'       => $request->remark,
            'post_balance' => showAmount($freelancer->balance),
        ]);

        return back()->withNotify($notify);
    }

    public function login($id)
    {
        if (auth()->check()) {
            auth()->logout();
        }
        Auth::guard('freelancer')->loginUsingId($id);
        return to_route('freelancer.home');
    }

    public function status(Request $request, $id)
    {
        $freelancer = Freelancer::findOrFail($id);

        if ($freelancer->status == 1) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $freelancer->status     = 0;
            $freelancer->ban_reason = $request->reason;
            $notify[]               = ['success', 'Freelancer banned successfully'];
        } else {
            $freelancer->status     = 1;
            $freelancer->ban_reason = null;
            $notify[]               = ['success', 'Freelancer unbanned successfully'];
        }

        $freelancer->save();
        return back()->withNotify($notify);
    }

    public function showNotificationSingleForm($id)
    {
        $freelancer = Freelancer::findOrFail($id);
        $general    = gs();

        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.freelancers.detail', $freelancer->id)->withNotify($notify);
        }

        $pageTitle = 'Send Notification to ' . $freelancer->username;
        return view('admin.freelancers.notification_single', compact('pageTitle', 'freelancer'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $freelancer = Freelancer::findOrFail($id);
        notify($freelancer, 'DEFAULT', [

            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();

        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $freelancers = Freelancer::where('ev', 1)->where('sv', 1)->where('status', 1)->count();
        $pageTitle   = 'Notification to Verified Freelancers';
        return view('admin.freelancers.notification_all', compact('pageTitle', 'freelancers'));
    }

    public function sendNotificationAll(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $freelancer = Freelancer::where('status', 1)->where('ev', 1)->where('sv', 1)->skip($request->skip)->first();
        notify($freelancer, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success'    => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }

    public function notificationLog($id)
    {
        $freelancer = Freelancer::findOrFail($id);
        $pageTitle  = 'Notifications Sent to ' . $freelancer->username;
        $logs       = NotificationLog::where('freelancer_id', $id)->with('user', 'freelancer')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.freelancers.reports.notification_history', compact('pageTitle', 'logs', 'freelancer'));
    }

    public function reviews($id)
    {
        $freelancer = Freelancer::findOrFail($id);
        $pageTitle  = 'Reviews of ' . $freelancer->username;
        $reviews    = Review::where('freelancer_id', $id)->with('user')->paginate(getPaginate());
        return view('admin.freelancers.reviews', compact('pageTitle', 'reviews'));
    }
    public function reviewRemove($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        $freelancer = Freelancer::with('reviews')->where('id', $review->freelancer_id)->firstOrFail();

        if ($freelancer->reviews->count() > 0) {
            $totalReview = $freelancer->reviews->count();
            $totalStar   = $freelancer->reviews->sum('star');
            $avgRating   = $totalStar / $totalReview;
        } else {
            $avgRating = 0;
        }
        $freelancer->rating = $avgRating;
        $freelancer->save();

        if ($review->service_id != 0) {
            $service = Service::approved()->where('id', $review->service_id)->with('reviews')->firstOrFail();
            if ($service->reviews->count() > 0) {
                $totalServiceReview = $service->reviews->count();
                $totalServiceStar   = $service->reviews->sum('star');
                $avgServiceRating   = $totalServiceStar / $totalServiceReview;
            } else {
                $avgServiceRating = 0;
            }

            $service->rating = $avgServiceRating;
            $service->save();
        }

        $notify[] = ['success', 'Review remove successfully'];
        return back()->withNotify($notify);
    }
}
