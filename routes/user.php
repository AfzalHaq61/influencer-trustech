<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'sendResetCodeEmail')->name('password.email');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });
    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');
                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            //Influencer Conversation
            Route::controller('ConversationController')->prefix('conversation')->name('conversation.')->group(function () {
                Route::middleware('client_kyc')->group(function () {
                    Route::get('influencer/contact/{id}/{jobid?}', 'create')->name('influencer.create');
                    Route::get('influencers', 'index')->name('index');
                    Route::post('store/{id}', 'store')->name('store');
                    Route::get('view/{id}', 'view')->name('view');
                    Route::get('message', 'message')->name('message');
                });
            });

            //Freelancer Conversation
            Route::controller('FreelancerConversationController')->prefix('conversation/freelancer')->name('conversation.freelancer.')->group(function () {
                Route::middleware('client_kyc')->group(function () {
                    Route::get('contact/{id}/{jobid?}', 'create')->name('create');
                    Route::get('influencers', 'index')->name('index');
                    Route::post('store/{id}', 'store')->name('store');
                    Route::get('view/{id}', 'view')->name('view');
                    Route::get('message', 'message')->name('message');
                });
            });

            //Jobs
            Route::controller('JobController')->name('jobs.')->group(function () {

                Route::middleware('client_kyc')->group(function () {
                    Route::get('jobs/create/', 'jobCreate')->name('create');
                    Route::post('jobs/insert/{id?}', 'jobInsert')->name('insert');
                    Route::get('jobs/detail/{id}', 'jobDetail')->name('detail');
                    
                    Route::get('jobs/all', 'allJobs')->name('all');
                    Route::get('jobs/edit/{job}', 'editJob')->name('edit');
                    Route::get('jobs/pending', 'pendingJobs')->name('pending');
                    Route::get('jobs/approved', 'approvedJobs')->name('approved');
                    Route::get('jobs/assigned', 'assignedJobs')->name('assigned');
                    Route::get('jobs/cancelled', 'cancelledJobs')->name('cancelled');
                    Route::get('jobs/closed', 'closedJobs')->name('closed');

                    Route::get('jobs/applications', 'jobApplications')->name('applications');
                    Route::get('jobs/assign-to-you', 'jobAssignToYou')->name('assign-to-you');
                    Route::get('jobs/order/detail/{order_id}', 'jobOrderDetail')->name('order.detail');
                    Route::get('jobs/applicants/{id}', 'jobApplicants')->name('applicants');
                    Route::get('jobs/assign/{user_type}/{user_id}/{application_id}', 'assignJob')->name('assign.job');
                    Route::post('jobs/accept/status/{id}', 'acceptStatus')->name('order.accept.status');
                    Route::post('jobs/jobDone/status/{id}', 'jobDoneStatus')->name('order.jobDone.status');
                    Route::post('jobs/cancel/status/{id}', 'cancelStatus')->name('order.cancel.status');

                    Route::post('jobs/complete/status/{id}', 'completeStatusInfluencer')->name('complete.status');
                    Route::post('jobs/freelancer/complete/status/{id}', 'completeStatusFreelancer')->name('freelancer.complete.status');
                });
            });

            //Hirings
            Route::controller('HiringController')->name('hiring.')->group(function () {
                Route::get('hiring/all', 'all')->name('history');

                Route::middleware('client_kyc')->group(function () {
                    Route::get('influencer/hiring/{name}/{id}', 'hiring')->name('request');
                    Route::post('influencer/hiring/{influencer_id?}/{service_id?}', 'hiringInfluencer')->name('influencer');

                    Route::get('/hiring/detail/{id}', 'detail')->name('detail');
                    Route::post('complete/status/{id}', 'completeStatus')->name('complete.status');
                    Route::post('report/status/{id}', 'reportStatus')->name('report.status');

                    Route::get('hiring/conversation/{id}', 'conversation')->name('conversation.view');
                    Route::post('hiring/conversation/store/{id}', 'conversationStore')->name('conversation.store');
                    Route::get('/hiring/message', 'conversationMessage')->name('conversation.message');
                });
            });

            Route::controller('OrderController')->name('order.')->prefix('order')->group(function () {
                Route::get('influencer-all', 'all')->name('all');

                Route::middleware('client_kyc')->group(function () {
                    Route::get('service/{id}', 'order')->name('form');
                    Route::get('create-offer-influencer/{influencer}', 'createOffer')->name('create-offer');
                    Route::post('influencer/hiring/{influencer_id}/{service_id}', 'orderConfirm')->name('confirm');

                    Route::get('/detail/{id}', 'detail')->name('detail');
                    Route::post('complete/status/{id}', 'completeStatus')->name('complete.status');
                    Route::post('report/status/{id}', 'reportStatus')->name('report.status');

                    Route::get('chat/{id}', 'conversation')->name('conversation.view');
                    Route::post('chat/store{id}', 'conversationStore')->name('conversation.store');
                    Route::get('/message', 'conversationMessage')->name('conversation.message');
                });
            });

           // Freelancer Orders
            Route::controller('FreelancerOrderController')->name('order.')->prefix('order')->group(function () {
                Route::get('freelancer-all', 'all')->name('freelancer.all');

                Route::middleware('client_kyc')->group(function () {
                    Route::get('freelancer-service/{id}', 'order')->name('form');
                    Route::get('create-offer-freelancer/{freelancer}', 'createOffer')->name('freelancer.create-offer');
                    Route::post('freelancer/hiring/{freelancer_id}/{service_id}', 'orderConfirm')->name('freelancer.confirm');

                    Route::get('freelancer/detail/{id}', 'detail')->name('freelancer.detail');
                    Route::post('freelancer/complete/status/{id}', 'completeStatus')->name('freelancer.complete.status');
                    Route::post('report/status/{id}', 'reportStatus')->name('report.status');

                    Route::get('freelancer/chat/{id}', 'conversation')->name('freelancer.conversation.view');
                    Route::post('chat/store{id}', 'conversationStore')->name('conversation.store');
                    Route::get('/message', 'conversationMessage')->name('conversation.message');
                });
            });

            Route::controller('ReviewController')->prefix('review')->name('review.')->group(function () {
                Route::middleware('client_kyc')->group(function () {
                    Route::get('order/index', 'orderReviews')->name('order.index');
                    Route::get('hiring/index', 'hiringReviews')->name('hiring.index');
                    Route::get('influencer/{id}', 'reviewInfluencer')->name('influencer');
                    Route::get('service/{id}', 'reviewService')->name('service');
                    Route::post('service/add/{id}', 'addServiceReview')->name('service.add');
                    Route::post('influencer/add/{id}', 'addHiringReview')->name('influencer.add');
                    Route::post('remove/service/{id}', 'removeServiceReview')->name('remove.service');
                    Route::post('remove/influencer/{id}', 'removeInfluencerReview')->name('remove.influencer');
                });
            });

            Route::controller('FavoriteController')->prefix('favorite')->name('favorite.')->group(function () {
                Route::get('list', 'favoriteList')->name('list');
                Route::get('influencer', 'favoriteInfluencer')->name('influencer');

                Route::middleware('client_kyc')->group(function () {
                    Route::post('add', 'addFavorite')->name('add');
                    Route::post('delete', 'delete')->name('delete');
                    Route::post('remove/{id}', 'remove')->name('remove');
                });
            });
        });
        // Payment for Influencer
        Route::middleware('registration.complete')->controller('Gateway\PaymentController')->group(function () {
            Route::any('payment', 'payment')->name('payment');
            Route::any('deposit', 'deposit')->name('deposit');
            Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
            Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
            Route::get('freelancer-deposit/manual', 'manualDepositConfirm')->name('freelancer.deposit.manual.confirm');
            Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
        });

         // Payment for Freelancer
         Route::middleware('registration.complete')->controller('Gateway\FreelancerPaymentController')->group(function () {
            Route::any('freelancer-payment', 'payment')->name('freelancer.payment');
            Route::any('deposit', 'deposit')->name('deposit');
            Route::post('freelancer-deposit/insert', 'depositInsert')->name('freelancer.deposit.insert');
            Route::get('freelancer-deposit/confirm', 'depositConfirm')->name('freelancer.deposit.confirm');
            Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
        });
    });
});
