@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-body">
            <form action="{{ route('influencer.jobs.insert','') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <label class="form-label" for="title">@lang('Image')<span class="text--danger">*</span></label>
                        <div class="profile-thumb p-0 text-center shadow-none">
                            <div class="thumb">
                                <img id="upload-img" src="{{ getImage(getFilePath('service') . '/' . @$job->image, getFileSize('service')) }}" alt="userProfile">
                                <label class="badge badge--icon badge--fill-base update-thumb-icon" for="update-photo"><i class="las la-pen"></i></label>
                            </div>
                            <div class="profile__info">
                                <input type="file" name="image" class="form-control d-none" id="update-photo">
                            </div>
                        </div>
                        <small class="text--warning">@lang('Supported files'): @lang('jpeg'), @lang('jpg'), @lang('png'). @lang('| Will be resized to'): {{ getFileSize('service') }}@lang('px').</small>
                    </div>
                    @php
                        if (@$job) {
                            $categoryId = $job->category_id;
                        } else {
                            $categoryId = old('category_id');
                        }
                    @endphp
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="form-label" for="name">@lang('Name')</label>
                            <input type="text" class="form-control form--control" name="name" id="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="category_id">@lang('Category')</label>
                            <select class="form-select form--control" name="category_id" id="category" required>
                                <option value="" selected disabled>@lang('Select category')</option>
                                @foreach ($jobCategories as $category)
                                    <option value="{{$category->id}}" @selected(@$category->id == old('category_id')) data-subcategories="{{@$category->JobSubcategories}}">{{__($category->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sub_category_id">@lang('Subcategory')</label>
                            <select class="form-select form--control subcategory" name="sub_category_id" id="sub_category_id">
                              
                            </select>
                        </div>
                        <div class="form-group skill-body">
                            <label for="skill" class="form-label">@lang('Skills')</label>
                            <select class="select2-auto-tokenize form-control form--control" multiple="multiple" name="skill[]">
                                @foreach (@$jobSkills as $skill)
                                    <option value="{{ @$skill->id }}">{{ __(@$skill->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="price">@lang('Budget')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control form--control" name="price" id="price" value="{{ getAmount(old('price')) }}" required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="delivery_time">@lang('Delivery Time')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control form--control" name="delivery_time" id="delivery_time" value="{{ old('delivery_time') }}" required>
                                    <span class="input-group-text">@lang('Day(s)')</span>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required" for="description">@lang('Description')</label>
                    <textarea rows="4" class="form-control form--control" name="description" id="description" placeholder="@lang('Write here')">@php echo old('description') @endphp</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label required" for="requirements">@lang('Requirements')</label>
                    <textarea rows="4" class="form-control form--control" name="requirements" id="requirements" placeholder="@lang('Write here')">@php echo old('requirements') @endphp</textarea>
                </div>
                <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
        </div>
        </form>
    </div>
    </div>
@endsection
@push('style')
    <style>
        .badge.badge--icon {
            border-radius: 5px 0 0 0;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/lib/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/lib/image-uploader.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            const inputField = document.querySelector('#update-photo'),
                uploadImg = document.querySelector('#upload-img');
            inputField.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const result = reader.result;
                        uploadImg.src = result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#category').on('change', function() {
                var subcategories = $(this).find('option:selected').data('subcategories');
                var html = `<option value="">@lang('Select One')</option>`;

                if (subcategories && subcategories.length > 0) {
                    $.each(subcategories, function(i, v) {
                        html += `<option value="${v.id}">@lang('${v.name}')</option>`;
                    });
                }
                $('.subcategory').html(html);
            }).change();


            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
            });


            $(".select2-auto-tokenize").select2({
                tags: true,
                tokenSeparators: [","],
                dropdownParent: $(".skill-body"),
            });

        })(jQuery);
    </script>
@endpush
