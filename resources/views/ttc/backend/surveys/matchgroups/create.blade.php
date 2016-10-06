@extends('ttc.backend.layout.master')

@section('content')
	@errors

	<div class="card">
		<div class="card-header">
			<h2>{{ trans('survey/matchgroups/create.title') }}</h2>
		</div>
		<div class="card-body">
			<form action="{{ URL::route('survey.matchgroups.store',[$survey->id]) }}" method="post"
			      class="form-horizontal" role="form">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="redirect" value="{{ Input::old('redirect',URL::previous()) }}">
				<input type="hidden" name="survey_id" value="{{ $survey->id }}">

				<div class="form-group">
					<label for="" class="col-sm-2 control-label">{{ trans('survey/matchgroups/edit.name') }}</label>

					<div class="col-sm-10">
						<div class="fg-line">
							<input type="text" class="form-control input-sm" id="survey.matchgroup.name"
							       value="{{ Input::old('name') }}"
							       placeholder="{{ trans('survey/matchgroups/edit.name') }}" name="name">
						</div>
					</div>
				</div>

				<div class="form-group">
					@foreach($attr_togo as $index => $class)
						<div class="option-group clearfix">
							<label for="" class="col-sm-2 control-label">&nbsp;</label>

							<div class="col-sm-9">
								<div class="col-sm-4">
									<input type="hidden" name="rules[{{ $index }}][attribute]" value="{{ $class }}">
									{{ ucfirst($index) }}
								</div>
								<div class="col-sm-4">
									<select class="operator form-control" name="rules[{{ $index }}][operator]">
										@foreach(app($attr_togo[$index])->operators as $operator)
											<option @if(\Input::old('rules.'.$index.'.operator')==get_class(app($operator))) selected
											        @endif
											        data-operator="{{ app($operator)->symbol }}"
											        data-index="{{ $index }}"
											        value="{{ get_class(app($operator)) }}">
												{{ app($operator)->name }}
											</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-4">
									@if($index == "country")
										<select class="values form-control" name="rules[{{ $index }}][values][]">
											<option value="">no filter</option>
											@foreach(\App\TTC\Models\Country::all() as $country)
												<option @if($country->id == Input::old('rules.'.$index.'.values')) selected
												        @endif value="{{ $country->id }}">{{ $country->name }}</option>
											@endforeach
										</select>
									@elseif($index == "gender")
										<select class="values form-control" name="rules[{{ $index }}][values][]">
											<option value="">no filter</option>
											@foreach(config('ttc.profile.genders') as $gender)
												<option value="{{ $gender }}">{{ $gender }}</option>
											@endforeach
										</select>
									@elseif($index == "age")
										@if(\Input::old('rules.'.$index.'.operator') == App\TTC\MatchMaker\Operator\Between::class)
											<input type="text" class="form-control {{ $index }}"
											       style="float:left;width: 45%; margin-right: 4%;" placeholder="From"
											       name="rules[{{ $index }}][values][]"
											       value="{{ Input::old('rules.'.$index.'.values.0') }}">
											<input type="text" class="form-control {{ $index }}"
											       style="width: 45%; margin-right: 4%;" placeholder="To"
											       name="rules[{{ $index }}][values][]"
											       value="{{ Input::old('rules.'.$index.'.values.1') }}">
										@else
											<input type="text" class="form-control {{ $index }}"
											       placeholder="{{ trans('survey/matchgroups/edit.value') }}"
											       name="rules[{{ $index }}][values][]"
											       value="{{ Input::old('rules.'.$index.'.values.0') }}"/>
										@endif
									@elseif($index == "survey")
										<select class="values form-control" name="rules[{{ $index }}][values][]">
											<option value="">no filter</option>
											@foreach(\App\TTC\Models\Survey::all() as $survey)
												<option @if($survey->id == Input::old('rules.'.$index.'.values')) selected
												        @endif value="{{ $survey->id }}">{{ $survey->name }}</option>
											@endforeach
										</select>
									@endif
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
