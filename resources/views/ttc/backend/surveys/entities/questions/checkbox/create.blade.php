<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.question') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <input type="text" class="form-control input-sm" id="question"
                   value="{{ Input::old('entity_type.question',$entity->question) }}"
                   placeholder="{{ trans('survey/entities/create.question') }}" name="entity_type[question]">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.description') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <textarea name="entity_type[description]" class="form-control input-sm" placeholder="{{ trans('survey/entities/edit.description') }}">{{ Input::old('entity_type.description',$entity->description) }}</textarea>
        </div>
    </div>
</div>

@include('ttc.backend.surveys.entities.questions.required')

@include('ttc.backend.surveys.entities.partials.afterId',['entities' => $entities])

<h3>{{ trans('survey/entities/edit.options') }}</h3>

@include('ttc.backend.surveys.entities.questions.options',['options' => $entity->options])