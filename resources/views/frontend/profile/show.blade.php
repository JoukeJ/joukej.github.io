@extends('frontend.layout.master')

@section('content')

    <div class="well">
        <h2>{{ trans('ui.profile.title') }}</h2>

        <div class="p-b-10"></div>
        <dl class="dl-horizontal">
            <dt>{{ trans('model/user.first_name') }}</dt>
            <dd>{{ $user->first_name }}</dd>

            <dt>{{ trans('model/user.last_name') }}</dt>
            <dd>{{ $user->last_name }}</dd>

            <dt>{{ trans('model/user.email') }}</dt>
            <dd>{{ $user->email }}</dd>
        </dl>
    </div>
@endsection
