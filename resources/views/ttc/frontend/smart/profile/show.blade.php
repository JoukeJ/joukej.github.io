@extends('ttc.frontend.smart.layout.master')

@section('content')

    <p>Please check your profile below.</p>

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

    <a href="{{ URL::route('profile.match',$profile->identifier) }}">Click here if the information on your profile is <u>correct</u></a>
    <br/>
    <a href="{{ URL::route('profile.edit',$profile->identifier) }}">Click here if you want to edit the information on your profile</a>


@endsection