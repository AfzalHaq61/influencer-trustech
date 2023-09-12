@php
    $login = getContent('user_login.content', true);
    $influencerLogin = getContent('influencer_login.content', true);
    $freelancerLogin = getContent('freelancer_login.content', true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="account-section pt-80 pb-80">
        <div class="container">
            <div class="account-wrapper">
                <div class="row gy-5">
                    <div class="col-lg-6">
                        <div class="account-thumb-wrapper">
                            <img src="{{ getImage('assets/images/frontend/user_login/' . @$login->data_values->image, '660x450') }}" class="mw-100 h-100">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-content">
                            <div class="d-flex justify-content-between flex-wrap gap-3 pb-5">
                                <div class="account-content-left">
                                    <h3 class="this-page-title">{{ __(@$login->data_values->title) }}</h3>
                                </div>
                                <div class="account-content-right">
                                    <button type="button" id="clientBtn" class="btn btn--md btn--outline-base actionBtn active" data-type="client">@lang('Client')</button>
                                    <button type="button" id="influencerBtn" class="btn btn--md btn--outline-base actionBtn" data-type="influencer">@lang('Influencer')</button>
                                    <button type="button" id="freelancerBtn" class="btn btn--md btn--outline-base actionBtn" data-type="freelancer">@lang('Freelancer')</button>

                                </div>
                            </div>

                            <form method="POST" action="{{ route('user.login') }}" class="account-form verify-gcaptcha">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">@lang('Username or Email') </label>
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">@lang('Password') </label>
                                    <input type="password" name="password" id="password" class="form-control form--control" required autocomplete="off">
                                </div>

                                <x-captcha></x-captcha>

                                <div class="d-flex justify-content-between flex-wrap">
                                    <div class="form-group custom--checkbox">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">@lang('Remember Me')</label>
                                    </div>
                                    <a class="text--base forgot-url" href="{{ route('user.password.request') }}">@lang('Forgot Password?')</a>
                                </div>
                                <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Submit')</button>
                            </form>
                            <div class="text-center">
                                <p class="mt-4">@lang('Don\'t have an account?')
                                    <a href="{{ route('user.register') }}" class="text--base register-url">@lang('Create an account')</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            let action;
            let forgotUrl;
            let registerUrl;
            let pageTitle;
            let session = sessionStorage.getItem("userType");

            if(session =='client'){
                action = `{{ route('user.login') }}`;
                forgotUrl = `{{ route('user.password.request') }}`;
                registerUrl = `{{ route('user.register') }}`;
                pageTitle = `{{ __(@$login->data_values->title) }}`;

                $('form')[0].action = action;
                $('.forgot-url').attr('href', forgotUrl);
                $('.register-url').attr('href', registerUrl);
                $('.this-page-title').text(pageTitle);
                $('#clientBtn').addClass('active');
                $('#influencerBtn').removeClass('active');

            }else if(session =='influencer'){
                action = `{{ route('influencer.login') }}`;
                forgotUrl = `{{ route('influencer.password.request') }}`;
                registerUrl = `{{ route('influencer.register') }}`;
                pageTitle = `{{ __(@$influencerLogin->data_values->title) }}`;

                $('form')[0].action = action;
                $('.forgot-url').attr('href', forgotUrl);
                $('.register-url').attr('href', registerUrl);
                $('.this-page-title').text(pageTitle);
                $('#clientBtn').removeClass('active');
                $('#influencerBtn').addClass('active');

            }else if(session =='freelancer'){
                action = `{{ route('freelancer.login') }}`;
                forgotUrl = `{{ route('freelancer.password.request') }}`;
                registerUrl = `{{ route('freelancer.register') }}`;
                pageTitle = `{{ __(@$freelancerLogin->data_values->title) }}`;

                $('form')[0].action = action;
                $('.forgot-url').attr('href', forgotUrl);
                $('.register-url').attr('href', registerUrl);
                $('.this-page-title').text(pageTitle);
                $('#clientBtn').removeClass('active');
                $('#freelancerBtn').addClass('active');
            }


            $('.actionBtn').on('click', function() {

                if ($(this).data('type') == 'client') {
                    action = `{{ route('user.login') }}`;
                    forgotUrl = `{{ route('user.password.request') }}`;
                    registerUrl = `{{ route('user.register') }}`;
                    pageTitle = `{{ __(@$login->data_values->title) }}`;
                    sessionStorage.setItem("userType","client");
                    
                }else if($(this).data('type') == 'freelancer'){
                    action = `{{ route('freelancer.login') }}`;
                    forgotUrl = `{{ route('freelancer.password.request') }}`;
                    registerUrl = `{{ route('freelancer.register') }}`;
                    pageTitle = `{{ __(@$freelancerLogin->data_values->title) }}`;
                    sessionStorage.setItem("userType","freelancer");
                    
                } else {
                    action = `{{ route('influencer.login') }}`;
                    forgotUrl = `{{ route('influencer.password.request') }}`;
                    registerUrl = `{{ route('influencer.register') }}`;
                    pageTitle = `{{ __(@$influencerLogin->data_values->title) }}`;
                    sessionStorage.setItem("userType","influencer");

                }
                $('form')[0].action = action;
                $('.forgot-url').attr('href', forgotUrl);
                $('.register-url').attr('href', registerUrl);
                $('.this-page-title').text(pageTitle);
                $(this).addClass('active');
                $('.actionBtn').not($(this)).removeClass('active');
            });

        })(jQuery);
    </script>
@endpush
