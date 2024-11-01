@extends('layouts.app')

@section('title', __('Edit Transfer'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('hidden_input')
    <input type="hidden" name="quantity_alert" value="{{ __('Transfer quantity is not greater than current stock.') }}">
    <input type="hidden" name="default_currency" value="{{ defaultCurrency() }}">
    <input type="hidden" name="select_warehouse" value="{{ __('Please Select From Warehouse!') }}">
    <input type="hidden" name="purchase_product" value="{{ route('purchase.product') }}">
    <input type="hidden" name="image_url" value="{{ asset('assets/images/') }}">
    <input type="hidden" name="select_variation" value="{{ __('Please Select Variation Value') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Transfer') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('transfer.index') }}" class="btn btn-danger label-btn">
                    <i class="ri-arrow-go-back-line label-btn-icon me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="{{ route('transfer.update', $transfer->id) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="{{ $transfer->date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('From Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="from_warehouse" id="from_warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $transfer->from_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('To Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="to_warehouse" id="to_warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $transfer->to_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Current Stock') }}</th>
                                    <th scope="col">{{ __('Transfer Qty') }}
                                        <span class="text-danger">*</span>
                                    </th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($transfer->products as $transferProduct)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $transferProduct->product->name }}@if($transferProduct->product->product_type != 'single')({{ $transferProduct->variation_value}})@endif" readonly>
                                            <input type="hidden" name="product_id[]" class="form-control" value="{{ $transferProduct->product_id }}" readonly>
                                            <input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="{{ stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id)+$transferProduct->quantity }}" readonly>
                                            <input type="hidden" name="variation_id[]" class="form-control" value="{{ $transferProduct->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control" value="{{ $transferProduct->variation_value }}" readonly>
                                        </td>
                                        <td>
                                            @if($transferProduct->product->product_type == 'single')
                                                {{ stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id) + $transferProduct->quantity }}
                                            @else
                                                {{ variant_stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id,$transferProduct->variation_id,$transferProduct->variation_value)+ $transferProduct->quantity }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control qty" name="qty[]" value="{{ $transferProduct->quantity }}"
                                                       aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $transferProduct->product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">
                                    {{ $transfer->note }}
                                </textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('purchase.include.__product_popup')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/transfer/edit.js') }}"></script>
@endpush
