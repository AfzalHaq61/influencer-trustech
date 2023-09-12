@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-body">
            <form action="{{ route('user.jobs.insert',$job->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <label class="form-label" for="title">@lang('Image')<span class="text--danger">*</span></label>
                        <div class="profile-thumb p-0 text-center shadow-none">
                            <div class="thumb">
                                <img id="upload-img" src="{{ getImage(getFilePath('service') . '/' . @$job->image, getFileSize('service')) }}" alt="userProfile">
                                <label class="badge badge--icon badge--fill-base update-thumb-icon" for="update-photo"><i class="las la-pen"></i></label>
                            </div>
                            <div class="profile__info">
                                <input type="file" name="image" class="form-control d-none" id="update-photo">
                            </div>
                        </div>
                        <small class="text--warning">@lang('Supported files'): @lang('jpeg'), @lang('jpg'), @lang('png'). @lang('| Will be resized to'): {{ getFileSize('service') }}@lang('px').</small>
                    </div>
                    @php
                        if (@$job) {
                            $categoryId = $job->category_id;
                        } else {
                            $categoryId = old('category_id');
                        }
                    @endphp
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="form-label" for="job_type">@lang('Job Type')</label>
                            <select class="form-select form--control jobType" name="job_type" id="job_type" readonly>
                                <option value="basic"    @selected(@$job->job_type == 'basic')>Basic</option>
                                <option value="campaign" @selected(@$job->job_type == 'campaign')>Campaign</option>
                                <option value="event"    @selected(@$job->job_type == 'event')>Event</option>
                                <option value="casting"  @selected(@$job->job_type == 'casting')>Casting/pitch </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="name">@lang('Name')</label>
                            <input type="text" class="form-control form--control" name="name" id="name" value="{{ $job->name }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="category_id">@lang('Category')</label>
                            <select class="form-select form--control" name="category_id" id="category" required>
                                <option value="" selected disabled>@lang('Select category')</option>
                                @foreach ($jobCategories as $category)
                                    <option value="{{$category->id}}" @selected(@$category->id == $job->job_category_id) data-subcategories="{{@$category->JobSubcategories}}">{{__($category->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sub_category_id">@lang('Subcategory')</label>
                            <select class="form-select form--control subcategory" name="sub_category_id" id="sub_category_id">
                              
                            </select>
                        </div>
                        <div class="form-group skill-body">
                            <label for="skill" class="form-label">@lang('Skills')</label>
                            <select class="select2-auto-tokenize form-control form--control" multiple="multiple" name="skill[]" required>
                                @if (@$job)
                                    @foreach (@$job->skill as $skill)
                                        <option value="{{ @$skill}}" selected>{{ __(@$skill) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="price">@lang('Budget')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control form--control" name="price" id="price" value="{{ getAmount(@$job->price) }}" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="delivery_time">@lang('Delivery Time')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control form--control" name="delivery_time" id="delivery_time" value="{{@$job->delivery_time}}" required>
                                    <span class="input-group-text">Day(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="form-label" for="price">@lang('Target Demography Age From')</label>
                                <select class="form-control form--control" name="target_demographic_from">
                                    <option>Select</option>
                                    @for($age=5; $age<=100; $age+=5)
                                        <option value="{{$age}}" @selected(@$age == $job->target_demographic_from)>{{$age}} &nbsp;Year</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label" for="delivery_time">@lang('Target Demography Age To')</label>
                                <select class="form-control form--control" name="target_demographic_to">
                                    <option>Select</option>
                                    @for($age=5; $age<=100; $age+=5)
                                        <option value="{{$age}}" @selected(@$age == $job->target_demographic_to)>{{$age}} &nbsp;Year</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label" for="delivery_time">@lang('Target Demography Gender')</label>
                                <select class="form-control form--control" name="target_demographic_gender">
                                    <option>Select</option>
                                    <option value="Male"   @selected($job->target_demographic_gender == 'Male')>Male</option>
                                    <option value="Female" @selected($job->target_demographic_gender == 'Female')>Female</option>
                                    <option value="LGBTQ+" @selected($job->target_demographic_gender == 'LGBTQ+')>LGBTQ+</option>
                                </select>
                            </div>
                        </div>
                        @if($job->job_type == 'campaign')
                        <div class="row campaignDetails jobtypeDiv">
                            <div class="form-group"><h5>Campaign Details</h5></div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="campaign_start_date">@lang('Start Date')</label>
                                <div class="input-group">
                                    <input type="date" class="form-control form--control" name="campaign_start_date" id="campaign_start_date" value="{{$job->campaign_start_date}}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="campaign_end_date">@lang('End Date')</label>
                                <div class="input-group">
                                    <input type="date" class="form-control form--control" name="campaign_end_date" id="campaign_end_date" value="{{$job->campaign_end_date}}">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label required" for="campaign_guidelines">@lang('Guidelines of the campaign ')</label>
                                <textarea rows="3" class="form-control form--control" name="campaign_guidelines" id="campaign_guidelines" 
                                placeholder="@lang('Write here')">@php echo $job->campaign_guidelines @endphp</textarea>
                            </div>
                        </div>
                        @endif
                        @if($job->job_type == 'event')
                        <div class="row eventDetails jobtypeDiv">
                            <div class="form-group"><h5>Event Details</h5></div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="event_date">@lang('Event Date')</label>
                                <div class="input-group">
                                    <input type="date" class="form-control form--control" name="event_date" id="event_date" value="{{@$job->event_date}}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="event_location">@lang('Location')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form--control" name="event_location" id="event_location" value="{{@$job->event_location}}">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="form-check-input" type="radio" id="event_attend" name="event_attend" value="yes" @checked($job->event_attend == 'yes')>
                                <label class="form-check-label" for="event_attend">Attend the event </label>
                                <input class="form-check-input" type="radio" id="promote_on_social" name="event_attend" value="no"  @checked($job->event_attend == 'no')>
                                <label class="form-check-label" for="promote_on_social">Promote on social media </label>
                            </div>
                        </div>
                        @endif
                        @if($job->job_type == 'casting')
                        <div class="row castingPitching jobtypeDiv">
                            <div class="form-group"><h5>Casting/Pitching</h5></div>
                            <div class="form-group col-md-12">
                                <label class="form-label required" for="casting_job_idea">@lang('Share Your ideas on this job')</label>
                                <textarea rows="3" class="form-control form--control" name="casting_job_idea" id="casting_job_idea" placeholder="@lang('Write here')">@php echo $job->casting_job_idea @endphp</textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-label required" for="description">@lang('Description')</label>
                        <textarea rows="4" class="form-control form--control" name="description" id="description" 
                            placeholder="@lang('Write here')">@php echo $job->description @endphp</textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label required" for="requirements">@lang('Requirements')</label>
                        <textarea rows="4" class="form-control form--control" name="requirements" id="requirements" 
                            placeholder="@lang('Write here')">@php echo $job->requirements @endphp</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
        </div>
        </form>
    </div>
    </div>
@endsection
@push('style')
    <style>
        .badge.badge--icon {
            border-radius: 5px 0 0 0;
        }

        .jobType{
            pointer-events: none;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/lib/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/lib/image-uploader.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
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

            $('.jobType').on('change', function(){
                let jobType = $(this).val();
                $('.jobtypeDiv textarea').val(''); 

                if(jobType == 'campaign'){
                    $('.campaignDetails').removeClass('d-none');
                    $('.eventDetails').addClass('d-none');
                    $('.castingPitching').addClass('d-none');
                    $('.jobtypeDiv input').val('');
                    $('.jobtypeDiv input[type="radio"]').prop('checked', false);

                }else if(jobType == 'event'){
                    $('.eventDetails').removeClass('d-none');
                    $('.campaignDetails').addClass('d-none');
                    $('.castingPitching').addClass('d-none');
                    $('#event_attend').val('yes');
                    $('#promote_on_social').val('no');
                    
                }else if(jobType == 'casting'){
                    $('.castingPitching').removeClass('d-none');
                    $('.eventDetails').addClass('d-none');
                    $('.campaignDetails').addClass('d-none');
                    $('.jobtypeDiv input').val('');
                    $('.jobtypeDiv input[type="radio"]').prop('checked', false);

                }else{
                    $('.campaignDetails').addClass('d-none');
                    $('.eventDetails').addClass('d-none');
                    $('.castingPitching').addClass('d-none');
                    $('.jobtypeDiv input').val('');
                    $('.jobtypeDiv input[type="radio"]').prop('checked', false);
                }

            });

            $('#category').on('change', function() {
                var subcategories = $(this).find('option:selected').data('subcategories');
                var html = `<option value="">@lang('Select One')</option>`;

                if (subcategories && subcategories.length > 0) {
                    $.each(subcategories, function(i, v) {
                        html += `<option value="${v.id}">@lang('${v.name}')</option>`;
                    });
                }
                $('.subcategory').html(html);
            }).change();


            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
            });


            $(".select2-auto-tokenize").select2({
                tags: true,
                tokenSeparators: [","],
                dropdownParent: $(".skill-body"),
            });

        })(jQuery);
    </script>
@endpush
