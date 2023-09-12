<?php

namespace App\Http\Controllers\Influencer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Form;
use App\Models\City;
use App\Models\Hiring;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class InfluencerController extends Controller {
    public function home() {
        $pageTitle                 = 'Dashboard';
        $influencerId              = authInfluencerId();
        $data['current_balance']   = authInfluencer()->balance;
        $data['withdraw_balance']  = Withdrawal::where('influencer_id', $influencerId)->where('status', 1)->sum('amount');
        $data['total_transaction'] = Transaction::where('influencer_id', $influencerId)->count();
        $data['total_hiring']      = Hiring::where('influencer_id', $influencerId)->count();
        $data['total_order']       = Order::where('influencer_id', $influencerId)->count();
        $data['total_service']     = Service::where('influencer_id', $influencerId)->count();

        $data['pending_order']    = Order::pending()->where('influencer_id', $influencerId)->count();
        $data['inprogress_order'] = Order::inprogress()->where('influencer_id', $influencerId)->count();
        $data['job_done_order']   = Order::jobDone()->where('influencer_id', $influencerId)->count();
        $data['completed_order']  = Order::completed()->where('influencer_id', $influencerId)->count();
        $data['cancelled_order']  = Order::cancelled()->where('influencer_id', $influencerId)->count();
        $data['reported_order']   = Order::reported()->where('influencer_id', $influencerId)->count();
        $data['rejected_order']   = Order::rejected()->where('influencer_id', $influencerId)->count();

        $data['pending_hiring']    = Hiring::pending()->where('influencer_id', $influencerId)->count();
        $data['inprogress_hiring'] = Hiring::inprogress()->where('influencer_id', $influencerId)->count();
        $data['job_done_hiring']    = Hiring::jobDone()->where('influencer_id', $influencerId)->count();
        $data['completed_hiring']  = Hiring::completed()->where('influencer_id', $influencerId)->count();
        $data['cancelled_hiring']  = Hiring::cancelled()->where('influencer_id', $influencerId)->count();
        $data['reported_hiring']   = Hiring::reported()->where('influencer_id', $influencerId)->count();
        $data['rejected_hiring']   = Hiring::rejected()->where('influencer_id', $influencerId)->count();

        return view($this->activeTemplate . 'influencer.dashboard', compact('pageTitle', 'data'));
    }

    public function show2faForm() {
        $general    = gs();
        $ga         = new GoogleAuthenticator();
        $influencer = authInfluencer();
        $secret     = $ga->createSecret();
        $qrCodeUrl  = $ga->getQRCodeGoogleUrl($influencer->username . '@' . $general->site_name, $secret);
        $pageTitle  = '2FA Security';
        return view($this->activeTemplate . 'influencer.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $influencer = authInfluencer();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($influencer, $request->code, $request->key);

        if ($response) {
            $influencer->tsc = $request->key;
            $influencer->ts  = 1;
            $influencer->save();
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

        $influencer = authInfluencer();
        $response   = verifyG2fa($influencer, $request->code);

        if ($response) {
            $influencer->tsc = null;
            $influencer->ts  = 0;
            $influencer->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }

    public function transactions(Request $request) {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::where('influencer_id', authInfluencerId())->distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('influencer_id', authInfluencerId());

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
        return view($this->activeTemplate . 'influencer.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm() {

        if (authInfluencer()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('influencer.home')->withNotify($notify);
        }

        if (authInfluencer()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('influencer.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Form';
        return view($this->activeTemplate . 'influencer.kyc.form', compact('pageTitle'));
    }

    public function kycData() {
        $influencer = authInfluencer();
        $pageTitle  = 'KYC Data';
        return view($this->activeTemplate . 'influencer.kyc.info', compact('pageTitle', 'influencer'));
    }

    public function kycSubmit(Request $request) {
        $form           = Form::where('act', 'influencer_kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $influencerData       = $formProcessor->processFormData($request, $formData);
        $influencer           = authInfluencer();
        $influencer->kyc_data = $influencerData;
        $influencer->kv       = 2;
        $influencer->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('influencer.home')->withNotify($notify);
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

    public function influencerData() {
        $influencer = authInfluencer();

        if ($influencer->reg_step == 1) {
            return to_route('influencer.home');
        }

        $pageTitle = 'Influencer Data';
        $cities = City::orderBy('name')->get();
        return view($this->activeTemplate . 'influencer.influencer_data', compact('pageTitle', 'influencer','cities'));
    }

    public function influencerDataSubmit(Request $request) {
        return $request;
        $influencer = authInfluencer();

        if ($influencer->reg_step == 1) {
            return to_route('influencer.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);

      //sample photos upload
        $sample_photos = [];
        $folderPathForPhoto = public_path('sample/'.$influencer->username.'/photos/');
        
        if (!File::exists($folderPathForPhoto)) {
            File::makeDirectory($folderPathForPhoto, 0777, true, true);
        }
       
        if (isset($request->sample_photos)){
            $sl = rand();
            foreach(($request->sample_photos) AS $photo){
                $photoName =  date('Ymd').'_'.$sl.'_'.'.'.$photo->getClientOriginalExtension();
                $photo->move($folderPathForPhoto, $photoName);
                $sample_photos[] = $photoName;
            }
            
        }


        //sample videos upload
        $sample_videos = [];
        $folderPathForVideo = public_path('sample/'.$influencer->username.'/videos/');
        
        if (!File::exists($folderPathForVideo)) {
            File::makeDirectory($folderPathForVideo, 0777, true, true);
        }
        if (isset($request->sample_videos)){
          
            foreach(($request->sample_videos) AS $video){
                $sll = rand();
                $videoName =  date('Ymd').'_'.$sll.'_'.'.'.$video->getClientOriginalExtension();
                $video->move($folderPathForVideo, $videoName);
                $sample_videos[] = $videoName;
                
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
                    'country' => @$influencer->address->country,
                    'address' => $request->address,
                    'state'   => $request->state,
                    'zip'     => $request->zip,
                    'city'     => $request->city
                ];

        $influencer->firstname                  = $request->firstname;
        $influencer->lastname                   = $request->lastname;
        $influencer->age                        = $request->age;
        $influencer->sex                        = $request->sex;
        $influencer->address                    = $address;
        $influencer->image                      = $imageName;
        $influencer->about_me                   = $request->about_me;
        $influencer->target_demographic_from    = $request->target_demographic_from;
        $influencer->target_demographic_to      = $request->target_demographic_to;
        $influencer->past_company_brands        = $request->past_companies_brands;
        $influencer->available_for_events       = $request->available_for_events;
        $influencer->available_for_travelling   = $request->available_for_travelling;
        $influencer->city_id                    = $request->city_id;
        $influencer->sample_photos              = json_encode($sample_photos);
        $influencer->sample_videos              = json_encode($sample_videos);
        $influencer->reg_step                   = 1;
        $influencer->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('influencer.home')->withNotify($notify);
    }

}
