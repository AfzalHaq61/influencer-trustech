@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="d-flex justify-content-between align-items-center position-relative mb-4 flex-wrap gap-4">

    {{-- <div class="d-flex align-items-start flex-wrap gap-2">
        <a class="btn btn--outline-custom {{ menuActive('influencer.jobs.all') }}" aria-current="page" href="{{ route('influencer.jobs.all') }}">@lang('All')</a>
        <a class="btn btn--outline-custom {{ menuActive('influencer.jobs.pending') }}" href="{{ route('influencer.jobs.pending') }}">@lang('Pending')</a>
        <a class="btn btn--outline-custom {{ menuActive('influencer.jobs.approved') }}" href="{{ route('influencer.jobs.approved') }}">@lang('Approved')</a>
        <a class="btn btn--outline-custom {{ menuActive('influencer.jobs.cancelled') }}" href="{{ route('influencer.jobs.cancelled') }}">@lang('Cancelled')</a>
        <a class="btn btn--outline-custom {{ menuActive('influencer.jobs.closed') }}" href="{{ route('influencer.jobs.closed') }}">@lang('Closed')</a>
    </div> --}}
    {{-- <form action="" class="service-search-form flex-fill">
        <div class="input-group">
            <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Title / Category')">
            <button class="input-group-text bg--base border-0 px-4 text-white"><i class="las la-search"></i></button>
        </div>
    </form> --}}
</div>

<div class="row">
    <div class="col-lg-12">
        <table class="table--responsive--lg table">
            <thead>
                <tr>
                    <th>@lang('Job')</th>
                    <th>@lang('Category | SubCategory')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td data-label="@lang('Title')">
                        {{ strLimit($job->job->name, 60) }}
                    </td>

                    <td data-label="@lang('Category | Subcategory')">
                        <div>
                            <span>{{strLimit(__(@$job->job->jobCategory->name), 20)}}</span><br>
                            <span>{{strLimit(__(@$job->job->JobSubCategory->name), 20)}}</span>
                        </div>
                    </td>

                    <td>
                        <div class=""><span>{{number_format($job->job->price, 2, '.', ',')}}</span></div>
                    </td>

                    <td data-label="@lang('Status')">
                        <div class="">
                            @if($job->status == 0)
                            <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($job->status == 1)
                            <span class="badge badge--success">@lang('Completed')</span>
                            @elseif($job->status == 2)
                            <span class="badge badge--primary">@lang('Inprogress')</span>
                            @elseif($job->status == 3)
                            <span class="badge badge--info">@lang('JobDone')</span>
                            @elseif($job->status == 4)
                            <span class="badge badge--danger">@lang('Closed')</span>
                            @elseif($job->status == 5)
                            <span class="badge badge--secondary">@lang('Rejected')</span>
                            @endif
                        </div>
                    </td>

                    <td data-label="@lang('Action')">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('freelancer.jobs.order.detail', $job->id) }}" class="btn btn--sm btn--outline-base">
                                <i class="la la-edit"></i> @lang('Details')
                            </a>
                        </div>
                    </td>
                </tr>
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
        {{ $jobs->links() }}
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