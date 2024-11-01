@extends('layouts.app')

@section('title', __('Accounts'))

@section('hidden_input')
    <input type="hidden" name="add_account" id="add_account_text" value="{{ __('Add Account') }}">
    <input type="hidden" name="save_change" id="save_change_text" value="{{ __('Save Changes') }}">
    <input type="hidden" name="edit" id="edit_text" value="{{ __('Edit Account') }}">
    <input type="hidden" name="edit_route" id="edit_route" value="{{ route('account.update', ':id') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Accounts') }}</h5>
        </div>
        @can('create-account')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <button class="btn btn-primary label-btn" data-bs-toggle="modal" data-bs-target="#modal" id="addBtn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </button>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $accounts->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($accounts->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Account Number') }}</th>
                                <th scope="col">{{ __('Opening Balance') }}</th>
                                <th scope="col">{{ __('Balance') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>
                                        <strong>{{ $account->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $account->account_number ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ showAmount($account->opening_balance) }}
                                    </td>
                                    <td>
                                        {{ showAmount(accountBalance($account->id)) }}
                                    </td>
                                    <td>
                                        @can('edit-account')
                                            <div class="hstack gap-2 flex-wrap">
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $account->id }}" data-name="{{ $account->name }}"
                                                    data-number="{{ $account->account_number }}"
                                                    data-balance="{{ $account->opening_balance }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $accounts->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    {{-- Modal --}}
    @include('account.include.__modal')    
@endsection
@push('script')
    <script src="{{ asset('assets/js/page/account.js') }}"></script>
@endpush
