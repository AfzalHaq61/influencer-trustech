<?php

namespace App\Http\Controllers\Admin;   

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\InfluencerCategory;
use App\Models\FreelancerCategory;
use App\Mail\InfluencerNewsMail;
use App\Mail\FreelancerNewsMail;
use App\Mail\AllUsersNewsMail;
use App\Mail\ClientNewsMail;
use App\Models\SocialLink;
use App\Models\Influencer;
use App\Models\Freelancer;
use App\Models\Category;
use App\Models\User;
use App\Models\City;

class NewsLetterController extends Controller
{
    public  $pageTitle;

    public function influencer(){
        $languageData   = config('languages');
        $pageTitle      = 'Influencers';
        $cities         = City::orderBy('name')->get();
        $countries      = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $influencers    = Influencer::active()->paginate(getPaginate(18));
        $allCategory    = Category::active()->where('belongs_to', 'influencer')->orderBy('name')->get();
        return view('admin.news_letter.influencer', compact('pageTitle', 'influencers','countries','allCategory','cities','languageData'));
    }

    public function filterInfluencer(Request $request){
        $influencers = $this->getInfluencer($request);
        $influencerEmails = $influencers->pluck('email')->toArray();
        session()->put('influencerEmails',$influencerEmails);
        $data= ['count'=>count($influencers), 'emails' =>$influencerEmails];
        return $data;
        // return view($this->activeTemplate . 'news_letter_influencer', compact('influencers'));
    }

    public function influencerSendMail(Request $request){
        if($request->mail_to != null){
            $to = [$request->mail_to];
        }else{
            $to= [];
        }

        $influencerEmails =  session('influencerEmails');
        Mail::to($to)->bcc($influencerEmails)->send(new InfluencerNewsMail($request));
        session()->forget('influencerEmails');
        $notify[] = ['success', 'Mail Sent successfully'];
        return back()->withNotify($notify);
    }
    
