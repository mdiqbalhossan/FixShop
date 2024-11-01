@extends('layouts.app')

@section('title', __('Quotation Details'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">{{ __('Quotation Details') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('quotation.index') }}" class="btn btn-danger label-btn">
                    <i class="ri-arrow-go-back-line label-btn-icon me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Purchase Details -->
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h2 class="invoice-title">{{ __('Invoice') }} #{{ $quotation->quotation_number }}</h2>
                            {!! generateQRCode(route('quotation.show', $quotation->id)) !!}
                            <div class="billed-from">
                                <h6>{{ settings('company_name') }}</h6>
                                <p>{{ settings('company_address') }}<br>
                                    {{ __('Tel No') }}: {{ settings('company_phone') }}<br>
                                    {{ __('Email') }}: {{ settings('company_email') }}</p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mt-4">
                            <div class="col-md">
                                <label class="text-gray-6">{{ __('Billed To') }}</label>
                                <div class="billed-to">
                                    <h6 class="fs-14 fw-semibold">{{ $quotation->customer->name }}</h6>
                                    <p>{{ $quotation->customer->address }}<br>
                                        {{ __('Tel No') }}: {{ $quotation->customer->phone }}<br>
                                        {{ __('Email') }}: {{ $quotation->customer->email }}</p>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="text-gray-6">{{ __('Invoice Information') }}</label>
                                <p class="invoice-info-row"><span>{{ __('Invoice No') }}</span> <span>{{ $quotation->quotation_number }}</span></p>
                                <p class="invoice-info-row"><span>{{ __('Warehouse') }}</span> <span>{{ $quotation->warehouse->name ?? 'N/A' }}</span></p>
                                <p class="invoice-info-row"><span>{{ __('Quotation Date') }}:</span> <span>{{ $quotation->quotation_date ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="w-20">{{ __('Product') }}</th>
                                    <th class="w-40">{{ __('Code') }}</th>
                                    <th class="text-center">{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($quotation->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}
                                            @if($product->product_type == 'variation')
                                                <p class="text-muted mb-0">{{ variationName($product->pivot->variation_id) }}: {{ $product->pivot->variation_value }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $product->code }}</td>
                                        <td class="text-center">{{ $product->pivot->quantity }} {{ $product->unit->name }}</td>
                                        <td>{{ showAmount($product->pivot->price) }}</td>
                                        <td>{{ showAmount($product->pivot->total_price) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="align-top" colspan="2" rowspan="4">
                                        <div class="invoice-notes">
                                            <label class="main-content-label fs-13">{{ __('Notes') }}</label>
                                            <p class="fw-normal fs-13">{{ $quotation->note }}</p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td>{{ __('Total Amount') }}</td>
                                    <td colspan="2">{{ showAmount($quotation->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Tax') }} ({{ $quotation->tax_amount }}%)</td>
                                    <td colspan="2">{{ showAmount(taxAmount($quotation->total_amount, $quotation->tax_amount)) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Discount') }}</td>
                                    <td colspan="2">-{{ showAmount($quotation->discount) }}</td>
                                </tr>
                                <tr>
                                    <td class=" text-uppercase tx-inverse">{{ __('Grand Total') }}</td>
                                    <td colspan="2">
                                        <h4 class="text-danger">{{ showAmount($quotation->grand_total) }}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="javascript:void(0);" class="btn btn-danger float-end mt-3 ms-2 btn_print">
                            <i class="mdi mdi-printer me-1"></i>{{ __('Print') }}
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!--End::row-1 -->
@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/quotation/invoice.js') }}"></script>
@endpush
