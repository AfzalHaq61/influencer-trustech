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
                            <input class=" sortCategory" type="checkbox" name="category" value="" id="category0" checked>
                            <label class="form-check-label" for="category0">@lang('All Categories')</label>
                        </div>
                        @foreach ($allCategory as $category)
                            <div class="custom--checkbox my-2">
                                <input class=" sortCategory" type="checkbox" name="category" value="{{ $category->id }}" id="category{{ $category->id }}">
                                <label class="form-check-label" for="category{{ $category->id }}">{{ __($category->name) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p id="showMore" style="cursor: pointer;">+ Show More</p>
                </div>
                 @endif

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
                    <h6 class="sidebar-widget__title">@lang('Completed Jobs')</h6>
                    <div class="radio-wrapper">
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="" name="complete_job" id="job0" checked>
                            <label class="form-check-label" for="job0">
                                @lang('All')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="10" name="complete_job" id="job1">
                            <label class="form-check-label" for="job1">
                                @lang('More than 10')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="30" name="complete_job" id="job2">
                            <label class="form-check-label" for="job2">
                                @lang('More than 30')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="50" name="complete_job" id="job3">
                            <label class="form-check-label" for="job3">
                                @lang('More than 50')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="80" name="complete_job" id="job4">
                            <label class="form-check-label" for="job4">
                                @lang('More than 80')
                            </label>
                        </div>
                        <div class="custom--radio my-2">
                            <input class="completedJob" type="radio" value="100" name="complete_job" id="job5">
                            <label class="form-check-label" for="job5">
                                @lang('More than 100')
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
                <strong id="freelancerCount">Total Freelancers - {{count($freelancers)}}</strong><br>
                <span class="mt-5">Mail To</span>
                <div class="overflow-auto filteredFreelancers" style="height: 80px">
                    @foreach($freelancers AS $freelancer)
                        <span class="btn btn-primary btn-sm rounded-pill my-1">{{$freelancer->email}}</span>
                    @endforeach
                </div>
                <form action="{{ route('admin.news-letter.freelancer.send-mail') }}" method="post">
                    @csrf
                    <div class="row">
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

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
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
                fetchFreelancer();
            });

            $('.country').on('change', function() {
                fetchFreelancer();
            });

            $('.city').on('change', function() {
                fetchFreelancer();
            });

            $('.language').on('change', function() {
                fetchFreelancer();
            });

            $('.completedJob').on('click', function() {
                fetchFreelancer();
            });

            function fetchFreelancer() {
                $('.loader-wrapper').removeClass('d-none');
                let data = {};
                data.categories = [];

                $.each($("[name=category]:checked"), function() {
                    if ($(this).val()) {
                        data.categories.push($(this).val());
                    }
                });

                data.country = $('.country').find(":selected").val();
                data.city = $('.city').find(":selected").val();
                data.language = $('.language').find(":selected").val();
                data.completedJob = $('.completedJob:checked').val();

                let url = `{{ route('admin.news-letter.filter.freelancer') }}`;

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        var div = " ";
                        for (let i = 0; i < response.emails.length; i++) {
                            div += "<span class='btn btn-primary btn-sm rounded-pill my-1 mx-1'>"+ response.emails[i]+"</span>";
                        }

                        $('#freelancerCount').text('Freelancers Found - '+ response.count)
                        $('.filteredFreelancers').html(div)
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }


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