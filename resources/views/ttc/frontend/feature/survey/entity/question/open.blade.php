@extends('ttc.frontend.feature.layout.master')

@section('content')
    <h3>
        {{ $entity->question }}
        @if($entity->required)
            *
        @endif
    </h3>

    <p>{{ $entity->description }}</p>

    @errors

    <form method="post" action="{{ $entity->getRouteUrl($profile) }}">
        <input type="text" name="answer" />

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="submit" value="next" />
    </form>
@endsection