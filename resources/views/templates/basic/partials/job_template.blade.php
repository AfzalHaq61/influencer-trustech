    <div class="item-card-wrapper list-view">
        @forelse($products as $product)
            <div class="item-card">
                <div class="item-card-thumb">
                    <img src="{{ getImage(getFilePath($type).'/'.$product->image, getFileSize($type)) }}" alt="@lang('Service Image')">
                    @if($product->featured)
                        <div class="item-level">@lang('Featured')</div>
                    @endif
                </div>
                <div class="item-card-content">
                    <div class="item-card-content-top">
                        {{-- <div class="left">
                           
                            <div class="author-thumb">
                                @if($product->user_id != 0) 
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $product->user->image, getFileSize('userProfile'), true) }}" alt="@lang('User Image')">
                                @elseif($product->influencer_id != 0)
                                    <a href="{{ route('influencer.profile', [slug($product->influencer->username), $product->influencer->id]) }}">
                                        <img src="{{ url('/') }}/core/public/sample/{{$product->influencer->username}}/photos/{{$product->influencer->image}}" alt="@lang('User Image')">
                                    </a>    
                                @elseif($product->freelancer_id != 0)
                                    <a href="{{ route('freelancer.profile', [slug($product->freelancer->username), $product->freelancer->id]) }}">
                                        <img src="{{ url('/') }}/core/public/sample/{{$product->freelancer->username}}/photos/{{$product->freelancer->image}}" alt="@lang('User Image')">
                                    </a>    
                                @endif
                            </div>
                            <div class="author-content">
                                <h5 class="name">
                                    @if($product->user_id != 0) 
                                        <a href="">{{__($product->user->username)}}</a>
                                    @elseif($product->influencer_id != 0)
                                        <a href="{{ route('influencer.profile', [slug($product->influencer->username), $product->influencer->id]) }}">{{__($product->influencer->username)}}</a>
                                    @elseif($product->freelancer_id != 0)
                                        <a href="{{ route('freelancer.profile', [slug($product->freelancer->username), $product->freelancer->id]) }}">{{__($product->freelancer->username)}}</a>
                                    @endif
                                </h5>
                                @if (request()->routeIs('home') || request()->routeIs('service') || $type == 'software')
                                    <div class="ratings">
                                        @php echo starRating($product->total_review, $product->total_rating) @endphp
                                        <span class="rating me-2">
                                            ({{$product->total_review}})
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        
                        {{-- <div class="right">
                            <div class="item-amount">
                                {{$general->cur_sym}}{{showAmount($product->price)}}
                            </div>
                        </div> --}}
                    </div>
                    @if ($type == 'job')
                        <div class="item-tags order-3">
                            @foreach($product->skill as $skill)
                                <a href="{{route('job')}}?skill={{$skill}}">{{__($skill)}}</a>
                            @endforeach
                        </div>
                    @endif
                    <h3 class="item-card-title">
                        <a href="{{route("job.details", [slug($product->name), $product->id])}}">{{__($product->name)}}</a>
                    </h3>
                    <div>
                        <p>{{ Str::words($product->description, 30, '...') }}</p>
                    </div>
                </div>
                <div class="item-card-footer">
                    <div class="left">
                        @if (request()->routeIs('home') || request()->routeIs('service') || $type == 'software')
                            <button type="button" class="item-love me-2 make-favorite" data-id="{{$product->id}}" data-type="@if ($type == 'service') service @else software @endif">
                                <i class="fas fa-heart"></i>
                                <span class="favorite-count">({{__($product->favorite)}})</span>
                            </button>
                            <span class="item-like">
                                <i class="las la-thumbs-up"></i> ({{$product->likes}})
                            </span>
                        @endif
                        @if ($type == 'software')
                            <a href="{{route('user.software.confirm.booking', [slug($product->name), $product->id])}}" class="btn--base active buy-btn"><i class="las la-shopping-cart"></i> @lang('Buy Now')</a>
                        @endif
                        @if ($type == 'job')
                            <span class="btn--base active date-btn">{{$product->delivery_time}} @lang('Days')</span>
                            <span class="btn--base bid-btn">@lang('Total Bids')({{$product->total_bid}})</span>
                        @endif
                    </div>
                    <div class="right">
                        <div class="order-btn">
                            <a href="{{route("job.details", [slug($product->name), $product->id])}}" ><i class="las la-eye"></i> @lang('View More')</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @include($activeTemplate.'partials.empty_data')
        @endforelse
    </div>
    <nav>
        {{ paginateLinks($products)}}
    </nav>

    @push('style')
        <style>
            .order-btn a {
                color: white;
                background-color: #1fab89;
                padding: 3px 5px;
                font-size: 12px;
                border-radius: 5px;
            }
        </style>
    @endpush