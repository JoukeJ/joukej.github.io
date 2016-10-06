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
        @foreach($entity->options as $option)
            <div class="checkbox-option">
                <input type="checkbox" name="answer[]" value="{{ $option->id }}" id="option-{{ $option->id }}" />
                <label for="option-{{ $option->id }}">{{ $option->value }}</label>
            </div>
        @endforeach

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="submit" value="next" />
    </form>
@endsection