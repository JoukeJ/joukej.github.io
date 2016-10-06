@extends('asimov.layout.master')

@section('content')
	<div class="btn-group">
		@foreach($colors as $color => $name)
			<div class="btn color-filter" style="background-color: {{ $color }}" data-color="{{ $color }}">
				{{ $name }}
			</div>
		@endforeach
	</div>
	<hr />
	<div class="row" id="search-results">
		<?php $i = 0; ?>
		@foreach($results as $result)
			<div class="col-sm-4">
				<div class="card" style="background-color: {{ $result->presenter->getSearchColor() }}" data-color="{{ $result->presenter->getSearchColor() }}">
					<a href="{{ $result->presenter->getSearchUrl() }}">
						<div class="card-header">
							<h2>
								{{ $result->presenter->getSearchTitle() }}
							</h2>
							<ul class="actions">
								<i class="icon-large {{ $result->presenter->getSearchIcon() }}"></i>
							</ul>
						</div>
						<div class="card-body card-padding">
							{{ $result->presenter->getSearchBody() }}
						</div>
					</a>
				</div>
			</div>
			@if($i + 1 % 3 == 0)
				</div>
				<div class="row">
			@endif
		@endforeach
	</div>
@endsection
