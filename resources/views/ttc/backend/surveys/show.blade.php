@extends('ttc.backend.layout.master')

@section('content')
	@errors
	<div class="card">
		<div class="card-header">
			<h2>
				Survey


				<a class="btn btn-inverse btn-sm waves-effect waves-button waves-float waves-effect waves-button waves-float pull-right"
				   style="margin-left: 10px;"
				   href="{{ \Auth::user()->getSurveyUrl() }}">back to overview</a>

				<a href="{{ URL::route('surveys.exportsmsdata',$survey->id) }}" class="btn btn-info btn-sm waves-effect waves-button waves-float waves-effect waves-button waves-float pull-right">
					{{ trans('survey/edit.export_sms_data') }}
				</a>

				@if($survey->status === 'draft')
					<a class="btn btn-primary btn-sm waves-effect waves-button waves-float waves-effect waves-button waves-float pull-right"
					   style="margin-left: 10px"
					   href="{{ URL::route('surveys.edit', $survey->id) }}">Edit</a>
				@endif

				<div class="pull-right btn-group">

					@if($survey->status !== 'cancelled')
						@include('ttc.backend.partials.confirm',[
							'title' => trans('survey/edit.cancel_confirm.title'),
							'text' => trans('survey/edit.cancel_confirm.text'),
							'url' => URL::route('surveys.cancel',$survey->id),
							'confirm' => trans('survey/edit.cancel_confirm.confirm'),
							'cancel' => trans('survey/edit.cancel_confirm.cancel'),
							'name' => trans('survey/edit.cancel_confirm.name'),
							'class' => 'btn btn-danger btn-sm waves-effect waves-button waves-float'
						])
					@endif
					@if($survey->status === 'draft')
						@include('ttc.backend.partials.confirm',[
							'title' => trans('survey/edit.open_confirm.title'),
							'text' => trans('survey/edit.open_confirm.text'),
							'url' => URL::route('surveys.open',$survey->id),
							'confirm' => trans('survey/edit.open_confirm.confirm'),
							'cancel' => trans('survey/edit.open_confirm.cancel'),
							'name' => trans('survey/edit.open_confirm.name'),
							'class' => 'btn btn-success btn-sm waves-effect waves-button waves-float'
						])
					@endif

				</div>
			</h2>
		</div>
		<div class="card-body">
			<div class="row">
				<label for="" class="col-sm-2 control-label">Name</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ $survey->name }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Status</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ ucfirst($survey->status) }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Language</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ \App\TTC\Common\Helper::getLanguages()[$survey->language] }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Start date</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ date('D. d-m-Y', strtotime($survey->start_date)) }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">End date</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ date('D. d-m-Y', strtotime($survey->end_date)) }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Priority</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ $survey->priority }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Repeating</label>

				<div class="col-sm-10">
					<div class="fg-line">
						@if($survey->repeat)
							{{ ucfirst($survey->repeat->interval) }},
							ending {{ date('D. d-m-Y', strtotime($survey->repeat->absolute_end_date)) }}
						@else
							Not repeating
						@endif
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Identifier</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ $survey->identifier }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label"></label>

				<div class="col-sm-10">
					<div class="fg-line">
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Created</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ $survey->created_at->format('D. d-m-Y') }}
					</div>
				</div>
			</div>

			<div class="row">
				<label for="" class="col-sm-2 control-label">Updated</label>

				<div class="col-sm-10">
					<div class="fg-line">
						{{ $survey->updated_at->format('D. d-m-Y') }}
					</div>
				</div>
			</div>

			@if($survey->isActive())
				<br>
				<div class="alert alert-success" role="alert">This survey is currently active and will
					end {{ $survey->end_date->format('D. d-m-Y') }}.
				</div>
			@elseif($survey->status === 'open' && $survey->start_date->gt(\Carbon\Carbon::now()))
				<br>
				<div class="alert alert-info" role="alert">This survey is currently open but it will not start
					until {{ $survey->start_date->format('D. d-m-Y') }}.
				</div>
			@elseif($survey->status === 'cancelled')
				<br>
				<div class="alert alert-warning" role="alert">This survey is <strong>cancelled</strong>.</div>
			@endif
		</div>
	</div>

	@if($survey->status === 'open')
		<div class="card">
			<div class="card-header">
				<h2>Statistics

					<a class="btn btn-primary pull-right"
					   href="{{ URL::route('surveys.statistic.export', [$survey->id]) }}">{{ trans('survey/statistics/index.export') }}</a>
				</h2>
			</div>

			<div class="card-body">
				<table class="table">
					<tr>
						<td>
							{{ trans('survey/statistics/index.total_participants') }}
							/ {{ trans('survey/statistics/index.total_unique_participants') }}
						</td>
						<td>
							{{ $statistic->totalParticipants() }} / {{ $statistic->totalUniqueParticipants() }}
						</td>
					</tr>
					<tr>
						<td>
							{{ trans('survey/statistics/index.total_abandoned') }}
							/ {{ trans('survey/statistics/index.total_unique_abandoned') }}
						</td>
						<td>
							{{ $statistic->totalAbandoned() }} / {{ $statistic->totalUniqueAbandoned() }}
						</td>
					</tr>
					<tr>
						<td>
							{{ trans('survey/statistics/index.total_progress') }}
						</td>
						<td>
							{{ $statistic->totalInProgress() }}
						</td>
					</tr>
				</table>
				@if($statistic->totalParticipants() > 0)
					<h4>
						{{ trans('survey/statistics/index.questions') }}
					</h4>

					<table class="table">
						@foreach($survey->entities as $entity)
							@if($entity->isSubclassOf(App\TTC\Models\Survey\Entity\Question\BaseQuestion::class))
								<tr>
									<td>
										{{ $entity->entity->question }}
									</td>
									<td>
										<?php $statisticEntity = $statistic->getEntity($entity); ?>

										<?php $percentages = $statisticEntity->percentages(); ?>

										@if(in_array($entity->entity_type, [\App\TTC\Models\Survey\Entity\Question\Open::class, \App\TTC\Models\Survey\Entity\Question\Text::class]))
											<div style="overflow-y: scroll; overflow-x: initial; width: 200px; height: 100px;">
												{!! implode("<br>---<br>", array_keys($percentages)) !!}
											</div>
										@elseif(in_array($entity->entity_type, [\App\TTC\Models\Survey\Entity\Question\Radio::class, \App\TTC\Models\Survey\Entity\Question\Checkbox::class]))
											@foreach($statisticEntity->countPerAnswer() as $answer => $count)
												{{ $count }} / {{ round($percentages[$answer], 1) }}% - {{ $answer }}
												<br/>
											@endforeach
										@endif
									</td>
								</tr>
							@endif
						@endforeach
					</table>
				@endif
			</div>
		</div>
	@endif

	<div class="card">
		<div class="card-header">
			<h2>
				Entities
			</h2>

			@if($survey->status ==='draft')
				<ul class="actions">
					<li class="dropdown">
						<a href="" data-toggle="dropdown" title="New entity">
							<i class="md md-my-library-add"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">

							@foreach($new_entities as $type => $e)
								<li>
									<a href="{{ URL::route('survey.entities.create',['surveys' => $survey->id,'type' => $type]) }}">
										{{ trans('survey/entities/list.add.'.$type) }}
									</a>
								</li>
							@endforeach

						</ul>
					</li>
				</ul>
			@endif
		</div>

		<div class="card-body">
			<table class="table table condensed table hover table striped table">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ trans('survey/list.name') }}</th>
						<th>Type</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($entities as $n => $e)
						{!! $e->entity->renderSummery($n+1) !!}
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			<h2>Matchgroups</h2>
			@if($survey->status === 'draft')
				<ul class="actions">
					<li>
						<a href="{{ URL::route('survey.matchgroups.create',$survey->id) }}" title="New matchgroup">
							<i class="md md-my-library-add"></i>
						</a>
					</li>
				</ul>
			@endif
		</div>
		<div class="card-body">
			@if(sizeof($survey->matchgroups) === 0)
				<p>
					There are no matchgroups configured.
					@if($survey->status === 'draft')
						Click <a href="{{ URL::route('survey.matchgroups.create',$survey->id) }}">here</a> to add one.
					@endif
				</p>
			@else
				<table class="table table condensed table hover table striped table">
					<thead>
						<tr>
							<th>{{ trans('survey/list.name') }}</th>
							<th>Rules</th>

							@if($survey->status === 'draft')
								<th></th>
								<th></th>
							@endif
						</tr>
					</thead>
					<tbody>

						@foreach($survey->matchgroups as $m)
							<tr>
								<td>{{ $m->name }}</td>
								<td>{{ $m->getShortDescription() }}</td>

								@if($survey->status === 'draft')
									<td>
										<a href="{{ URL::route('survey.matchgroups.edit', [$survey->id, $m->id]) }}">Edit</a>
									</td>
									<td>
										@delete(['url' => URL::route('survey.matchgroups.destroy', [$survey->id, $m->id]), 'title' => 'Delete this matchgroup?', 'name' => 'Delete'])
									</td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif

		</div>
	</div>

	<div>
		<a class="btn btn-inverse btn-sm waves-effect waves-button waves-float waves-effect waves-button waves-float"
		   href="{{ \Auth::user()->getSurveyUrl() }}">back to overview</a>
	</div>
@endsection
