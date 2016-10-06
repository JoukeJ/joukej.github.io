@extends('asimov.management.layout')

@section('content')
	@errors

	<div class="card">
        <div class="card-header">
            <h2>{{ trans('model/role.plural') }}</h2>
            <ul class="actions">
                <li>
                    <a href="{{ URL::route('management.roles.create') }}">
                        <i class="md md-my-library-add"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">

            <table class="table table condensed table hover table striped table bootgrid-table">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">{{ trans('model/role.id') }}</th>
                    <th data-column-id="name">{{ trans("model/role.name") }}</th>
                    <th data-column-id="name">{{ trans("model/role.display_name") }}</th>
                    <th data-column-id="email">{{ trans("model/role.description") }}</th>
                    <th data-column-id=""></th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td><a href="{{ URL::route('management.roles.edit', $role->id) }}">{{ $role->name }}</a></td>
                        <td>{{ $role->display_name }}</td>
                        <td>{{ str_limit($role->description, 50) }}</td>
	                    <td>
		                    @delete(['url' => URL::route('management.roles.destroy', [$role->id]), 'title' => trans('management/role.confirm_delete_text'), 'name' => trans('common.delete')])
	                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

