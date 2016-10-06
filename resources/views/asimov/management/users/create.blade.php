@extends('asimov.management.layout')

@section('content')
	@errors

	<div class="card">
        <div class="card-header">
            <h2>{{ trans('management/user.new.title') }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ URL::route('management.users.store') }}" method="post"
                  class="form-horizontal" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

	            <div class="form-group">
		            <label for="" class="col-sm-2 control-label">{{ trans('model/user.first_name') }}</label>

		            <div class="col-sm-10">
			            <div class="fg-line">
				            <input type="text" class="form-control input-sm" id="user.first_name"
				                   value="{{ Input::old('first_name') }}"
				                   placeholder="{{ trans('model/user.first_name') }}" name="first_name">
			            </div>
		            </div>
	            </div>
	            <div class="form-group">
		            <label for="" class="col-sm-2 control-label">{{ trans('model/user.last_name') }}</label>

		            <div class="col-sm-10">
			            <div class="fg-line">
				            <input type="text" class="form-control input-sm" id="user.last_name"
				                   value="{{ Input::old('last_name') }}"
				                   placeholder="{{ trans('model/user.last_name') }}" name="last_name">
			            </div>
		            </div>
	            </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/user.email') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="user.email"
                                   value="{{ Input::old('email') }}"
                                   placeholder="{{ trans('model/user.email') }}" name="email">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/user.password') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="user.password" value="{{ Input::old('password') }}"
                                   placeholder="{{ trans('model/user.password') }}" name="password">
                        </div>
                    </div>
                </div>

	            <div class="form-group">
		            <label for="" class="col-sm-2 control-label">{{ trans('model/user.roles') }}</label>

		            <div class="col-sm-10">
			            <select class="tag-select" multiple="" data-placeholder="{{ trans('model/user.roles') }}" name="roles[]">
				            @foreach($roles as $role)
					            <option name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, Input::old('roles', [])) ? 'selected' : '' }}>{{ $role->name }}</option>
				            @endforeach
			            </select>
		            </div>
	            </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{ URL::route('management.users.index') }}"
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
