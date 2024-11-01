@extends('layouts.app')

@section('title', __('Plugins'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Plugins') }}</h5>
        </div>
    </div>

    <div class="row">
        @foreach ($plugins as $plugin)
            <div class="col-md-4">
                <div class="card custom-card">
                    <img src="{{ asset('assets/images/plugins/' . $plugin->code . '.png') }}" class="card-img-top"
                        alt="{{ $plugin->name }}">
                    <div class="card-body">
                        <h6 class="card-title fw-semibold">{{ $plugin->name }}</h6>
                        <p class="card-text text-muted">{{ $plugin->description }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="card-text">{{ __('Version') }} {{ $plugin->version }}</span>

                        @if ($plugin->status == 'active')
                            @can('uninstall-plugin')
                                <a href="{{ route('plugin.status', $plugin->id) }}" class="btn btn-success btn-sm btn-wave"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Click here for deactive') }}">{{ __('Active') }}</a>
                            @endcan
                        @else
                            @can('install-plugin')
                                <a href="{{ route('plugin.status', $plugin->id) }}" class="btn btn-danger btn-sm btn-wave"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Click here for active') }}">{{ __('Deactive') }}</a>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
