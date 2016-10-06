@extends('asimov.management.layout')

@section('content')
	@errors

	<div class="card">
        <div class="card-header">
            <h2>{{ $role->name }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ URL::route('management.roles.update', [$role->id]) }}" method="post" class="form-horizontal" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">

                <input type="hidden" name="id" value="{{ $role->id }}">

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/role.name') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="role.name"
                                   value="{{ Input::old('name', $role->name) }}"
                                   placeholder="{{ trans('model/role.name') }}" name="name">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/role.display_name') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="role.display_name"
                                   value="{{ Input::old('name', $role->display_name) }}"
                                   placeholder="{{ trans('model/role.display_name') }}" name="display_name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('model/role.description') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="role.description"
                                   value="{{ Input::old('description', $role->description) }}"
                                   placeholder="{{ trans('model/role.description') }}" name="description">
                        </div>
                    </div>
                </div>

	            <div class="form-group">
		            <label for="" class="col-sm-2 control-label">{{ trans('model/role.permissions') }}</label>

		            <div class="col-sm-10">
			            @foreach($permissions as $permission)
							<div class="checkbox m-b-15">
								<label>
									<input type="checkbox" name="permissions[{{ $permission->id }}]" value="{{ $permission->id }}" {{ (array_has(Input::old('roles', []), $permission->id) || $role->hasPermission($permission)) ? 'checked' : '' }}>
									<i class="input-helper"></i>
									{{ $permission->name }}
								</label>
							</div>
			            @endforeach
		            </div>
	            </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{ URL::route('management.roles.index') }}"
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
