@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($skills as $skill)
                                    <tr>
                                        <td>{{ $loop->index+$skills->firstItem() }}</td>
                                        <td>{{ __($skill->name) }}</td>
                                        <td> @php echo $skill->statusBadge @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editButton" data-name="{{ $skill->name }}" data-id="{{ $skill->id }}">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($skill->status == 0)
                                                    <button class="btn btn-sm btn-outline--success ms-1 statusBtn" data-action="{{ route('admin.jobskill.status',$skill->id) }}" data-question="@lang('Are you sure to enable this Skill?')" data-status="1" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger statusBtn" data-action="{{ route('admin.jobskill.status',$skill->id) }}" data-question="@lang('Are you sure to disable this Skill?')" data-status="0" type="button">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($skills->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($skills) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel"></h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save" value="add">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- Status MODAL --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action=" " method="POST">
                @csrf
                <input type="hidden" name="status">
                <div class="modal-body">
                    <p class="modal-detail"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-colum flex-wrap gap-2 justify-content-end align-items-center">
    <button class="btn btn-lg btn-outline--primary createButton"><i class="las la-plus"></i>@lang('Add New')</button>
    <form action="" method="GET" class="form-inline float-sm-end">
        <div class="input-group justify-content-end">
            <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Name')" value="{{ request()->search }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>
</div>
@endpush

@push('script')
<script>
    (function($) {
            "use strict"

            let modal = $('#skillModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Skill')`);
                modal.find('.status').addClass('d-none');
                modal.find('form').attr('action', `{{ route('admin.jobskill.store','') }}`);
                modal.modal('show');
            });

            $('.editButton').on('click', function() {
                modal.find('form').attr('action', `{{ route('admin.jobskill.store','') }}/${$(this).data('id')}`);
                modal.find('.modal-title').text(`@lang('Update Skill')`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('.status').removeClass('d-none');

                if ($(this).data('status') == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
                modal.modal('show')
            });

            $('.statusBtn').on('click', function() {
                var modal = $('#statusModal');
                var status = $(this).data('status')
                var action = $(this).data('action')
                modal.find('form').attr('action', action);
                modal.find('[name=status]').val(status);
                if (status == 1) {
                    $('.modal-detail').text(`@lang('Are you sure to enable this Skill?')`)
                    $('.admin-feedback').addClass('d-none')
                } else {
                    $('.modal-detail').text(`@lang('Are you sure to disable this Skill?')`)
                    $('.admin-feedback').removeClass('d-none')
                }
                modal.modal('show');
            });

            var defautlImage = `{{ getImage(getFilePath('category'), getFileSize('category')) }}`;

            modal.on('hidden.bs.modal', function () {
                modal.find('.profilePicPreview').attr('style', `background-image: url(${defautlImage})`);
                $('#skillModal form')[0].reset();
            });

        })(jQuery);
</script>
@endpush
