<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminTransactionManage;
use App\Models\AdminNotification;
use App\Models\GatewayCurrency;
use App\Models\Job;
use App\Models\JobSkill;
use App\Models\JobCategory;
use App\Models\JobSubCategory;
use App\Models\ApplyJobOrder;
use App\Models\ApprovedJobOrder;
use App\Rules\FileTypeValidate;
use App\Models\Transaction;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    public function jobCreate()
    {
        $pageTitle  = 'New Job';
        $jobCategories = JobCategory::active()->orderBy('name')->with('JobSubCategories',function($q){
            $q->active();
        })->get();
        $jobSkills = JobSkill::active()->orderBy('name')->get();
        $languageData = config('languages');
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.jobs.create', compact('pageTitle', 'jobCategories','jobSkills','languageData','countries'));
    }

    public function jobInsert(Request $request, $id = 0){
        // return $request;
        $this->jobValidation($request, $id);
        $check = $this->checkData($request, $id);

        if ($check[0] == 'error') {
            $notify[] = $check;
            return back()->withNotify($notify);
        }

        if ($id) {
            $job          = Job::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
            $notification = 'Job updated successfully';
        } else {
            $job          = new Job();
            $job->user_id = auth()->id();
            $notification = 'Job added successfully';
        }

        if ($request->hasFile('image')) {
            $jobImage   = fileUploader($request->image, getFilePath('service'), getFileSize('service'), @$job->image);
            $job->image = $jobImage;
        }

        if(gs()->post_approval) {
            $job->status = Status::ENABLE;
        }

        $job->name                          = $request->name;
        $job->price                         = $request->price;
        $job->skill                         = $request->skill;
        $job->description                   = $request->description;
        $job->requirements                  = $request->requirements;
        $job->delivery_time                 = $request->delivery_time;
        $job->job_category_id               = $request->category_id;
        $job->job_sub_category_id           = $request->sub_category_id;
        $job->target_demographic_from       = $request->target_demographic_from;
        $job->target_demographic_to         = $request->target_demographic_to;
        $job->target_demographic_gender     = $request->target_demographic_gender;
        $job->target_country                = $request->country;
        $job->language                      = $request->language;
        $job->job_type                      = $request->job_type;
        $job->campaign_start_date           = $request->campaign_start_date;
        $job->campaign_end_date             = $request->campaign_end_date;
        $job->campaign_social               = json_encode($request->campaign_social);
        $job->campaign_guidelines           = $request->campaign_guidelines;
        $job->event_date                    = $request->event_date;
        $job->event_location                = $request->event_location;
        $job->event_attend                  = $request->event_attend;
        $job->casting_job_idea              = $request->casting_job_idea;
        $job->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
        
    }

    protected function jobValidation($request, $id){
        $imageValidation = $id ? 'nullable' : 'required';

        $request->validate([
            'image'           => [$imageValidation, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|integer|gt:0',
            'price'           => 'required|numeric|gt:0',
            'delivery_time'   => 'required|integer|min:1',
            'description'     => 'required',
            'requirements'    => 'required',
        ]);
    }

    protected function checkData($request, $id){
        $category    = JobCategory::active();
        // $subcategory = JobSubCategory::active();

        $category = $category->where('id', $request->category_id)->first();

        if (!$category) {
            return ['error', 'Category not found or disabled'];
        }

        // if (!$category) {
        //     return ['error', 'Category not found or disabled'];
        // } else {
        //     $subcategory = $subcategory->where('id', $request->sub_category_id)->where('job_category_id', $category->id)->first();

        //     if (!$subcategory) {
        //         return ['error', 'Subcategory not found or disabled'];
        //     }
        // }

        return ['success'];
    }

    public function jobDetail($id){
        $order = ApprovedJobOrder::where('job_id', $id)->with('job')->first();
        $pageTitle = 'Job Order Detail';
        return view($this->activeTemplate . 'user.jobs.job_detail', compact('pageTitle', 'order'));
    }

    public function completeStatusInfluencer($id){

        $order = ApprovedJobOrder::find($id);
        $order->status = 1;
        $order->save();

        $job = job::find($order->job_id);
        $job->status = 7;
        $job->save();

        $user = auth()->user();
        $influencer = $order->influencer;
        $general    = gs();

        $adminTransaction = AdminTransactionManage::where('order_id', $id)->firstOrFail();
        $adminTransaction->status = 3;
        $adminTransaction->save();
        $payble_amount = $adminTransaction->payble_amount;

        $influencer->balance += $payble_amount;
        $influencer->increment('completed_order');
        $influencer->save();

        notify($influencer, 'ORDER_COMPLETED_INFLUENCER', [
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($payble_amount),
            'order_no'      => $order->order_no,
        ]);

        $transaction                = new Transaction();
        $transaction->influencer_id = $influencer->id;
        $transaction->amount        = $payble_amount;
        $transaction->post_balance  = $influencer->balance;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Payment received for completing a new job order';
        $transaction->trx           = getTrx();
        $transaction->remark        = 'order_payment';
        $transaction->save();

        $notify[] = ['success', 'Job Order completed successfully'];
        return back()->withNotify($notify);

    }

    public function completeStatusFreelancer($id){

        $order = ApprovedJobOrder::find($id);
        $order->status = 1;
        $order->save();

        $job = job::find($order->job_id);
        $job->status = 7;
        $job->save();

        $user = auth()->user();
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
        $transaction->details       = 'Payment received for completing a new job order';
        $transaction->trx           = getTrx();
        $transaction->remark        = 'order_payment';
        $transaction->save();

        $notify[] = ['success', 'Job Order completed successfully'];
        return back()->withNotify($notify);

    }


    public function allJobs($scope = null){
        $pageTitle = 'All Jobs';

        if ($scope) {
            $jobs = Job::$scope();
        }else{
            $jobs = Job::query();
        }
        $jobs = $jobs->where('user_id', auth()->id())->latest()->with(['user', 'jobCategory', 'JobSubCategory'])->paginate(getPaginate());

        return view($this->activeTemplate . 'user.jobs.list', compact('pageTitle', 'jobs'));
    }

    public function editJob(Job $job){
        
        $pageTitle  = 'Edit Job';
        $jobCategories = JobCategory::active()->orderBy('name')->with('JobSubCategories',function($q){
            $q->active();
        })->get();
        return view($this->activeTemplate . 'user.jobs.edit_job', compact('pageTitle', 'job', 'jobCategories'));
    }

    public function pendingJobs(){
        $status = 0;
        $title = 'Pending Jobs';
        return $this->filterJobs($status, $title);
    }

    public function approvedJobs(){
        $status = 1;
        $title = 'Approved Jobs';
        return $this->filterJobs($status, $title);
    }

    public function assignedJobs(){
        $status = 2;
        $title = 'Assigned Jobs';
        return $this->filterJobs($status, $title);
    }

    public function cancelledJobs(){
        $status = 5;
        $title = 'Cancelled Jobs';
        return $this->filterJobs($status, $title);
    }

    public function closedJobs(){
        $status = 6;
        $title = 'Closed Jobs';
        return $this->filterJobs($status, $title);
    }

    protected function filterJobs($status, $title){
        $pageTitle = $title;
        $jobs =Job::where('user_id', auth()->id())->where('status', $status)->latest()->with(['user', 'jobCategory', 'JobSubCategory'])->paginate(getPaginate());
        return view($this->activeTemplate . 'user.jobs.list', compact('pageTitle', 'jobs'));
    }

    public function jobApplications(){
        $pageTitle = 'Job Applications';
        $jobs =Job::where('user_id', auth()->id())->where('status', 1)->latest()->with(['user', 'jobCategory', 'JobSubCategory','jobApplications'])->paginate(getPaginate());
        return view($this->activeTemplate . 'user.jobs.job_applications', compact('pageTitle', 'jobs'));
    }

    public function jobApplicants($id){
        $pageTitle = 'Job Applicants';
        $job_applications = ApplyJobOrder::where('job_id', $id)->with(['appliedUser', 'appliedInfluencer', 'appliedFreelancer'])->paginate(getPaginate());
        return view($this->activeTemplate . 'user.jobs.applicants', compact('pageTitle', 'job_applications'));
    }

    public function jobAssignToYou(){
        $jobs = ApprovedJobOrder::where('user_id', auth()->id())->paginate(getPaginate());
        $pageTitle = 'Assign to you';
        return view($this->activeTemplate . 'user.jobs.assign_to_you', compact('pageTitle', 'jobs'));
    }

    public function jobOrderDetail($order_id){
        $order = ApprovedJobOrder::find($order_id);
        $pageTitle = 'Job Order Detail';
        // return $order;
        return view($this->activeTemplate . 'user.jobs.job_order_detail', compact('pageTitle', 'order'));
    }

    public function acceptStatus($id) {
        $order = ApprovedJobOrder::find($id);
        $order->status = 2;
        $order->save();

        $job = job::find($order->job_id);
        $job->status = 3;
        $job->save();

        $notify[] = ['success', 'Job has now inprogress'];
        return back()->withNotify($notify);
    }

    public function jobDoneStatus($id) {
        $order = ApprovedJobOrder::find($id);
        $order->status = 3;
        $order->save();

        $job = job::find($order->job_id);
        $job->status = 4;
        $job->save();

        $notify[] = ['success', 'Job has now Done'];
        return back()->withNotify($notify);
    }

    
    public function assignJob($user_type, $user_id, $application_id){
        
        $job_application  = ApplyJobOrder::find($application_id);
        $job_order = new ApprovedJobOrder();

        if($user_type == 'user'){
            $job_order->user_id = $user_id;

        }elseif($user_type == 'influencer'){
            $job_order->influencer_id = $user_id;

        }elseif($user_type == 'freelancer'){
            $job_order->freelancer_id = $user_id;
        }

        $job_order->job_id    = $job_application->job_id;
        $job_order->order_no  = getTrx();
        $job_order->amount    = $job_application->amount;
        $job_order->save();

        Job::where('id', $job_application->job_id)->update(['status'=> 2]);
        ApplyJobOrder::where('id', $application_id)->update(['status'=> 1]);
        ApplyJobOrder::where('id','!=', $application_id)->update(['status'=> 2]);

        session()->put('payment_data', [
            'job_order_id' => $job_order->id,
            'amount' => $job_order->amount,
        ]);

        session()->put('order_details', [
            'order_id' => $job_order->id,
            'amount' => $job_order->amount,
            'job_id' => $job_order->job_id,
            'order_type' => 'client job order',
        ]);

        if($user_type == 'influencer'){
            return redirect()->route('user.payment');

        }elseif($user_type == 'freelancer'){
            return redirect()->route('user.freelancer.payment');
        }

        $notification= "Job Assigned Successfully";
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    
    
}
