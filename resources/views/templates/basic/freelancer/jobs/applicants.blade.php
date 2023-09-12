@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <table class="table--responsive--lg table">
            <thead>
                <tr>
                    <th>@lang('Applicants Name')</th>
                    <th>@lang('Username')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($job_applications as $applicant)
                    @if($applicant->user_id != 0)
                    <tr>
                        <td data-label="@lang('Title')">
                            {{ strLimit($applicant->appliedUser->firstname.' '.$applicant->appliedUser->lastname, 60) }}
                        </td>

                        <td data-label="@lang('Title')">
                            {{ strLimit($applicant->appliedUser->username, 60) }}
                        </td>

                        <td>
                            <div class="">
                                <span class="badge badge--warning">Client</span>
                            </div>
                        </td>
                        <td data-label="@lang('Action')">
                            @if($applicant->status == 1)
                            <div class="">
                                <span class="badge badge--success">Assigned</span>
                            </div>
                            @else
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <a href="{{ route('freelancer.jobs.assign.job', ['user', $applicant->user_id, $applicant->id]) }}" class="btn btn--sm btn--outline-base @if ($applicant->status == 2) disabled @endif">
                                    <i class="la la-check"></i> @lang('Approve')
                                </a>
                                <a href=" " class="btn btn--sm btn--outline-danger @if ($applicant->status == 2) disabled @endif">
                                    <i class="las la-times"></i> @lang('Reject')
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @elseif($applicant->influencer_id != 0)
                    <tr>
                        <td>
                            {{ strLimit($applicant->appliedInfluencer->firstname.' '.$applicant->appliedInfluencer->lastname, 60) }}
                        </td>
                        <td>
                            {{ strLimit($applicant->appliedInfluencer->username, 60) }}
                        </td>
                        <td>
                            <div class="">
                                <span class="badge badge--success">Influencer</span>
                            </div>
                        </td>
                        <td data-label="@lang('Action')">
                            @if($applicant->status == 1)
                            <div class="">
                                <span class="badge badge--success">Assigned</span>
                            </div>
                            @else
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <a href="{{ route('freelancer.jobs.assign.job', ['influencer', $applicant->influencer_id, $applicant->id]) }}" class="btn btn--sm btn--outline-base @if($applicant->status == 2) disabled @endif">
                                    <i class="la la-check"></i> @lang('Approve')
                                </a>
                                <a href=" " class="btn btn--sm btn--outline-danger @if ($applicant->status == 2) disabled @endif">
                                    <i class="las la-times"></i> @lang('Reject')
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @elseif($applicant->freelancer_id != 0)
                    <tr>
                        <td>
                            {{ strLimit($applicant->appliedFreelancer->firstname.' '.$applicant->appliedFreelancer->lastname, 60) }}
                        </td>
                        <td data-label="@lang('Title')">
                            {{ strLimit($applicant->appliedFreelancer->username, 60) }}
                        </td>
                        <td>
                            <div class="">
                                <span class="badge badge--info">Freelancer</span>
                            </div>
                        </td>
                        <td data-label="@lang('Action')">
                            @if($applicant->status == 1)
                            <div class="">
                                <span class="badge badge--success">Assigned</span>
                            </div>
                            @else
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <a href="{{ route('freelancer.jobs.assign.job', ['freelancer', $applicant->freelancer_id, $applicant->id]) }}" class="btn btn--sm btn--outline-base @if ($applicant->status == 2) disabled @endif">
                                    <i class="la la-check"></i> @lang('Approve')
                                </a>
                                <a href=" " class="btn btn--sm btn--outline-danger @if ($applicant->status == 2) disabled @endif">
                                    <i class="las la-times"></i> @lang('Reject')
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td class="justify-content-center text-center" colspan="100%">
                        <i class="la la-4x la-frown"></i>
                        <br>
                        {{ __($emptyMessage) }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $job_applications->links() }}
    </div>
</div>
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reason of Rejection')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <p class="modal-detail"></p>
            </div>
        </div>
    </div>
</div>
<x-confirmation-modal></x-confirmation-modal>
@endsection

@push('style')
<style>
    .nav-link {
        color: rgb(var(--base));
    }

    .nav-tabs .nav-link:focus,
    .nav-tabs .nav-link:hover {
        border-color: rgb(var(--base)) rgb(var(--base)) rgb(var(--base));
        color: rgb(var(--base));
        isolation: isolate;
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                modal.find('.modal-detail').text($(this).data('admin_feedback'));
                modal.modal('show');
            });

        })(jQuery);
</script>
@endpush