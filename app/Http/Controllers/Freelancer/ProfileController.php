<?php

namespace App\Http\Controllers\Freelancer;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Category;
use App\Models\Freelancer;
use App\Models\FreelancerEducation;
use App\Models\FreelancerQualification;
use App\Models\FreelancerOrder;
use App\Models\FreelancerService;
use App\Models\SocialLink;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller {

    public function profile() {
        $pageTitle    = "Profile Setting";
        
        $freelancer   = Freelancer::where('id', authFreelancerId())->with('education', 'qualification', 'socialLink', 'categories')->firstOrFail();
        
        $languageData = config('languages');
        // $info        = json_decode(json_encode(getIpInfo()), true);
        $info        =["code"=>[]];
        $mobile_code = @implode(',', $info['code']);
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $cities = City::orderBy('name')->get();
        $data['ongoing_orders']   = FreelancerOrder::inprogress()->where('freelancer_id', $freelancer->id)->count();
        $data['completed_orders'] = FreelancerOrder::completed()->where('freelancer_id', $freelancer->id)->count();
        $data['pending_orders']   = FreelancerOrder::pending()->where('freelancer_id', $freelancer->id)->count();
        $data['total_services']   = FreelancerService::where('status', 1)->where('freelancer_id', $freelancer->id)->count();
        $freelancerFategories     = Category::where('belongs_to','freelancer')->get();
        return view($this->activeTemplate . 'freelancer.profile_setting', compact('pageTitle', 'freelancer', 'countries','cities','mobile_code', 'data', 'languageData','freelancerFategories'));
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

        $freelancer = authFreelancer();
        $folderPathForPhoto = public_path('sample/'.$freelancer->username.'/photos/');
        if ($request->hasFile('image')) {
            try {
                $imageName = time().'_avatar_'.'.'.$request->image->getClientOriginalExtension();
                $request->image->move($folderPathForPhoto, $imageName);
                $freelancer->image = $imageName;
            } catch (\Exception$exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }

        }

        $freelancer->firstname                  = $request->firstname;
        $freelancer->lastname                   = $request->lastname;
        $freelancer->age                        = $request->age;
        $freelancer->sex                        = $request->sex;
        $freelancer->target_demographic_from    = $request->target_demographic_from;
        $freelancer->target_demographic_to      = $request->target_demographic_to;
        $freelancer->past_company_brands        = $request->past_companies_brands;
        $freelancer->available_for_events       = $request->available_for_events;
        $freelancer->available_for_travelling   = $request->available_for_travelling;
        $freelancer->about_me                   = $request->about_me;
        $freelancer->mobile                     = $request->mobile;
        $freelancer->country_code               = $request->country_code;
        $freelancer->city_id                    = $request->city_id;

        $freelancer->address = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => $request->country,
            'city'    => $request->city,
        ];

        $freelancer->profession = $request->profession;
        $freelancer->summary    = nl2br($request->summary);

        if ($request->category) {
            $categoriesId = $request->category;
            $freelancer->categories()->sync($categoriesId);
        }

        $freelancer->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function updateSamplePhotos(Request $request){
        $freelancer = authFreelancer();
        $sample_photos = (array)json_decode($freelancer->sample_photos);

        $folderPathForPhoto = public_path('sample/'.$freelancer->username.'/photos/');
        
        if (!File::exists($folderPathForPhoto)) {
            File::makeDirectory($folderPathForPhoto, 0777, true, true);
        }
        
        if (isset($request->sample_photos)){
            $random = rand(10,1000);
            foreach(($request->sample_photos) AS $photo){
                $photoName = date('Ymd').'_'.$random.'_'.'.'.$photo->getClientOriginalExtension();
                $photo->move($folderPathForPhoto, $photoName);
                $sample_photos[] = $photoName;
            }
            $sample_photos = (array)$sample_photos;
        }

        $freelancer->sample_photos = json_encode($sample_photos);
        $freelancer->save();
        $notify[] = ['success', 'Photos Added successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSamplePhoto($photo){
        $new_sample_photos = [];
        $freelancer = authFreelancer();
        $sample_photos = (array)json_decode($freelancer->sample_photos);

        $new_sample_photos = array_diff($sample_photos, [$photo]);
        $new_sample_photos = (array)$new_sample_photos;

        $folderPathForPhoto = public_path('sample/'.$freelancer->username.'/photos/'.$photo);
        
        if (File::exists($folderPathForPhoto)) {
            File::delete($folderPathForPhoto);
        }

        $freelancer->sample_photos = json_encode($new_sample_photos);
        $freelancer->save();

        return ['status' =>'success'];

    }

    public function updateSampleVideos(Request $request){
        $freelancer = authFreelancer();
        $sample_videos = (array)json_decode($freelancer->sample_videos);
        $folderPathForVideo = public_path('sample/'.$freelancer->username.'/videos/');
        
        if (!File::exists($folderPathForVideo)) {
            File::makeDirectory($folderPathForVideo, 0777, true, true);
        }
        if (isset($request->sample_videos)){
          
            foreach(($request->sample_videos) AS $video){
                $random = rand(10,1000);
                $videoName =  date('Ymd').'_'.$random.'_'.'.'.$video->getClientOriginalExtension();
                $video->move($folderPathForVideo, $videoName);
                $sample_videos[] = $videoName;
            }
            $sample_videos = (array)$sample_videos;
        }

        $freelancer->sample_videos = json_encode($sample_videos);
        $freelancer->save();
        $notify[] = ['success', 'Videos Added successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSampleVideo($video){
        $new_sample_videos = [];
        $freelancer = authFreelancer();
        $sample_videos = (array)json_decode($freelancer->sample_videos);

        $new_sample_videos = array_diff($sample_videos, [$video]);
        $new_sample_videos = (array)$new_sample_videos;

        $folderPathForVideo = public_path('sample/'.$freelancer->username.'/videos/'.$video);
        
        if (File::exists($folderPathForVideo)) {
            File::delete($folderPathForVideo);
        }

        $freelancer->sample_videos = json_encode($new_sample_videos);
        $freelancer->save();

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
                $freelancer = authFreelancer();
                $freelancer->home_screen_photos = json_encode($request->home_screen_photos);
                $freelancer->save();
        
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
                $freelancer = authFreelancer();;
                $freelancer->home_screen_videos = json_encode($request->home_screen_videos);
                $freelancer->save();
        
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

        $freelancer = authFreelancer();
        $freelancer->skills = $request->skills;
        $freelancer->save();

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

        $freelancer = authFreelancer();
        $oldLanguages = authFreelancer()->languages ?? [];

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

        $freelancer->languages = $languages;
        $freelancer->save();

        $notify[] = ['success', 'Language added successfully'];
        return back()->withNotify($notify);
    }

    public function removeLanguage($language) {
        $freelancer = authFreelancer();
        $oldLanguages   = $freelancer->languages ?? [];
        $addedLanguages = array_keys($oldLanguages);

        if (in_array($language, $addedLanguages)) {
            unset($oldLanguages[$language]);
        }

        $freelancer->languages = $oldLanguages;
        $freelancer->save();

        $notify[] = ['success', 'Language removed successfully'];
        return back()->withNotify($notify);
    }

    public function addSocialLink(Request $request, $id = 0) {
        $request->validate([
            'social_platform' => 'required',
            'username'   => 'required',
        ]);

        $followers = 0;
        if($request->social_platform == "instagram"){
            $api_token = env('API_TOKEN');
            $response = Http::post('https://api.apify.com/v2/acts/apify~instagram-profile-scraper/run-sync-get-dataset-items?token='.$api_token, ['usernames' => [$request->username], ]);

            if ($response->successful()) {
                $followers = $response[0]['followersCount'];
            } else {
                $error = "Something went wrong";
            }
        }

        if($request->social_platform == "youtube"){
            $api_token = env('API_TOKEN');
            $response = Http::post('https://api.apify.com/v2/acts/apify~instagram-profile-scraper/run-sync-get-dataset-items?token='.$api_token, ['usernames' => [$request->username], ]);

            if ($response->successful()) {
                $followers = $response[0]['followersCount'];
            } else {
                $error = "Something went wrong";
            }
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

        $social->social_icon     = $request->social_icon;
        $social->social_platform = $request->social_platform;
        $social->username        = $request->username;
        $social->url        = $request->url;
        $social->followers       = $followers;
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
        return view($this->activeTemplate . 'freelancer.password', compact('pageTitle'));
    }

    public function addEducation(Request $request, $id = 0) {

        $request->validate([
            'country'    => 'required|string',
            'institute'  => 'required|string',
            'degree'     => 'required|string',
            'start_year' => 'required|date_format:Y',
            'end_year'   => 'required|date_format:Y|after_or_equal:start_year',
        ]);

        $freelancerId = authFreelancerId();

        if ($id) {
            $education    = FreelancerEducation::where('freelancer_id', $freelancerId)->findOrFail($id);
            $notification = 'Education updated successfully';
        } else {
            $education                = new FreelancerEducation();
            $education->freelancer_id = $freelancerId;
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
        $freelancerId = authFreelancerId();
        FreelancerEducation::where('freelancer_id', $freelancerId)->where('id', $id)->delete();
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

        $freelancerId = authFreelancerId();

        if ($id) {
            $education    = FreelancerQualification::where('freelancer_id', $freelancerId)->findOrFail($id);
            $notification = 'Qualification updated successfully';
        } else {
            $education                = new FreelancerQualification();
            $education->freelancer_id = $freelancerId;
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
        $freelancerId = authFreelancerId();
        FreelancerQualification::where('freelancer_id', $freelancerId)->where('id', $id)->delete();
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

        $user = authFreelancer();

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
