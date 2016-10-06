<option @if(Input::old('afterId')==$entity->baseEntity->id) selected @endif value="{{ $entity->baseEntity->id }}">
    {{ trans('survey/entities/create.after').': '.$entity->question }}
</option>