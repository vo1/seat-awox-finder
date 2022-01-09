@extends('web::layouts.grids.12')

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/vo1-awox-finder.css') }}" />
@endpush

@section('full')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('awox::awox.settings') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('awox.settings') }}" method="post">
                <div class="card-body">
                    <p>{{ trans_choice('awox::awox.alliance_ids', 1) }}</p>
                    <div class="form-group">
                        <textarea class="form-control input-md" name="alliance_ids" rows="5">{{ $allianceIds }}</textarea>
                    </div>
                </div>
                <div class="card-body">
                    <p>{{ trans_choice('awox::awox.discord_urls', 1) }}</p>
                    <div class="form-group">
                        <textarea class="form-control input-md" name="discord_urls" rows="5">{{ $discordUrls }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group pull-right" role="group">
                        <input type="submit" id="submitBtn" class="btn btn-primary" value="{{ trans('awox::awox.submit') }}" />
                    </div>
                    {{ csrf_field() }}
                </div>
            </form>
        </div>
    </div>
@stop

@push('javascript')

@endpush