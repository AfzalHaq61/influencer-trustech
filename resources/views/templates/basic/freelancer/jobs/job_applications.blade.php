@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <table class="table--responsive--lg table">
            <thead>
                <tr>
                    <th>@lang('Job')</th>
                    <th>@lang('Category | SubCategory')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('No. of Applications')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                @if(count($job->jobApplications) != 0)
                <tr>
                    <td data-label="@lang('Title')">
                        {{ strLimit($job->name, 60) }}
                    </td>

                    <td data-label="@lang('Category | Subcategory')">
                        <div>
                            <span>{{strLimit(__(@$job->jobCategory->name), 20)}}</span><br>
                            <span>{{strLimit(__(@$job->JobSubCategory->name), 20)}}</span>
                        </div>
                    </td>

                    <td>
                        <div class=""><span>{{number_format($job->price, 2, '.', ',')}}</span></div>
                    </td>

                    <td>
                        <div class="fs-5 text-center">
                            <span class="badge badge--warning">{{count($job->jobApplications)}}</span>
                        </div>
                    </td>
                    <td data-label="@lang('Action')">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('freelancer.jobs.applicants', $job->id) }}" class="btn btn--sm btn--outline-base">
                                <i class="la la-eye"></i> @lang('View')
                            </a>
                        </div>
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