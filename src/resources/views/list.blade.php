@extends('web::layouts.grids.12')

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/vo1-awox-finder.css') }}" />
@endpush

@section('full')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('awox::awox.list') }}</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link fas fa-user-plus" href="{{ route('awox.form.create') }}"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop

@push('javascript')
    {!! $dataTable->scripts() !!}
@endpush