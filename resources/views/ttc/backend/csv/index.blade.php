@extends('ttc.backend.layout.master')

@section('content')
	@errors

	<div class="card">
		<div class="card-header">
			<h2>{{ trans('csv.title') }}</h2>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h3>
						{{ trans('csv.import') }}
					</h3>

					Example:
					<br>
					<img src="/img/csv-import-example.png" width="170" />
					<br>
					<br>
					<form method="post" action="{{ route('csv.import') }}" enctype="multipart/form-data">
						{{ csrf_field() }}

						<input type="file" name="csv" />

						<br>

						<button type="submit" class="btn btn-primary btn-sm waves-effect waves-button waves-float">
							{{ trans('csv.import') }}
						</button>
					</form>
				</div>
				<div class="col-md-6">
					<h3>
						{{ trans('csv.export') }}
					</h3>

					Example:
					<br>
					<img src="/img/csv-export-example.png" width="100%" />
					<br>
					<br>
					<a href="{{ route('csv.export') }}" class="btn btn-primary">
						{{ trans('csv.export') }}
					</a>
				</div>
			</div>
		</div>
	</div>
@endsection
