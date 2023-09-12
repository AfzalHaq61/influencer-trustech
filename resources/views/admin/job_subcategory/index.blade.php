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
                                    <th>@lang('Image')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subcategories as $subcategory)
                                    <tr>
                                        <td>{{ $loop->index+$subcategories->firstItem() }}</td>
                                        <td>{{ __($subcategory->name) }}</td>
                                        <td>
                                            <div class="user justify-content-center">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('category') . '/' . $subcategory->image, getFileSize('category')) }}" alt="@lang('image')">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ __(@$subcategory->jobCategory->name) }}</td>
                                        <td>
                                            @php echo $subcategory->statusBadge @endphp
                                        </td>
                                        <td>
                                            @php
                                                $subcategory->image_with_path = getImage(getFilePath('category') . '/' . $subcategory->image, getFileSize('category'));
                                            @endphp
                                            <button type="button" class="btn btn-sm btn-outline--primary editButton" data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}" data-category_id="{{ $subcategory->job_category_id }}" data-image="{{ $subcategory->image }}">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </button>
                                            @if ($subcategory->status == 0)
                                            <button class="btn btn-sm btn-outline--success ms-1 statusBtn" data-action="{{ route('admin.jobsubcategory.status',$subcategory->id) }}" data-question="@lang('Are you sure to enable this subcategory?')" data-status="1" type="button">
                                                <i class="la la-eye"></i> @lang('Enable')
                                            </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger statusBtn" data-action="{{ route('admin.jobsubcategory.status',$subcategory->id) }}" data-question="@lang('Are you sure to disable this subcategory?')" data-status="0" type="button">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
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
                @if ($subcategories->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($subcategories) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
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
                        <div class="form-group">
                            <label>@lang('Category')</label>
                            <select class="form-control" name="category_id">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{ __($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row form-group">
                            <label>@lang('Image')<span class="text--danger">*</span></label>
                            <div class="col-sm-12">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="background-image: url({{ getImage('/',getFileSize('category'))}})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                            <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                                            <small class="mt-2 text-facebook">@lang('Supported files'):
                                                <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b>
                                                @lang('Image will be resized into '){{ getFileSize('category') }} @lang('px')
                                            </small>
                                        </div>
                                    </div>
                                </div>
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

            let modal = $('#categoryModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Subcategory')`);
                modal.find('.status').addClass('d-none');
                modal.find('form').attr('action', `{{ route('admin.jobsubcategory.store','') }}`);
                modal.modal('show');
            });

            $('.editButton').on('click', function() {
                modal.find('form').attr('action', `{{ route('admin.jobsubcategory.store','') }}/${$(this).data('id')}`);
                modal.find('.modal-title').text(`@lang('Update Sub Category')`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('[name=category_id]').val($(this).data('category_id'));
                modal.find('.profilePicPreview').attr('style', `background-image: url(${$(this).data('image')})`);

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
                    $('.modal-detail').text(`@lang('Are you sure to enable this category?')`)
                    $('.admin-feedback').addClass('d-none')
                } else {
                    $('.modal-detail').text(`@lang('Are you sure to disable this category?')`)
                    $('.admin-feedback').removeClass('d-none')
                }
                modal.modal('show');
            });

            var defautlImage = `{{ getImage(getFilePath('category'), getFileSize('category')) }}`;

            modal.on('hidden.bs.modal', function () {
                modal.find('.profilePicPreview').attr('style', `background-image: url(${defautlImage})`);
                $('#categoryModal form')[0].reset();
            });

        })(jQuery);
</script>
@endpush
