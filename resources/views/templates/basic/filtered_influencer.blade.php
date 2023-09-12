@php
$favorite    = App\Models\Favorite::where('user_id', auth()->id())->select('influencer_id')->pluck('influencer_id');
$influencersId = json_decode($favorite);
$emptyMsgImage = getContent('empty_message.content', true);
$logged_influencer = authInfluencer();
@endphp
@if (request()->search)
<p>@lang('Search Result For') <span class="text--base">{{ __(request()->search) }}</span> : @lang('Total') <span class="text--base">{{ $influencers->count() }}</span> @lang('Influencers Found')</p>
@endif
@forelse ($influencers as $influencer)
    @if($logged_influencer != null)
        @if($influencer->id != $logged_influencer->id)
        <div class="col-md-6 col-lg-3 col-xl-3 col-sm-9 col-xs-10">
            <div class="card rounded influencer-item">
                <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}">
                    <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" alt="Card image cap" height="300" width="150">
                    <div class="card-body card-img-overlay top-50">
                        <h5 class="card-title text-white">{{ __($influencer->fullname) }}</h5>
                        <p class="text-white">{{ __($influencer->profession) }}</p>
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
                            @endphp
                            <li><a class="text-white" href="{{ $social->url }}" data-bs-toggle="tooltip" data-placement="top" title="{{ __($social->followers) }}" target="_blank">@php echo $social->social_icon; echo $count @endphp</a></li>
                            @endforeach
                        </ul>
                    </div>
                </a>
            </div>
        </div>
        @endif
    @else 
    <div class="col-md-6 col-lg-3 col-xl-3 col-sm-9 col-xs-10">
        
        <div class="card rounded influencer-item">
            <a href="{{ route('influencer.profile', [slug($influencer->username), $influencer->id]) }}">
                <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$influencer->username}}/photos/{{$influencer->image}}" alt="Card image cap" height="300" width="150">
                <div class="card-body card-img-overlay top-50">
                    <h5 class="card-title text-white">{{ __($influencer->fullname) }}</h5>
                    <p class="text-white">{{ __($influencer->profession) }}</p>
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
                        @endphp
                        <li><a class="text-white" href="https://{{ $social->url }}" data-bs-toggle="tooltip" data-placement="top" title="{{ __($social->followers) }}" target="_blank">@php echo $social->social_icon; echo $count @endphp</a></li>
                        @endforeach
                    </ul>
                </div>
            </a>
        </div>

    </div>
    @endif
@empty
<div class="col-md-6 col-lg-8">
    <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '800x600') }}" alt="" class="w-100">
</div>
@endforelse
{{ $influencers->links() }}

<script>
    try {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
    } catch (error) {

    }
</script>
