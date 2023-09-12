@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="card custom--card">
                <div class="card-header">
                    <h4 class="card-title text-start">
                        @lang('Job'): {{ __($job->name) }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('job.order.apply', [$user_type, $job->id]) }}" method="POST">
                        @csrf
                        <div class="row gy-3">

                            {{-- <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Request Title') </label>
                                    <input type="text" name="title" class="form-control form--control" value="{{ old('title') }}" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Estimated Delivery Date') </label>
                                    <input type="text" class="datepicker-here form-control form--control" data-language='en' data-date-format="yyyy-mm-dd" data-position='bottom left' placeholder="@lang('Select Date')" name="delivery_date" autocomplete="off" required>
                                </div>
                            </div> --}}

                            @if($job->influencer_id != 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Posted By Influencer')</label>
                                    <input type="text" class="form-control form--control" value="{{$job->Influencer->username}}" disabled>
                                </div>
                            </div>
                            @elseif($job->freelancer_id != 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Posted By Freelancer')</label>
                                    <input type="text" class="form-control form--control" value="{{$job->freelancer->username}}" disabled>
                                </div>
                            </div>
                            @else
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Posted By Client')</label>
                                    <input type="text" class="form-control form--control" value="{{$job->user->username}}" disabled>
                                </div>
                            </div>
                            @endif
                           

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Job Price')</label>
                                    <div class="input-group">
                                        <input type="text" step="any" class="form-control form--control" name="price" value="{{ showAmount($job->price) }}">
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="form-label" for="description">@lang('Description')</label>
                                    <textarea rows="4" class="form-control form--control nicEdit" name="description" id="description" placeholder="@lang('Description')">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Apply Now')</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/datepicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        $('.datepicker-here').datepicker({
            changeYear: true,
            changeMonth: true,
            minDate: new Date(),
        });
    </script>
@endpush
