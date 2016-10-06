<div class="form-group">
	<label for="" class="col-sm-2 control-label">{{ trans('survey/entities/create.after_label') }}</label>

	<div class="col-sm-10">
		<div class="fg-line">
			<select name="afterId" class="form-control">
				@foreach($entities->reverse() as $e)
					{!! $e->entity->renderAfterIdOption() !!}
				@endforeach
				<option value="0">{{ trans('survey/entities/create.place_as_first') }}</option>
			</select>
		</div>
	</div>
</div>
