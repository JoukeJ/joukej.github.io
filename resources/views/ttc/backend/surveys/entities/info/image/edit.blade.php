<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.description') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <textarea name="entity_type[description]" class="form-control input-sm" placeholder="{{ trans('survey/entities/edit.description') }}">{{ Input::old('entity_type.description',$entity->description) }}</textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.image') }}</label>

    <div class="col-sm-5">
        <div class="fg-line">
            <input type="file" name="path" />
        </div>
    </div>
    <div class="col-sm-5">
        <img width="100%" src="{{ URL::route('storage.image',$entity->identifier) }}" />
    </div>
</div>