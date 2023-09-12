@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="pt-50 pb-50">
    <div class="influencer-profile-area container">
        <div class="container-fluid px-3">
            @if($influencer->home_screen_photos != null && $influencer->home_screen_videos != null)
                @php
                    $photos = json_decode($influencer->home_screen_photos);
                    $videos = json_decode($influencer->home_screen_videos);
                @endphp
                <div class="w-100 row">
                    <div class="collage gap-2 collage-5" data-controller="glightbox">
                        @foreach($photos AS $photo)
                        <a href="javascript:void(0)" class="lightBoxImageOpen" data-image="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$photo}}">
                            <img src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$photo}}" class="img-fluid" />
                        </a>
                        @endforeach
                    
                        @foreach($videos AS $video)
                        <a href="javascript:void(0)" class="lightBoxVideoOpen" data-video="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}">
                            <video class="img-fluid">
                                <source src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/videos/{{$video}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="influencer-profile-wrapper">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="content">
                                <h4 class="fw-medium name account-status d-inline-block">{{ $influencer->fullname }}: {{ $influencer->about_me }}</h4><br>
                                <span class="text--base"> {{ $influencer->profession }}</span>
                                <span class="w-100"><p><i> {{ $influencer->summary }}</i></p></span>
                                @if($influencer->categories)
                                    @foreach (@$influencer->categories as $category)
                                        <div class="justify-content-between skill-card">
                                            <span>{{ __(@$category->name) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="d-flex justify-content-between mt-4 mb-2">
                                <h6 class="fw-medium">@lang('About') @ {{ $influencer->username }}</h6>
                                {{-- <a href="#"><i class="fa fa-solid fa-signal"></i>&nbsp; See More Analytics</a> --}}
                            </div>
                            @if(count($influencer->socialLink) != 0)
                                @php
                                    $followers=[];
                                @endphp
                                @foreach ($influencer->socialLink as $social)
                                    @php
                                        $value = $social->followers;
                                        array_push($followers,$value);
                                        
                                    @endphp
                                @endforeach
                                @php 
                                $showFollowers =0;
                                    $highest = max($followers); 
                                    if ($highest >= 1000 && $highest < 1000000) {
                                        $showFollowers = round($highest / 1000, 2). 'k';
                                    }elseif($highest >= 1000000){
                                        $showFollowers = round($highest / 1000000, 2). 'M';
                                    } else {
                                        $showFollowers = $highest;
                                    }
                                @endphp
                            @else 
                                @php
                                    $showFollowers='';
                                @endphp   
                            @endif  
                            {{-- <div class="d-flex justify-content-between gap-5">
                                <div class="w-100 card">
                                    <div class="card-body">  
                                      <span>FOLLOWERS</span><br>
                                      <h4>{{$showFollowers}}</h4>
                                    </div>
                                </div>
                                <div class="w-100 card">
                                    <div class="card-body">  
                                      <span>FOLLOWERS</span><br>
                                      <h4>{{$showFollowers}}</h4>
                                    </div>
                                </div>
                                <div class="w-100 card">
                                    <div class="card-body">
                                        <span>AVG. ENG. PER POST</span><br>
                                        <h4>450.0</h4>
                                    </div>
                                </div>
                                <div class="w-100 card">
                                    <div class="card-body">
                                        <span>ENG. RATE</span><br>
                                        <h4>2.68 %</h4>
                                    </div>
                                </div>  
                            </div> --}}
                            <div class="row ">
                                <div class="col-md-3 mb-2">
                                    <div class=" card card-body">  
                                      <span>@lang('PENDING JOB')</span><br>
                                      <h4>{{ $data['pending_job'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="card card-body">  
                                      <span>@lang('ONGOING JOB')</span><br>
                                      <h4>{{ $data['ongoing_job'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="card card-body">
                                        <span>@lang('QUEQU JOB')</span><br>
                                        <h4>{{ $data['queue_job'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="card card-body">
                                        <span>@lang('COMPLEATED JOB')</span><br>
                                        <h4>{{ $data['completed_job'] }}</h4>
                                    </div>
                                </div>  
                            </div>
                           
                            @php $i=1; @endphp
                            @if(count($services) == 0)
                            <div class="d-flex justify-content-center mt-5">
                                <h4 id="services" data-services="0">@lang('This Influencer has not provided any services yet')</h4>
                            </div>
                            @else
                            <h6 class="mt-3">@lang('Services')</h6>
                            <p  class="mb-3">@lang('The specific services available from this influencer. Choose one.')</p>
                                @foreach($services AS $service)
                                    <div class="profile-content mt-1">
                                        <div class="custom--card">
                                            <div class="card-body">
                                                <div class="influencer-profile-sidebar">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-3">{{$service->title}}</h6>
                                                        <div class="form-check">
                                                            <label class="form-check-label" for="serviceId{{$i}}" id="planAnount">€ {{round($service->price)}}</label>
                                                            <input class="form-check-input serviceId" type="radio" data-amount="{{round($service->price)}}" data-name="{{$service->title}}" data-serviceid="{{$service->id}}" data-i="{{$i}}" name="plan_id" id="serviceId{{$i}}" {{$i==1 ?'checked' : ''}}>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 text-gray-600 gx-3 gy-2 font-size-base-to-mobile-sm servicesTags" id="servicesTags{{$i}}">
                                                        @foreach ($service->tags AS $tag)
                                                            <div class="col-auto svg-icon-sm d-flex align-items-center">
                                                                <span>{{$tag->name}}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="row text-gray-600 font-size-base-to-mobile-sm">
                                                        <div class="col-full">
                                                            @php echo $service->description @endphp
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1 font-size-base-to-mobile-sm mt-1">
                                                        <div class="col-full">
                                                        <strong>@lang('Features')</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row text-gray-600 font-size-base-to-mobile-sm">
                                                    @foreach ($service->key_points AS $key_point)
                                                    <div class="col-auto svg-icon-sm"><i class="fa fa-check"></i>&nbsp;<span>{{$key_point}}</span></div>
                                                    @endforeach    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @php $i++; @endphp
                                @endforeach
                            @endif    
                        </div>
                        <div class="col-md-4">
                            <div>
                                <img src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" class="rounded-circle" width="70" height="70" />
                                <a href="#"><strong class="text--base ml-2">{{ $influencer->fullname }}</strong></a>
                                @foreach ($influencer->socialLink as $social)
                                @php
                                    $value = $social->followers;

                                    if ($value >= 1000 && $value < 1000000) {
                                        $count = round($value / 1000, 2). 'k';
                                    }elseif($value >= 1000000){
                                        $count = round($value / 1000000, 2). 'M';
                                    } else {
                                        $count = $value;
                                    }
                                @endphp
                                    <a href="https://{{$social->url}}" class="mx-2"><strong>@php echo $social->social_icon @endphp</strong><i>&nbsp;{{ $count }}</i></a>
                                @endforeach
                            </div>
                            <div class="w-100 card mt-2">
                                <div class="card-body text-bg-primary">
                                    <span>@lang('Thaks for checking my profile. Have any question ?')</span>
                                    <div class="pt-2">
                                        <a href="{{ route('user.conversation.influencer.create', $influencer->id) }}" class="msgButton">@lang('Message')</a>
                                        {{-- <a href=" " class="btn btn-dark mx-2">Public Profile</a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="profile-content sticky-top custom-top">
                                <div class="custom--card">
                                    <div class="card-body">
                                        <div class="influencer-profile-sidebar">
                                            <h6 class="mb-3">@lang('Listing Overview')</h6>
                                            <div class="row mb-3 text-gray-600 gx-3 gy-2 font-size-base-to-mobile-sm">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mt-2 checkoutplanName"> </h6>
                                                    <strong class="mt-2 checkoutplanAmt"> </strong>
                                                </div>    
                                                <div id="tagsOncheckout">    
                                                </div>    
                                                <div class="d-flex justify-content-between">
                                                    <strong class="mt-2">@lang('SubTotal')</strong>
                                                    <p class="mt-2 checkoutplanAmt"> </p>
                                                </div> 
                                                @if(!authInfluencerId())
                                                    <div class="d-grid gap-2">
                                                        <a href="" class="btn btn-success buyNowRoute">
                                                            @lang('Buy Now')
                                                        </a>
                                                    </div>
                                                    <div class="col-auto mb-1">
                                                        <p class="text-sm">@lang('You wont be charged until after you click') <b>@lang('Buy Now')</b></p>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('user.order.create-offer', $influencer->username) }}" class="text--base">
                                                            @lang('Create Custom Offer')
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row position-relative">
                <div class="d-flex justify-content-start pt-4 pb-2 mx-2">
                    <h4>@lang('Similar Influencers')</h4>
                </div>
                <div class="row justify-content-center">
                    @include($activeTemplate . 'similar_influencer')
                </div>
            </div>
        </div>
    </div>
</div>


{{--Image LightBox modal start--}}
<div class="modal fade bd-example-modal-lg" id="lightBoxImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"> </h5>
          <h3 data-bs-dismiss="modal"><i class="far fa-times-circle"></i></h3>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <img src="" class="img-fluid rounded" id="lightBoxImg" />
            </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
</div>
{{--Image LightBox modal end--}}

{{--Video LightBox modal start--}}
<div class="modal fade bd-example-modal-lg" id="lightBoxVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"> </h5>
            <h3 data-bs-dismiss="modal"><i class="far fa-times-circle"></i></h3>
        </div>
        <div class="modal-body">
            <video class="w-100" height="550" id="lightBoxVideo" controls autoplay>
                <source src="" type="video/mp4" >
                Your browser does not support the video tag.
            </video>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
</div>
{{--Video LightBox modal end--}}

@endsection
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/templates/basic/css/grid_style.css') }}">
@endpush
@push('style')
<style>

    .msgButton {
        color: white;
        background-color: #1fab89;
        padding: 8px 25px;
        font-size: 1rem;
        font-weight: 400;
        border-radius: 4px;
    }
    .profile .thumb {
        width: 100px;
        height: 100px;
    }
    
    .custom-top {
        top: 6rem;
    }
    .pagination {
        display: none;
    }

    .image-size {
        min-height: 300px;
        height: 300px;
        margin-bottom: 1%;
    }

.grid-sizer,
.grid-item { width: 20%; }
/* 2 columns */
.grid-item--width2 { width: 40%; }

</style>
@endpush
@push('script')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        (function($) {
            "use strict";

            $('.grid').masonry({
                // set itemSelector so .grid-sizer is not used in layout
                itemSelector: '.grid-item',
                // use element for option
                columnWidth: '.grid-sizer',
                percentPosition: true
            })

            var services  = $('#services').data('services');
            if(services !=0){
                var amount =    $('.serviceId').data().amount;             
                var planname =  $('.serviceId').data().name;  
                var tagsDiv =   $('#servicesTags'+1).clone();
                var serviceId = $('.serviceId').data().serviceid;

                $('#tagsOncheckout').html(tagsDiv);
                $('.checkoutplanAmt').html('€'+ amount);
                $('.checkoutplanName').html(planname);
                $('.buyNowRoute').attr('href', '/influencer/client/order/service/'+serviceId);
            };


            $('.serviceId').on('click', function() {
                var i = $(this).data().i;
                var amount =   $(this).data().amount;             
                var planname = $(this).data().name; 
                var tagsDiv =  $('#servicesTags'+i).clone();
                var serviceId = $(this).data().serviceid;

                $('#tagsOncheckout').html(tagsDiv);
                $('.checkoutplanAmt').html('€'+ amount);
                $('.checkoutplanName').html(planname);
                $('.buyNowRoute').attr('href', '/influencer/client/order/service/'+serviceId);
            });

            $('.lightBoxImageOpen').on('click', function() {
                var image = $(this).data().image;
                var modal = $('#lightBoxImageModal');
                modal.find('[id=lightBoxImg]').attr('src',image);
                modal.modal('show')
            });

            $('.lightBoxVideoOpen').on('click', function() {
                var video = $(this).data().video;
                var modal = $('#lightBoxVideoModal');
                modal.find('[id=lightBoxVideo]').attr('src',video);
                modal.modal('show')
            });

        })(jQuery);
    </script>
@endpush
