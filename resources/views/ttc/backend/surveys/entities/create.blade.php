@extends('ttc.backend.layout.master')

@section('content')
    @errors

    <div class="card">
        <div class="card-header">
            <h2>{{ $title }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ URL::route('survey.entities.store',[$survey->id]) }}" method="post"
                  class="form-horizontal" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="redirect" value="{{ URL::previous() }}">
                <input type="hidden" name="type" value="{{ $entity->getShortType() }}">
                <input type="hidden" name="entity[survey_id]" value="{{ $survey->id }}" />

                {!! $entity->renderCreateForm($survey) !!}

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{ URL::route('survey.show',[$survey->id]) }}"
                           class="btn btn-inverse btn-sm waves-effect waves-button waves-float">
                            {{ trans('ui.back') }}
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-button waves-float"
                                name="save" value="1">
                            {{ trans('ui.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
