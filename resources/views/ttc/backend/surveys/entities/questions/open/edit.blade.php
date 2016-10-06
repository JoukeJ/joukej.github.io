<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.question') }}</label>
    <div class="col-sm-10">
        <div class="fg-line">
            <input type="text" class="form-control input-sm" id="survey.entities.question"
                   value="{{ Input::old('entity_type.question',$entity->question) }}"
                   placeholder="{{ trans('survey/entities/create.question') }}" name="entity_type[question]">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.description') }}</label>
    <div class="col-sm-10">
        <div class="fg-line">
            <textarea class="form-control" id="survey.entities.description" placeholder="{{ trans('survey/entities/edit.description') }}" name="entity_type[description]">{{ Input::old('entity_type.description',$entity->description) }}</textarea>
        </div>
    </div>
</div>

@include('ttc.backend.surveys.entities.questions.required')