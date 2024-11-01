@extends('layouts.app')

@section('title', __('Add Product'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush

@section('hidden_input')
<input type="hidden" name="category_store" value="{{ route('category.store') }}">
<input type="hidden" name="brand_store" value="{{ route('brand.store') }}">
<input type="hidden" name="unit_store" value="{{ route('units.store') }}">
<input type="hidden" name="default_currency" value="{{defaultCurrency() }}">
@endsection

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('Add New Product') }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger-light">{{ __('Back') }} <i
                                class="ri-arrow-go-back-line ms-2 d-inline-block align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.store') }}" class="row g-3" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Product Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="code" class="form-label fs-14 text-dark">{{ __('Product Code') }} <span
                                        class="text-danger">*</span></label>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="code" name="code"
                                           placeholder="generate the barcode">
                                    <button class="input-group-text" type="button"
                                            id="generateBarcode"><i class="ri-barcode-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label fs-14 text-dark">{{ __('Category') }} <span
                                        class="text-danger">*</span></label>

                                <div class="d-flex gap-2">
                                    <select class="js-example-basic-single" name="category" id="category">
                                        <option selected disabled>{{ __('-- Select Category --') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-icon btn-primary-transparent btn-wave"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addCategoryModal">
                                        <i class="ri-add-circle-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">{{ __('SKU') }}
                                    <span class="text-danger">*</span>
                                    <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="{{ __('Stock Keeping Unit (SKU) is a store product and service identification code') }}"></i>
                                </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="Sku">
                                    <button class="input-group-text" type="button"
                                            id="generateSku"><i class="ri-barcode-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="brand" class="form-label fs-14 text-dark">{{ __('Brand') }} <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <select class="js-example-basic-single" name="brand" id="brand">
                                        <option selected disabled>{{ __('-- Select Brand --') }}</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-icon btn-primary-transparent btn-wave"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addBrandModal">
                                        <i class="ri-add-circle-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit" class="form-label fs-14 text-dark">{{ __('Unit') }} <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <select class="js-example-basic-single" name="unit" id="unit">
                                        <option selected disabled>{{ __('-- Select Unit --') }}</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-icon btn-primary-transparent btn-wave"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addUnitModal">
                                        <i class="ri-add-circle-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">'{{ __('Product Image') }}</label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="form-password"
                                       class="form-label fs-14 text-dark me-2">{{ __('Status') }}</label>
                                <input type="radio" class="btn-check" value="1" name="status" id="success-outlined"/>
                                <label class="btn btn-outline-success m-1"
                                       for="success-outlined">{{ __('Active') }}</label>
                                <input type="radio" class="btn-check" value="0" name="status" id="danger-outlined"
                                       checked/>
                                <label class="btn btn-outline-danger m-1"
                                       for="danger-outlined">{{ __('Deactive') }}</label>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label fs-14 text-dark">{{ __('Product Type') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="type" id="type">
                                    <option selected disabled>{{ __('-- Select Type --') }}</option>
                                    <option value="single">{{ __('Single') }}</option>
                                    <option value="variation">{{ __('Variation') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-none" id="variation_sec">
                            <div class="mb-3">
                                <label for="variation" class="form-label fs-14 text-dark">{{ __('Variations') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="variation" id="variation">
                                    <option selected disabled>{{ __('-- Select Variation --') }}</option>
                                    @foreach($variants as $variation)
                                        <option
                                            value="{{ $variation->id }}|{{ arrayToString($variation->values) }}">{{ $variation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-none" id="variation_value">

                        </div>
                        <hr>
                        <div class="row auto_append_sec">

                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Add New Product') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('product.include.__category_modal')
    @include('product.include.__unit')
    @include('product.include.__brand')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/product/create.js') }}"></script>
@endpush
