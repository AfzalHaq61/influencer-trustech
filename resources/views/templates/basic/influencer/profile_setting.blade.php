@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-section">
        <div class="container">
            <div class="row gy-4 gy-sm-5">
                <div class="col-lg-12">
                    <div class="dashboard-body">
                        <div class="card custom--card influencer-profile-edit">
                            <div class="card-body has-select2">
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gy-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="profile-thumb text-center">
                                                    <div class="thumb">
                                                        <img id="upload-img" src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" alt="userProfile">
                                                        <label class="badge badge--icon badge--fill-base update-thumb-icon" for="update-photo"><i class="las la-pen"></i></label>
                                                    </div>
                                                    <div class="profile__info">
                                                        <input type="file" name="image" class="form-control d-none" id="update-photo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="firstname" class="col-form-label">@lang('First Name')</label>
                                                    <input type="text" class="form-control form--control" id="firstname" name="firstname" value="{{ __($influencer->firstname) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="lastname" class="col-form-label">@lang('Last Name')</label>
                                                    <input type="text" class="form-control form--control" id="lastname" name="lastname" value="{{ __($influencer->lastname) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="lastname" class="col-form-label">@lang('Age')</label>
                                                    <input type="number" class="form-control form--control" id="age" name="age" value="{{ __($influencer->age) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="col-form-label">@lang('Sex')</label>
                                                    <select class="form-control form--control" name="sex">
                                                        <option>select</option>
                                                        <option value="male"    {{$influencer->sex == 'male'   ?'selected':''}}>@lang('Male')</option>
                                                        <option value="female"  {{$influencer->sex == 'female' ?'selected':''}}>@lang('Female')</option>
                                                        <option value="lgbtq+"  {{$influencer->sex == 'lgbtq+' ?'selected':''}}>@lang('LGBTQ')+</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="firstname" class="col-form-label">@lang('Address')</label>
                                                    <input type="text" class="form-control form--control" id="address" name="address" value="{{ __($influencer->address->address) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="lastname" class="col-form-label">@lang('State')</label>
                                                    <input type="text" class="form-control form--control" id="state" name="state" value="{{ __($influencer->address->state) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="firstname" class="col-form-label">@lang('Zip Code')</label>
                                                    <input type="text" class="form-control form--control" id="zip" name="zip" value="{{ __($influencer->address->zip) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label" for="email">@lang('City')</label>
                                                    <select name="city_id" id="city_id" class="form-select form--control" required>
                                                        <option>@lang('Select')</option>
                                                        @foreach ($cities as $key => $city)
                                                            <option data-city="{{ $city->name }}" value="{{ $city->id }}" @selected($city->id == $influencer->city_id)>{{ __($city->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" id="cityName" name="city" value="">
                                                 
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label" for="email">@lang('Country')</label>
                                                    <select name="country" class="form-select form--control" required>
                                                        <option data-mobile_code="30" vlaue="Greece" data-code="GR" selected>@lang('Greece')</option>
                                                        {{-- @foreach ($countries as $key => $country)
                                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}" {{$influencer->address->country == $country->country ?'selected':''}}>
                                                                {{ __($country->country) }}
                                                            </option>
                                                        @endforeach --}}
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label" for="mobile">@lang('Mobile')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text mobile-code"></span>
                                                        <input type="hidden" name="mobile_code">
                                                        <input type="hidden" name="country_code">
                                                        <input type="number" name="mobile" value="{{ __($influencer->mobile) }}" class="form-control form--control checkUser" required>
                                                    </div>
                                                    <small class="text-danger mobileExist"></small>
                                                </div>
                                                 
                                                <div class="form-group col-sm-6">
                                                    <label class="col-form-label">@lang('Target Demography From')</label>
                                                    <select class="form-control form--control" name="target_demographic_from">
                                                        <option>@lang('select')</option>
                                                        @for($age=5; $age<=100; $age+=5)
                                                            <option value="{{$age}}" {{$influencer->target_demographic_from == $age ?'selected':''}}>{{$age}} &nbsp; @lang('Year')</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                              
                                                <div class="form-group col-sm-6">
                                                    <label class="col-form-label">@lang('Target Demography To')</label>
                                                    <select class="form-control form--control" name="target_demographic_to">
                                                        <option>@lang('select')</option>
                                                        @for($age=5; $age<=100; $age+=5)
                                                            <option value="{{$age}}" {{$influencer->target_demographic_to == $age ?'selected':''}}>{{$age}} &nbsp; @lang('Year')</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                @php
                                                    $demographic_sex = [];
                                                    $target_demographic_sex =json_decode($influencer->target_demographic_sex);
                                                    foreach ($target_demographic_sex as $sex) {
                                                        $demographic_sex[] = $sex;
                                                    }
                                                @endphp

                                            <div class="form-group col-sm-12">
                                                <label for="professional-headline" class="col-form-label">@lang('Target Demography Sex')</label>
                                                <select class="from--control form-control select2-multi-select form-select" name="target_demographic_sex[]"  multiple>
                                                        <option value="Male"   @if (in_array('Male', $demographic_sex)) selected @endif>@lang('Male')</option>
                                                        <option value="Female" @if (in_array('Female', $demographic_sex)) selected @endif>@lang('Female')</option>
                                                        <option value="LBGTQ+" @if (in_array('LBGTQ+', $demographic_sex)) selected @endif>@lang('LGBTQ+')</option>
                                                </select>
                                            </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="col-form-label">@lang('Available For Events')</label>
                                                    <select class="form-control form--control" name="available_for_events">
                                                        <option>select</option>
                                                        <option value="yes" {{$influencer->available_for_events == 'yes' ?'selected':''}}>@lang('Yes')</option>
                                                        <option value="no"  {{$influencer->available_for_events == 'no' ?'selected':''}}>@lang('No')</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="col-form-label">@lang('Available For Travelling')</label>
                                                    <select class="form-control form--control" name="available_for_travelling">
                                                        <option>select</option>
                                                        <option value="yes" {{$influencer->available_for_travelling == 'yes' ?'selected':''}}>@lang('Yes')</option>
                                                        <option value="no"  {{$influencer->available_for_travelling == 'no' ?'selected':''}}>>@lang('No')</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="col-form-label">@lang('Past Companies/Brands')</label>
                                                    <input type="text" class="form-control form--control" name="past_companies_brands" value="{{ __($influencer->past_company_brands) }}">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="col-form-label">@lang('Bio')</label>
                                                    <input type="text" class="form-control form--control" id="professional-headline" name="about_me" value="{{ __($influencer->about_me) }}">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="professional-headline" class="col-form-label">@lang('Profession')</label>
                                                    <input type="text" class="form-control form--control" id="professional-headline" name="profession" value="{{ __($influencer->profession) }}">
                                                </div>
                                                @php
                                                    $categoryId = [];
                                                    foreach (@$influencer->categories as $category) {
                                                        $categoryId[] = $category->id;
                                                    }
                                                @endphp

                                                <div class="form-group col-sm-12">
                                                    <label for="professional-headline" class="col-form-label">@lang('Category')</label>
                                                    <select name="category[]" class="from--control form-control select2-multi-select form-select" multiple>

                                                        @foreach ($influencerFategories as $category)
                                                            <option value="{{ $category->id }}" @if (in_array($category->id, $categoryId)) selected @endif>{{ __($category->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="summary" class="col-form-label">@lang('Summary')</label>
                                                    <textarea name="summary" id="summary" class="form-control form--control">{{ br2nl($influencer->summary) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-end mt-3">
                                        <button type="button" class="btn btn--dark btn--md cancelBtn">@lang('Cancel')</button>
                                        <button type="submit" class="btn btn--base btn--md">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="show-sample-photo card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Sample Photos')</h6>
                    @if ($influencer->sample_photos)
                        <button type="button" class="btn--no-border editSamplePhotobtn border-0"> <i class="la la-edit"></i> @lang('Edit')</button>
                        <button type="button" class="btn--no-border addToHomescreenbtn border-0"> <i class="la la-plus"></i> @lang('Add to Homescreen')</button>
                    @else    
                        <button type="button" class="btn btn--outline-base btn--sm editSamplePhotobtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                    @endif 
                </div>
                @php
                    $sample_photos = json_decode($influencer->sample_photos);
                @endphp
                <div class="card-body">
                    @if ($influencer->sample_photos)
                        @foreach ($sample_photos as $photo)
                            <div class="justify-content-between skill-card my-1">
                                <img src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$photo}}" class="img-thumbnail" alt="userProfile" width="200" height="180">
                            </div>
                        @endforeach
                    @else
                        <div class="show-sample-photo justify-content-center noSkill">
                            <span>@lang('No photos added')</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="edit-sample-photo card custom--card mt-5 d-none">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Edit Sample Photos')</h6>
                </div>
                @php
                    $sample_photos = json_decode($influencer->sample_photos);
                @endphp
                <div class="card-body">
                    <form action="{{ route('influencer.update-sample-photos') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @if ($influencer->sample_photos)
                            @foreach ($sample_photos as $photo)
                                <div class="justify-content-between skill-card my-1 samplePhotoDiv">
                                    <img src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$photo}}" class="img-thumbnail" alt="userProfile" width="200" height="180">
                                    <span class="text-danger deleteSamplePhoto" data-photo="{{$photo}}"><i class="fa fa-trash"></i></span>
                                </div>
                            @endforeach
                        @endif
                            <div class="add-sample-photo gap-2 mb-2 mt-2">
                                <b>@lang('Upload New Photos')</b>
                                <input type="file" name="sample_photos[]" class="form-control form--control" multiple />
                            </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md cancelSamplePhotosBtn">@lang('Cancel')</button>
                            <button class="btn btn--base btn--md">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="add-photo-to-homescreen card custom--card mt-5 d-none">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Select photos to add on home screen')</h6>
                </div>
                @php
                    $sample_photos = json_decode($influencer->sample_photos);
                @endphp
                <div class="card-body">
                    <p><b>@lang('Note* :')</b>@lang(' Only 5 photos or 3 photos and 2 videos can be shown in your homescreen. Select atleast 3 photos or atmost 5 photos you like to show others on your profile homescreen')</p>
                    <form action="{{ route('influencer.add-photo-to-homescreen') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @if ($influencer->sample_photos)
                            @foreach ($sample_photos as $photo)
                                <div class="justify-content-between skill-card my-1 samplePhotoDiv">
                                    <img src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$photo}}" class="img-thumbnail" alt="userProfile" width="200" height="180">
                                    <input name="home_screen_photos[]" type="checkbox" value="{{$photo}}">
                                </div>
                            @endforeach
                        @endif
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md canceladdToHomescreenBtn">@lang('Cancel')</button>
                            <button class="btn btn--base btn--md">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="show-sample-video card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Sample Videos')</h6>
                    @if ($influencer->sample_photos)
                        <button type="button" class="btn--no-border editSampleVideobtn border-0"> <i class="la la-edit"></i> @lang('Edit')</button>
                        <button type="button" class="btn--no-border addVideoToHomescreenbtn border-0"> <i class="la la-plus"></i> @lang('Add to Homescreen')</button>
                    @else    
                        <button type="button" class="btn btn--outline-base btn--sm editSampleVideobtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                    @endif 
                </div>
                @php
                     $sample_videos = json_decode($influencer->sample_videos);
                @endphp
                <div class="card-body">
                    @if ($influencer->sample_videos)
                        @foreach ($sample_videos as $video)
                        <div class="justify-content-between skill-card my-1">
                            {{-- <object data="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" width="200" height="200"
                                onload="this.contentDocument.querySelector('video').pause()">
                            </object> --}}
                            <video width="200" height="200" controls>
                                <source src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" type="video/mp4">
                                @lang('Your browser does not support the video tag.')
                            </video>
                        </div>   
                        @endforeach
                    @else
                        <div class="justify-content-center noSkill">
                            <span>@lang('No videos added')</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="edit-sample-video card custom--card mt-5 d-none">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Edit Sample Videos')</h6>
                </div>
                @php
                    $sample_videos = json_decode($influencer->sample_videos);
                @endphp
                <div class="card-body">
                    <form action="{{ route('influencer.update-sample-videos') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @if ($influencer->sample_videos)
                            @foreach ($sample_videos as $video)
                                <div class="justify-content-between skill-card my-1 sampleVideoDiv">
                                    {{-- <object data="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" width="200" height="200"
                                        onload="this.contentDocument.querySelector('video').pause()">
                                    </object> --}}
                                    <video width="200" height="200" controls>
                                        <source src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" type="video/mp4">
                                       @lang(' Your browser does not support the video tag.')
                                    </video>
                                    <span class="text-danger deleteSampleVideo" data-video="{{$video}}"><i class="fa fa-trash"></i></span>
                                </div>
                            @endforeach
                        @endif
                            <div class="add-sample-video gap-2 mb-2 mt-2">
                                <b>@lang('Upload New Videos')</b>
                                <input type="file" name="sample_videos[]" class="form-control form--control" multiple />
                            </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md cancelSampleVideoBtn">@lang('Cancel')</button>
                            <button class="btn btn--base btn--md">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="add-video-to-homescreen card custom--card mt-5 d-none">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Edit Sample Videos')</h6>
                </div>
                @php
                    $sample_videos = json_decode($influencer->sample_videos);
                @endphp
                <div class="card-body">
                    <p><b>@lang('Note* :')</b>@lang(' Only 2 videos can be shown in your homescreen. Select 2 videos you like to show others on your profile homescreen')</p>
                    <form action="{{ route('influencer.add-video-to-homescreen') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        @if ($influencer->sample_videos)
                            @foreach ($sample_videos as $video)
                                <div class="justify-content-between skill-card my-1 sampleVideoDiv">
                                    <video width="200" height="200" controls>
                                        <source src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" type="video/mp4">
                                        @lang('Your browser does not support the video tag.')
                                    </video>
                                    <div class="form-check">
                                        <input name="home_screen_videos[]" class="form-check-input" type="checkbox" value="{{$video}}">
                                        <label class="form-check-label"> @lang('Select') </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md canceladdVideoToHomescreenbtn">@lang('Cancel')</button>
                            <button class="btn btn--base btn--md">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom--card skill-edit d-none mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Skills')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm skillBtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                </div>
                <div class="card-body">
                    <form action="{{ route('influencer.skill') }}" method="POST">
                        @csrf
                        <div id="skillContainer">
                            @if ($influencer->skills)
                                @foreach ($influencer->skills as $skill)
                                    <div class="add-skill d-flex gap-2 mb-2">
                                        <input type="text" name="skills[]" class="form-control form--control" value="{{ $skill }}" required />
                                        <button class="btn btn--danger @if ($loop->first) remove-disable-btn @else remove-btn @endif" type="button"><i class="las la-times"></i></button>
                                    </div>
                                @endforeach
                            @else
                                <div class="add-skill d-flex gap-2  mb-2">
                                    <input type="text" name="skills[]" class="form-control form--control" placeholder="@lang('Enter your skill')" required />
                                    <button class="btn btn--danger remove-disable-btn" type="button"><i class="las la-times"></i></button>
                                </div>
                            @endif
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md cancelSkillBtn">@lang('Cancel')</button>
                            <button class="btn btn--base btn--md">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom--card influencer-skill mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Skills')</h6>
                    <button type="button" class="btn--no-border editSkillbtn border-0"> <i class="la la-edit"></i> @lang('Edit')</button>
                </div>
                <div class="card-body">
                    @if ($influencer->skills)
                        @foreach (@$influencer->skills as $skill)
                            <div class="justify-content-between skill-card my-1">
                                <span>{{ __(@$skill) }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="justify-content-center noSkill">
                            <span>@lang('No skill added yet')</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Language')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm languageBtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                </div>
                <div class="card-body py-0">
                    <div class="row">
                        @if ($influencer->languages)
                            @foreach (@$influencer->languages as $key => $profiencies)
                                <div class="col-12">
                                    <div class="education-content py-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2 gap-3">
                                            <h6>{{ __($key) }}</h6>
                                            <div class="d-flex gap-sm-2 gap-1">
                                                <button type="button" class="btn--no-border confirmationBtn border-0" data-action="{{ route('influencer.language.remove', $key) }}" data-question="@lang('Are you sure to removed this language?')" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('Delete')</span></button>
                                            </div>

                                        </div>
                                        <div class="d-flex my-2 flex-wrap gap-2">
                                            @foreach ($profiencies as $key => $profiency)
                                                <span class="me-3 py-1">
                                                    <span class="fw-medium">{{ keyToTitle($key) }}</span>: {{ $profiency }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 py-3">
                                <div class="justify-content-center">
                                    <span>@lang('No language added yet')</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Social Links')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm socialBtn"><i class="la la-plus"></i> @lang('Add New')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->socialLink as $social)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex flex-wrap">
                                    <span class="me-2">@php  echo $social->social_icon @endphp</span>
                                    <span class="text-break">{{ __($social->url) }}</span>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <span>{{ __($social->followers) }}</span>
                                    <span class="ms-2">@lang('Followers')</span>
                                </div>
                                <div class="d-flex gap-sm-2 gap-1">
                                    <button type="button" class="btn--no-border editSocialBtn border-0" data-username="{{ $social->username }}" data-social_icon="{{ $social->social_icon }}" data-url="{{ $social->url }}" data-platform="{{ $social->social_platform }}" data-action="{{ route('influencer.add.socialLink', $social->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('Edit')</span></button>
                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-action="{{ route('influencer.remove.socialLink', $social->id) }}" data-question="@lang('Are you sure to removed this social link?')" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('Delete')</span></button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('No social link added yet')</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('Education')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm educationBtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->education as $education)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>{{ __($education->degree) }}</h6>
                                <div class="d-flex gap-sm-2 gap-1">

                                    <button type="button" class="btn--no-border editEduBtn border-0" data-degree="{{ $education->degree }}" data-institute="{{ $education->institute }}" data-country="{{ $education->country }}" data-start_year="{{ $education->start_year }}" data-end_year="{{ $education->end_year }}" data-action="{{ route('influencer.add.education', $education->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('Edit')</span></button>

                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-question="@lang('Are you sure to remove this education?')" data-action="{{ route('influencer.remove.education', $education->id) }}" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('Delete')</span></button>

                                </div>
                            </div>
                            <p>
                                {{ __($education->institute) }}, <span>{{ __($education->country) }}</span>
                            </p>
                            <p>{{ $education->start_year }} - {{ $education->end_year }}</p>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('No education added yet')</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 border-none">
                    <h6 class="card-title">@lang('Qualifications')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm qualificationBtn"> <i class="la la-plus"></i> @lang('Add New')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->qualification as $qualification)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <h6>{{ __($qualification->certificate) }}</h6>
                                <div class="d-flex gap-sm-2 gap-1">

                                    <button type="button" class="btn--no-border editQualifyBtn border-0" data-certificate="{{ $qualification->certificate }}" data-organization="{{ $qualification->organization }}" data-year="{{ $qualification->year }}" data-summary="{{ $qualification->summary }}" data-action="{{ route('influencer.add.qualification', $qualification->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('Edit')</span></button>

                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-question="@lang('Are you sure to remove this qualification?')" data-action="{{ route('influencer.remove.qualification', $qualification->id) }}" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('Delete')</span></button>

                                </div>
                            </div>
                            <p class="fw-medium my-2">
                                {{ __($qualification->organization) }}, <span>{{ __($qualification->year) }}</span>
                            </p>
                            <p>{{ $qualification->summary }}</p>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('No qualification added yet')</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="socialLinkModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Social Link')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('Platform')</label>
                            <select class="form-control form--control" name="social_platform">
                                <option>select</option>
                                <option value="instagram">@lang('Instagram')</option>
                                <option value="facebook">@lang('Facebook')</option>
                                <option value="tiktok">@lang('Tik Tok')</option>
                                <option value="linkedin">@lang('Linked In')</option>
                                <option value="twitter">@lang('Twitter')</option>
                                <option value="youtube">@lang('You Tube')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('Username')</label>
                            <div class="input-group">
                                <input type="text" name="username" class="form-control form--control" value="{{ old('username') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('Profile URL')</label>
                            <div class="input-group">
                                <input type="text" name="url" class="form-control form--control" value="{{ old('url') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base btn--md w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="languageModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Language')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">@lang('Name')</label>
                            <select name="name" class="form-control form--control form-select" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($languageData as $lang)
                                    <option value="{{ $lang }}">{{ __($lang) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="from-group">
                            <label class="col-form-label">@lang('Listening')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="listening" id="basic-listening" value="Basic" required>
                                    <label for="basic-listening">@lang('Basic')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-listening" type="radio" name="listening" value="Medium" required>
                                    <label for="medium-listening">@lang('Medium')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-listening" type="radio" name="listening" value="Fluent" required>
                                    <label for="fluent-listening">@lang('Fluent')</label>
                                </div>
                            </div>
                        </div>
                        <div class="from-group">
                            <label class="col-form-label">@lang('Speaking')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="speaking" id="basic-speaking" value="Basic" required>
                                    <label for="basic-speaking">@lang('Basic')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-speaking" type="radio" name="speaking" value="Medium" required>
                                    <label for="medium-speaking">@lang('Medium')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-speaking" type="radio" name="speaking" value="Fluent" required>
                                    <label for="fluent-speaking">@lang('Fluent')</label>
                                </div>
                            </div>
                        </div>
                        <div class="from-group">
                            <label class="col-form-label">@lang('Writing')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="writing" id="basic-writing" value="Basic" required>
                                    <label for="basic-writing">@lang('Basic')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-writing" type="radio" name="writing" value="Medium" required>
                                    <label for="medium-writing">@lang('Medium')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-writing" type="radio" name="writing" value="Fluent" required>
                                    <label for="fluent-writing">@lang('Fluent')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base btn--md w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="educationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="form-group col-md-6">
                                <label for="skill" class="col-form-label">@lang('Country')</label>
                                <select name="country" class="form-control form--control form-select" required>
                                    <option value="" selected disabled>@lang('Select Country')</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country }}">{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('University/College')</label>
                                <input type="text" name="institute" class="form-control form--control" value="{{ old('institute') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('Degree')</label>
                                <input type="text" name="degree" class="form-control form--control" value="{{ old('degree') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('Start Year')</label>
                                <select name="start_year" class="form-control form--control form-select start-year" required></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('End Year')</label>
                                <select name="end_year" class="form-control form--control form-select end-year" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--base btn--md w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="qualificationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('Professional Certificate or Award')</label>
                                <input type="text" name="certificate" class="form-control form--control" value="{{ old('certificate') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('Conferring Organization')</label>
                                <input type="text" name="organization" class="form-control form--control" value="{{ old('organization') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('Summary')</label>
                                <textarea name="summary" class="form-control form--control">{{ old('summary') }}</textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('Year')</label>
                                <select name="year" class="form-control form--control form-select year" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--base btn--md w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush

@push('style')
    <style>
        .badge.badge--icon {
            border-radius: 5px 0 0 0;
        }

        .select2-container--open {
            z-index: 99999;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            @if ($mobile_code)
                $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
            @endif
            
            $('#city_id').on('change', function(){
                let city_name = $(this).find('option:selected').data('city');
                $('input[name=city]').val(city_name);
            });

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            const inputField = document.querySelector('#update-photo'),
                uploadImg = document.querySelector('#upload-img');
            inputField.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const result = reader.result;
                        uploadImg.src = result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            let presentYear = new Date().getFullYear();
            let options = "";
            for (var year = presentYear; year >= 1970; year--) {
                options += `<option value="${year}">${year}</option>`;
            }

            $('.start-year').html(options)
            $('.end-year').html(options)
            $('.year').html(options)

            $('.skillBtn').on('click', function() {
                $('.noSkill').addClass('d-none');
                $("#skillContainer").append(`
                    <div class="add-skill d-flex gap-2 mb-2">
                        <input type="text" name="skills[]" class="form-control form--control" placeholder="@lang('Enter your skill')" require />
                        <button class="btn btn--danger remove-btn" type="button"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.add-skill').remove();
                if ($("#skillContainer").children().length == 0) {
                    $('.noSkill').removeClass('d-none');
                }
            });

            $('.socialBtn').on('click', function() {
                var modal = $('#socialLinkModal');
                modal.find('form').attr('action', `{{ route('influencer.add.socialLink') }}`);
                modal.modal('show')
            });

            $('.editSocialBtn').on('click', function() {
                var modal = $('#socialLinkModal');
                modal.find('.modal-title').text('Update Social Link');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=social_icon]').val($(this).data('social_icon'));
                modal.find('[name=username]').val($(this).data('username'));
                modal.find('[name=social_platform]').val($(this).data('platform'));
                modal.find('[name=url]').val($(this).data('url'));
                modal.modal('show')
            });

            $('.languageBtn').on('click', function() {
                var modal = $('#languageModal');
                modal.find('form').attr('action', `{{ route('influencer.language.add') }}`);
                modal.modal('show')
            });

            $('.editLangBtn').on('click', function() {
                var modal = $('#languageModal');
                modal.find('.modal-title').text('Update Language');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('select[name=label]').val($(this).data('label'));
                modal.modal('show')
            });

            $('.educationBtn').on('click', function() {
                var modal = $('#educationModal');
                modal.find('.modal-title').text('Add New Education');
                modal.find('form').attr('action', `{{ route('influencer.add.education', '') }}`);
                modal.modal('show')
            });
            $('.editEduBtn').on('click', function() {
                var modal = $('#educationModal');
                modal.find('.modal-title').text('Update Education');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('select[name=country]').val($(this).data('country'));
                modal.find('[name=institute]').val($(this).data('institute'));
                modal.find('[name=degree]').val($(this).data('degree'));
                modal.find('select[name=start_year]').val($(this).data('start_year'));
                modal.find('select[name=end_year]').val($(this).data('end_year'));
                modal.modal('show')
            });

            $('.qualificationBtn').on('click', function() {
                var modal = $('#qualificationModal');
                modal.find('.modal-title').text('Add New Qualification');
                modal.find('form').attr('action', `{{ route('influencer.add.qualification', '') }}`);
                modal.modal('show')
            });

            $('.editQualifyBtn').on('click', function() {
                var modal = $('#qualificationModal');
                modal.find('.modal-title').text('Update Qualification');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=certificate]').val($(this).data('certificate'));
                modal.find('[name=organization]').val($(this).data('organization'));
                modal.find('[name=summary]').val($(this).data('summary'));
                modal.find('select[name=year]').val($(this).data('year'));
                modal.modal('show')
            });

            $('.editbtn').on('click', function() {
                $('.influencer-profile-edit').removeClass('d-none');
                $('.influencer-profile').addClass('d-none');
            });
            $('.cancelBtn').on('click', function() {
                $('.influencer-profile-edit').addClass('d-none');
                $('.influencer-profile').removeClass('d-none');

            });

            $('.editSamplePhotobtn').on('click', function() {
                $('.edit-sample-photo').removeClass('d-none');
                $('.show-sample-photo').addClass('d-none');
            });

            $('.cancelSamplePhotosBtn').on('click', function() {
                $('.edit-sample-photo').addClass('d-none');
                $('.show-sample-photo').removeClass('d-none');
            });

            $('.addToHomescreenbtn').on('click', function() {
                console.log(1212);
                $('.add-photo-to-homescreen').removeClass('d-none');
                $('.show-sample-photo').addClass('d-none');
            });

            $('.canceladdToHomescreenBtn').on('click', function() {
                $('.add-photo-to-homescreen').addClass('d-none');
                $('.show-sample-photo').removeClass('d-none');
            });

            $('.deleteSamplePhoto').on('click', function(){
                var photo = $(this).data().photo;
                
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this photo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $(this).closest('.samplePhotoDiv').addClass('d-none');
                            $.ajax({
                                url:"/influencer/influencer/delete-sample-photo/"+photo,
                                type:"get",
                                success: function(data){
                                    
                                }
                            });
                            swal("Your photo has been deleted!", {
                            icon: "success",
                            });
                        } else {
                            swal("Cancelled",{
                            icon: "success",
                            });
                        }
                });
               
            })

            $('.editSampleVideobtn').on('click', function() {
                $('.edit-sample-video').removeClass('d-none');
                $('.show-sample-video').addClass('d-none');
            });

            $('.cancelSampleVideoBtn').on('click', function() {
                $('.edit-sample-video').addClass('d-none');
                $('.show-sample-video').removeClass('d-none');
            });

            $('.addVideoToHomescreenbtn').on('click', function() {
                $('.add-video-to-homescreen').removeClass('d-none');
                $('.show-sample-video').addClass('d-none');
            });

            $('.canceladdVideoToHomescreenbtn').on('click', function() {
                $('.add-video-to-homescreen').addClass('d-none');
                $('.show-sample-video').removeClass('d-none');
            });


            $('.editSkillbtn').on('click', function() {
                $('.skill-edit').removeClass('d-none');
                $('.influencer-skill').addClass('d-none');
            });

            $('.deleteSampleVideo').on('click', function(){
                var video = $(this).data().video;
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this photo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $(this).closest('.sampleVideoDiv').addClass('d-none');
                            $.ajax({
                                url:"/influencer/influencer/delete-sample-video/"+video,
                                type:"get",
                                success: function(data){
                                }
                            });
                            swal("Your photo has been deleted!", {
                            icon: "success",
                            });
                        } else {
                            swal("Cancelled",{
                            icon: "success",
                            });
                        }
                });
                
            })

            $('.cancelSkillBtn').on('click', function() {
                $('.skill-edit').addClass('d-none');
                $('.influencer-skill').removeClass('d-none');
            });


            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            $('#educationModal').on('hidden.bs.modal', function() {
                $('#educationModal form')[0].reset();
            });

            $('#qualificationModal').on('hidden.bs.modal', function() {
                $('#qualificationModal form')[0].reset();
            });

            $('#socialLinkModal').on('hidden.bs.modal', function() {
                $('#socialLinkModal form')[0].reset();
            });

            $(".select2-multi-select").select2({
                dropdownParent: $('.has-select2')
            });

        })(jQuery);
    </script>
@endpush
