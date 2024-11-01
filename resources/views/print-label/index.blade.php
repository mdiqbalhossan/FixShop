@extends('layouts.app')

@section('title', __('Print Labels'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('hidden_input')
    <input type="hidden" id="defaultCurrency" value="{{ defaultCurrency() }}">
    <input type="hidden" id="select_warehouse" value="{{ __('Please Select Warehouse First') }}">
    <input type="hidden" id="product_route" value="{{ route('purchase.product') }}">
    <input type="hidden" id="image_path" value="{{ asset('assets/images/') }}">
    <input type="hidden" id="select_variation" value="{{ __('Please Select Variation Value') }}">
    <input type="hidden" id="select_product" value="{{ __('Please Select Product First') }}">
    <input type="hidden" id="print_labels" value="{{ route('print-labels.print') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Print Labels') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                    class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="warehouse" id="warehouse">
                                <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="product" class="form-label fs-14 text-dark">{{ __('Products') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="ri-search-line"></i></span>
                                <input type="text" class="form-control form-control-lg" name="product" id="product"
                                       placeholder="Search Product....">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead class="table-secondary">
                            <tr>
                                <th scope="col" width="20%">{{ __('Name') }}</th>
                                <th scope="col" width="20%">{{ __('Code') }}</th>
                                <th scope="col" width="10%">{{ __('Price') }}</th>
                                <th scope="col" width="10%">{{ __('Quantity') }}</th>
                            </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="paper_size" class="form-label fs-14 text-dark">{{ __('Paper Size') }} <span
                                    class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="paper_size" id="paper_size">
                                <option selected disabled>{{ __('-- Select Paper Size --') }}</option>
                                <option value="40">{{ __('40 Per Sheet') }} (a4) (1.799 * 1.003)</option>
                                <option value="30">{{ __('30 Per Sheet') }} (2.625 * 1)</option>
                                <option value="24">{{ __('24 Per Sheet') }} (a4) (2.48 * 1.334)</option>
                                <option value="20">{{ __('20 Per Sheet') }} (4 * 1)</option>
                                <option value="18">{{ __('18 Per Sheet') }} (a4) (2.5 * 1.835)</option>
                                <option value="14">{{ __('14 Per Sheet') }} (4 * 1.33)</option>
                                <option value="12">{{ __('12 Per Sheet') }} (a4) (2.5 * 2.834)</option>
                                <option value="10">{{ __('10 Per Sheet') }} (4 * 2)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex">
                        <button type="button" class="btn btn-primary label-btn me-2 rfrsBtn">
                            <i class="ri-refresh-line label-btn-icon me-2"></i>
                            {{ __('Refresh') }}
                        </button>
                        <button type="button" class="btn btn-danger label-btn me-2 resetBtn">
                            <i class="ri-shut-down-line label-btn-icon me-2"></i>
                            {{ __('Reset') }}
                        </button>
                        <button class="btn btn-success label-btn me-2 printBtn">
                            <i class="ri-printer-line label-btn-icon me-2"></i>
                            {{ __('Print Labels') }}
                        </button>
                    </div>
                    <hr>
                    {{--Show Bar Code By Ajax--}}
                    <div class="barcode" id="barcode-sheet">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Popup Modal -->
    @include('purchase.include.__product_popup')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/print.js') }}"></script>
@endpush
