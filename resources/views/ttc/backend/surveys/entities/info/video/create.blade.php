<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.description') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <textarea name="entity_type[description]" class="form-control input-sm" placeholder="{{ trans('survey/entities/edit.description') }}">{{ Input::old('entity_type.description',$entity->description) }}</textarea>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.video.service') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <select name="entity_type[service]">
                @foreach(config('ttc.survey.entity.video.services') as $service => $serviceFormat)
                    <option @if(Input::old('entity_type.service',null)==$service) selected @endif value="{{ $service }}">{{ $service }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="" class="col-sm-2 control-label">{{ trans('survey/entities/edit.video.url') }}</label>

    <div class="col-sm-10">
        <div class="fg-line">
            <input type="text" class="form-control input-sm" id="url"
                   value="{{ Input::old('entity_type.url',$entity->url) }}"
                   placeholder="{{ trans('survey/entities/edit.video.url') }}" name="entity_type[url]">
        </div>
    </div>
</div>

@include('ttc.backend.surveys.entities.partials.afterId',['entities' => $entities])