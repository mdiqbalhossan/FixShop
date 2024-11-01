@extends('layouts.app')

@section('title', __('Edit Email Template'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
@endpush

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('Edit Email Template') }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger-light">{{ __('Back') }} <i
                                class="ri-arrow-go-back-line ms-2 d-inline-block align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('email-template.update', $emailTemplate) }}" class="row g-3" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Template Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter email template"
                                       value="{{ $emailTemplate->name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subject" class="form-label fs-14 text-dark">{{ __('Subject') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject"
                                       value="{{ $emailTemplate->subject }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label fs-14 text-dark">{{ __('Type') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="type" id="type">
                                    <option selected disabled>{{ __('-- Select Type --') }}</option>
                                    <option value="default" {{ $emailTemplate->type == 'default' ? 'selected' : '' }}>
                                        {{ __('Default') }}
                                    </option>
                                    <option value="customer" {{ $emailTemplate->type == 'customer' ? 'selected' : '' }}>
                                        {{ __('Customer') }}
                                    </option>
                                    <option value="supplier" {{ $emailTemplate->type == 'supplier' ? 'selected' : '' }}>
                                        {{ __('Supplier') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Short Code') }}</label><br>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[full_name]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[user_name]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[company_name]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[contact_no]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[email]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[password]]
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary short_code">
                                    [[url]]
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Content') }}</label>
                                <div id="note"
                                          class="form-control">{!! $emailTemplate->content !!}</div>
                                <textarea rows="3" class="mb-3 d-none" placeholder="Write Content" name="content" id="quill-editor-area">{!! $emailTemplate->content !!}</textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Update Template') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/email.js') }}"></script>
@endpush