    protected function getInfluencer($request){
        $influencers = Influencer::active();

        if ($request->categories) {
            $influencerId = InfluencerCategory::whereIn('category_id', $request->categories)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
        }

        if ($request->categoryId) {
            $influencerId = InfluencerCategory::where('category_id', $request->categoryId)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
        }

        if ($request->country) {
            $influencers = $influencers->whereJsonContains('address', ['country' => $request->country]);
        }

        if ($request->country && $request->city) {
            $influencers = $influencers->whereJsonContains('address', ['country' => $request->country])->where('city_id', $request->city);
        }

        if ($request->language) {
            if ($request->language == "All") {
                $influencers = $influencers;
            }else{
                $influencers = $influencers->whereRaw('JSON_CONTAINS(languages, \'{"'.$request->language.'": {}}\')');
            }
        }

        if ($request->avlForAll) {
            $influencers = $influencers;
        }

        if ($request->avlForEvents) {
            $influencers = $influencers->where('available_for_events', 'yes')->orderBy('id', 'desc');
        }

        if ($request->avlForTravell) {
            $influencers = $influencers->where('available_for_travelling', 'yes')->orderBy('id', 'desc');
        }

        if ($request->instgramAccount) {
            $influencerId = collect(SocialLink::where('social_platform', 'instagram')->orderBy('followers', 'desc')->select('influencer_id')->get());
            $influencers  = $influencers->whereIn('id', $influencerId)->orderBy('id', 'desc');
        }

        if ($request->twitterAccount) {
            $influencerId = collect(SocialLink::where('social_platform', 'twitter')->orderBy('followers', 'desc')->select('influencer_id')->get());
            $influencers  = $influencers->whereIn('id', $influencerId)->orderBy('id', 'desc');
        }

        if (!isset($request->followersCountFilter) && $request->followersCount == 'instagram') {
            $influencerId = collect(SocialLink::where('social_platform', 'instagram')->orderBy('followers', 'desc')->select('influencer_id')->get());
            $idValues = $influencerId->pluck('influencer_id')->toArray();
            $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
        }

        if (!isset($request->followersCountFilter) && $request->followersCount == 'twitter') {
            $influencerId = collect(SocialLink::where('social_platform', 'twitter')->orderBy('followers', 'desc')->select('influencer_id')->get());
            $idValues = $influencerId->pluck('influencer_id')->toArray();
            $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
        }

        if ($request->followersCountFilter){
            if($request->followersCountFilter == 'nano'){
                $influencerId = collect(SocialLink::where('social_platform', $request->followersCount)
                                                    ->where('followers', '>', 0)
                                                    ->where('followers', '<', 10000)
                                                    ->orderBy('followers', 'desc')
                                                    ->select('influencer_id')->get());
                $idValues = $influencerId->pluck('influencer_id')->toArray();
                $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
            }
            if($request->followersCountFilter == 'micro'){
                $influencerId = collect(SocialLink::where('social_platform', $request->followersCount)
                                                    ->where('followers', '>=', 10000)
                                                    ->where('followers', '<', 100000)
                                                    ->orderBy('followers', 'desc')
                                                    ->select('influencer_id')->get());
                $idValues = $influencerId->pluck('influencer_id')->toArray();
                $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
            }
            if($request->followersCountFilter == 'middle'){
                $influencerId = collect(SocialLink::where('social_platform', $request->followersCount)
                                                    ->where('followers', '>=', 100000)
                                                    ->where('followers', '<', 500000)
                                                    ->orderBy('followers', 'desc')
                                                    ->select('influencer_id')->get());
                $idValues = $influencerId->pluck('influencer_id')->toArray();
                $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
            }
            if($request->followersCountFilter == 'macro'){
                $influencerId = collect(SocialLink::where('social_platform', $request->followersCount)
                                                    ->where('followers', '>=', 500000)
                                                    ->where('followers', '<', 1000000)
                                                    ->orderBy('followers', 'desc')
                                                    ->select('influencer_id')->get());
                $idValues = $influencerId->pluck('influencer_id')->toArray();
                $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
            }
            if($request->followersCountFilter == 'mega'){
                $influencerId = collect(SocialLink::where('social_platform', $request->followersCount)
                                                    ->where('followers', '>', 1000000)
                                                    ->orderBy('followers', 'desc')
                                                    ->select('influencer_id')->get());
                $idValues = $influencerId->pluck('influencer_id')->toArray();
                $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
            }
           
        }

        if ($request->sex){
            if($request->sex == "all"){
                $influencers = $influencers;
            }else{
                $influencers = $influencers->where('sex', $request->sex);
            }
            
        }

        if ($request->targetDemoAgeFrom != null){
            if ($request->targetDemoAgeFrom == "all"){
                $influencers = $influencers;
            }else{
                $influencers = $influencers->where('target_demographic_from', $request->targetDemoAgeFrom)->orderBy('id', 'desc');
            }
        }

        if ($request->targetDemoAgeTo != null){
            if ($request->targetDemoAgeTo == "all"){
                $influencers = $influencers;
            }else{
                if($request->targetDemoAgeFrom != null){
                    $influencers = $influencers->where('target_demographic_from', $request->targetDemoAgeFrom)
                                            ->where('target_demographic_to', $request->targetDemoAgeTo)
                                            ->orderBy('id', 'desc');
                }else{
                    $influencers = $influencers->where('target_demographic_to', $request->targetDemoAgeTo)->orderBy('id', 'desc');
                }
            }
        }

        if($request->targetDemoSex){
            if($request->targetDemoSex == "all"){
                $influencers = $influencers;
            }else{
                $influencers = $influencers->whereJsonContains('target_demographic_sex', $request->targetDemoSex)->orderBy('id', 'desc');
            }
        }

        if ($request->completedJob) {
            $influencers = $influencers->where('completed_order', '>', $request->completedJob)->orderBy('completed_order', 'desc');
        }

        return $influencers->get();
    }

