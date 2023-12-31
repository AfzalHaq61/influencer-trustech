@php
    $breadcrumb       = getContent('breadcrumb.content',true);
    $showBreadcrumb   = $showBreadcrumb??true;
    $routes           = ['user.register', 'influencer.register', 'user.login', 'influencer.login', 'password.request'];
    $currentRouteName = request()->route()->getName();
    $currentUrl = url()->current();
@endphp

@if(!in_array($currentRouteName, $routes))
    @if(strpos($currentUrl, 'influencer/profile') != true && strpos($currentUrl, 'influencer/create-offer') != true && strpos($currentUrl, 'freelancer/profile') != true)
        <section class="inner-banner bg_img" style="background: url('{{ getImage('assets/images/frontend/breadcrumb/'.@$breadcrumb->data_values->image,'1920x250') }}') center;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-xl-6 text-center">
                        <h3 class="title text-white">{{ __($pageTitle) }}</h3>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif
