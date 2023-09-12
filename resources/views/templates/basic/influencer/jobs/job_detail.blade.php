@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        <div class="col-md-5">
            <div class="card custom--card">
                <div class="card-header">
                    @if($order->influencer_id != 0)
                    <h5 class="title">@lang('Influencer Information')</h5>
                    @elseif($order->freelancer_id != 0)
                    <h5 class="title">@lang('Freelancer Information')</h5>
                    @elseif($order->user_id != 0)
                    <h5 class="title">@lang('Client Information')</h5>
                    @endif
                </div>

                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Name')</span>
                            @if($order->influencer_id != 0)
                                <span>{{ __(@$order->influencer->fullname) }}</span>
                            @elseif($order->freelancer_id != 0)
                                <span>{{ __(@$order->freelancer->fullname) }}</span>
                            @elseif($order->user_id != 0)
                                <span>{{ __(@$order->user->fullname) }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Email')</span>
                            @if($order->influencer_id != 0)
                                <span>{{ __(@$order->influencer->email) }}</span>
                            @elseif($order->freelancer_id != 0)
                                <span>{{ __(@$order->freelancer->email) }}</span>
                            @elseif($order->user_id != 0)
                                <span>{{ __(@$order->user->email) }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Country')</span>
                            @if($order->influencer_id != 0)
                                <span>{{ __(@$order->influencer->address->country) }}</span>
                            @elseif($order->freelancer_id != 0)
                                <span>{{ __(@$order->freelancer->address->country) }}</span>
                            @elseif($order->user_id != 0)
                                <span>{{ __(@$order->user->address->country) }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Member Since')</span>
                            @if($order->influencer_id != 0)
                                <span>{{ showDateTime($order->influencer->created_at, 'd M, Y') }}</span>
                            @elseif($order->freelancer_id != 0)
                                <span>{{ showDateTime($order->freelancer->created_at, 'd M, Y') }}</span>
                            @elseif($order->user_id != 0)
                                <span>{{ showDateTime($order->user->created_at, 'd M, Y') }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Order Complted')</span>
                            @if($order->influencer_id != 0)
                                <span>{{ getAmount($order->influencer->completed_order) }}</span>
                            @elseif($order->freelancer_id != 0)
                                <span>{{ getAmount($order->freelancer->completed_order) }}</span>
                            @elseif($order->user_id != 0)
                                <span>{{ getAmount($order->user->completed_order) }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Visit Profile')</span>
                            @if($order->influencer_id != 0)
                                <span><a href="{{ route('influencer.profile', [slug($order->influencer->username), $order->influencer_id]) }}" class="text--base"><i class="las la-external-link-alt"></i> @lang('Link')</a></span>
                            @elseif($order->freelancer_id != 0)
                                <span><a href="{{ route('freelancer.profile', [slug($order->freelancer->username), $order->freelancer_id]) }}" class="text--base"><i class="las la-external-link-alt"></i> @lang('Link')</a></span>
                            @elseif($order->user_id != 0)
                                <span><a href="" class="text--base"><i class="las la-external-link-alt"></i> @lang('Link')</a></span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            @if ($order->status == 3)
            <div class="card custom--card mt-4">
                <div class="card-header">
                    <h5 class="title">@lang('Take Action')</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">

                        <button type="button" class="btn btn--sm btn--outline-dark reportBtn" data-id="{{ $order->id }}">
                            <i class="las la-gavel"></i> @lang('Report to Admin')
                        </button>
                        @if($order->influencer_id != 0)
                            <button type="button" class="btn btn--sm btn--outline-success confirmationBtn" data-action="{{ route('influencer.jobs.influencer.complete.status',$order->id) }}" data-question="@lang('Are you sure to complete this order?')" data-btn_class="btn btn--base btn--md">
                                <i class="las la-check-double"></i> @lang('Complete')
                            </button>
                        @elseif($order->freelancer_id != 0)
                            <button type="button" class="btn btn--sm btn--outline-success confirmationBtn" data-action="{{ route('influencer.jobs.freelancer.complete.status',$order->id) }}" data-question="@lang('Are you sure to complete this order?')" data-btn_class="btn btn--base btn--md">
                                <i class="las la-check-double"></i> @lang('Complete')
                            </button>
                        @elseif($order->user_id != 0)
                            <button type="button" class="btn btn--sm btn--outline-success confirmationBtn" data-action="{{ route('influencer.jobs.client.complete.status',$order->id) }}" data-question="@lang('Are you sure to complete this order?')" data-btn_class="btn btn--base btn--md">
                                <i class="las la-check-double"></i> @lang('Complete')
                            </button>    
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-7">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="title">@lang('Order Information')</h5>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Job')</span>
                            <span>{{ $order->job->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Delivery Date')</span>
                            <span>{{ $order->job->delivery_time }} Days</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Amount')</span>
                            <span>{{ showAmount($order->amount) }} {{ $general->cur_text }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Order No')</span>
                            <span>{{ $order->order_no }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Order Status')</span>
                            <span>
                                @if($order->status == 0)
                                <span class="fw-bold badge badge--secondary">Pending</span>
                                @elseif($order->status == 1)
                                <span class="fw-bold badge badge--success">Complete</span>
                                @elseif($order->status == 2)
                                <span class="fw-bold badge badge--primary">Inprogress</span>
                                @elseif($order->status == 3)
                                <span class="fw-bold badge badge--info">Jobdone</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Description')</span>
                            <button class="btn btn--sm btn--outline-base descriptionBtn" data-description="{{ $order->job->description }}">@lang('View')</button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Requirements')</span>
                            <button class="btn btn--sm btn--outline-base descriptionBtn" data-description="{{ $order->job->requirements }}">@lang('View')</button>
                        </li>
                        @if ($order->status == 4)
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold">@lang('Reason of Report')</span>
                            <button class="btn btn--sm btn--outline-dark reasonBtn" data-reason="{{ $order->reason }}">@lang('View')</button>
                        </li>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="reportModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Report to Admin')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group report-reason">
                            <label class="form-label">@lang('Reason')</label>
                            <textarea name="reason" class="form-control form--control">{{ old('reason') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark btn--md" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base btn--md">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="descriptionModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="description"></p>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>

@endsection
@push('script')
    <script>
        (function($) {
            "use strict";
            $('.descriptionBtn').on('click',function () {
                var modal = $("#descriptionModal");
                modal.find('.modal-title').text('Description');
                modal.find('.description').html($(this).data('description'));
                modal.modal('show');
            });
            $('.reasonBtn').on('click',function () {
                var modal = $("#descriptionModal");
                modal.find('.modal-title').text('Reason of Report')
                modal.find('.description').text($(this).data('reason'));
                modal.modal('show');
            });

            $('.reportBtn').on('click', function() {
                var modal = $('#reportModal');
                let id = $(this).data('id');
                modal.find('form').attr('action', `{{ route('user.order.report.status','') }}/${id}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
