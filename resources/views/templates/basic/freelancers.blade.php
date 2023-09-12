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
                                            <label class="form-check-label" for="category{{ $category->id }}">{{ __($category->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <p id="showMore" style="cursor: pointer;">@lang('+ Show More')</p>
                            </div>
                        @endif

                        <div class="sidebar-widget has-select2 position-relative d-none">
                            <h6 class="sidebar-widget__title">@lang('Country')</h6>
                            <select class="form-control form--control country form-select select2-basic" name="country">
                                <option value="">@lang('All')</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->country }}" @selected($country->country == "Greece")>{{ __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sidebar-widget city-select2 position-relative">
                            <h6 class="sidebar-widget__title">@lang('City')</h6>
                            <select class="form-control form--control city form-select select2-basic" name="city">
                                <option value="">@lang('All')</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ __($city->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sidebar-widget lang-select2 position-relative">
                            <h6 class="sidebar-widget__title">@lang('Language')</h6>
                            <select class="form-control form--control languageFilter form-select select2-basic" name="name">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($languageData as $lang)
                                    <option value="{{ $lang }}">{{ __($lang) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sidebar-widget position-relative">
                            <h6 class="sidebar-widget__title">@lang('Price')</h6>
                            <div class="price-range-slider">
                                <p class="range-value">
                                  <input type="text" id="amount" readonly>
                                </p>
                                <div id="slider-range" class="range-bar"></div>
                            </div>
                        </div>

                        <div class="sidebar-widget">
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
                        </div>

                        <div class="sidebar-widget">
                            <h6 class="sidebar-widget__title">@lang('Completed Jobs')</h6>
                            <div class="radio-wrapper">
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="" name="complete_job" id="job0" checked>
                                    <label class="form-check-label" for="job0">
                                        @lang('All')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="10" name="complete_job" id="job1">
                                    <label class="form-check-label" for="job1">
                                        @lang('More than 10')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="30" name="complete_job" id="job2">
                                    <label class="form-check-label" for="job2">
                                        @lang('More than 30')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="50" name="complete_job" id="job3">
                                    <label class="form-check-label" for="job3">
                                        @lang('More than 50')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="80" name="complete_job" id="job4">
                                    <label class="form-check-label" for="job4">
                                        @lang('More than 80')
                                    </label>
                                </div>
                                <div class="custom--radio my-2">
                                    <input class="form-check-input completedJob" type="radio" value="100" name="complete_job" id="job5">
                                    <label class="form-check-label" for="job5">
                                        @lang('More than 100')
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
                    <div class="row gy-4 justify-content-center" id="freelancers">
                        @include($activeTemplate . 'filtered_freelancer')
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" crossorigin="anonymous"/>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
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

 .price-range-slider {
	 width: 100%;
	 float: left;
	 padding: 10px 20px;
}
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
	 background: #1fab89;
}
 .price-range-slider .range-bar .ui-slider-handle {
	 border: none;
	 border-radius: 25px;
	 background: #fff;
	 border: 2px solid #1fab89;
	 height: 17px;
	 width: 17px;
	 top: -0.52em;
	 cursor: pointer;
}
 .price-range-slider .range-bar .ui-slider-handle + span {
	 background: #1fab89;
}
/*--- /.price-range-slider ---*/


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

            $('.sortCategory, .completedJob, .sortInfluencer').on('click', function() {
                $('#category0').removeAttr('checked', 'checked');
                if ($('#category0').is(':checked')) {
                    $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
                }

                if ($("input[type='checkbox'][name='category']:checked").length == 0) {
                    $('#category0').attr('checked', 'checked');
                }
                fetchFreelancer();
            });

            $('.sortRating').on('click', function() {
                if ($('#ratings-0').is(':checked')) {
                    $("input[type='radio'][name='star']").not(this).prop('checked', false);
                }
                fetchFreelancer();
            });

            $('.country').on('change', function() {
                fetchFreelancer();
            });

            $('.city').on('change', function() {
                fetchFreelancer();
            });

            $('.languageFilter').on('change', function() {
                fetchFreelancer();
            });

            $('#slider-range').on('click', function() {
                fetchFreelancer();
            });

            $('.searchBtn').on('click', function() {
                $(this).attr('disabled', 'disabled');
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

                data.search         = $('.mySearch').val();
                data.sort           = $('.sortInfluencer:checked').val();
                data.completedJob   = $('.completedJob:checked').val();
                data.rating         = $('.sortRating:checked').val();
                data.country        = $('.country').find(":selected").val();
                data.city           = $('.city').find(":selected").val();
                data.language       = $('.languageFilter').find(":selected").val();
                data.categoryId     = "{{ @$id }}";
                data.priceRange     = $( "#amount" ).val();

                let url = `{{ route('freelancer.filter') }}`;

                if (page) {
                    url = `{{ route('freelancer.filter') }}?page=${page}`;
                }

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        $('#freelancers').html(response);
                        $('.searchBtn').removeAttr('disabled');
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                fetchFreelancer();
            });

            $(".select2-basic").select2({
                dropdownParent: $('.has-select2')
            });

            $(".select2-basic").select2({
                dropdownParent: $('.city-select2')
            });

           //-----JS for Price Range slider-----

            $(function() {
                $( "#slider-range" ).slider({
                range: true,
                min: 10,
                max: 1000,
                values: [ {{ @$serviceMinPrice }}, {{ @$serviceMaxPrice }} ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "€" + ui.values[ 0 ] + " - €" + ui.values[ 1 ] );
                }
                });
                $( "#amount" ).val( "€" + $( "#slider-range" ).slider( "values", 0 ) +
                " - €" + $( "#slider-range" ).slider( "values", 1 ) );
            });

        })(jQuery);
    </script>
@endpush
