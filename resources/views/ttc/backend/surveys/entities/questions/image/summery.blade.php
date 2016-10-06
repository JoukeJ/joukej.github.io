@extends('ttc.backend.surveys.entities.summery')
@section('table')
	<td>{{ $entity->question }}</td>
	<td>Ask Image</td>
	<td>
		@if($entity->baseEntity->survey->status === 'draft')
			<a href="{{ URL::route('survey.entities.edit',[$entity->baseEntity->survey->id,$entity->baseEntity->id]) }}">
				<i class="fa fa-pencil"></i>
			</a>
		@endif
	</td>
	<td>
		@if($entity->baseEntity->survey->status === 'draft')
			@include('asimov.partials.delete',[
				'url' => URL::route('survey.entities.destroy', [$entity->baseEntity->survey->id,$entity->baseEntity->id]),
				'text' => trans('common.cannot_be_undone'),
				'title' => trans('survey/entities/list.confirm_delete_text'),
				'name' => '<i class="fa fa-trash"></i>'
			])
		@endif
	</td>
@overwrite
