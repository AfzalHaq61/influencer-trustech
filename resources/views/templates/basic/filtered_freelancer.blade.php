@php
$favorite    = App\Models\Favorite::where('user_id', auth()->id())->select('freelancer_id')->pluck('freelancer_id');
$freelancersId = json_decode($favorite);
$emptyMsgImage = getContent('empty_message.content', true);
$logged_freelancer = authFreelancer();
@endphp
@if (request()->search)
<p>@lang('Search Result For') <span class="text--base">{{ __(request()->search) }}</span> : @lang('Total') <span class="text--base">{{ $freelancer->count() }}</span> @lang('Freelancer Found')</p>
@endif
@forelse ($freelancers as $freelancer)
@if($thisFreelancerId != $freelancer->id)
    @if($logged_freelancer != null)
        @if($freelancer->id != $logged_freelancer->id)
        <div class="col-md-6 col-lg-3 col-xl-4 col-sm-9 col-xs-10">
            <div class="card rounded influencer-item">
                <a href="{{ route('freelancer.profile', [slug($freelancer->username), $freelancer->id]) }}">
                    <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$freelancer->username}}/photos/{{$freelancer->image}}" alt="Card image cap" height="300" width="150">
                </a>
                <div class="card-body card-img-overlay grid-overlay">
                    <a href="{{ route('freelancer.profile', [slug($freelancer->username), $freelancer->id]) }}" class="top-marign">
                    <p class="card-title text-white">{{ __($freelancer->fullname) }} <br>
                        {{ __($freelancer->address->city) }}</p>
                    </a>
                   
                </div>
            </div>
            <p class="text-black">{{ __($freelancer->about_me) }}</p>
        </div>
        @endif
    @else 
    <div class="col-md-6 col-lg-3 col-xl-4 col-sm-9 col-xs-10">
        <div class="card rounded influencer-item">
            <a href="{{ route('freelancer.profile', [slug($freelancer->username), $freelancer->id]) }}">
                <img class="card-img-top object-fit-cover" src="{{ url('/') }}/core/public/sample/{{$freelancer->username}}/photos/{{$freelancer->image}}" alt="Card image cap" height="300" width="150">
            </a>
            <div class="card-body card-img-overlay grid-overlay">
                <a href="{{ route('freelancer.profile', [slug($freelancer->username), $freelancer->id]) }}" class="top-marign">
                <p class="card-title text-white">{{ __($freelancer->fullname) }} <br>
                    {{ __($freelancer->address->city) }}</p>
                </a>
               
            </div>
        </div>
        <p class="text-black">{{ __($freelancer->about_me) }}</p>
    </div>
    @endif
@endif   
@empty
<div class="col-md-6 col-lg-8">
    <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '800x600') }}" alt="" class="w-100">
</div>
@endforelse
{{ $freelancers->links() }}

<style>
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
        margin-top: 80%;
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
