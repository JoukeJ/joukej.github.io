@extends('ttc.frontend.feature.layout.master')

@section('content')

    <p>{{ trans('frontend/profile/show.title') }}</p>

    <hr/>

    <strong>{{ trans('frontend/profile/show.labels.name') }}</strong>:<br/>
    @if(!empty($profile->name))
        {{ $profile->name }}
    @else
        -
    @endif

    <hr/>

    <strong>{{ trans('frontend/profile/show.labels.birthday') }}</strong>:<br/>
    @if($profile->birthday !== null)
        {{ date('Y-m-d', strtotime($profile->birthday)) }}
    @else
        -
    @endif

    <hr/>

    <strong>{{ trans('frontend/profile/show.labels.gender') }}</strong>:<br/>
    @if(!empty($profile->gender))
        {{ $profile->gender }}
    @else
        -
    @endif

    <hr/>

    <strong>{{ trans('frontend/profile/show.labels.country') }}</strong>:<br/>
    {{ $profile->country->name }}

    <hr/>

    <strong>{{ trans('frontend/profile/show.labels.city') }}</strong>:<br/>
    @if(!empty($profile->geo_city ))
        {{ $profile->geo_city }}
    @else
        -
    @endif

    <br/><br/>

    <a class="button button_edit"
       href="{{ URL::route('profile.edit', $identifier) }}">{{ trans('frontend/profile/show.buttons.edit') }}</a>

    <a class="button button_success"
       href="{{ URL::route('profile.match',$identifier) }}">{{ trans('frontend/profile/show.buttons.ok') }}</a>
@endsection
