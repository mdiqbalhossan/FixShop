@extends('layouts.app')

@section('title', __('Quotations'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Quotations') }}</h5>
        </div>
        @can('create-quotation')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('quotation.create') }}" class="btn btn-primary label-btn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->
    <div class="card custom-card {{ $quotations->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($quotations->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">{{ __('Quotation No|Date') }}</th>
                                <th scope="col">{{ __('Customer|Mobile') }}</th>
                                <th scope="col">{{ __('Total Amount|Warehouse') }}</th>
                                <th scope="col">{{ __('Discount|Tax|Grand Total') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotations as $quotation)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $quotation->quotation_number }}</strong><br>
                                        <span>{{ $quotation->quotation_date }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $quotation->customer->name }}</strong><br><span>{{ $quotation->customer->phone }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($quotation->total_amount) }}<br>
                                        <span class="text-primary">{{ $quotation->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($quotation->discount_amount) }} <br>
                                        {{ showAmount(taxAmount($quotation->total_amount, $quotation->tax_amount)) }} <br>
                                        {{ showAmount($quotation->grand_total) }}
                                    </td>                                   

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @can('invoice-quotation')
                                                <a href="{{ route('quotation.show', $quotation->id) }}"
                                                    class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Invoice') }}"><i
                                                        class="ri-download-2-line"></i></a>
                                            @endcan                                            
                                            @can('edit-quotation')
                                                <a href="{{ route('quotation.edit', $quotation->id) }}"
                                                    class="btn btn-icon btn-sm btn-info" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Edit') }}"><i
                                                        class="ri-edit-line"></i></a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $quotations->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>
@endsection

