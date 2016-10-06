<div class="form-group">
    <label for="required" class="col-sm-2 control-label">{{ trans('survey/entities/edit.required') }}</label>
    <div class="col-sm-10">
        <div class="fg-line">
            <input type="hidden" name="entity_type[required]" value="0" />
            <input type="checkbox" name="entity_type[required]" id="required" value="1" @if(Input::old('entity_type.required',$entity->required)==1) checked @endif />
        </div>
    </div>
</div>