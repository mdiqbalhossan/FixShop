@extends('layouts.app')

@section('title', 'Dashboard')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/apexcharts/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/dragula/dragula.min.css') }}">
@endpush

@section('hidden_input')
    <input type="hidden" name="purchase_date" value='{!! json_encode(array_values($purchaseDataForEveryDay)) !!}'>
    <input type="hidden" name="sale_date" value='{!! json_encode(array_values($saleDataForEveryDay)) !!}'>
    <input type="hidden" name="category_date" value='{!! json_encode(array_values($thisMonthDate)) !!}'>
    <input type="hidden" name="currency_symbol" value="{{ settings('currency') }}({{ settings('currency_symbol') }})">
    <input type="hidden" name="manage_widget" value="{{ __('Manage Widget') }}">
    <input type="hidden" name="save_exit" value="{{ __('Save & Exit') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Dashboard') }}</h5>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <button class="btn btn-primary label-btn" data-bs-toggle="modal" data-bs-target="#modal" id="manageWidget">
                    <i class="ri-dashboard-line label-btn-icon me-2"></i>{{ __('Manage Widget') }}
                </button>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->
    <div id="dragable_card">
        <!-- Start::row-1 -->
        <div class="row" id="dragable_card1">
            <div class="col-lg-6 col-xl-3 col-md-6 col-12 {{ isset($widget['total_products']) ? '' : 'd-none' }}">
                <div class="card bg-primary-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-box fs-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ __('Total Products') }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $total['total_product'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 col-md-6 col-12 {{ isset($widget['total_customers']) ? '' : 'd-none' }}">
                <div class="card bg-danger-gradient text-fixed-white" id="customer_card">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-user-plus fs-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ __('Total Customers') }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $total['total_customer'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 col-md-6 col-12 {{ isset($widget['total_suppliers']) ? '' : 'd-none' }}">
                <div class="card bg-success-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-user-minus fs-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ __('Total Suppliers') }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $total['total_supplier'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-3 col-md-6 col-12 {{ isset($widget['total_category']) ? '' : 'd-none' }}">
                <div class="card bg-warning-gradient text-fixed-white">
                    <div class="card-body text-fixed-white">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-folder fs-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-fixed-white">{{ __('Total Category') }}</span>
                                    <h3 class="text-fixed-white mb-0">{{ $total['total_category'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::row-1 -->
        <div class="row" id="dragable_card2">
            <div class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_sale']) ? '' : 'd-none' }}">
                <div class="card text-center border border-primary custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-shopping-cart-full project bg-primary-transparent mx-auto text-primary "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Sale') }}</h6>
                        <h4 class="fw-semibold">{{ $sale['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_sale_amount']) ? '' : 'd-none' }}">
                <div class="card text-center border border-secondary custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-money project bg-pink-transparent mx-auto text-pink "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Sale Amount') }}</h6>
                        <h4 class="fw-semibold">{{ showAmount($sale['total_amount']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_sale_return']) ? '' : 'd-none' }}">
                <div class="card text-center border border-info custom-card">
                    <div class="card-body">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-back-left  project bg-teal-transparent mx-auto text-teal "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Sale Return') }}</h6>
                        <h4 class="fw-semibold">{{ $sale['total_return'] }}</h4>
                    </div>
                </div>
            </div>
            <div
                class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_sale_return_amount']) ? '' : 'd-none' }}">
                <div class="card text-center border border-success custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-reload project bg-success-transparent mx-auto text-success "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Sale Return Amount') }}</h6>
                        <h4 class="fw-semibold">{{ showAmount($sale['total_return_amount']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="dragable_card3">
            <div class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_purchase']) ? '' : 'd-none' }}">
                <div class="card text-center border border-danger custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-shopping-cart-full project bg-primary-transparent mx-auto text-primary "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Purchase') }}</h6>
                        <h4 class="fw-semibold">{{ $purchase['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div
                class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_purchase_amount']) ? '' : 'd-none' }}">
                <div class="card text-center border border-success custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-money project bg-pink-transparent mx-auto text-pink "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Purchase Amount') }}</h6>
                        <h4 class="fw-semibold">{{ showAmount($purchase['total_amount']) }}</h4>
                    </div>
                </div>
            </div>
            <div
                class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_purchase_return']) ? '' : 'd-none' }}">
                <div class="card text-center border border-dark custom-card">
                    <div class="card-body">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-back-left  project bg-teal-transparent mx-auto text-teal "></i>
                        </div>
                        <h6 class="fs-14 mb-1 text-muted">{{ __('Total Purchase Return') }}</h6>
                        <h4 class="fw-semibold">{{ $purchase['total_return'] }}</h4>
                    </div>
                </div>
            </div>
            <div
                class="col-xxl-3 col-lg-3 col-sm-6 col-md-6 {{ isset($widget['total_purchase_return_amount']) ? '' : 'd-none' }}">
                <div class="card text-center border border-warning custom-card">
                    <div class="card-body ">
                        <div class="feature widget-2 text-center mt-0 mb-3">
                            <i class="ti-reload project bg-success-transparent mx-auto text-success "></i>
                        </div>
                        <h6 class="fs-14    mb-1 text-muted">{{ __('Total Purchase Return Amount') }}</h6>
                        <h4 class="fw-semibold">{{ showAmount($purchase['total_return_amount']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row {{ isset($widget['purchase_sale_report']) ? '' : 'd-none' }}">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">{{ __('Purchases & Sales Report') }}</div>
                    </div>
                    <div class="card-body">
                        <div id="line-chart-datalabels"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-6 {{ isset($widget['top_selling_product']) ? '' : 'd-none' }}">
                <div class="card card-table">
                    <div class=" card-header p-0 d-flex justify-content-between">
                        <h4 class="card-title mb-1">{{ __('Top Selling Product') }}</h4>
                    </div>
                    <span class="fs-12 text-muted mb-3 ">{{ __('This is top most selling product.') }}</span>
                    <div class="table-responsive country-table">
                        <table class="table table-striped table-bordered mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th class="wd-lg-25p">{{ __('Product') }}</th>
                                    <th class="wd-lg-25p">{{ __('Code') }}</th>
                                    <th class="wd-lg-25p">{{ __('Quantity') }}</th>
                                    <th class="wd-lg-25p">{{ __('Value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSellingProducts as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ productInfo($product->product_id)->name }}</strong>
                                            @if ($product->variation_id != null)
                                                <br>
                                                <strong>{{ variationName($product->variation_id) }}</strong>:
                                                {{ $product->variation_value }}
                                            @endif
                                        </td>
                                        <td class="fw-medium">{{ productInfo($product->product_id)->code }}</td>
                                        <td class="fw-medium">{{ $product->total_quantity }}</td>
                                        <td class="text-success fw-medium">
                                            @if ($product->variation_id != null)                                                
                                                @php($salePrice = productInfoByVariation($product->product_id, $product->variation_id, $product->variation_value)->pivot->sale_price)
                                            @else
                                                @php($salePrice = productInfo($product->product_id)->sale_price)
                                            @endif
                                            {{ showAmount($product->total_quantity * $salePrice) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6 {{ isset($widget['stock_level_alert']) ? '' : 'd-none' }}">
                <div class="card card-table">
                    <div class=" card-header p-0 d-flex justify-content-between">
                        <h4 class="card-title mb-1">{{ __('Stock Level Alert') }}</h4>
                    </div>
                    <span class="fs-12 text-muted mb-3 ">{{ __('This is stock alert which is low stock') }}</span>
                    <div class="table-responsive country-table">
                        <table class="table table-striped table-bordered mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th class="wd-lg-25p">{{ __('Product') }}</th>
                                    <th class="wd-lg-25p">{{ __('Warehouse') }}</th>
                                    <th class="wd-lg-25p">{{ __('Alert') }}</th>
                                    <th class="wd-lg-25p">{{ __('Stock') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockLevelAlert as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item['product'] }}</strong>
                                            @if ($item['variants'])
                                                <br>
                                                <small><em>{{ $item['variants'] }}</em></small>
                                            @endif
                                        </td>
                                        <td class="fw-medium">{{ $item['warehouse'] }}</td>
                                        <td class="fw-medium">{{ $item['alert'] }}</td>
                                        <td
                                            class="{{ $item['stock'] <= $item['alert'] ? 'text-danger' : 'text-success' }} fw-medium">
                                            {{ $item['stock'] }}{{ $item['unit'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('includes.__widget_handle')
    </div>
@endsection
@push('script')
    <!-- Apex Charts JS -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/dashboard.js') }}"></script>
@endpush
