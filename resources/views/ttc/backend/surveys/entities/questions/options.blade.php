<div class="form-group" id="options-container">
    @foreach($options as $option)

        <div class="option-group clearfix">
            <label for="" class="col-sm-2 control-label">&nbsp;</label>

            <div class="col-sm-9">
                <input type="text" class="form-control input-sm" id="type_options_{{ $option->id }}"
                       value="{{ Input::old('type_options.'.$option->id,$option->value) }}"
                       placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options[{{ $option->id }}]">
            </div>
            <div class="col-sm-1">
                <a href="Javascript:;" class="removeButton">{{ trans('common.delete') }}</a>
            </div>
        </div>

    @endforeach

    @foreach(Input::old('type_options_new',[]) as $value)
        <div class="option-group clearfix">
            <label for="" class="col-sm-2 control-label">&nbsp;</label>

            <div class="col-sm-9">
                <input type="text" class="form-control input-sm"
                       value="{{ $value }}"
                       placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options_new[]">
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

    <div class="col-sm-9">
        <input type="text" class="form-control input-sm"
               placeholder="{{ trans('survey/entities/edit.answer') }}" name="type_options_new[]" disabled>
    </div>
    <div class="col-sm-1">
        <a href="Javascript:;" class="removeButton">{{ trans('common.delete') }}</a>
    </div>
</div>