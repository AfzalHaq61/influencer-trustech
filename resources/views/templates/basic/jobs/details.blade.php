@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="all-sections pt-60 pb-60">
        <div class="container-fluid p-max-sm-0">
            <div class="sections-wrapper d-flex flex-wrap justify-content-center">
                <article class="main-section">
                    <div class="section-inner">
                        <div class="item-section item-details-section">
                            <div class="container">
                                <div class="row justify-content-center mb-30-none">
                                    <div class="col-xl-9 col-lg-9 mb-30">
                                        <div class="item-details-area">
                                            <div class="item-details-box">
                                                <div class="item-details-thumb-area">
                                                    <div class="item-details-slider-area">
                                                        <div class="item-details-slider">
                                                            <div class="swiper-wrapper">
                                                                <div class="swiper-slide">
                                                                    <div class="item-details-thumb">
                                                                        <img src="{{ getImage(getFilePath('service').'/'.$productDetails->image, getFileSize('service')) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item-details-content">
                                                            <h2 class="title">{{__($productDetails->name)}}</h2>
                                                            <div class="item-details-footer">
                                                                <div class="left">
                                                                    <div class="item-details-tag p-0 m-0 border-0">
                                                                        <ul class="tags-wrapper">
                                                                            <li class="caption">@lang('Skill')</li>
                                                                            @foreach($productDetails->skill as $skill)
                                                                                <li>
                                                                                    <a href="{{route('jobs')}}?skill={{$skill}}">{{__($skill)}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="right">
                                                                    <div class="social-area">
                                                                        <ul class="footer-social">
                                                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Facebook')">
                                                                                <a href="http://www.facebook.com/sharer.php?u={{urlencode(url()->current())}}&p[title]={{slug($productDetails->name)}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                                                            </li>
                                                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Linkedin')">
                                                                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{urlencode(url()->current()) }}&title={{slug($productDetails->name)}}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                                                            </li>
                                                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Twitter')">
                                                                                <a href="http://twitter.com/share?text={{slug($productDetails->name)}}&url={{urlencode(url()->current()) }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                                                            </li>
                                                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Pinterest')">
                                                                                <a href="http://pinterest.com/pin/create/button/?url={{urlencode(url()->current()) }}&description={{slug($productDetails->name)}}" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-tab mt-40">
                                                <nav>
                                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                        <button class="nav-link active detailButton" id="des-tab" data-bs-toggle="tab" data-bs-target="#des" type="button"role="tab" aria-controls="des" aria-selected="true">@lang('Description')</button>
                                                        <button class="nav-link detailButton" id="req-tab" data-bs-toggle="tab" data-bs-target="#req" type="button" role="tab" aria-controls="req" aria-selected="false">@lang('Requirements')</button>
                                                        @if($productDetails->job_type == 'campaign')
                                                        <button class="nav-link detailButton" id="gui-tab" data-bs-toggle="tab" data-bs-target="#gui" type="button" role="tab" aria-controls="gui" aria-selected="false">@lang('Guidelines')</button>
                                                        @endif
                                                    </div>
                                                </nav>

                                                <div class="tab-content" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="des" role="tabpanel" aria-labelledby="des-tab">
                                                        <div class="product-desc-content">
                                                            @php echo $productDetails->description @endphp
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade show" id="req" role="tabpanel" aria-labelledby="req-tab">
                                                        <div class="product-desc-content">
                                                            @php echo $productDetails->requirements @endphp
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade show" id="gui" role="tabpanel" aria-labelledby="gui-tab">
                                                        <div class="product-desc-content">
                                                            @php echo $productDetails->campaign_guidelines @endphp
                                                        </div>
                                                    </div>

                                                    {{-- <div class="tab-pane fade" id="bids" role="tabpanel" aria-labelledby="bids-tab">
                                                        @if(count($productDetails->jobBidings))
                                                            <div class="item-card-wrapper item-card-wrapper--style border-0 p-0 list-view justify-content-center mt-30">
                                                                @foreach($productDetails->jobBidings as $biding)
                                                                    <div class="item-card">
                                                                        <div class="item-card-content">
                                                                            <div class="item-card-content-top">
                                                                                <div class="item-top-wrapper d-flex flex-wrap align-items-center justify-content-between">
                                                                                    <h3 class="item-card-title">{{__($biding->title)}}</h3>
                                                                                    <div class="right">
                                                                                        <div class="item-amount">{{$general->cur_sym}}{{showAmount($biding->price)}}</div>
                                                                                    </div>
                                                                                </div>
                                                                                <p>{{__($biding->description)}}</p>
                                                                                <div class="item-footer-wrapper d-flex flex-wrap align-items-center justify-content-between">
                                                                                    <div class="left">
                                                                                        <div class="author-thumb">
                                                                                            <img src="{{ getImage(getFilePath('userProfile').'/'.$biding->user->image, getFileSize('userProfile')) }}" alt="@lang('bidder')">
                                                                                        </div>
                                                                                        <div class="author-content">
                                                                                            <h5 class="name">
                                                                                                <a href="{{route('public.profile', $biding->user->username)}}">{{$biding->user->username}}</a>
                                                                                                <span class="level-text">{{$biding->user->level->name}}</span>
                                                                                            </h5>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            @if(count($productDetails->jobBidings) > 5)
                                                                <div class="view-more-btn text-center mt-4">
                                                                    <button type="button" class="btn--base"> @lang('View More')</button>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @include($activeTemplate.'partials.empty_data')
                                                        @endif
                                                    </div> --}}

                                                    {{-- @include($activeTemplate.'partials.comment_reply') --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 mb-30">
                                        <div class="sidebar">
                                            <div class="widget custom-widget mb-30">
                                                <h3 class="widget-title">@lang('SHORT DETAILS')</h3>    
                                                <ul class="details-list">
                                                    <li><span>@lang('Delivery Time')</span> <span>{{$productDetails->delivery_time}} @lang('Days')</span></li>
                                                    @if($productDetails->job_type == 'campaign')
                                                        <li><span>@lang('Job Type')</span> <span>@lang('Campaign')</span></li>
                                                        <li><span>@lang('Strat Date')</span> <span>{{date('d-m-Y',strtotime($productDetails->campaign_start_date))}}</span></li>
                                                        <li><span>@lang('End Date')</span> <span>{{date('d-m-Y',strtotime($productDetails->campaign_end_date))}}</span></li>
                                                    @elseif($productDetails->job_type == 'event')
                                                        <li><span>@lang('Job Type')</span> <span></span>@lang('Event')</li>
                                                        <li><span>@lang('Event Date')</span> <span>{{date('d-m-Y',strtotime($productDetails->event_date))}}</span></li>
                                                        <li><span>@lang('Location')</span> <span></span>{{$productDetails->event_location}}</li>
                                                    @elseif($productDetails->job_type == 'casting')
                                                        <li><span>@lang('Job Type')</span> <span></span>@lang('Casting/Pitch')</li>
                                                    @else
                                                        <li><span>@lang('Job Type')</span> <span></span>@lang('Basic')</li>
                                                    @endif
                                                    
                                                    <li><span>@lang('Target D. Age From')</span> <span><b>{{$productDetails->target_demographic_from}}</b>@lang('Years')</span></li>
                                                    <li><span>@lang('Target D. Age To')</span> <span><b>{{$productDetails->target_demographic_to}}</b>@lang('Years')</span></li>
                                                    <li><span>@lang('Target D. Gender')</span> <span><b>{{$productDetails->target_demographic_gender}}</b> </span></li>
                                                    <li><span>@lang('Budget')</span> <span><b>{{showAmount($productDetails->price)}}</b> {{__($general->cur_text)}}</span></li>
                                                </ul>
                                                @if($user_type == 'user')
                                                    @if($productDetails->influencer_id != 0)
                                                        <a href="{{ route('user.conversation.influencer.create', [$productDetails->influencer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @elseif($productDetails->freelancer_id != 0)
                                                        <a href="{{ route('user.conversation.freelancer.create', [$productDetails->freelancer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @else
                                                        @if(isset(Auth::user()->id) != $productDetails->user_id)
                                                            {{-- <a href=" " class="btn--base w-100" >@lang('Message')</a> --}}
                                                            <button class="btn btn-success w-100" disabled>@lang('Message')</button>
                                                        @else
                                                            <p class="text-center"><b>@lang('You posted this job')</b></p> 
                                                        @endif
                                                    @endif
                                                @elseif($user_type == 'influencer')
                                                    @if($productDetails->influencer_id != 0)
                                                        @if(authInfluencerId() != $productDetails->influencer_id)
                                                            <a href="{{ route('influencer.conversation.influencer.create', [$productDetails->influencer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                        @else
                                                            <p class="text-center"><b>@lang('You posted this job')</b></p> 
                                                        @endif
                                                    @elseif($productDetails->freelancer_id != 0)
                                                        <a href="{{ route('influencer.conversation.freelancer.create', [$productDetails->freelancer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @else
                                                        <a href="{{ route('influencer.conversation.client.create', [$productDetails->user_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @endif
                                                @elseif($user_type == 'freelancer')
                                                    @if($productDetails->influencer_id != 0)
                                                        <a href="{{ route('freelancer.conversation.influencer.create', [$productDetails->influencer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @elseif($productDetails->freelancer_id != 0)
                                                        @if(authFreelancerId() != $productDetails->freelancer_id)
                                                            <a href="{{ route('freelancer.conversation.freelancer.create', [$productDetails->freelancer_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                        @else
                                                            <p class="text-center"><b>@lang('You posted this job')</b></p> 
                                                        @endif
                                                    @else
                                                        <a href="{{ route('freelancer.conversation.client.create', [$productDetails->user_id, $productDetails->id]) }}" class="btn--base w-100">@lang('Message')</a>
                                                    @endif
                                                @elseif($user_type == 'no_login')
                                                    <a href="{{ route('user.login') }}" class="btn--base w-100">@lang('Message')</a>
                                                @endif
                                               
                                            </div>
                                            <a href="{{route('job.order.custom', [slug($productDetails->name), $productDetails->id])}}" class="btn--base w-100">@lang('Order Now')</a>
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
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $(".select2-basic").select2({
                dropdownParent: $('.lang-select2')
            });
            
        })(jQuery);
    </script>
@endpush 