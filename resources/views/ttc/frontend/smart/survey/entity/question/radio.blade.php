@extends('ttc.frontend.smart.layout.master')

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
        @foreach($entity->options as $option)
            <input type="radio" name="answer" value="{{ $option->id }}" /> {{ $option->value }}<br/>
        @endforeach

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="submit" value="next" />
    </form>
@endsection