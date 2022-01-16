@extends('web::layouts.grids.12')

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/vo1-awox-finder.css') }}" />
@endpush

@section('full')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $action == 'create' ? trans('awox::awox.create') : trans('awox::awox.update') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ $action == 'create' ? route('awox.create') : route('awox.update', [ $row ]) }}" method="post">
                <input type="hidden" name="id" value="0"/>
                <div class="card-body">
                    <p>{{ trans('awox::awox.form.description') }}</p>
                    <div class="form-group">
                        <label for="name" class="control-label">{{ trans_choice('web::seat.name', 1) }}</label>
                        <input {{ $action == 'update' ? ' readonly="1" ' : null }}type="text" class="form-control" id="name" name="name" placeholder="{{ trans('awox::awox.name.placeholder') }}" value="{{ $row->name ?? '' }}"/>
                        <span class="help-block multiple-found" style="display: none;">Multiple characters found: <span class="list"></span></span>
                        <span class="help-block not-found" style="display: none;">Nothing found.</span>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label">{{ trans_choice('awox::awox.reason', 1) }}</label>
                        <input type="text" class="form-control" id="reason" name="reason" placeholder="{{ trans('awox::awox.reason.placeholder') }}" value="{{ $row->reason ?? '' }}"/>
                    </div>
                    <div class="form-group">
                        <label for="affiliation" class="control-label">{{ trans_choice('web::seat.affiliation', 1) }}</label>
                        <input type="text" class="form-control" id="affiliation" name="affiliation" placeholder="{{ trans('awox::awox.affiliation.placeholder') }}" value="{{ $row->affiliation ?? '' }}"/>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ trans('awox::awox.description') }}</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="{{ trans('awox::awox.description.placeholder') }}">{{ $row->description ?? '' }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group pull-right" role="group">
                        <input type="submit" id="submitBtn" {{ $action == 'create' ? ' disabled="disabled" ' : null }} class="btn btn-primary" value="{{ trans('awox::awox.submit') }}" />
                    </div>
                    {{ csrf_field() }}
                </div>
            </form>
        </div>
    </div>
@stop

@push('javascript')
    <script>
        $('#name').on('change', function() {
            $('.help-block').hide();
            let name = $(this).val();
            let url = '{{ route('awox.api.find', ['name' => '@NAME']) }}';
            url = url.replace('@NAME', name);
            $.ajax({
                method: 'GET',
                url: url,
                success: function(response) {
                    switch (response.length) {
                        case 0:
                            $('.help-block.not-found').show();
                            break;
                        case 1:
                            $('input[name="id"]').val(response[0].id);
                            $('input[name="name"]').val(response[0].name);
                            $('#submitBtn').prop('disabled', null);
                            break;
                        default:
                            let list = $('.help-block.multiple-found').show().find('span.list');
                            response.forEach(function(value) {
                                console.log(value);
                            });
                            break;
                    }
                }
            });
        })
    </script>
@endpush