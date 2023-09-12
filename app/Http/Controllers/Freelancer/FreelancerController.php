<?php

namespace App\Http\Controllers\Freelancer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\City;
use App\Models\Form;
use App\Models\Hiring;
use App\Models\FreelancerOrder;
use App\Models\FreelancerService;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class FreelancerController extends Controller {
    public function home() {
        $pageTitle                 = 'Dashboard';
        $freelancerId              = authFreelancerId();
        $data['current_balance']   = authFreelancer()->balance;
        $data['withdraw_balance']  = Withdrawal::where('freelancer_id', $freelancerId)->where('status', 1)->sum('amount');
        $data['total_transaction'] = Transaction::where('freelancer_id', $freelancerId)->count();
        $data['total_hiring']      = Hiring::where('freelancer_id', $freelancerId)->count();
        $data['total_order']       = FreelancerOrder::where('freelancer_id', $freelancerId)->count();
        $data['total_service']     = FreelancerService::where('freelancer_id', $freelancerId)->count();

        $data['pending_order']    = FreelancerOrder::pending()->where('freelancer_id', $freelancerId)->count();
        $data['inprogress_order'] = FreelancerOrder::inprogress()->where('freelancer_id', $freelancerId)->count();
        $data['job_done_order']   = FreelancerOrder::jobDone()->where('freelancer_id', $freelancerId)->count();
        $data['completed_order']  = FreelancerOrder::completed()->where('freelancer_id', $freelancerId)->count();
        $data['cancelled_order']  = FreelancerOrder::cancelled()->where('freelancer_id', $freelancerId)->count();
        $data['reported_order']   = FreelancerOrder::reported()->where('freelancer_id', $freelancerId)->count();
        $data['rejected_order']   = FreelancerOrder::rejected()->where('freelancer_id', $freelancerId)->count();

        $data['pending_hiring']    = Hiring::pending()->where('freelancer_id', $freelancerId)->count();
        $data['inprogress_hiring'] = Hiring::inprogress()->where('freelancer_id', $freelancerId)->count();
        $data['job_done_hiring']   = Hiring::jobDone()->where('freelancer_id', $freelancerId)->count();
        $data['completed_hiring']  = Hiring::completed()->where('freelancer_id', $freelancerId)->count();
        $data['cancelled_hiring']  = Hiring::cancelled()->where('freelancer_id', $freelancerId)->count();
        $data['reported_hiring']   = Hiring::reported()->where('freelancer_id', $freelancerId)->count();
        $data['rejected_hiring']   = Hiring::rejected()->where('freelancer_id', $freelancerId)->count();

        return view($this->activeTemplate . 'freelancer.dashboard', compact('pageTitle', 'data'));
    }

    public function show2faForm() {
        $general    = gs();
        $ga         = new GoogleAuthenticator();
        $freelancer = authFreelancer();
        $secret     = $ga->createSecret();
        $qrCodeUrl  = $ga->getQRCodeGoogleUrl($freelancer->username . '@' . $general->site_name, $secret);
        $pageTitle  = '2FA Security';
        return view($this->activeTemplate . 'freelancer.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $freelancer = authFreelancer();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($freelancer, $request->code, $request->key);

        if ($response) {
            $freelancer->tsc = $request->key;
            $freelancer->ts  = 1;
            $freelancer->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }

    }

    public function disable2fa(Request $request) {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $freelancer = authFreelancer();
        $response   = verifyG2fa($freelancer, $request->code);

        if ($response) {
            $freelancer->tsc = null;
            $freelancer->ts  = 0;
            $freelancer->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }

    public function transactions(Request $request) {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::where('freelancer_id', authFreelancerId())->distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('freelancer_id', authFreelancerId());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'freelancer.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm() {

        if (authFreelancer()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('freelancer.home')->withNotify($notify);
        }

        if (authFreelancer()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('freelancer.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Form';
        return view($this->activeTemplate . 'freelancer.kyc.form', compact('pageTitle'));
    }

    public function kycData() {
        $freelancer = authFreelancer();
        $pageTitle  = 'KYC Data';
        return view($this->activeTemplate . 'freelancer.kyc.info', compact('pageTitle', 'freelancer'));
    }

    public function kycSubmit(Request $request) {
        $form           = Form::where('act', 'freelancer_kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $freelancerData       = $formProcessor->processFormData($request, $formData);
        $freelancer           = authFreelancer();
        $freelancer->kyc_data = $freelancerData;
        $freelancer->kv       = 2;
        $freelancer->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('freelancer.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash) {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function freelancerData() {
        $freelancer = authFreelancer();

        if ($freelancer->reg_step == 1) {
            return to_route('freelancer.home');
        }

        $pageTitle = 'Freelancer Data';
        $cities = City::orderBy('name')->get();
        return view($this->activeTemplate . 'freelancer.freelancer_data', compact('pageTitle', 'freelancer','cities'));
    }

    public function freelancerDataSubmit(Request $request) {
        $freelancer = authFreelancer();

        if ($freelancer->reg_step == 1) {
            return to_route('freelancer.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);

      //sample photos upload
        $sample_photos = [];
        $folderPathForPhoto = public_path('sample/'.$freelancer->username.'/photos/');
        
        if (!File::exists($folderPathForPhoto)) {
            File::makeDirectory($folderPathForPhoto, 0777, true, true);
        }
        $sl = 1;
        if (isset($request->sample_photos)){
          
            foreach(($request->sample_photos) AS $photo){
                $photoName =  date('Ymd').'_'.$sl.'_'.'.'.$photo->getClientOriginalExtension();
                $photo->move($folderPathForPhoto, $photoName);
                $sample_photos[] = $photoName;
                $sl++;
            }
            
        }


        //sample videos upload
        $sample_videos = [];
        $folderPathForVideo = public_path('sample/'.$freelancer->username.'/videos/');
        
        if (!File::exists($folderPathForVideo)) {
            File::makeDirectory($folderPathForVideo, 0777, true, true);
        }
        $sll = 1;
        if (isset($request->sample_videos)){
          
            foreach(($request->sample_videos) AS $video){
                
                $videoName =  date('Ymd').'_'.$sll.'_'.'.'.$video->getClientOriginalExtension();
                $video->move($folderPathForVideo, $videoName);
                $sample_videos[] = $videoName;
                $sll++;
            }
            
        }

        //Avatar Image Upload
        $imageName='';
        if (isset($request->image)){
            $imageName =  date('Ymd').'_avatar_'.'.'.$request->image->getClientOriginalExtension();
            $request->image->move($folderPathForPhoto, $imageName);
        }
        // return $imageName;
        $address = [
                    'country' => @$freelancer->address->country,
                    'address' => $request->address,
                    'state'   => $request->state,
                    'zip'     => $request->zip,
                    'city'     => $request->city
                ];

        $freelancer->firstname                  = $request->firstname;
        $freelancer->lastname                   = $request->lastname;
        $freelancer->age                        = $request->age;
        $freelancer->sex                        = $request->sex;
        $freelancer->address                    = $address;
        $freelancer->image                      = $imageName;
        $freelancer->about_me                   = $request->about_me;
        $freelancer->target_demographic_from    = $request->target_demographic_from;
        $freelancer->target_demographic_to      = $request->target_demographic_to;
        $freelancer->past_company_brands        = $request->past_companies_brands;
        $freelancer->available_for_events       = $request->available_for_events;
        $freelancer->available_for_travelling   = $request->available_for_travelling;
        $freelancer->city_id                    = $request->city_id;
        $freelancer->sample_photos              = json_encode($sample_photos);
        $freelancer->sample_videos              = json_encode($sample_videos);
        $freelancer->reg_step                   = 1;
        $freelancer->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('freelancer.home')->withNotify($notify);
    }

}
