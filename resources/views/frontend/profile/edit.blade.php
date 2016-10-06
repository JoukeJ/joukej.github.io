@extends('frontend.layout.master')

@section('content')

    @errors

    <form action="{{ URL::route('frontend.profile.update') }}" method="post">
        <input type="hidden" name="_method" value="put"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="form-group">
            <label for="first_name">{{ trans('model/user.first_name') }}</label>
            <input type="text" class="form-control" id="first_name" placeholder="{{ trans('model/user.first_name') }}"
                   name="first_name" value="{{ Input::old('first_name', $user->first_name) }}">
        </div>

        <div class="form-group">
            <label for="last_name">{{ trans('model/user.last_name') }}</label>
            <input type="text" class="form-control" id="last_name" placeholder="{{ trans('model/user.last_name') }}"
                   name="last_name" value="{{ Input::old('last_name', $user->last_name) }}">
        </div>

        <div class="form-group">
            <label for="email">{{ trans('model/user.email') }}</label>
            <input type="text" class="form-control" id="email" placeholder="{{ trans('model/user.email') }}"
                   name="email" value="{{ Input::old('email', $user->email) }}">
        </div>

        <a href="{{ URL::route('frontend.profile.me') }}" class="btn btn-default">{{ trans('ui.back') }}</a>
        <button type="submit" class="btn btn-success">{{ trans('ui.save') }}</button>

    </form>
@endsection
