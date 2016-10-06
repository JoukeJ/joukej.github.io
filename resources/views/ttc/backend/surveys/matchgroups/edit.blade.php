@extends('ttc.backend.layout.master')

@section('content')
	@errors

	<div class="card">
		<div class="card-header">
			<h2>{{ trans('survey/matchgroups/edit.title') }}</h2>
		</div>
		<div class="card-body">
			<form action="{{ URL::route('survey.matchgroups.update',[$survey->id,$matchgroup->id]) }}" method="post"
			      class="form-horizontal" role="form">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="_method" value="put">
				<input type="hidden" name="redirect" value="{{ Input::old('redirect',URL::previous()) }}">
				<input type="hidden" name="survey_id" value="{{ $survey->id }}">

				<div class="form-group">
					<label for="" class="col-sm-2 control-label">{{ trans('survey/matchgroups/edit.name') }}</label>

					<div class="col-sm-10">
						<div class="fg-line">
							<input type="text" class="form-control input-sm" id="survey.matchgroup.name"
							       value="{{ Input::old('name',$matchgroup->name) }}"
							       placeholder="{{ trans('survey/matchgroups/edit.name') }}" name="name">
						</div>
					</div>
				</div>

				<div class="form-group">
					@foreach($matchgroup->rules as $rule)
						<input type="hidden" name="rules[{{ $rule->id }}][id]" value="{{ $rule->id }}">
						<input type="hidden" name="rules[{{ $rule->id }}][matchgroup_id]" value="{{ $matchgroup->id }}">

						<div class="option-group clearfix">
							<label for="" class="col-sm-2 control-label">&nbsp;</label>

							<div class="col-sm-9">
								<div class="col-sm-4">
									<input type="hidden" name="rules[{{ $rule->id }}][attribute]"
									       value="{{ $rule->attribute }}">
									{{ ucfirst($rule->getShortAttribute()) }}
								</div>
								<div class="col-sm-4">
									<select class="operator form-control" name="rules[{{ $rule->id }}][operator]">
										@foreach(app($attr_all[$rule->getShortAttribute()])->operators as $operator)
											<option
													@if(get_class(app($operator)) == Input::old('rules.'.$rule->id.'.operator',$rule->operator)) selected
													@endif
													data-operator="{{ app($operator)->symbol }}"
													data-index="{{ $rule->getShortAttribute() }}"
													value="{{ get_class(app($operator)) }}">
												{{ app($operator)->name }}
											</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-4">
									@if($rule->getShortAttribute() == "country")
										<select class="values form-control" name="rules[{{ $rule->id }}][values][]">
											<option value="">No filter</option>
											@foreach(\App\TTC\Models\Country::all() as $country)
												<option @if($country->id == Input::old('rules.'.$rule->id.'.values.0',json_decode($rule->values)[0])) selected
												        @endif value="{{ $country->id }}">{{ $country->name }}</option>
											@endforeach
										</select>
									@elseif($rule->getShortAttribute() == "gender")
										<select class="values form-control" name="rules[{{ $rule->id }}][values][]">
											<option value="">No filter</option>
											@foreach(config('ttc.profile.genders') as $gender)
												<option
														@if(Input::old('rules.'.$rule->id.'.values.0',json_decode($rule->values)[0]) == $gender)
														selected
														@endif
														value="{{ $gender }}">{{ $gender }}</option>
											@endforeach
										</select>
									@elseif($rule->getShortAttribute() == "survey")
										<select class="values form-control" name="rules[{{ $rule->id }}][values][]">
											<option value="">No filter</option>
											@foreach(\App\TTC\Models\Survey::all() as $survey)
												<option @if($survey->id == Input::old('rules.'.$rule->id.'.values.0',json_decode($rule->values)[0])) selected
												        @endif value="{{ $survey->id }}">{{ $survey->name }}</option>
											@endforeach
										</select>
									@else

										@if(Input::old('rules.'.$rule->id.'.values',null)!==null)
											@foreach(Input::old('rules.'.$rule->id.'.values') as $key => $value)
												<input type="text"
												       class="{{ $rule->getShortAttribute() }} matchgroup_rule form-control col-sm-6"
												       placeholder="{{ trans('survey/matchgroups/edit.value') }}"
												       name="rules[{{ $rule->id }}][values][]" value="{{ $value }}">
											@endforeach
										@else
											@foreach(json_decode($rule->values) as $index => $values)
												<input type="text"
												       class="{{ $rule->getShortAttribute() }} matchgroup_rule form-control col-sm-6"
												       placeholder="{{ trans('survey/matchgroups/edit.value') }}"
												       name="rules[{{ $rule->id }}][values][]" value="{{ $values }}">
											@endforeach
										@endif
									@endif
								</div>
							</div>
							<div class="col-sm-1">

							</div>
						</div>
					@endforeach

					@foreach($attr_togo as $index => $class)
						<input type="hidden" name="rules[{{ $index }}][id]" value="">
						<input type="hidden" name="rules[{{ $index }}][matchgroup_id]" value="{{ $matchgroup->id }}">
						<div class="option-group clearfix">
							<label for="" class="col-sm-2 control-label">&nbsp;</label>

							<div class="col-sm-9">
								<div class="col-sm-4">
									<input type="hidden" name="rules[{{ $index }}][attribute]" value="{{ $class }}">
									{{ ucfirst($index) }}
								</div>
								<div class="col-sm-4">
									<select name="rules[{{ $index }}][operator]" class="form-control">
										@foreach(app($attr_all[$index])->operators as $operator)
											<option value="{{ app($operator)->symbol }}">{{ app($operator)->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-4">
									<div class="fg-line">
										<input type="text" class="{{ $index }} form-control"
										       placeholder="{{ trans('survey/matchgroups/edit.value') }}"
										       name="rules[{{ $index }}][values][]" value="">
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<a href="{{ URL::route('survey.show', $survey->id) }}"
						   class="btn btn-inverse btn-sm waves-effect waves-button waves-float">
							{{ trans('ui.back') }}
						</a>
						<button type="submit" class="btn btn-primary btn-sm waves-effect waves-button waves-float"
						        name="save" value="1">
							{{ trans('ui.save') }}
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
