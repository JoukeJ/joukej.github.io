<div class="form-group" id="options-container">
    @foreach($options as $option)

        <div class="option-group clearfix">
            <label for="" class="col-sm-2 control-label">&nbsp;</label>

            <div class="col-sm-6">
                <input type="text" class="form-control input-sm" id="type_options_{{ $option->id }}"
                       value="{{ Input::old('type_options.'.$option->id,$option->value) }}"
                       placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options[{{ $option->id }}]">
            </div>
            <div class="col-sm-3">
                <select name="skip[{{ $option->id }}]" class="form-control input-sm">

                    <option value="">No skip logic</option>
                    @foreach($entity->getSkipOptions() as $e)
                        <option @if($e->id == Input::old('skip.'.$option->id,$option->getSkipLogicId())) selected @endif value="{{ $e->id }}">{{ $e->entity->renderSkipOption() }}</option>
                    @endforeach
                    <option @if(Input::old('skip.'.$option->id,$option->id)===null) selected @endif value="eos">{{ trans('survey/entities/edit.end_of_survey') }}</option>
                </select>
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;" class="removeButton">{{ trans('common.delete') }}</a>
            </div>
        </div>

    @endforeach

    @foreach(Input::old('type_options_new',[]) as $key => $value)
        <div class="option-group clearfix">
            <label for="" class="col-sm-2 control-label">&nbsp;</label>

            <div class="col-sm-6">
                <input type="text" class="form-control input-sm"
                       value="{{ $value }}"
                       placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options_new[]">
            </div>
            <div class="col-sm-3">
                <select name="skip_new[]" class="form-control input-sm">

                    <option value="">No skip logic</option>
                    @foreach($entity->getSkipOptions() as $e)
                        <option @if($e->id == Input::old('skip_new.'.$key,null)) selected @endif value="{{ $e->id }}">{{ $e->entity->renderSkipOption() }}</option>
                    @endforeach
                    <option @if(Input::old('skip_new.'.$key,null)=='eos') selected @endif value="eos">{{ trans('survey/entities/edit.end_of_survey') }}</option>
                </select>
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;" class="removeButton">{{ trans('common.delete') }}</a>
            </div>
        </div>
    @endforeach
</div>

<div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <a href="Javascript:;" onclick="cloneElementAndAppendTo('#new_option_group','#options-container')">Add option</a>
    </div>
</div>

<div class="option-group clearfix hidden" id="new_option_group">
    <label for="" class="col-sm-2 control-label">&nbsp;</label>

    <div class="col-sm-6">
        <input type="text" class="form-control input-sm"
               placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options_new[]" disabled>
    </div>
    <div class="col-sm-3">
        <select name="skip_new[]" class="form-control input-sm" disabled>

            <option value="">No skip logic</option>
            @foreach($entity->getSkipOptions() as $e)
                <option value="{{ $e->id }}">{{ $e->entity->renderSkipOption() }}</option>
            @endforeach
            <option value="eos">{{ trans('survey/entities/edit.end_of_survey') }}</option>
        </select>
    </div>
    <div class="col-sm-1">
        <a href="Javascript:;" class="removeButton">{{ trans('common.delete') }}</a>
    </div>
</div>