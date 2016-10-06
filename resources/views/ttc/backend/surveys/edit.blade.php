@extends('ttc.backend.layout.master')

@section('content')
    @errors

    <div class="card">
        <div class="card-header">
            <h2>Edit: <a href="{{ URL::route('survey.show',$survey->id) }}">{{ $survey->name }}</a></h2>
        </div>
        <div class="card-body">
            <form action="{{ URL::route('surveys.update',[$survey->id]) }}" method="post"
                  class="form-horizontal" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
                <input type="hidden" name="redirect" value="{{ Input::old('redirect',URL::previous()) }}">

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.name') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="survey.name"
                                   value="{{ Input::old('name',$survey->name) }}"
                                   placeholder="{{ trans('survey/create.name') }}" name="name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.language') }}</label>

                    <div class="col-sm-10">
                        <div class="fg-line">
                            <select name="language" class="form-control">
                                @foreach(\App\TTC\Common\Helper::getLanguages() as $key => $language)
                                    <option @if($key === Input::old('language',$survey->language)) selected @endif value="{{ $key }}">{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.start_date') }}</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm datetimepicker" id="survey.start_date"
                                   value="{{ Input::old('start_date',$survey->start_date) }}"
                                   placeholder="{{ trans('survey/edit.start_date') }}" name="start_date">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.end_date') }}</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm datetimepicker" id="survey.end_date"
                                   value="{{ Input::old('end_date',$survey->end_date) }}"
                                   placeholder="{{ trans('survey/edit.end_date') }}" name="end_date">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.priority') }}</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm" id="survey.priority"
                                   value="{{ Input::old('priority',$survey->priority) }}"
                                   placeholder="{{ trans('survey/edit.priority') }}" name="priority">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.repeating') }}</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <select name="repeat[interval]" id="repeating_survey" class="form-control">
                                <option value="">{{ trans('survey/edit.not_repeating') }}</option>
                                @foreach(config('ttc.survey.repeating.intervals') as $key => $interval)
                                    <option @if(Input::old('repeating.interval',$repeat['interval'])==$key) selected @endif value="{{ $key }}">{{ $interval }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="repeating_end_date" style="display: @if(Input::old('repeating.interval',$repeat['interval'])!=='') block @else none @endif ;">
                    <label for="" class="col-sm-2 control-label">{{ trans('survey/edit.repeating_absolute_end_date') }}</label>
                    <div class="col-sm-10">
                        <div class="fg-line">
                            <input type="text" class="form-control input-sm datetimepicker" id="repeating.absolute_end_date"
                                   value="{{ Input::old('repeating.absolute_end_date',$repeat['absolute_end_date']) }}"
                                   placeholder="{{ trans('survey/edit.repeating_absolute_end_date') }}" name="repeat[absolute_end_date]">
                        </div>
                    </div>
                </div>

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
