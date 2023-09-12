@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong> <i class="la la-info-circle"></i> @lang('You need to complete your profile to get access to your dashboard')</strong>
                        </div>

                        <form method="POST" action="{{ route('freelancer.data.submit') }}" class="form row gy-2" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="lastname" class="col-form-label">@lang('Age')</label>
                                <input type="number" class="form-control form--control" id="age" name="age" value="{{ old('age') }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-form-label">@lang('Sex')</label>
                                <select class="form-control form--control" name="sex">
                                    <option>select</option>
                                    <option value="male"  >Male</option>
                                    <option value="female">Female</option>
                                    <option value="lgbtq+">LGBTQ+</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Address')</label>
                                <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('State')</label>
                                <input type="text" class="form-control form--control" name="state" value="{{ old('sate') }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Zip Code')</label>
                                <input type="text" class="form-control form--control" name="zip" value="{{ old('zip') }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('City')</label>
                                <select name="city_id" id="city_id" class="form-select form--control" required>
                                    <option>Select</option>
                                    @foreach ($cities as $key => $city)
                                        <option data-city="{{ $city->name }}" value="{{ $city->id }}">{{ __($city->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="cityName" name="city" value="">

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Avatar Image')</label>
                                <input type="file" class="form-control form--control" name="image" value="">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Bio')</label>
                                <input type="text" class="form-control form--control" name="about_me" value="{{ old('about_me') }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Sample Photos')</label>
                                <input type="file" class="form-control form--control" name="sample_photos[]" value="" multiple>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Sample Videos')</label>
                                <input type="file" class="form-control form--control" name="sample_videos[]" value="">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-form-label">@lang('Target Demography From')</label>
                                <select class="form-control form--control" name="target_demographic_from">
                                    <option>select</option>
                                    @for($age=5; $age<=100; $age+=5)
                                        <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-form-label">@lang('Target Demography To')</label>
                                <select class="form-control form--control" name="target_demographic_to">
                                    <option>select</option>
                                    @for($age=5; $age<=100; $age+=5)
                                        <option value="{{$age}}">{{$age}} &nbsp;Year</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="form-label">@lang('Past Companies/Brands you have worked with')</label>
                                <input type="text" class="form-control form--control" name="past_companies_brands" value="{{ old('past_companies_brands') }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Available For Events')</label>
                                <select class="form-control form--control" name="available_for_events">
                                    <option>select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Available For Travelling')</label>
                                <select class="form-control form--control" name="available_for_travelling">
                                    <option>select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#city_id').on('change', function(){
        let city_name = $(this).find('option:selected').data('city');
        $('input[name=city]').val(city_name);
    });
</script>
@endpush