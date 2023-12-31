@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{ $general->site_name }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> @lang('Timezone')</label>
                                    <select class="select2-basic" name="timezone">
                                        @foreach ($timezones as $timezone)
                                            <option value="'{{ @$timezone }}'">{{ __($timezone) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{ $general->cur_text }}">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>@lang('Currency Symbol')</label>
                                    <input class="form-control" type="text" name="cur_sym" required value="{{ $general->cur_sym }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('SMTP HOST')</label>
                                    <input class="form-control" type="text" name="smtp_host" value="{{$general->smtp_host}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('SMTP USERNAME')</label>
                                    <input class="form-control" type="text" name="smtp_username" value="{{$general->smtp_username}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('SMTP PASSWORD')</label>
                                    <input class="form-control" type="password" name="smtp_password" value="{{$general->smtp_password}}">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>@lang('Client Commission')</label>
                                    <input class="form-control" type="text" name="client_commission" value="{{$general->client_commission}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>@lang('Influencer Commission')</label>
                                    <input class="form-control" type="text" name="influencer_commission" value="{{$general->influencer_commission}}">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>@lang('Freelancer Commission')</label>
                                    <input class="form-control" type="text" name="freelancer_commission" value="{{$general->freelancer_commission }}">
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label> @lang('Site Base Color')</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 p-0">
                                            <input type='text' class="form-control colorPicker" value="{{ $general->base_color }}" />
                                        </span>
                                        <input type="text" class="form-control colorCode" name="base_color" value="{{ $general->base_color }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2({
                dropdownParent: $('.card-body')
            });

        })(jQuery);
    </script>
@endpush
