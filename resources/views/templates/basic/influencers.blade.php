@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="influencer-section pt-80 pb-80">
        <div class="container ">
            <div class="row gy-4 justify-content-center">
                <div class="col-xl-3">
                    <div class="d-flex justify-content-between dash-sidebar filter-sidebar p-xl-0 flex-wrap gap-4 shadow-none">
                        <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
                        <div class="w-100 search-widget">
                            <div class="input-group">
                                <input type="text" name="" class="form-control form--control mySearch" placeholder="@lang('Search here')" value="{{ request()->search }}">
                                <button class="input-group-text bg--base border--base searchBtn border-0 px-3 text-white" type="button"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Sort By')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--radio my-2">
                                    <input class="form-check-input sortInfluencer" type="radio" value="latest" name="sort" id="latest" checked>
                                    <label class="form-check-label" for="latest">
                                        @lang('Latest')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input sortInfluencer" type="radio" value="top_rated" name="sort" id="top_rated">
                                    <label class="form-check-label" for="top_rated">
                                        @lang('Top Rated')
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if (@$allCategory)
                            <div class="sidebar-widget">
                                <h6 class="sidebar-widget__title">@lang('Categories')</h6>
                                <div class="checkbox-wrapper categoryDiv" id="categoryDiv">
                                    <div class="custom--checkbox">
                                        <input class="form-check-input sortCategory" type="checkbox" name="category" value="" id="category0" checked>
                                        <label class="form-check-label" for="category0">@lang('All Categories')</label>
                                    </div>
                                    @foreach ($allCategory as $category)
                                        <div class="custom--checkbox my-2">
                                            <input class="form-check-input sortCategory" type="checkbox" name="category" value="{{ $category->id }}" id="category{{ $category->id }}">
                                            <label class="form-check-label" for="category{{ $category->id }}">@lang($category->name)</label>
                                        </div>
                                    @endforeach
                                </div>
                                <p id="showMore" style="cursor: pointer;">@lang('+ Show More')</p>
                            </div>
                        @endif
                        
                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Social Handles')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--checkbox my-2">
                                    <input class="form-check-input instgramAccount" type="checkbox" value="instagram" name="instagram" id="instagram">
                                    <label class="form-check-label" for="instagram">
                                        @lang('Instagram')
                                    </label>
                                </div>
                                <div class="custom--checkbox my-2">
                                    <input class="form-check-input twitterAccount" type="checkbox" value="twitter" name="twitter" id="twitter">
                                    <label class="form-check-label" for="twitter">
                                        @lang('Twitter')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Followers Count')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--radio my-2">
                                    <input class="form-check-input followersCount" type="radio" value="instagram" name="sort" id="instagram_followers">
                                    <label class="form-check-label" for="instagram_followers">
                                        @lang('Instagram followers')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input followersCount" type="radio" value="twitter" name="sort" id="twitter_followers">
                                    <label class="form-check-label" for="twitter_followers">
                                        @lang('Twitter followers')
                                    </label>
                                </div>
                                <div class="followersCountDiv d-none">
                                    <b class="sidebar-widget__title">@lang('sort by numbers of followers')</b>
                                    <div class="custom--radio my-2">
                                        <input class="form-check-input followersCountFilter" type="radio" value="nano" name="followers_count" id="nano">
                                        <label class="form-check-label" for="nano">
                                            @lang('Nano (0-10k)')
                                        </label>
                                    </div>
                                    <div class="custom--radio my-2">
                                        <input class="form-check-input followersCountFilter" type="radio" value="micro" name="followers_count" id="micro">
                                        <label class="form-check-label" for="micro">
                                            @lang('Micro (10k-100k)')
                                        </label>
                                    </div>
                                    <div class="custom--radio my-2">
                                        <input class="form-check-input followersCountFilter" type="radio" value="middle" name="followers_count" id="middle">
                                        <label class="form-check-label" for="middle">
                                            @lang('Middle (100k-500k)')
                                        </label>
                                    </div>
                                    <div class="custom--radio my-2">
                                        <input class="form-check-input followersCountFilter" type="radio" value="macro" name="followers_count" id="macro">
                                        <label class="form-check-label" for="macro">
                                            @lang('Macro (500k-1M)')
                                        </label>
                                    </div>
                                    <div class="custom--radio my-2">
                                        <input class="form-check-input followersCountFilter" type="radio" value="mega" name="followers_count" id="mega">
                                        <label class="form-check-label" for="mega">
                                            @lang('Mega (1M+)')
                                        </label>
                                    </div>
                                </div>    
                            </div>
                        </div>

                        <div class="sidebar-widget has-select2 position-relative d-none">
                            <h6 class="sidebar-widget__title">@lang('Country')</h6>
                            <select class="form-control form--control country form-select select2-basic" name="country">
                                <option value="">@lang('All')</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->country }}" @selected($country->country == "Greece")>@lang($country->country)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sidebar-widget city-select2 position-relative">
                            <h6 class="sidebar-widget__title">@lang('City')</h6>
                            <select class="form-control form--control city form-select select2-basic" name="city">
                                <option value="">@lang('All')</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">@lang($city->name)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sidebar-widget lang-select2 position-relative">
                            <h6 class="sidebar-widget__title">@lang('Language')</h6>
                            <select class="form-control form--control languageFilter form-select select2-basic" name="name">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($languageData as $lang)
                                    <option value="{{ $lang }}">@lang($lang)</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Rating')</h6>
                            <div class="action-widget__body" style="">

                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" value="" type="radio" name="star" id="ratings-0">
                                        <label class="form-check-label" for="ratings-0">@lang('All') </label>
                                    </div>
                                </div>

                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" value="4" type="radio" name="star" id="ratings-4">
                                        <label class="form-check-label" for="ratings-4">
                                            <span class="text--warning">
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star-o"></i>
                                            </span>
                                            & @lang('up')
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" value="3" type="radio" name="star" id="ratings-3">
                                        <label class="form-check-label" for="ratings-3">
                                            <span class="text--warning">
                                                <i class="las la-star"></i>
                                                <i class="las la-star"></i>
                                                <i class="las la-star"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>
                                            </span>
                                            & @lang('up')
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" value="2" type="radio" name="star" id="ratings-2">
                                        <label class="form-check-label" for="ratings-2">
                                            <span class="text--warning">
                                                <i class="las la-star"></i>
                                                <i class="las la-star"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>

                                            </span>
                                            & @lang('up')
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" value="1" type="radio" name="star" id="ratings-1">
                                        <label class="form-check-label" for="ratings-1">
                                            <span class="text--warning">
                                                <i class="las la-star"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>
                                                <i class="la la-star-o"></i>
                                            </span>
                                            & @lang('up')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Availability')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--checkbox my-2">
                                    <input class="form-check-input avlForEvents" type="checkbox" value="available_for_events" name="available_for_events" id="available_for_events">
                                    <label class="form-check-label" for="available_for_events">
                                        @lang('Available For Events')
                                    </label>
                                </div>
                                <div class="custom--checkbox my-2">
                                    <input class="form-check-input avlForTravell" type="checkbox" value="available_for_travelling" name="available_for_travelling" id="available_for_travelling">
                                    <label class="form-check-label" for="available_for_travelling">
                                        @lang('Available For Travelling')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Sex')</h6>
                            <div class="radio-wrapper">
                                {{-- <div class="custom--radio my-2">
                                    <input class="form-check-input sexInfluencer" type="radio" value="all" name="sex" id="all" checked>
                                    <label class="form-check-label" for="all">
                                        @lang('All')
                                    </label>
                                </div> --}}
                                <div class="custom--radio my-2">
                                    <input class="form-check-input sexInfluencer" type="radio" value="male" name="sex" id="male">
                                    <label class="form-check-label" for="male">
                                        @lang('Male')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input sexInfluencer" type="radio" value="female" name="sex" id="female">
                                    <label class="form-check-label" for="female">
                                        @lang('Female')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input sexInfluencer" type="radio" value="LGBTQ+" name="sex" id="LGBTQ+">
                                    <label class="form-check-label" for="LGBTQ+">
                                        @lang('LGBTQ+')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Target Demographics')</h6>
                            <h6>@lang('Age')</h6>
                            <select class="form-control form--control form-select targetDemographicAgeFrom" name="target_demographic_from">
                                <option value="">@lang('From')</option>
                                @for($age=5; $age<=100; $age+=5)
                                    <option value="{{$age}}">@lang($age.' Year')</option>
                                @endfor
                            </select>
                            <select class="form-control form--control form-select targetDemographicAgeTo mt-2" name="target_demographic_to">
                                <option value="">@lang('To')</option>
                                @for($age=5; $age<=100; $age+=5)
                                    <option value="{{$age}}">@lang($age.' Year')</option>
                                @endfor
                            </select>

                            <h6  class="mt-2">@lang('Gender')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--radio my-2">
                                    <input class="form-check-input targetDemographicSex" type="radio" value="Male" name="target_demo_sex" id="target_male">
                                    <label class="form-check-label" for="target_male">
                                        @lang('Male')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input targetDemographicSex" type="radio" value="Female" name="target_demo_sex" id="target_female">
                                    <label class="form-check-label" for="target_female">
                                        @lang('Female')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input targetDemographicSex" type="radio" value="LGBTQ+" name="target_demo_sex" id="target_LGBTQ+">
                                    <label class="form-check-label" for="target_LGBTQ+">
                                        @lang('LGBTQ+')
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-9 position-relative">
                    <div class="dashboard-toggler-wrapper text-end radius-5 d-xl-none d-inline-block mb-4">
                        <div class="filter-toggler dashboard-toggler">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                    </div>
                    <div class="loader-wrapper">
                        <div class="loader-pre"></div>
                    </div>
                    <div class="row gy-4 justify-content-center" id="influencers">
                        @include($activeTemplate . 'similar_influencer')
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection


