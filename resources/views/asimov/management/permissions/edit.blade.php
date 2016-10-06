@extends('asimov.management.layout')

@section('content')
	@errors

	<div class="card">
        <div class="card-header">
            <h2>{{ $permission->name }}</h2>
        </div>
        <div class="card-body">

            <form action="{{ URL::route('management.permissions.update') }}" method="post"
                  class="form-horizontal" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">

                <input type="hidden" name="id" value="{{ $permission->id }}">

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/permission.name') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="permission.name"
                                   value="{{ Input::old('name', $permission->name) }}"
                                   placeholder="{{ trans('model/permission.name') }}" name="name">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/permission.display_name') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="permission.display_name"
                                   value="{{ Input::old('name', $permission->display_name) }}"
                                   placeholder="{{ trans('model/permission.display_name') }}" name="display_name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/permission.description') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="permission.description"
                                   value="{{ Input::old('description', $permission->description) }}"
                                   placeholder="{{ trans('model/permission.description') }}" name="description">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{ URL::route('management.permissions.index') }}"
                           class="btn btn-inverse btn-sm waves-effect waves-button waves-float">
                            {{ trans('ui.back') }}
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-button waves-float"
                                name="save" value="1">
                            {{ trans('ui.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
