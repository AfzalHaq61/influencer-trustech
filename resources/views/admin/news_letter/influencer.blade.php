@extends('admin.layouts.app')
@section('panel')
<section class="influencer-section">
    <div class="container ">
        <div class="row gy-4 justify-content-center">
            <div class="col-xl-3">
                @if (@$allCategory)
                <div class="sidebar-widget w-full">
                    <div class="form-group">
                        <a href="" class="btn btn-primary">@lang('Clear Filter')</a>
                    </div>
                    <h6 class="sidebar-widget__title">@lang('Categories')</h6>
                    <div class="checkbox-wrapper categoryDiv" id="categoryDiv">
                        <div class="custom--checkbox">
                            <input class="sortCategory" type="checkbox" name="category" value="" id="category0" checked>
                            <label class="form-check-label" for="category0">@lang('All Categories')</label>
                        </div>
                        @foreach ($allCategory as $category)
                            <div class="custom--checkbox my-2">
                                <input class="sortCategory" type="checkbox" name="category" value="{{ $category->id }}" id="category{{ $category->id }}">
                                <label class="form-check-label" for="category{{ $category->id }}">{{ __($category->name) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p id="showMore" style="cursor: pointer;">+ Show More</p>
                </div>
                 @endif

                 <div class="sidebar-widget w-full mt-3">
                    <h6 class="sidebar-widget__title">@lang('Social Handles')</h6>
                    <div class="checkbox-wrapper">
                        <div class="custom--checkbox my-2">
                            <input class="instgramAccount" type="checkbox" value="instagram" name="instagram" id="instagram">
                            <label class="form-check-label" for="instagram">@lang('Instagram')</label>
                        </div>
                    </div>
                    <div class="checkbox-wrapper my-2">
                        <div class="custom--checkbox">
                            <input class="twitterAccount" type="checkbox" value="twitter" name="twitter" id="twitter">
                            <label class="form-check-label" for="twitter">@lang('Twitter')</label>
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget w-full mt-3">
                    <h6 class="sidebar-widget__title">@lang('Followers Count')</h6>
                    <div class="radio-wrapper">
                        <div class="custom--radio my-2">
                            <input class="followersCount" type="radio" value="instagram" name="sort" id="instagram_followers">
                            <label class="form-check-label" for="instagram_followers">
                                @lang('Instagram followers')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="followersCount" type="radio" value="twitter" name="sort" id="twitter_followers">
                            <label class="form-check-label" for="twitter_followers">
                                @lang('Twitter followers')
                            </label>
                        </div>
                        <div class="followersCountDiv d-none">
                            <b class="sidebar-widget__title">@lang('sort by numbers of followers')</b>
                            <div class="custom--radio my-2">
                                <input class="followersCountFilter" type="radio" value="nano" name="followers_count" id="nano">
                                <label class="form-check-label" for="nano">
                                    @lang('Nano (0-10k)')
                                </label>
                            </div>
                            <div class="custom--radio my-2">
                                <input class="followersCountFilter" type="radio" value="micro" name="followers_count" id="micro">
                                <label class="form-check-label" for="micro">
                                    @lang('Micro (10k-100k)')
                                </label>
                            </div>
                            <div class="custom--radio my-2">
                                <input class="followersCountFilter" type="radio" value="middle" name="followers_count" id="middle">
                                <label class="form-check-label" for="middle">
                                    @lang('Middle (100k-500k)')
                                </label>
                            </div>
                            <div class="custom--radio my-2">
                                <input class="followersCountFilter" type="radio" value="macro" name="followers_count" id="macro">
                                <label class="form-check-label" for="macro">
                                    @lang('Macro (500k-1M)')
                                </label>
                            </div>
                            <div class="custom--radio my-2">
                                <input class="followersCountFilter" type="radio" value="mega" name="followers_count" id="mega">
                                <label class="form-check-label" for="mega">
                                    @lang('Mega (1M+)')
                                </label>
                            </div>
                        </div>    
                    </div>
                </div>

                <div class="sidebar-widget has-select2 position-relative mt-3 d-none">
                    <h6 class="sidebar-widget__title">@lang('Country')</h6>
                    <select class="form-control form--control country form-select select2-basic" name="country">
                        <option value="">@lang('All')</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->country }}" @selected($country->country == "Greece")>{{ __($country->country) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sidebar-widget city-select2 position-relative mt-3">
                    <h6 class="sidebar-widget__title">@lang('City')</h6>
                    <select class="form-control form--control city form-select select2-basic" name="city">
                        <option value="">@lang('All')</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ __($city->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sidebar-widget lang-select2 position-relative mt-3">
                    <h6 class="sidebar-widget__title">@lang('Language')</h6>
                    <select class="form-control form--control language form-select select2-basic" name="name">
                        <option value="" selected disabled>@lang('Select One')</option>
                        @foreach ($languageData as $lang)
                            <option value="{{ $lang }}">{{ __($lang) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sidebar-widget mt-3">
                    <h6 class="sidebar-widget__title">@lang('Availability')</h6>
                    <div class="radio-wrapper">
                        <div class="custom--checkbox my-2">
                            <input class="avlForAll" type="checkbox" value="available_for_all" name="available_for_all" id="available_for_all" checked>
                            <label class="form-check-label" for="available_for_all">
                                @lang('All')
                            </label>
                        </div>
                        <div class="custom--checkbox my-2">
                            <input class="avlForEvents" type="checkbox" value="available_for_events" name="available_for_events" id="available_for_events">
                            <label class="form-check-label" for="available_for_events">
                                @lang('Available For Events')
                            </label>
                        </div>
                        <div class="custom--checkbox my-2">
                            <input class="avlForTravell" type="checkbox" value="available_for_travelling" name="available_for_travelling" id="available_for_travelling">
                            <label class="form-check-label" for="available_for_travelling">
                                @lang('Available For Travelling')
                            </label>
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget mt-3">
                    <h6 class="sidebar-widget__title">@lang('Sex')</h6>
                    <div class="radio-wrapper">
                        <div class="custom--radio my-2">
                            <input class="sexInfluencer" type="radio" value="all" name="sex" id="all" checked>
                            <label class="form-check-label" for="all">
                                @lang('All')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="sexInfluencer" type="radio" value="male" name="sex" id="male">
                            <label class="form-check-label" for="male">
                                @lang('male')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="sexInfluencer" type="radio" value="female" name="sex" id="female">
                            <label class="form-check-label" for="female">
                                @lang('Female')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="sexInfluencer" type="radio" value="LGBTQ+" name="sex" id="LGBTQ+">
                            <label class="form-check-label" for="LGBTQ+">
                                @lang('LGBTQ+')
                            </label>
                        </div>
                    </div>
                </div>
                <div class="sidebar-widget mt-3">
                    <h6 class="sidebar-widget__title">@lang('Target Demographics')</h6>
                    <h6 class="mt-1">Age</h6>
                    <select class="form--control form-select targetDemographicAgeFrom" name="target_demographic_from">
                        <option value="">@lang('From')</option>
                        <option value="all">All</option>
                        @for($age=5; $age<=100; $age+=5)
                            <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                        @endfor
                    </select>
                    <select class="form--control form-select targetDemographicAgeTo mt-2" name="target_demographic_to">
                        <option value="">@lang('To')</option>
                        <option value="all">All</option>
                        @for($age=5; $age<=100; $age+=5)
                            <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                        @endfor
                    </select>

                    <h6  class="mt-2">Gender</h6>
                    <div class="radio-wrapper">
                        <div class="custom--radio my-2">
                            <input class="targetDemographicSex" type="radio" value="all" name="target_demo_sex" id="target_all">
                            <label class="form-check-label" for="target_all">
                                @lang('All')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="targetDemographicSex" type="radio" value="Male" name="target_demo_sex" id="target_male">
                            <label class="form-check-label" for="target_male">
                                @lang('Male')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="targetDemographicSex" type="radio" value="Female" name="target_demo_sex" id="target_female">
                            <label class="form-check-label" for="target_female">
                                @lang('Female')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="targetDemographicSex" type="radio" value="LGBTQ+" name="target_demo_sex" id="target_LGBTQ+">
                            <label class="form-check-label" for="target_LGBTQ+">
                                @lang('LGBTQ+')
                            </label>
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
                {{-- <div class="row gy-4 justify-content-center" id="influencers">
                    @include($activeTemplate . 'news_letter_influencer')
                </div> --}}
                <strong id="influencerCount">Total Influencers - {{count($influencers)}}</strong><br>
                <span class="mt-5">Mail To</span>
                <div class="overflow-auto filteredInfluencers" style="height: 80px">
                    @foreach($influencers AS $influencer)
                        <span class="btn btn-primary btn-sm rounded-pill my-1">{{$influencer->email}}</span>
                    @endforeach
                </div>
                <form action="{{ route('admin.news-letter.influencer.send-mail') }}" method="post">
                    @csrf
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="form-group">
                                <label> @lang('Subject')</label>
                                <input class="form-control" type="text" name="subject" value="" required>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label required" for="message">@lang('Message')</label>
                            <textarea rows="10" class="form-control form--control nicEdit" name="message" id="message" placeholder="@lang('Write here')">@php echo old('description') @endphp</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary w-100">@lang('Send')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
        .categoryDiv {
            max-height: 100px;
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
                    $('#showMore').text("- Show Less");
                } else {
                    $('#showMore').text("+ Show More");
                }

            });

            $('.sortCategory').on('click', function() {
                $('#category0').removeAttr('checked', 'checked');
                if ($('#category0').is(':checked')) {
                    $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
                }

                if ($("input[type='checkbox'][name='category']:checked").length == 0) {
                    $('#category0').attr('checked', 'checked');
                }
                fetchInfluencer();
            });

            $('.instgramAccount').on('click', function() {
                fetchInfluencer();
            });

            $('.twitterAccount').on('click', function() {
                fetchInfluencer();
            });

            $('.followersCount').on('click', function() {
                $('.followersCountDiv').removeClass('d-none');
                fetchInfluencer();
            });

            $('.followersCountFilter').on('click', function() {
                fetchInfluencer();
            });

            $('.country').on('change', function() {
                fetchInfluencer();
            });

            $('.city').on('change', function() {
                fetchInfluencer();
            });

            $('.language').on('change', function() {
                fetchInfluencer();
            });

            $('.avlForAll').on('click', function() {
                if ($(this).is(':checked')) {
                    $(".avlForEvents").prop('checked', false);
                    $(".avlForTravell").prop('checked', false);
                }
                fetchInfluencer();
            });

            $('.avlForEvents').on('click', function() {
                $(".avlForAll").prop('checked', false);
                if ($("input[type='checkbox'][name='available_for_events']:checked").length == 0 && $("input[type='checkbox'][name='available_for_travelling']:checked").length == 0) {
                    $('.avlForAll').attr('checked', 'checked');
                }
                fetchInfluencer();
            });

            $('.avlForTravell').on('click', function() {
                $(".avlForAll").prop('checked', false);
                if ($("input[type='checkbox'][name='available_for_events']:checked").length == 0 && $("input[type='checkbox'][name='available_for_travelling']:checked").length == 0) {
                    $('.avlForAll').attr('checked', 'checked');
                }
                fetchInfluencer();
            });

            $('.sexInfluencer').on('click', function() {
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


            function fetchInfluencer() {
                $('.loader-wrapper').removeClass('d-none');
                let data = {};
                data.categories = [];

                $.each($("[name=category]:checked"), function() {
                    if ($(this).val()) {
                        data.categories.push($(this).val());
                    }
                });
                data.instgramAccount = $('.instgramAccount:checked').val();
                data.twitterAccount = $('.twitterAccount:checked').val();
                data.followersCount = $('.followersCount:checked').val();
                data.followersCountFilter = $('.followersCountFilter:checked').val();
                data.country = $('.country').find(":selected").val();
                data.city = $('.city').find(":selected").val();
                data.language = $('.language').find(":selected").val();
                data.avlForAll = $('.avlForAll:checked').val();
                data.avlForEvents = $('.avlForEvents:checked').val();
                data.avlForTravell = $('.avlForTravell:checked').val();
                data.sex = $('.sexInfluencer:checked').val();
                data.targetDemoAgeFrom = $('.targetDemographicAgeFrom').find(":selected").val();
                data.targetDemoAgeTo = $('.targetDemographicAgeTo').find(":selected").val();
                data.targetDemoSex = $('.targetDemographicSex:checked').val();


                let url = `{{ route('admin.news-letter.filter.influencer') }}`;

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        var div = " ";
                        for (let i = 0; i < response.emails.length; i++) {
                            div += "<span class='btn btn-primary btn-sm rounded-pill my-1 mx-1'>"+ response.emails[i]+"</span>";
                        }
                        $('#influencerCount').text('Influencers Found - '+ response.count);
                        $('.filteredInfluencers').html(div)
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }

            // $(document).on('click', '.pagination a', function(event) {
            //     event.preventDefault();
            //     page = $(this).attr('href').split('page=')[1];
            //     fetchInfluencer();
            // });

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