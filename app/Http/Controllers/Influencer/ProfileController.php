<?php

namespace App\Http\Controllers\Influencer;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\InfluencerEducation;
use App\Models\InfluencerQualification;
use App\Models\City;
use App\Models\Order;
use App\Models\Service;
use App\Models\Category;
use App\Models\SocialLink;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller {

    public function profile() {
        $pageTitle    = "Profile Setting";
        $influencer   = Influencer::where('id', authInfluencerId())->with('education', 'qualification', 'socialLink', 'categories')->firstOrFail();
        $languageData = config('languages');
        // $info        = json_decode(json_encode(getIpInfo()), true);
        $info        =["code"=>[]];
        $mobile_code = @implode(',', $info['code']);

        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $cities = City::orderBy('name')->get();
        $data['ongoing_orders']   = Order::inprogress()->where('influencer_id', $influencer->id)->count();
        $data['completed_orders'] = Order::completed()->where('influencer_id', $influencer->id)->count();
        $data['pending_orders']   = Order::pending()->where('influencer_id', $influencer->id)->count();
        $data['total_services']   = Service::where('status', 1)->where('influencer_id', $influencer->id)->count();
        $influencerFategories     = Category::where('belongs_to','influencer')->get();
        return view($this->activeTemplate . 'influencer.profile_setting', compact('pageTitle', 'influencer', 'countries', 'cities' ,'mobile_code', 'data', 'languageData','influencerFategories'));
    }

    public function submitProfile(Request $request) {
        $request->validate([
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'profession' => 'nullable|max:40|string',
            'summary'    => 'nullable|string',
            'image'      => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required'  => 'Last name field is required',
        ]);

        $influencer = authInfluencer();
        $folderPathForPhoto = public_path('sample/'.$influencer->username.'/photos/');
        if ($request->hasFile('image')) {
            try {
                $imageName = time().'_avatar_'.'.'.$request->image->getClientOriginalExtension();
                $request->image->move($folderPathForPhoto, $imageName);
                $influencer->image = $imageName;
            } catch (\Exception$exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }

        }

        $influencer->firstname                = $request->firstname;
        $influencer->lastname                 = $request->lastname;
        $influencer->age                      = $request->age;
        $influencer->sex                      = $request->sex;
        $influencer->target_demographic_from  = $request->target_demographic_from;
        $influencer->target_demographic_to    = $request->target_demographic_to;
        $influencer->target_demographic_sex   = json_encode($request->target_demographic_sex);
        $influencer->past_company_brands      = $request->past_companies_brands;
        $influencer->available_for_events     = $request->available_for_events;
        $influencer->available_for_travelling = $request->available_for_travelling;
        $influencer->about_me                 = $request->about_me;
        $influencer->mobile                   = $request->mobile;
        $influencer->country                  = $request->country;
        $influencer->country_code             = $request->country_code;
        $influencer->city_id                  = $request->city_id;

        $influencer->address = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => $request->country,
            'city'    => $request->city,
        ];

        $influencer->profession = $request->profession;
        $influencer->summary    = nl2br($request->summary);

        if ($request->category) {
            $categoriesId = $request->category;
            $influencer->categories()->sync($categoriesId);
        }

        $influencer->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function updateSamplePhotos(Request $request){
        $influencer = authInfluencer();
        $sample_photos = (array)json_decode($influencer->sample_photos);

        $folderPathForPhoto = public_path('sample/'.$influencer->username.'/photos/');
        
        if (!File::exists($folderPathForPhoto)) {
            File::makeDirectory($folderPathForPhoto, 0777, true, true);
        }
       
        if (isset($request->sample_photos)){
          
            foreach(($request->sample_photos) AS $photo){
                $sl = rand();
                $photoName = date('Ymd').'_'.$sl.'_'.'.'.$photo->getClientOriginalExtension();
                $photo->move($folderPathForPhoto, $photoName);
                $sample_photos[] = $photoName;
            }
            $sample_photos = (array)$sample_photos;
        }

        $influencer->sample_photos = json_encode($sample_photos);
        $influencer->save();
        return redirect()->back();
    }

    public function deleteSamplePhoto($photo){
        $new_sample_photos = [];
        $influencer = authInfluencer();
        $sample_photos = (array)json_decode($influencer->sample_photos);

        $new_sample_photos = array_diff($sample_photos, [$photo]);
        $new_sample_photos = (array)$new_sample_photos;

        $folderPathForPhoto = public_path('sample/'.$influencer->username.'/photos/'.$photo);
        
        if (File::exists($folderPathForPhoto)) {
            File::delete($folderPathForPhoto);
        }

        $influencer->sample_photos = json_encode($new_sample_photos);
        $influencer->save();

        return ['status' =>'success'];

    }

    public function updateSampleVideos(Request $request){
        $influencer = authInfluencer();
        $sample_videos = (array)json_decode($influencer->sample_videos);
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
            $sample_videos = (array)$sample_videos;
        }

        $influencer->sample_videos = json_encode($sample_videos);
        $influencer->save();
        return redirect()->back();
    }

    public function deleteSampleVideo($video){
        $new_sample_videos = [];
        $influencer = authInfluencer();
        $sample_videos = (array)json_decode($influencer->sample_videos);

        $new_sample_videos = array_diff($sample_videos, [$video]);
        $new_sample_videos = (array)$new_sample_videos;

        $folderPathForVideo = public_path('sample/'.$influencer->username.'/videos/'.$video);
        
        if (File::exists($folderPathForVideo)) {
            File::delete($folderPathForVideo);
        }

        $influencer->sample_videos = json_encode($new_sample_videos);
        $influencer->save();

        return ['status' =>'success'];

    }

    public function addPhotoToHomescreen(Request $request){
        if(isset($request->home_screen_photos)){
            if(count($request->home_screen_photos) > 5 || count($request->home_screen_photos) < 3){
                if(count($request->home_screen_photos) > 5){
                    $notify[] = ['error', 'more than 5 photos can not be added'];
                }

                if(count($request->home_screen_photos) < 3){
                    $notify[] = ['error', 'less than 3 photos can not be added'];
                }

                return back()->withNotify($notify);

            }else{
                $influencer = authInfluencer();
                $influencer->home_screen_photos = json_encode($request->home_screen_photos);
                $influencer->save();
        
                $notify[] = ['success', 'Photos added to Homescreen'];
                return back()->withNotify($notify);
            }
        }else{
            $notify[] = ['warning', 'please select photos to add'];
            return back()->withNotify($notify);
        }

    }

    public function addVideoToHomescreen(Request $request)
    {
        if(isset($request->home_screen_videos)){
            if(count($request->home_screen_videos) != 2){
                if(count($request->home_screen_videos) > 2){
                    $notify[] = ['error', 'more than 2 videos can not be added'];
                }else{
                    $notify[] = ['error', 'less than 2 videos can not be added'];
                }
                return back()->withNotify($notify);

            }else{
                $influencer = authInfluencer();
                $influencer->home_screen_videos = json_encode($request->home_screen_videos);
                $influencer->save();
        
                $notify[] = ['success', 'videos added to Homescreen'];
                return back()->withNotify($notify);
            }
        }else{
            $notify[] = ['warning', 'please select videos to add'];
            return back()->withNotify($notify);
        }
    }

    public function submitSkill(Request $request) {

        $request->validate([
            "skills"   => "nullable|array",
            "skills.*" => "required|string",
        ]);

        $influencer         = authInfluencer();
        $influencer->skills = $request->skills;
        $influencer->save();

        $notify[] = ['success', 'Skill added successfully'];
        return back()->withNotify($notify);
    }

    public function addLanguage(Request $request) {

        $request->validate([
            'name'      => 'required|string|max:40',
            'listening' => 'required|in:Basic,Medium,Fluent',
            'speaking'  => 'required|in:Basic,Medium,Fluent',
            'writing'   => 'required|in:Basic,Medium,Fluent',
        ]);

        $influencer   = authInfluencer();
        $oldLanguages = authInfluencer()->languages ?? [];

        $addedLanguages = array_keys($oldLanguages);

        if (in_array(strtolower($request->name), array_map('strtolower', $addedLanguages))) {
            $notify[] = ['error', $request->name . ' already added'];
            return back()->withNotify($notify);
        }

        $newLanguage[$request->name] = [
            'listening' => $request->listening,
            'speaking'  => $request->speaking,
            'writing'   => $request->writing,
        ];

        $languages = array_merge($oldLanguages, $newLanguage);

        $influencer->languages = $languages;
        $influencer->save();

        $notify[] = ['success', 'Language added successfully'];
        return back()->withNotify($notify);
    }

    public function removeLanguage($language) {
        $influencer     = authInfluencer();
        $oldLanguages   = $influencer->languages ?? [];
        $addedLanguages = array_keys($oldLanguages);

        if (in_array($language, $addedLanguages)) {
            unset($oldLanguages[$language]);
        }

        $influencer->languages = $oldLanguages;
        $influencer->save();

        $notify[] = ['success', 'Language removed successfully'];
        return back()->withNotify($notify);
    }

    public function addSocialLink(Request $request, $id = 0) {
        $request->validate([
            'social_platform' => 'required',
            'username'   => 'required',
        ]);

        $followers = 0;
        $icon = NUll;
        if($request->social_platform == "instagram"){
            $api_token = env('API_TOKEN');
            $response = Http::post('https://api.apify.com/v2/acts/apify~instagram-profile-scraper/run-sync-get-dataset-items?token='.$api_token, ['usernames' => [$request->username], ]);
            
            if ($response->successful()) {
                $followers = $response[0]['followersCount'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-instagram" style="color: #fe0090;"></i>';
        }

        if($request->social_platform == "facebook"){
            $api_token = env('API_TOKEN');
            $params = [
                        'startUrls'=>[
                            ['url'=> $request->url]
                        ]
                     ];
            $response = Http::post('https://api.apify.com/v2/acts/apify~facebook-pages-scraper/run-sync-get-dataset-items?token='.$api_token, $params);
            if ($response->successful()) {
                $followers = $response[0]['followers'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-facebook" style="color: #1877f2;"></i>';
        }

        if($request->social_platform == "linkedin"){
            $api_token = env('API_TOKEN');
            $params = [
                'action'=>'get-companies',
                'isName'=> false,
                'isUrl'=>false,
                'keywords'=>[$request->username]
            ];
            $response = Http::post('https://api.apify.com/v2/acts/bebity~linkedin-premium-actor/run-sync-get-dataset-items?token='.$api_token, $params);
            if ($response->successful()) {
                $followers = $response[0]['followerCount'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-linkedin" style="color: #0075b4;"></i>';
        }

        if($request->social_platform == "youtube"){
            $api_token = env('API_TOKEN');
            $params =[
                "downloadSubtitles"=> false,
                "hasCC"=> false,
                "hasLocation"=> false,
                "hasSubtitles"=> false,
                "is360"=> false,
                "is3D"=> false,
                "is4K"=> false,
                "isBought"=> false,
                "isHD"=> false,
                "isHDR"=> false,
                "isLive"=> false,
                "isVR180"=> false,
                "maxResultStreams"=> 0,
                "maxResults"=> 1,
                "maxResultsShorts"=> 0,
                "preferAutoGeneratedSubtitles"=> false,
                "saveSubsToKVS"=> false,
                "searchKeywords"=> $request->username,
                "startUrls"=> [
                    [
                        "url" => $request->url
                    ],
                ]
            ];
            $response = Http::post('https://api.apify.com/v2/acts/streamers~youtube-scraper/run-sync-get-dataset-items?token='.$api_token, $params);
            if ($response->successful()) {
                $followers = $response[0]['numberOfSubscribers'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-youtube" style="color: #ff0103;"></i>';
        }

        if($request->social_platform == "tiktok"){
            $api_token = env('API_TOKEN');
            $new_api_token='apify_api_kzH3jGloqIAXXRicyJqm9upjOsCi8n1Kuwgq';
            $params =[
                "disableCheerioBoost"=> false,
                "disableEnrichAuthorStats"=> false,
                "profiles"=> [$request->username],
                "shouldDownloadCovers"=> false,
                "shouldDownloadSlideshowImages"=> false,
                "shouldDownloadVideos"=> false
            ];
            $response = Http::post('https://api.apify.com/v2/acts/clockworks~tiktok-scraper/run-sync-get-dataset-items?token='.$new_api_token, $params);
            if ($response->successful()) {
                $followers = $response[0]['authorMeta']['fans'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-tiktok" style="color: #ff0103;"></i>';
        }

        if($request->social_platform == "twitter"){
            $api_token = env('API_TOKEN');
            $new_api_token='apify_api_kzH3jGloqIAXXRicyJqm9upjOsCi8n1Kuwgq';
            $params =[
                "addUserInfo"=> true,
                "startUrls"=> [
                    [
                        "url" => $request->url
                    ]
                ],
                "tweetsDesired"=> 1
            ];
            $response = Http::post('https://api.apify.com/v2/acts/quacker~twitter-url-scraper/run-sync-get-dataset-items?token='.$new_api_token, $params);
            if ($response->successful()) {
                $followers = $response[0]['user']['followers_count'];
            } else {
                $error = "Something went wrong";
            }
            $icon = '<i class="fab fa-twitter" style="color: #179cf0;"></i>';
        }

        $influencerId = authInfluencerId();

        if ($id) {
            $social       = SocialLink::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Social link updated successfully';
        } else {
            $social                = new SocialLink();
            $social->influencer_id = $influencerId;
            $notification          = 'Social link added successfully';
        }

        $social->social_icon        = $icon;
        $social->social_platform    = $request->social_platform;
        $social->username           = $request->username;
        $social->url                = $request->url;
        $social->followers          = $followers;
        $social->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function removeSocialLink($id) {
        $influencerId = authInfluencerId();
        SocialLink::where('influencer_id', $influencerId)->findOrFail($id)->delete();
        $notify[] = ['success', 'Social link removed successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        return view($this->activeTemplate . 'influencer.password', compact('pageTitle'));
    }

    public function addEducation(Request $request, $id = 0) {

        $request->validate([
            'country'    => 'required|string',
            'institute'  => 'required|string',
            'degree'     => 'required|string',
            'start_year' => 'required|date_format:Y',
            'end_year'   => 'required|date_format:Y|after_or_equal:start_year',
        ]);

        $influencerId = authInfluencerId();

        if ($id) {
            $education    = InfluencerEducation::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Education updated successfully';
        } else {
            $education                = new InfluencerEducation();
            $education->influencer_id = $influencerId;
            $notification             = 'Education added successfully';
        }

        $education->country    = $request->country;
        $education->institute  = $request->institute;
        $education->degree     = $request->degree;
        $education->start_year = $request->start_year;
        $education->end_year   = $request->end_year;
        $education->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function removeEducation($id) {
        $influencerId = authInfluencerId();
        InfluencerEducation::where('influencer_id', $influencerId)->where('id', $id)->delete();
        $notify[] = ['success', 'Education remove successfully'];
        return back()->withNotify($notify);
    }

    public function addQualification(Request $request, $id = 0) {
        $request->validate([
            'certificate'  => 'required|string',
            'organization' => 'required|string',
            'summary'      => 'nullable|string',
            'year'         => 'required|date_format:Y',
        ]);

        $influencerId = authInfluencerId();

        if ($id) {
            $education    = InfluencerQualification::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Qualification updated successfully';
        } else {
            $education                = new InfluencerQualification();
            $education->influencer_id = $influencerId;
            $notification             = 'Qualification added successfully';
        }

        $education->certificate  = $request->certificate;
        $education->organization = $request->organization;
        $education->summary      = $request->summary;
        $education->year         = $request->year;
        $education->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function removeQualification($id) {
        $influencerId = authInfluencerId();
        InfluencerQualification::where('influencer_id', $influencerId)->where('id', $id)->delete();
        $notify[] = ['success', 'Qualification remove successfully'];
        return back()->withNotify($notify);
    }

    public function submitPassword(Request $request) {

        $passwordValidation = Password::min(6);
        $general            = gs();

        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = authInfluencer();

        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changes successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }

    }

}
