@extends('layouts.app')

@section('title', __('Expense Type'))

@section('hidden_input')
    <input type="hidden" id="edit_expense_type" value="{{ __('Edit Expense Type') }}">
    <input type="hidden" id="submit_expense_type" value="{{ __('Save Changes') }}">
    <input type="hidden" id="update_route" value="{{ route('expense-type.update', ':id') }}">
    <input type="hidden" id="destroy_route" value="{{ route('expense-type.destroy', ':id') }}">
    <input type="hidden" id="delete_text" value="{{ __('Are you sure you want to delete the items') }}">
@endsection

@section('content')
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card custom-card collapse-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0" id="title">
                        {{ __('Add Expense Type') }}
                    </div>
                    <div>
                        <a href="{{ route('category.index') }}" class="mx-2"><i class="ri-refresh-line fs-18"></i></a>
                        <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                            <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample">
                    <form action="{{ route('expense-type.store') }}" method="POST" id="form">
                        @csrf
                        <div id="method_sec">
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Expense Type') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter type">
                            </div>
                        </div>
                        <div class="card-footer">
                            @can('create-expense-type')
                                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card custom-card {{ $types->count() <= 0 ? 'text-center' : '' }}">
                <div class="card-header justify-content-between">
                    @include('includes.__table_header')
                </div>
                <div class="card-body">
                    @if ($types->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($types as $type)
                                        <tr>
                                            <td>{{ $type->name }}</td>
                                            <td>
                                                <div class="hstack gap-2 flex-wrap">
                                                    @can('edit-expense-type')
                                                        <a href="javascript:void(0);" data-id="{{ $type->id }}"
                                                            data-name="{{ $type->name }}"
                                                            class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit') }}"><i class="ri-edit-line"></i></a>
                                                    @endcan
                                                    @can('delete-expense-type')
                                                        <button data-id="{{ $type->id }}"
                                                            class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}"><i
                                                                class="ri-delete-bin-5-line"></i></button>
                                                    @endcan
                                                    <form action="" id="dltForm" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $types->links('includes.__pagination') }}
                    @else
                        @include('includes.__empty_table')
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/page/expense_type.js') }}"></script>
@endpush
