@extends('ttc.frontend.feature.layout.master')

@section('content')
    <h3>{{ trans('frontend/survey/noSurveys.title') }}</h3>

    <a href="{{ URL::route('profile.show',$profile->identifier) }}" class="button button_success">
        {{ trans('frontend/survey/noSurveys.button') }}
    </a>
@endsection