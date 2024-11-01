@extends('layouts.app')

@section('title', __('Edit Purchase Return'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('hidden_input')
    <input type="hidden" name="default_currency" id="default_currency" value="{{ defaultCurrency() }}">
    <input type="hidden" name="select_warehouse" id="select_warehouse" value="{{ __('Please Select Warehouse') }}">
    <input type="hidden" name="purchase_product" id="purchase_product" value="{{ route('purchase.product') }}">
    <input type="hidden" name="image_url" id="image_url" value="{{ asset('assets/images/') }}">
    <input type="hidden" name="select_variation" id="select_variation" value="{{ __('Please Select Variation') }}">
    <input type="hidden" name="supplier_store" id="supplier_store" value="{{ route('supplier.store') }}">
    <input type="hidden" name="total_price" id="total_price" value="{{ $purchase->return_amount }}">
    <input type="hidden" name="payable_amount" id="payable_amount" value="{{ $purchase->receivable_amount }}">
    <input type="hidden" name="discount" id="discount" value="{{ $purchase->return_discount }}">
    <input type="hidden" name="paying_amount" id="paying_amount" value="{{ $purchase->received_amount }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Purchase  Return') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('purchase-return.index') }}" class="btn btn-danger label-btn">
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
                    <form action="{{ route('purchase-return.update', $purchase->id) }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_no" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                       value="{{ $purchase->invoice_no }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="supplier" class="form-label fs-14 text-dark">{{ __('Supplier') }} <span
                                        class="text-danger">*</span></label>
                                <input type="hidden" class="form-control" id="supplier" name="supplier"
                                       value="{{ $purchase->supplier->id }}" readonly>
                                <input type="text" class="form-control" id="supplier" name="supplier_text"
                                       value="{{ $purchase->supplier->name }}" readonly>
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
                                               value="{{ $purchase->return_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <input type="hidden" class="form-control" id="warehouse" name="warehouse"
                                       value="{{ $purchase->warehouse->id }}" readonly>
                                <input type="text" class="form-control" id="warehouse" name="warehouse_text"
                                       value="{{ $purchase->warehouse->name }}" readonly>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead class="table-secondary">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($purchase->returns as $product)
                                    <tr>
                                        <td><input type="text" class="form-control" value="{{ $product->product->name }} @if($product->variation_id != null)({{ $product->variation_value }})@endif"
                                                   readonly></td>
                                        <input type="hidden" name="product_id[]" class="form-control"
                                               value="{{ $product->id }}" readonly>
                                        @if($product->variation_id != null)
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="{{ $product->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="{{ $product->variation_value }}" readonly>
                                        @else
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="" readonly>
                                        @endif
                                    
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control quantity" name="quantity[]"
                                                       value="{{ $product->return_quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $product->product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                                <input type="number" class="form-control product_price" name="price[]"
                                                       value="{{ $product->return_price }}"
                                                       aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                                <input type="number" class="form-control total" name="total[]"
                                                       value="{{ $product->total_return_price }}"
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
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">{!! $purchase->note !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="return_amount" class="form-label fs-14 text-dark">{{ __('Return Amount') }}
                                    <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg return_amount"
                                           name="return_amount" value="{{ $purchase->return_amount }}"
                                           aria-label="Currency" id="return_amount" aria-describedby="return_amount_addon"
                                           readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="return_discount" class="form-label fs-14 text-dark">{{ __('Lessed') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_discount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg return_discount" name="return_discount"
                                           value="{{ $purchase->return_discount }}"
                                           aria-label="Currency" aria-describedby="return_discount_addon" id="return_discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="receivable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Receivable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="receivable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg receivable_amount"
                                           name="receivable_amount" value="{{ $purchase->receivable_amount }}"
                                           aria-label="Currency" aria-describedby="receivable_amount_addon"
                                           id="receivable_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label fs-14 text-dark">{{ __('Account') }} </label>
                                <select class="js-example-basic-single" name="account" id="account">
                                    <option selected disabled>{{ __('-- Select Account --') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $purchase->returnTransaction->account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="received_amount"
                                       class="form-label fs-14 text-dark">{{ __('Received Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="received_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg received_amount"
                                           name="received_amount" value="{{ $purchase->received_amount }}"
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
                                           name="due_amount" value="{{ $purchase->returnDueAmount() }}"
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
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/purchase-return/edit.js') }}"></script>
@endpush
