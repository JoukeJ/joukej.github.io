@extends('asimov.management.layout')

@section('content')
	@errors

	<div class="card">
        <div class="card-header">
            <h2>{{ trans('model/user.plural') }}</h2>
            <ul class="actions">
                <li>
                    <a href="{{ URL::route('management.users.create') }}">
                        <i class="md md-person-add"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <table class="table table condensed table hover table striped table bootgrid-table">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">{{ trans('model/user.id') }}</th>
	                <th data-column-id="name">{{ trans("model/user.name") }}</th>
                    <th data-column-id="email">{{ trans("model/user.email") }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
	                    <td><a href="{{ URL::route('management.users.edit', $user->id) }}">{{ $user->presenter->fullname() }}</a></td>
                        <td>{{ $user->email }}</td>
	                    <td>
		                    @delete(['url' => URL::route('management.users.destroy', [$user->id]), 'title' => trans('management/user.confirm_delete_text'), 'name' => trans('common.delete')])
	                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
