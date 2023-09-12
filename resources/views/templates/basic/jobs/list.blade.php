
@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="all-sections pt-60">
        <div class="container-fluid p-max-sm-0">
            <div class="sections-wrapper d-flex flex-wrap justify-content-center">
                <article class="main-section">
                    <div class="section-inner">
                        <div class="item-section">
                            <div class="container">
                                @include($activeTemplate.'partials.top_filter')
                                <div class="item-bottom-area">
                                    <div class="row justify-content-center mb-30-none">
                                        
                                        <div class="col-xl-3 col-lg-3 mb-30">
                                            <div class="d-flex justify-content-between dash-sidebar filter-sidebar p-xl-0 flex-wrap gap-4 shadow-none">
                                                <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
                                                <div class="sidebar">
                                                    <div class="widget mb-30">
                                                        <h3 class="widget-title">@lang('Posted By')</h3>
                                                        <div class="radio-wrapper">
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input jobPostedBy" type="radio" value="all" name="posted_by" id="allUser" checked>
                                                                <label class="form-check-label" for="allUser">
                                                                    @lang('All')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input jobPostedBy" type="radio" value="user" name="posted_by" id="user">
                                                                <label class="form-check-label" for="user">
                                                                    @lang('Users')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input jobPostedBy" type="radio" value="influencer" name="posted_by" id="influencer">
                                                                <label class="form-check-label" for="influencer">
                                                                    @lang('Influencers')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input jobPostedBy" type="radio" value="freelancer" name="posted_by" id="freelancer">
                                                                <label class="form-check-label" for="freelancer">
                                                                    @lang('Freelancers')
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget mb-30">
                                                        <h3 class="widget-title">@lang('Categories')</h3>
                                                        <div class="checkbox-wrapper">
                                                            <div class="custom--checkbox">
                                                                <input class="form-check-input sortCategory" type="checkbox" name="category" value="" id="category0" checked>
                                                                <label class="form-check-label" for="category0">@lang('All Categories')</label>
                                                            </div>
                                                            @foreach ($jobcategories as $category)
                                                                <div class="custom--checkbox my-2">
                                                                    <input class="form-check-input sortCategory" type="checkbox" name="category" value="{{ $category->id }}" id="category{{ $category->id }}">
                                                                    <label class="form-check-label" for="category{{ $category->id }}">{{ __($category->name) }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="widget mb-30">
                                                        <h3 class="widget-title">@lang('Job Type')</h3>
                                                        <div class="radio-wrapper">
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input sortJobType" type="radio" value="all" name="sort" id="allType" checked>
                                                                <label class="form-check-label" for="allType">
                                                                    @lang('All')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input sortJobType" type="radio" value="basic" name="sort" id="basic">
                                                                <label class="form-check-label" for="basic">
                                                                    @lang('Basic')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input sortJobType" type="radio" value="campaign" name="sort" id="campaign">
                                                                <label class="form-check-label" for="campaign">
                                                                    @lang('Campaign')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input sortJobType" type="radio" value="event" name="sort" id="event">
                                                                <label class="form-check-label" for="event">
                                                                    @lang('Event')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input sortJobType" type="radio" value="casting" name="sort" id="casting">
                                                                <label class="form-check-label" for="casting">
                                                                    @lang('Casting')
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget mb-30 CampaignSocialDiv d-none">
                                                        <h3 class="widget-title">@lang('Campaign Social')</h3>
                                                        <div class="checkbox-wrapper">
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="all" name="campaign_social" id="allSocial" checked>
                                                                <label class="form-check-label" for="allSocial">
                                                                    @lang('All')
                                                                </label>
                                                            </div>
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="facebook" name="campaign_social" id="facebook">
                                                                <label class="form-check-label" for="facebook">
                                                                    @lang('Facebook')
                                                                </label>
                                                            </div>
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="instagram" name="campaign_social" id="instagram">
                                                                <label class="form-check-label" for="instagram">
                                                                    @lang('Instagram')
                                                                </label>
                                                            </div>
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="tiktok" name="campaign_social" id="tiktok">
                                                                <label class="form-check-label" for="tiktok">
                                                                    @lang('Tiktok')
                                                                </label>
                                                            </div>
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="twitter" name="campaign_social" id="twitter">
                                                                <label class="form-check-label" for="twitter">
                                                                    @lang('Twitter')
                                                                </label>
                                                            </div>
                                                            <div class="custom--checkbox my-2">
                                                                <input class="form-check-input campSocial" type="checkbox" value="linkedin" name="campaign_social" id="linkedin">
                                                                <label class="form-check-label" for="linkedin">
                                                                    @lang('Linkedin')
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget mb-30 has-select2 position-relative d-none">
                                                        <h3 class="widget-title">@lang('Country')</h3>
                                                        <select name="country" class="form-control country form--control form-select select2-basic" required>
                                                            <option value="" selected disabled>@lang('Select Country')</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->country }}" @selected($country->country == "Greece")>{{ __($country->country) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="widget mb-30 lang-select2 position-relative">
                                                        <h3 class="widget-title">@lang('Language')</h3>
                                                        <select class="form-control form--control languageFilter form-select select2-basic" name="name">
                                                            <option value="" selected disabled>@lang('Select One')</option>
                                                            @foreach ($languageData as $lang)
                                                                <option value="{{ $lang }}">{{ __($lang) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="widget mb-30 position-relative">
                                                        <h2 class="widget-title">@lang('Target Demographic')</h2>
                                                        <h4 class="widget-title">@lang('Age')</h4>
                                                        <select class="form-control form--control form-select select2-basic targetDemographicAgeFrom" name="target_demographic_from">
                                                            <option value="" selected disabled>@lang('From')</option>
                                                            @for($age=5; $age<=100; $age+=5)
                                                                <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                                                            @endfor
                                                        </select>
                                                        <select class="form-control form--control form-select select2-basic targetDemographicAgeTo" name="target_demographic_to">
                                                            <option value="" selected disabled>@lang('To')</option>
                                                            @for($age=5; $age<=100; $age+=5)
                                                                <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="widget mb-30">
                                                        <h3 class="widget-title">@lang('Gender')</h3>
                                                        <div class="radio-wrapper">
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input demoGraphicGender" type="radio" value="all" name="target_demographic_gender" id="allDemoUser" checked>
                                                                <label class="form-check-label" for="allDemoUser">
                                                                    @lang('All')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input demoGraphicGender" type="radio" value="Male" name="target_demographic_gender" id="male">
                                                                <label class="form-check-label" for="male">
                                                                    @lang('Male')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input demoGraphicGender" type="radio" value="Female" name="target_demographic_gender" id="female">
                                                                <label class="form-check-label" for="female">
                                                                    @lang('Female')
                                                                </label>
                                                            </div>
                                                            <div class="custom--radio my-2">
                                                                <input class="form-check-input demoGraphicGender" type="radio" value="LGBTQ+" name="target_demographic_gender" id="lgbtq+">
                                                                <label class="form-check-label" for="lgbtq+">
                                                                    @lang('LGBTQ+')
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="widget mb-30">
                                                        <h3 class="widget-title">@lang('Price')</h3>
                                                        <div class="price-range-slider">
                                                            <p class="range-value">
                                                            <input type="text" id="JobPrice" readonly>
                                                            </p>
                                                            <div id="slider-range" class="range-bar"></div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-9 col-lg-9 mb-30" id="jobTemplate">
                                            <div class="dashboard-toggler-wrapper text-end radius-5 d-xl-none d-inline-block mb-4">
                                                <div class="filter-toggler dashboard-toggler">
                                                    <i class="fas fa-sliders-h"></i>
                                                </div>
                                            </div>
                                            @include($activeTemplate.'partials.job_template')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
    @include($activeTemplate.'partials.down_ad')
@endsection
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/templates/basic/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" crossorigin="anonymous"/>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
@endpush

@push('style')
<style>

/* .price-range-slider {
	 width: 100%;
	 float: left;
	 padding: 10px 20px;
} */
 .price-range-slider .range-value {
	 margin: 0;
}
 .price-range-slider .range-value input {
	 width: 100%;
	 background: none;
	 color: #000;
	 font-size: 16px;
	 font-weight: initial;
	 box-shadow: none;
	 border: none;
	 margin: 20px 0 20px 0;
}
 .price-range-slider .range-bar {
	 border: none;
	 background: #000;
	 height: 3px;
	 width: 96%;
	 margin-left: 8px;
}
 .price-range-slider .range-bar .ui-slider-range {
	 background: #28d8a8;
}
 .price-range-slider .range-bar .ui-slider-handle {
	 border: none;
	 border-radius: 25px;
	 background: #000;
	 border: 2px solid #28d8a8;
	 height: 17px;
	 width: 17px;
	 top: -0.52em;
	 cursor: pointer;
}
 .price-range-slider .range-bar .ui-slider-handle + span {
	 background: #28d8a8;
}
/*--- /.price-range-slider ---*/


</style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";


            //-----JS for Price Range slider-----//
             $(function() {
                $( "#slider-range" ).slider({
                range: true,
                min: 10,
                max: 10000,
                values: [ {{ @$jobMinPrice }}, {{ @$jobMaxPrice }} ],
                slide: function( event, ui ) {
                    $( "#JobPrice" ).val( "€" + ui.values[ 0 ] + " - €" + ui.values[ 1 ] );
                }
                });
                $( "#JobPrice" ).val( "€" + $( "#slider-range" ).slider( "values", 0 ) +
                " - €" + $( "#slider-range" ).slider( "values", 1 ) );
            });

            $('.sortCategory').on('click', function() {
                $('#category0').removeAttr('checked', 'checked');
                if ($('#category0').is(':checked')) {
                    $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
                }

                if ($("input[type='checkbox'][name='category']:checked").length == 0) {
                    $('#category0').attr('checked', 'checked');
                }
                fetchFilterJob();
            });

            $('.sortJobType').on('click', function() {
                if($('.sortJobType:checked').val() == 'campaign'){
                    $('.CampaignSocialDiv').removeClass('d-none');
                }else{
                    $('.CampaignSocialDiv').addClass('d-none');
                }
                fetchFilterJob();
            });

            $('.jobPostedBy').on('click', function() {
                fetchFilterJob();
            });

            $('#slider-range').on('click', function() {
                fetchFilterJob();
            });

            $('.country').on('change', function() {
                fetchFilterJob();
            });

            $('.languageFilter').on('change', function() {
                fetchFilterJob();
            });

            $('.targetDemographicAgeFrom').on('change', function() {
                fetchFilterJob();
            });

            $('.targetDemographicAgeTo').on('change', function() {
                fetchFilterJob();
            });

            $('.demoGraphicGender').on('click', function() {
                fetchFilterJob();
            });

            $('.campSocial').on('click', function() {
                $('#allSocial').removeAttr('checked', 'checked');
                if ($('#allSocial').is(':checked')) {
                    $("input[type='checkbox'][name='campaign_social']").not(this).prop('checked', false);
                }

                if ($("input[type='checkbox'][name='campaign_social']:checked").length == 0) {
                    $('#allSocial').attr('checked', 'checked');
                }
                fetchFilterJob();
            });

            function fetchFilterJob() {
                $('.loader-wrapper').removeClass('d-none');
                let data = {};
                data.categories = [];
                data.campSocial = [];

                $.each($("[name=category]:checked"), function() {
                    if ($(this).val()) {
                        data.categories.push($(this).val());
                    }
                });

                $.each($("[name=campaign_social]:checked"), function() {
                    if ($(this).val()) {
                        data.campSocial.push($(this).val());
                    }
                });

                data.jobType = $('.sortJobType:checked').val();
                data.jobPostedBy = $('.jobPostedBy:checked').val();
                data.priceRange = $( "#JobPrice" ).val();
                data.country = $( ".country" ).val();
                data.language = $( ".languageFilter" ).val();
                data.targetDemoAgeFrom = $('.targetDemographicAgeFrom').find(":selected").val();
                data.targetDemoAgeTo = $('.targetDemographicAgeTo').find(":selected").val();
                data.targetDemoGender = $('.demoGraphicGender:checked').val();

                let url = `{{ route('job.filter') }}`;

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        $('#jobTemplate').html(response);
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }

            $(".select2-basic").select2({
                dropdownParent: $('.has-select2')
            });

            $(".select2-basic").select2({
                dropdownParent: $('.lang-select2')
            })

        })(jQuery);
    </script>
@endpush


