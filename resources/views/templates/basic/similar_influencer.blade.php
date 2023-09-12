@php
$favorite    = App\Models\Favorite::where('user_id', auth()->id())->select('influencer_id')->pluck('influencer_id');
$influencersId = json_decode($favorite);
$emptyMsgImage = getContent('empty_message.content', true);
$logged_influencer = authInfluencer();
if(isset($thisInfluencerId)){
    $thisInfluencerId = $thisInfluencerId;
}
else{
    $thisInfluencerId = null;

}
@endphp
@if (request()->search)
<p>@lang('Search Result For') <span class="text--base">{{ __(request()->search) }}</span> : @lang('Total') <span class="text--base">{{ $influencers->count() }}</span> @lang('Influencers Found')</p>
@endif
@forelse ($influencers as $influencer)
@if($thisInfluencerId != $influencer->id)
    @if($logged_influencer != null)
        @if($influencer->id != $logged_influencer->id)
        <div class="col-md-6 col-lg-3 col-xl-4 col-sm-9 col-xs-10">
            <div class="card rounded influencer-item">
                <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}">
                    <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" alt="Card image cap" height="300" width="150">
                </a>
                <div class="card-body card-img-overlay grid-overlay">
                    @if(count($influencer->socialLink) != 0)
                    <ul class="social-links d-flex justify-content-center flex-wrap">
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

                            if($social->social_platform == "instagram"){
                                $style = "#fe0090";
                            }elseif($social->social_platform == "twitter"){
                                $style = "#179cf0";
                            }elseif($social->social_platform == "facebook"){
                                $style = "#1877f2";
                            }elseif($social->social_platform == "linkedin"){
                                $style = "#0075b4";
                            }elseif($social->social_platform == "youtube"){
                                $style = "#ff0103";
                            }
                        @endphp
                        <li><a href="https://{{ $social->url }}" data-bs-toggle="tooltip" data-placement="top" title="{{ __($social->followers) }}" target="_blank" style="color: {{$style}}">@php echo $social->social_icon; echo " ".$count @endphp</a></li>
                        @endforeach
                        
                    </ul>
                    @endif
                    <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}" class="top-marign">
                    <p class="card-title text-white">{{ __($influencer->fullname) }} <br>
                        {{ __($influencer->address->city) }}</p>
                    </a>
                   
                </div>
            </div>
            <p class="text-black text-center">{{ __($influencer->about_me) }}</p>
        </div>
        @endif
    @else 
        <div class="col-md-6 col-lg-3 col-xl-4 col-sm-9 col-xs-10">
            <div class="card rounded influencer-item">
                <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}">
                    <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" alt="Card image cap" height="300" width="150">
                </a>
                <div class="card-body card-img-overlay grid-overlay">
                    @if(count($influencer->socialLink) != 0)
                        <ul class="social-links d-flex justify-content-center flex-wrap">
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

                                if($social->social_platform == "instagram"){
                                    $style = "#fe0090";
                                }elseif($social->social_platform == "twitter"){
                                    $style = "#179cf0";
                                }elseif($social->social_platform == "facebook"){
                                    $style = "#1877f2";
                                }elseif($social->social_platform == "linkedin"){
                                    $style = "#0075b4";
                                }elseif($social->social_platform == "youtube"){
                                    $style = "#ff0103";
                                }
                            @endphp
                            <li><a href="https://{{ $social->url }}" data-bs-toggle="tooltip" data-placement="top" title="{{ __($social->followers) }}" target="_blank" style="color: {{$style}}">@php echo $social->social_icon; echo " ".$count @endphp</a></li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}" class="top-marign">
                    <p class="card-title text-white">{{ __($influencer->fullname) }} <br>
                        {{ __($influencer->address->city) }}</p>
                    </a>
                   
                </div>
            </div>
            <p class="text-black text-center">{{ __($influencer->about_me) }}</p>
        </div>
    @endif
@endif  
@empty
<div class="col-md-6 col-lg-8">
    <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '800x600') }}" alt="" class="w-100">
</div>
@endforelse
{{ $influencers->links() }}

<style>
.influencer-item .social-links i {
    font-size: 18px;
    color: #ffffff;
    text-align: center;
}

.influencer-item .social-links {
    padding: 5px 20px;
    background-color: #19875487;
    border-radius: 25px;
    gap: 8px;
}
.card.rounded.influencer-item {
    border: 0;
    height: 92%;
}
img.card-img-top.object-fit-cover {
    object-fit: cover;
    border-radius: 15px !important;
}

.grid-overlay {
    background-color: rgba(0,0,0,0.3);
    border-radius: 15px;
}

.top-marign{
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
}
.influencer-item + p.text-black {
    padding-left: 10px;
}
</style>

<script>
    try {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
    } catch (error) {

    }
</script>
