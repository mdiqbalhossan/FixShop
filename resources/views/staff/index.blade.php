@extends('layouts.app')

@section('title', __('Staff'))

@section('hidden_input')
    <input type="hidden" name="udpate_url" id="udpate_url" value="{{ route('staff.update', ':id') }}">
    <input type="hidden" name="dlt_url" id="dlt_url" value="{{ route('staff.destroy', ':id') }}">
    <input type="hidden" name="password_instruction" id="password_instruction" value="{{ __('Leave blank if you don\'t want to change password') }}">
    <input type="hidden" name="ban_confirmation" id="ban_confirmation" value="{{ __('Are you sure you want to ban the staff') }}">
    <input type="hidden" name="active_confirmation" id="active_confirmation" value="{{ __('Are you sure you want to activate the staff') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Staff') }}</h5>
        </div>
        @can('create-staff')
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

    <div class="card custom-card {{ $staffs->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($staffs->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Role') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffs as $staff)
                                <tr>
                                    <td>
                                        <strong>{{ $staff->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $staff->email ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $staff->getRoleNames()->first() ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($staff->status == 'active')
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Banned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-staff')
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $staff->id }}" data-name="{{ $staff->name }}"
                                                    data-email="{{ $staff->email }}"
                                                    data-role="{{ $staff->getRoleNames()->first() }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            @endcan
                                            @can('delete-staff')
                                                <button data-id="{{ $staff->id }}"
                                                    class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                                    data-type="{{ $staff->status === 'active' ? 'ban' : 'active' }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $staff->status === 'active' ? __('Ban') : __('Active') }}"
                                                    {{ $staff->getRoleNames()->first() === 'Super Admin' ? 'disabled' : '' }}>
                                                    @if ($staff->status === 'active')
                                                        <i class="ri-user-unfollow-line"></i>
                                                    @else
                                                        <i class="ri-user-follow-line"></i>
                                                    @endif
                                                </button>
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
                {{ $staffs->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="title"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('staff.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name...">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Enter email...">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label fs-14 text-dark">{{ __('Role') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" aria-label="Default select example">
                                <option value="" selected disabled>{{ __('Select Roles') }}
                                </option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fs-14 text-dark">{{ __('Password') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Password"
                                    aria-label="Recipient's username" id="password" name="password"
                                    aria-describedby="button-addon2">
                                <button class="input-group-text" type="button"
                                    id="generate_password">{{ __('Generate') }}</button>
                            </div>
                            <div id="passwordHelp" class="form-text"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/page/staff.js') }}"></script>
@endpush
