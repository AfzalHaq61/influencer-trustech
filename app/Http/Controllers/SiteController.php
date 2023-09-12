<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Frontend;
use App\Models\Job;
use App\Models\Hiring;
use App\Models\HiringConversation;
use App\Models\Influencer;
use App\Models\Freelancer;
use App\Models\FreelancerCategory;
use App\Models\InfluencerCategory;
use App\Models\Language;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderConversation;
use App\Models\FreelancerOrder;
use App\Models\FreelancerOrderConversation;
use App\Models\Page;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceTag;
use App\Models\SupportMessage;
use App\Models\SocialLink;
use App\Models\SupportTicket;
use App\Models\Tag;
use App\Models\FreelancerService;
use App\Models\JobCategory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SiteController extends Controller {

    public function index() {
        $pageTitle = 'Home';
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();
        $tags      = Tag::withCount('serviceTag')->orderBy('service_tag_count', 'desc')->take(6)->get();
        return view($this->activeTemplate . 'home', compact('pageTitle', 'sections', 'tags'));
    }

    public function pages($slug) {
        $page      = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections  = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }

    public function contact() {
        $pageTitle = "Contact Us";
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->first();
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'sections'));
    }

    public function login() {
        $pageTitle    = "Login";
        $loginContent = Frontend::where('data_keys', 'login.content')->first();
        return view($this->activeTemplate . 'login', compact('pageTitle', 'loginContent'));
    }

    public function contactSubmit(Request $request) {

        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;

        if(!auth()->id()){
            $ticket->influencer_id = authInfluencerId()??0;
        }

        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = 2;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = 0;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        if(auth()->user()){
            $view = 'ticket.view';
        }elseif(authInfluencer()){
            $view = 'influencer.ticket.view';
        }else{
            $view = 'ticket.view';
        }

        return to_route($view, [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug, $id) {
        $policy    = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate . 'policy', compact('policy', 'pageTitle'));
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();

        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function cookieAccept() {
        $general = gs();
        Cookie::queue('gdpr_cookie', $general->site_name, 43200);
        return back();
    }

    public function cookiePolicy() {
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view($this->activeTemplate . 'cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null) {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize  = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) {
            $fontSize = 9;
        }

        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance() {
        $pageTitle = 'Maintenance Mode';
        $general   = gs();

        if ($general->maintenance_mode == 0) {
            return to_route('home');
        }

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view($this->activeTemplate . 'maintenance', compact('pageTitle', 'maintenance'));
    }

    public function services(Request $request) {
        $influencer = authInfluencer();
        $pageTitle   = 'Services';
        $services    = $this->getServices($request);
        $allCategory = Category::active()->orderBy('name')->get();
        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'service')->first();
        return view($this->activeTemplate . 'service.list', compact('influencer','services', 'pageTitle', 'allCategory', 'sections'));
    }

    public function serviceByTag(Request $request, $id, $name) {
        $pageTitle = 'Service - ' . $name;

        $serviceId = collect(ServiceTag::where('tag_id', $id)->pluck('service_id'))->toArray();
        $orders    = array_map(function ($item) {
            return "id = {$item} desc";
        }, $serviceId);
        $rawOrder    = implode(', ', $orders);
        $services    = Service::approved()->whereIn('id', $serviceId)->orderByRaw($rawOrder)->with('influencer', 'category')->paginate(getPaginate());
        $allCategory = Category::active()->orderBy('name')->get();

        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'service')->first();
        return view($this->activeTemplate . 'service.list', compact('services', 'pageTitle', 'id', 'sections', 'allCategory'));
    }

    public function filterService(Request $request) {
        $services = $this->getServices($request);
        return view($this->activeTemplate . 'service.filtered', compact('services'));
    }

    protected function getServices($request) {

        $services = Service::approved();

        if ($request->categories) {
            $services = $services->whereIn('category_id', $request->categories);
        }

        if ($request->tagId) {
            $serviceId = collect(ServiceTag::where('tag_id', $request->tagId)->pluck('service_id'))->toArray();
            $services  = $services->whereIn('id', $serviceId);
        }

        if ($request->min && $request->max) {
            $min      = intval($request->min);
            $max      = intval($request->max);
            $services = $services->whereBetween('price', [$min, $max]);
        }

        if ($request->sort) {
            $sort     = explode('_', $request->sort);
            $services = $services->orderBy(@$sort[0], @$sort[1]);
        }

        if ($request->search) {
            $search   = $request->search;
            $services = $services->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%');
            })->orWhereHas('category', function ($category) use ($search) {
                $category->where('name', 'like', "%$search%");
            });
        }

        return $services->latest()->with('influencer', 'category')->paginate(getPaginate(15));
    }

    public function serviceDetails($slug, $id, $orderId = 0) {

        if ($orderId) {
            $order = Order::completed()->where('user_id', auth()->id())->where('service_id', $id)->findOrFail($orderId);
        }

        $service         = Service::approved()->where('id', $id)->with('category', 'influencer.socialLink', 'gallery', 'reviews.user', 'tags')->firstOrFail();
        $pageTitle       = 'Service Details';
        $customPageTitle = $service->title;

        $anotherServices = Service::approved()->where('influencer_id', $service->influencer->id)->where('id', '!=', $id)->with('influencer')->latest()->take(4)->get();

        $seoContents['keywords']           = $service->meta_keywords ?? [];
        $seoContents['social_title']       = $service->title;
        $seoContents['description']        = strLimit(strip_tags($service->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($service->description), 150);
        $seoContents['image']              = getImage(getFilePath('service') . '/' . $service->image, getFileSize('service'));
        $seoContents['image_size']         = getFileSize('service');

        return view($this->activeTemplate . 'service.detail', compact('service', 'pageTitle', 'anotherServices', 'seoContents', 'orderId', 'customPageTitle'));
    }

    public function influencerProfile($name, $id) {
        $influencer = Influencer::active()->with('education', 'qualification', 'services.category', 'socialLink')->findOrFail($id);
        // $category_id = $influencer->services[0]->category->id;
        $thisInfluencerId = $influencer->id;
        $pageTitle  = 'Influencer Profile';
        $reviews    = Review::where('influencer_id', $id)->where('order_id', 0)->with('user')->latest()->paginate(10);
        $services   = Service::where('influencer_id', $influencer->id)->with('tags')->get();
        $data['ongoing_job']   = Order::inprogress()->where('influencer_id', $id)->count() + Hiring::inprogress()->where('influencer_id', $id)->count();
        $data['completed_job'] = Order::completed()->where('influencer_id', $id)->count() + Hiring::completed()->where('influencer_id', $id)->count();;
        $data['queue_job']     = Order::whereIn('status', [2, 3])->where('influencer_id', $id)->count() + Hiring::whereIn('status', [2, 3])->where('influencer_id', $id)->count();
        $data['pending_job']   = Order::pending()->where('influencer_id', $id)->count() + Hiring::pending()->where('influencer_id', $id)->count();
        $influencers = Influencer::orderBy('id','DESC')->with('socialLink')->paginate(4);
        return view($this->activeTemplate . 'influencer.profile', compact('pageTitle', 'influencer', 'thisInfluencerId', 'data', 'reviews','services','influencers'));
    }

    public function influencers(Request $request) {
        $languageData     = config('languages');
        $pageTitle        = 'Influencers';
        $thisInfluencerId = null;
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $cities           = City::orderBy('name')->get();
        $influencers      = $this->getInfluencer($request);
        $sections         = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $allCategory      = Category::active()->where('belongs_to', 'influencer')->orderBy('name')->get();
        return view($this->activeTemplate . 'influencers', compact('thisInfluencerId','influencers', 'pageTitle', 'sections', 'countries', 'cities','allCategory','languageData'));
    }

    public function freelancer(Request $request){
        $languageData     = config('languages');
        $thisFreelancerId = null;
        $pageTitle        = 'Freelancers';
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $cities           = City::orderBy('name')->get();
        $freelancers      = $this->getFreelancer($request);
        $sections         = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $allCategory      = Category::active()->where('belongs_to', 'freelancer')->orderBy('name')->get();
        $serviceMin       = FreelancerService::orderBy('price', 'asc')->first();
        $serviceMinPrice  = $serviceMin->price;
        $serviceMax       = FreelancerService::orderBy('price', 'desc')->first();
        $serviceMaxPrice  = round($serviceMax->price);
        return view($this->activeTemplate . 'freelancers', compact('languageData','thisFreelancerId','freelancers', 'pageTitle', 'sections', 'countries', 'cities', 'allCategory','serviceMinPrice','serviceMaxPrice'));
    }

    public function freelancerProfile($name, $id) {
        $freelancer = Freelancer::active()->with('education', 'qualification', 'services.category')->findOrFail($id);
        $thisFreelancerId = $freelancer->id;
        $pageTitle  = 'FreeLancer Profile';
        $reviews    = Review::where('freelancer_id', $id)->where('order_id', 0)->with('user')->latest()->paginate(10);
        $services   = FreelancerService::where('freelancer_id', $freelancer->id)->with('tags')->get();

        $data['ongoing_job']   = FreelancerOrder::inprogress()->where('freelancer_id', $id)->count() + Hiring::inprogress()->where('freelancer_id', $id)->count();
        $data['completed_job'] = FreelancerOrder::completed()->where('freelancer_id', $id)->count() + Hiring::completed()->where('freelancer_id', $id)->count();;
        $data['queue_job']     = FreelancerOrder::whereIn('status', [2, 3])->where('freelancer_id', $id)->count() + Hiring::whereIn('status', [2, 3])->where('freelancer_id', $id)->count();
        $data['pending_job']   = FreelancerOrder::pending()->where('freelancer_id', $id)->count() + Hiring::pending()->where('freelancer_id', $id)->count();
        $freelancers = Freelancer::orderBy('id','DESC')->paginate(4);
        // return $services;
        return view($this->activeTemplate . 'freelancer.profile', compact('thisFreelancerId','pageTitle', 'freelancer', 'data', 'reviews','services','freelancers'));
    }

    public function influencerByCategory(Request $request, $id, $name) {

        $pageTitle    = 'Category - ' . $name;
        $countries    = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $influencerId = InfluencerCategory::where('category_id', $id)->select('influencer_id')->get();
        $influencers  = Influencer::active()->whereIn('id', $influencerId)->with('socialLink')->latest()->paginate(getPaginate(15));
        $sections     = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        return view($this->activeTemplate . 'influencers', compact('influencers', 'pageTitle', 'sections', 'countries', 'id'));
    }

    public function filterInfluencer(Request $request) {
        // return $request;
        $influencers = $this->getInfluencer($request);
        return view($this->activeTemplate . 'similar_influencer', compact('influencers'));
    }

    protected function getInfluencer($request) {
        $influencers = Influencer::active();

        if ($request->categories) {
            $influencerId = InfluencerCategory::whereIn('category_id', $request->categories)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
        }

        if ($request->categoryId) {
            $influencerId = InfluencerCategory::where('category_id', $request->categoryId)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
        }

        if ($request->search) {
            $search      = $request->search;
            $influencers = $influencers->where(function ($query) use ($search) {
                $query->where('firstname', "LIKE", "%$search%")
                    ->orWhere('lastname', 'LIKE', "%%$search")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('profession', 'LIKE', "%$search%");
            });
        }

        if ($request->country) {
            $influencers = $influencers->whereJsonContains('address', ['country' => $request->country]);
        }

        if ($request->country && $request->city) {
            $influencers = $influencers->whereJsonContains('address', ['country' => $request->country])->where('city_id', $request->city);
        }

        if ($request->language) {
            $influencers = $influencers->whereRaw('JSON_CONTAINS(languages, \'{"'.$request->language.'": {}}\')');
        }

        if ($request->rating) {
            $influencers = $influencers->where('rating', '>=', $request->rating);
        }

        if ($request->sort == 'top_rated') {
            $influencers = $influencers->where('completed_order', '>', 0)->orderBy('completed_order', 'desc');
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

        if ($request->followersCount == 'instagram') {
            $influencerId = collect(SocialLink::where('social_platform', 'instagram')->orderBy('followers', 'desc')->select('influencer_id')->get());
            $idValues = $influencerId->pluck('influencer_id')->toArray();
            $influencers  = $influencers->whereIn('id', $influencerId)->orderByRaw("FIELD(id, " . implode(',', $idValues) . ")");
        }

        if ($request->followersCount == 'twitter') {
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
            $influencers = $influencers->where('sex', $request->sex)->orderBy('id', 'desc');
        }

        if ($request->targetDemoAgeFrom != null){
            $influencers = $influencers->where('target_demographic_from', $request->targetDemoAgeFrom)->orderBy('id', 'desc');
        }

        if ($request->targetDemoAgeTo != null){
            if($request->targetDemoAgeFrom != null){
                $influencers = $influencers->where('target_demographic_from', $request->targetDemoAgeFrom)
                                           ->where('target_demographic_to', $request->targetDemoAgeTo)
                                           ->orderBy('id', 'desc');
            }else{
                $influencers = $influencers->where('target_demographic_to', $request->targetDemoAgeTo)->orderBy('id', 'desc');
            }
        }

        if($request->targetDemoSex){
            $influencers = $influencers->whereJsonContains('target_demographic_sex', $request->targetDemoSex)
                                       ->orderBy('id', 'desc');
        }

        if ($request->completedJob) {
            $influencers = $influencers->where('completed_order', '>', $request->completedJob)->orderBy('completed_order', 'desc');
        }

        return $influencers->with('socialLink')->orderBy('completed_order', 'desc')->paginate(getPaginate(18));
    }

    function filterFreelancer(Request $request){
        $freelancers = $this->getFreelancer($request);
        $thisFreelancerId = null;
        return view($this->activeTemplate . 'filtered_freelancer', compact('freelancers','thisFreelancerId'));
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

        if ($request->search) {
            $search      = $request->search;
            $freelancers = $freelancers->where(function ($query) use ($search) {
                $query->where('firstname', "LIKE", "%$search%")
                    ->orWhere('lastname', 'LIKE', "%%$search")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('profession', 'LIKE', "%$search%");
            });
        }

        if ($request->country) {
            $freelancers = $freelancers->whereJsonContains('address', ['country' => $request->country]);
        }

        if ($request->country && $request->city) {
            $freelancers = $freelancers->whereJsonContains('address', ['country' => $request->country])->where('city_id', $request->city);
        }

        if ($request->language) {
            $freelancers = $freelancers->whereRaw('JSON_CONTAINS(languages, \'{"'.$request->language.'": {}}\')');
        }

        if ($request->priceRange) {
            $priceString = str_replace(['€', ' '], '', $request->priceRange);
            $values = explode('-', $priceString);
            $min = (int) $values[0];
            $max = (int) $values[1];
            $freelancers = $freelancers->whereHas('services', function ($query) use ($min, $max) {
                $query->whereBetween('price', [$min, $max]);
            });
        }

        if ($request->rating) {
            $freelancers = $freelancers->where('rating', '>=', $request->rating);
        }

        if ($request->sort == 'top_rated') {
            $freelancers = $freelancers->where('completed_order', '>', 0)->orderBy('completed_order', 'desc');
        }

        if ($request->completedJob) {
            $freelancers = $freelancers->where('completed_order', '>', $request->completedJob)->orderBy('completed_order', 'desc');
        }

        return $freelancers->with('socialLink')->orderBy('completed_order', 'desc')->paginate(getPaginate(18));
    }

    public function attachmentDownload($attachment, $conversation_id, $type) {
        if($type == 'order'){
            OrderConversation::where('id',$conversation_id)->firstOrFail();
        }elseif($type == 'hiring'){
            HiringConversation::where('id',$conversation_id)->firstOrFail();
        }else{
            ConversationMessage::where('id',$conversation_id)->firstOrFail();
        }
        $path = getFilePath('conversation');
        $file = $path . '/' . $attachment;
        return response()->download($file);
    }

    protected function priceRangeCalc($products)
    {
        $minPrice = count($products) ? intval($products->min('price')) : 0;
        $maxPrice = count($products) ? intval($products->max('price')) : 0;

        return [$minPrice, $maxPrice];
    }

    public function jobs(){
        $pageTitle = 'Jobs';
        $languageData = config('languages');
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $query     = Job::active()->checkData()->latest()->with(['user','influencer','freelancer']);
        if (request()->skill) {
            $query->whereJsonContains('skill',request()->skill);
        }
        
        $jobcategories       = JobCategory::active()->get();
        $products   = $query->paginate(getPaginate());
        $priceRange = $this->priceRangeCalc($products);
        $type       = 'service';
        $jobMin = Job::orderBy('price', 'asc')->first();
        $jobMinPrice = $jobMin->price;
        $jobMax = Job::orderBy('price', 'desc')->first();
        $jobMaxPrice = round($jobMax->price);
        return view($this->activeTemplate . 'jobs.list', compact('pageTitle', 'products', 'priceRange', 'type','jobcategories','jobMinPrice','jobMaxPrice','languageData','countries'));
    }

    public function jobDetails($slug, $id){

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

        $pageTitle           = 'Job Details';
        $productDetails      = Job::where('id', $id)->active()->checkData()->firstOrFail();

        // return $productDetails;
        // $comments            = Comment::where('job_id', $productDetails->id)->latest()->with(['user', 'replies', 'replies.user'])->limit(6)->get();
        // $seoContents         = $this->seoContentSliced($productDetails->skill, $productDetails->name, $productDetails->description, getFilePath('job'), $productDetails->image, getFileSize('job'));
        // $existingJobBidCheck = JobBid::where('job_id', $productDetails->id)->where('user_id', auth()->id() ?? 0 )->exists();

        return view($this->activeTemplate . 'jobs.details', compact('pageTitle', 'productDetails','user_type','user'));
    }

    public function jobsByCategory($slug, $id)
    {
        $category  = JobCategory::where('id', $id)->active()->with('JobSubCategories', function ($subCategories) {
            $subCategories->active();
        })->firstOrFail();
        $pageTitle = $category->name;
       
        $jobs  = Job::where('job_category_id', $category->id)->active()->whereHas('JobSubCategory', function ($subCategory) {
            $subCategory->active();
        })->latest()->limit(10)->with('user','influencer','freelancer')->get();

        return view($this->activeTemplate . 'jobs.products', compact('pageTitle', 'category', 'jobs'));
    }

    
    function filterJob(Request $request){
        $products = $this->getFilteredJobs($request);
        $type     = 'service';
        return view($this->activeTemplate . 'partials.job_template', compact('products','type'));
    }

    protected function getFilteredJobs($request){
        $products = Job::active()->checkData()->latest()->with(['user','influencer','freelancer']);

        if ($request->categories) {
            $products  = $products->whereIn('job_category_id', $request->categories);
        }

        if ($request->jobType) {
            if($request->jobType == 'all'){
                $products  = $products;
            }else{
                $products  = $products->where('job_type', $request->jobType);
            }
            
        }

        if ($request->jobPostedBy) {
            if($request->jobPostedBy == 'user'){
                $products  = $products->where('user_id', '!=', 0);
            }else if($request->jobPostedBy == 'influencer'){
                $products  = $products->where('influencer_id', '!=', 0);
            }else if($request->jobPostedBy == 'freelancer'){
                $products  = $products->where('freelancer_id', '!=', 0);
            }else{
                $products = $products;
            }
        }

        if ($request->priceRange) {
            $priceString = str_replace(['€', ' '], '', $request->priceRange);
            $values = explode('-', $priceString);
            $min = (int) $values[0];
            $max = (int) $values[1];
            $products = $products->whereBetween('price', [$min, $max]);
        }

        if($request->country != ''){
            $products  = $products->where('target_country', $request->country);
        }

        if($request->language != ''){
            $products  = $products->where('language', $request->language);
        }

        if ($request->targetDemoAgeFrom != ''){
            $products = $products->where('target_demographic_from', $request->targetDemoAgeFrom);
        }

        if ($request->targetDemoAgeTo != ''){
            if($request->targetDemoAgeFrom != ''){
                $products = $products->where('target_demographic_from', $request->targetDemoAgeFrom)
                                           ->where('target_demographic_to', $request->targetDemoAgeTo);
            }else{
                $products = $products->where('target_demographic_to', $request->targetDemoAgeTo);
            }
        }

        if ($request->targetDemoGender) {
            if($request->targetDemoGender == 'Male'){
                $products  = $products->where('target_demographic_gender', '=', 'Male');
            }else if($request->targetDemoGender == 'Female'){
                $products  = $products->where('target_demographic_gender', '=', 'Female');
            }else if($request->targetDemoGender == 'LGBTQ+'){
                $products  = $products->where('target_demographic_gender', '=', 'LGBTQ+');
            }else{
                $products = $products;
            }
        }

        if ($request->campSocial) {
            foreach($request->campSocial AS $social){
                if($social != 'all'){
                    $products  = $products->whereJsonContains('campaign_social', $social);
                }else{
                    $products = $products;
                }
            }
            
        }

       return $products->paginate(getPaginate());
    }
}
