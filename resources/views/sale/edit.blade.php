@extends('layouts.app')

@section('title', __('Edit Sale'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('hidden_input')
<input type="hidden" name="default_currency" id="default_currency" value="{{ defaultCurrency() }}">
<input type="hidden" name="select_warehouse" id="select_warehouse" value="{{ __('Please Select Warehouse') }}">
<input type="hidden" name="purchase_product" id="purchase_product" value="{{ route('purchase.product') }}">
<input type="hidden" name="image_url" id="image_url" value="{{ asset('assets/images/') }}">
<input type="hidden" name="select_variation" id="select_variation" value="{{ __('Please Select Variation Value') }}">
<input type="hidden" name="quantity_alert" id="quantity_alert" value="{{ __('Quantity is greater than stock. Your stock is') }}">
<input type="hidden" name="customer_store" id="customer_store" value="{{ route('customer.store') }}">
<input type="hidden" name="total_price" id="total_price" value="{{ $sale->total_price }}">
<input type="hidden" name="receivable_amount" id="receivable_amount" value="{{ $sale->receivable_amount }}">
<input type="hidden" name="tax_amount" id="tax_amount" value="{{ taxAmount($sale->total_price, $sale->tax_amount) }}">
<input type="hidden" name="discount" id="discount" value="{{ $sale->discount }}">
<input type="hidden" name="received_amount" id="received_amount" value="{{ $sale->received_amount }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Sale') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('sale.index') }}" class="btn btn-danger label-btn">
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
                    <form action="{{ route('sale.update', $sale) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_no" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                       value="{{ $sale->invoice_no }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="customer" class="form-label fs-14 text-dark">{{ __('Customer') }} <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                <select class="js-example-basic-single" name="customer" id="customer">
                                    <option selected disabled>{{ __('-- Select customer --') }}</option>
                                    @foreach($customers as $customer)
                                        <option
                                            value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id || settings('default_customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                    <button type="button" class="btn btn-icon btn-primary-transparent btn-wave"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addCustomerModal">
                                        <i class="ri-add-circle-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="{{ $sale->date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="warehouse" id="warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}" {{ $sale->warehouse_id == $warehouse->id || settings('default_warehouse') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                    <th scope="col">{{ __('In Stock') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($sale->products as $product)
                                    <tr>
                                        <td><input type="text" class="form-control" value="{{ $product->name }} @if($product->product_type == 'variation')({{ $product->pivot->variation_value }})@endif"
                                                   readonly></td>
                                        @if($product->product_type == 'variation')
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="{{ $product->pivot->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="{{ $product->pivot->variation_value }}" readonly>
                                        @else
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="" readonly>
                                        @endif
                                        <input type="hidden" name="product_id[]" class="form-control"
                                               value="{{ $product->id }}" readonly>
                                               @php
                                                   if($product->product_type == 'single'){
                                                    $stock = stock_quantity($product->id, $sale->warehouse_id);
                                                   }else{
                                                    $stock = variant_stock_quantity($product->id, $sale->warehouse_id, $product->pivot->variation_id, $product->pivot->variation_value);
                                                   }
                                                   $exact_stock = $stock + $product->pivot->quantity;
                                               @endphp
                                               <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="{{ $stock }}" readonly>
                                                        <span class="input-group-text" id="basic-addon2">
                                                            <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="{{ __('Stock With this sale Quantity') }}: {{ $exact_stock }}"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                            <input type="hidden" name="exact_stock" value="{{ $exact_stock }}">
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control quantity" name="quantity[]"
                                                       value="{{ $product->pivot->quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                                <input type="number" class="form-control product_price" name="price[]"
                                                       value="{{ $product->pivot->price }}"
                                                       aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                                <input type="number" class="form-control total" name="total[]"
                                                       value="{{ $product->pivot->total_price }}"
                                                       aria-label="Recipient's username" aria-describedby="basic-addon2"
                                                       readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-icon dltBtn disabled"><i
                                                    class="ri-delete-bin-2-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">{!! $sale->note !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="total_price" class="form-label fs-14 text-dark">{{ __('Total Price') }}
                                    <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="total_price_addon">$</span>
                                    <input type="number" class="form-control form-control-lg total_price"
                                           name="total_price" value="{{ $sale->total_price }}"
                                           aria-label="Currency" id="total_price" aria-describedby="total_price_addon"
                                           readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label fs-14 text-dark">{{ __('Tax') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="discount_addon">%</span>
                                    <input type="text" class="form-control form-control-lg tax" name="tax"
                                           value="{{ $sale->tax_amount }}"
                                           aria-label="Currency" aria-describedby="discount_addon" id="tax">
                                </div>
                                <p class="text-muted">{{ __('Tax Amount') }}: <span class="tax_amount">{{ taxAmount($sale->total_price, $sale->tax_amount) }}</span></p>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label fs-14 text-dark">{{ __('Discount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="discount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg discount" name="discount"
                                           value="{{ $sale->discount }}"
                                           aria-label="Currency" aria-describedby="discount_addon" id="discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="receivable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Receivable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="receivable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg receivable_amount"
                                           name="receivable_amount" value="{{ $sale->receivable_amount }}"
                                           aria-label="Currency" aria-describedby="receivable_amount_addon"
                                           id="receivable_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label fs-14 text-dark">{{ __('Account') }} </label>
                                <select class="js-example-basic-single" name="account" id="account">
                                    <option selected disabled>{{ __('-- Select Account --') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $sale->transaction->account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="received_amount"
                                       class="form-label fs-14 text-dark">{{ __('Received Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="received_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg received_amount"
                                           name="received_amount" value="{{ $sale->received_amount }}"
                                           aria-label="Currency" aria-describedby="received_amount_addon" id="received_amount"
                                    >
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="due_amount"
                                       class="form-label fs-14 text-dark">{{ __('Due Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="due_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg due_amount"
                                           name="due_amount" value="{{ $sale->dueAmount() }}"
                                           aria-label="Currency" aria-describedby="due_amount_addon" id="due_amount"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Popup Modal -->
    @include('sale.include.__product_popup')
    <!-- Add Customer Modal -->
    @include('sale.include.__customer_modal')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale/edit.js') }}"></script>
@endpush
