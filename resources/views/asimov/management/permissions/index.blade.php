@extends('asimov.management.layout')

@section('content')
	@errors

    <div class="card">
        <div class="card-header">
            <h2>{{ trans('model/permission.plural') }}</h2>
            <ul class="actions">
                <li>
                    <a href="{{ URL::route('management.permissions.create') }}">
                        <i class="md md-my-library-add"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">

            <table class="table table condensed table hover table striped table bootgrid-table">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">{{ trans('model/permission.id') }}</th>
                    <th data-column-id="name">{{ trans("model/permission.name") }}</th>
                    <th data-column-id="name">{{ trans("model/permission.display_name") }}</th>
                    <th data-column-id="email">{{ trans("model/permission.description") }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>
                            <a href="{{ URL::route('management.permissions.edit', $permission->id) }}">{{ $permission->name }}</a>
                        </td>
                        <td>{{ $permission->display_name }}</td>
                        <td>{{ str_limit($permission->description, 50) }}</td>
	                    <td>
		                    @delete(['url' => URL::route('management.permissions.destroy', [$permission->id]), 'name' => trans('common.delete'), 'title' => trans('management/permission.confirm_delete_text')])
	                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