@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    .categoryDiv {
        max-height: 70px;
        overflow: hidden;
    }

    .categoryDiv.expanded {
        max-height: none;
    }
</style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            let page = null;
            $('.loader-wrapper').addClass('d-none');

            $('#showMore').click(function(){
                $('#categoryDiv').toggleClass("expanded");

                if ($('#categoryDiv').hasClass("expanded")) {
                    
                    $('#showMore').html("@lang('- Show Less')");
                } else {
                    $('#showMore').html("@lang('+ Show More')");
                }
            });

            $('.sortCategory, .completedJob, .sortInfluencer, .sexInfluencer').on('click', function() {
                $('#category0').removeAttr('checked', 'checked');
                if ($('#category0').is(':checked')) {
                    $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
                }

                if ($("input[type='checkbox'][name='category']:checked").length == 0) {
                    $('#category0').attr('checked', 'checked');
                }
                fetchInfluencer();
            });

            $('.sortRating').on('click', function() {
                if ($('#ratings-0').is(':checked')) {
                    $("input[type='radio'][name='star']").not(this).prop('checked', false);
                }
                fetchInfluencer();
            });

            $('.country').on('change', function() {
                fetchInfluencer();
            });

            $('.city').on('change', function() {
                fetchInfluencer();
            });

            $('.languageFilter').on('change', function() {
                fetchInfluencer();
            });

            $('.searchBtn').on('click', function() {
                $(this).attr('disabled', 'disabled');
                fetchInfluencer();
            });

            $('.followersCount').on('click', function() {
                $('.followersCountDiv').removeClass('d-none');
                fetchInfluencer();
            });

            $('.followersCountFilter').on('click', function() {
                fetchInfluencer();
            });
            
            $('.targetDemographicAgeFrom').on('change', function() {
                fetchInfluencer();
            });

            $('.targetDemographicAgeTo').on('change', function() {
                fetchInfluencer();
            });

            $('.targetDemographicSex').on('click', function() {
                fetchInfluencer();
            });

            $('.avlForEvents').on('click', function() {
                fetchInfluencer();
            });

            $('.avlForTravell').on('click', function() {
                fetchInfluencer();
            });

            $('.instgramAccount').on('click', function() {
                fetchInfluencer();
            });

            $('.twitterAccount').on('click', function() {
                fetchInfluencer();
            });

            function fetchInfluencer() {
                $('.loader-wrapper').removeClass('d-none');
                let data = {};
                data.categories = [];

                $.each($("[name=category]:checked"), function() {
                    if ($(this).val()) {
                        data.categories.push($(this).val());
                    }
                });

                data.search                 = $('.mySearch').val();
                data.sort                   = $('.sortInfluencer:checked').val();
                data.avlForEvents           = $('.avlForEvents:checked').val();
                data.avlForTravell          = $('.avlForTravell:checked').val();
                data.instgramAccount        = $('.instgramAccount:checked').val();
                data.twitterAccount         = $('.twitterAccount:checked').val();
                data.followersCount         = $('.followersCount:checked').val();
                data.followersCountFilter   = $('.followersCountFilter:checked').val();
                data.sex                    = $('.sexInfluencer:checked').val();
                data.completedJob           = $('.completedJob:checked').val();
                data.rating                 = $('.sortRating:checked').val();
                data.country                = $('.country').find(":selected").val();
                data.city                   = $('.city').find(":selected").val();
                data.language               = $('.languageFilter').find(":selected").val();
                data.targetDemoAgeFrom      = $('.targetDemographicAgeFrom').find(":selected").val();
                data.targetDemoAgeTo        = $('.targetDemographicAgeTo').find(":selected").val();
                data.targetDemoSex          = $('.targetDemographicSex:checked').val();
                data.categoryId             = "{{ @$id }}";

                let url = `{{ route('influencer.filter') }}`;

                if (page) {
                    url = `{{ route('influencer.filter') }}?page=${page}`;
                }

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        $('#influencers').html(response);
                        $('.searchBtn').removeAttr('disabled');
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                fetchInfluencer();
            });

            $(".select2-basic").select2({
                dropdownParent: $('.has-select2')
            });

            $(".select2-basic").select2({
                dropdownParent: $('.city-select2')
            });

            $(".select2-basic").select2({
                dropdownParent: $('.lang-select2')
            });
        })(jQuery);
    </script>
@endpush
