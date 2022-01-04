@extends('web::layouts.grids.12')

@section('title', trans('awox::awox.list'))
@section('page_header', trans('awox::awox.list'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/vo1-awox-finder.css') }}" />
@endpush

@section('full')
FULL LIST
@stop

@push('javascript')
@endpush