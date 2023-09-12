@extends('admin.layouts.app')
@section('panel')
<section class="influencer-section">
    <div class="container ">
        <div class="row gy-4 justify-content-center">
            <div class="row">
                <div class="col-md-4">
                    <strong id="clientCount">Total Clients - {{count($clients)}}</strong><br>
                    <span class="mt-5">Mail To</span>
                    <div class="overflow-auto" style="height: 80px">
                        @foreach($clients AS $client)
                            <span class="btn btn-primary btn-sm rounded-pill my-1">{{$client->email}}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <strong id="clientCount">Total Influencers - {{count($influencers)}}</strong><br>
                    <span class="mt-5">Mail To</span>
                    <div class="overflow-auto" style="height: 80px">
                        @foreach($influencers AS $influencer)
                            <span class="btn btn-primary btn-sm rounded-pill my-1">{{$influencer->email}}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <strong id="clientCount">Total Freelancers - {{count($freelancers)}}</strong><br>
                    <span class="mt-5">Mail To</span>
                    <div class="overflow-auto" style="height: 80px">
                        @foreach($freelancers AS $freelancer)
                            <span class="btn btn-primary btn-sm rounded-pill my-1">{{$freelancer->email}}</span>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('admin.news-letter.all-users.send-mail') }}" method="post">
                    @csrf
                    <div class="row mt-4">
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