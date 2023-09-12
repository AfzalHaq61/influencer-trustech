<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ApplyJobOrder;
use App\Models\Job;

class JobOrderController extends Controller {

    public function customJobOrder($slug ,$id){
        if(Auth::guard('influencer')->check()) {
           $user = Auth::guard('influencer')->user();
           $user_type = 'influencer';
          
        } elseif (Auth::guard('freelancer')->check()) {
           $user = Auth::guard('freelancer')->user();
           $user_type = 'freelancer';
            
        }elseif(auth()->check()){
            $user_type = 'user';
            $user = Auth::user();
         }else{
            $user_type = 'no_login';
            $user = [];
         }

         if($user_type == 'no_login'){
            return to_route('user.login');
         }

        $pageTitle  = 'Custom Order Job';
        $job = Job::find($id);
        return view($this->activeTemplate . 'jobs.custom_job_order', compact('pageTitle','job','user_type','user'));
      
    }

    public function applyJobOrder(Request $request,$slug,$id){

        $job = Job::find($id);
        $applyJob = new ApplyJobOrder();

        if($slug == 'influencer'){
            $applyJob->influencer_id = Auth::guard('influencer')->user()->id;

        }elseif($slug == 'freelancer'){
            $applyJob->freelancer_id = Auth::guard('freelancer')->user()->id;

        }else{
            $applyJob->user_id = Auth::user()->id;
        }

        $applyJob->job_id       = $id; 
        $applyJob->amount       = $job->price;
        $applyJob->description  = $request->description;
        $applyJob->save();

        $notification= "Applied Successfully";

        $notify[] = ['success', $notification];
        return redirect()->route('jobs')->withNotify($notify);
        
    }

}
