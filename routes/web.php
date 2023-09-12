<?php

use App\Lib\Router;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->group(function () {
    Route::get('/', 'supportTicket')->name('ticket');
    Route::get('/new', 'openSupportTicket')->name('ticket.open');
    Route::post('/create', 'storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'replyTicket')->name('ticket.reply');
    Route::post('/close/{ticket}', 'closeTicket')->name('ticket.close');
    Route::get('/download/{ticket}', 'ticketDownload')->name('ticket.download');
});


Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');


Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/login', 'login')->name('login');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('/services', 'services')->name('services');
    Route::get('service/tags/{id}/{name}', 'serviceByTag')->name('service.tag');

    // Jobs
    Route::get('jobs', 'jobs')->name('jobs');
    Route::get('job/details/{slug}/{id}', 'jobDetails')->name('job.details');
    Route::get('job/filter', 'filterJob')->name('job.filter');

    // Job Orders
Route::controller('JobOrderController')->name('job.order.')->group(function () {
    Route::get('job/order/custom/{slug}/{id}', 'customJobOrder')->name('custom');
    Route::post('job/order/apply/{slug}/{id}', 'applyJobOrder')->name('apply');
  
});
    


    // Products By Category-Subcategory
    Route::get('category/{slug}/{id}', 'jobsByCategory')->name('by.category');
    Route::get('subcategory/{slug}/{id}', 'jobsBySubcategory')->name('by.subcategory');

    // User Profile
    Route::get('user/{username}', 'publicProfile')->name('public.profile');

    Route::get('service/filtered', 'filterService')->name('service.filter');
    Route::get('service/{slug}/{id}/{order_id?}', 'serviceDetails')->name('service.details');

    Route::get('influencers', 'influencers')->name('influencers');
    Route::get('influencers/category/{id}/{name}', 'influencerByCategory')->name('influencer.category');
    Route::get('influencer/filtered', 'filterInfluencer')->name('influencer.filter');
    Route::get('/influencer/profile/{name}/{id}', 'influencerProfile')->name('influencer.profile');


    Route::get('freelancers', 'freelancer')->name('freelancers');
    Route::get('freelancer/filtered', 'filterFreelancer')->name('freelancer.filter');
    Route::get('/freelancer/profile/{name}/{id}', 'freelancerProfile')->name('freelancer.profile');


    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('attachment/download/{attachment}/{conversation_id}/{type?}', 'attachmentDownload')->name('attachment.download');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