    public function freelancer(){
        $pageTitle    = 'Freelancers';
        $languageData = config('languages');
        $countries    = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $cities       = City::orderBy('name')->get();
        $freelancers  = Freelancer::active()->paginate(getPaginate(18));
        $allCategory  = Category::active()->where('belongs_to', 'freelancer')->orderBy('name')->get();
        return view('admin.news_letter.freelancer', compact('pageTitle','languageData','countries','cities','freelancers','allCategory'));
        
    }

    public function filterFreelancer(Request $request){
        $freelancers = $this->getFreelancer($request);
        $freelancerEmails = $freelancers->pluck('email')->toArray();
        session()->put('freelancerEmails',$freelancerEmails);
        $data= ['count'=>count($freelancers), 'emails' =>$freelancerEmails];
        return $data;
    }

    public function freelancerSendMail(Request $request){

        if($request->mail_to != null){
            $to = [$request->mail_to];
        }else{
            $to= [];
        }
        $freelancerEmails =  session('freelancerEmails');
        Mail::to($to)->bcc($freelancerEmails)->send(new FreelancerNewsMail($request));
        session()->forget('freelancerEmails');
        $notify[] = ['success', 'Mail Sent successfully'];
        return back()->withNotify($notify);
    }

    protected function getFreelancer($request) {

        $freelancers = Freelancer::active();
        if ($request->categories) {
            $freelancerId = FreelancerCategory::whereIn('category_id', $request->categories)->select('freelancer_id')->get();
            $freelancers  = $freelancers->whereIn('id', $freelancerId);
        }

        if ($request->categoryId) {
            $freelancerId = FreelancerCategory::where('category_id', $request->categoryId)->select('freelancer_id')->get();
            $freelancers  = $freelancers->whereIn('id', $freelancerId);
        }

        if ($request->country) {
            $freelancers = $freelancers->whereJsonContains('address', ['country' => $request->country]);
        }

        if ($request->country && $request->city) {
            $freelancers = $freelancers->whereJsonContains('address', ['country' => $request->country])->where('city_id', $request->city);
        }

        if ($request->language) {
            if ($request->language == "All") {
                $freelancers = $freelancers;
            }else{
                $freelancers = $freelancers->whereRaw('JSON_CONTAINS(languages, \'{"'.$request->language.'": {}}\')');
            }
            
        }

       
        if ($request->completedJob) {
            $freelancers = $freelancers->where('completed_order', '>', $request->completedJob)->orderBy('completed_order', 'desc');
        }

        return $freelancers->orderBy('id', 'desc')->get();
    }

    public function client(){
        $pageTitle = 'Clients';
        $clients =User::active()->get();
        return view('admin.news_letter.client', compact('pageTitle', 'clients'));
    }

    public function clientSendMail(Request $request){
        if($request->mail_to != null){
            $to = [$request->mail_to];
        }else{
            $to= [];
        }
        $clientEmails =  User::active()->pluck('email')->toArray();
        Mail::to($to)->bcc($clientEmails)->send(new ClientNewsMail($request));
        $notify[] = ['success', 'Mail Sent successfully'];
        return back()->withNotify($notify);
    }

    public function allUsers(){
        $pageTitle   = 'All Users';
        $clients     = User::active()->get();
        $influencers = Influencer::active()->get();
        $freelancers = Freelancer::active()->get();
        return view('admin.news_letter.all_users', compact('pageTitle', 'clients','influencers','freelancers'));
    }

    public function allUsersSendMail(Request $request){

        if($request->mail_to != null){
            $to = [$request->mail_to];
        }else{
            $to= ['mohammedshahrukhalam@gmail.com'];
        }
        $clientEmails     = User::active()->pluck('email')->toArray();
        $influencerEmails = Influencer::active()->pluck('email')->toArray();
        $freelancerEmails = Freelancer::active()->pluck('email')->toArray();
        $emails = array_merge($clientEmails, $influencerEmails, $freelancerEmails);
        Mail::to($to)->bcc($emails)->send(new AllUsersNewsMail($request));
        $notify[] = ['success', 'Mail Sent successfully'];
        return back()->withNotify($notify);
    }
}
