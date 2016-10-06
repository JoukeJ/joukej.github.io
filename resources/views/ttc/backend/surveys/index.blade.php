@extends('ttc.backend.layout.master')

@section('content')
    @errors

    <div class="card">
        <div class="card-header">
            <h2>{{ $title }}</h2>
            <ul class="actions">
                <li>
                    <a href="{{ URL::route('surveys.create') }}" title="Create new survey">
                        <i class="md md-my-library-add"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <table class="table table condensed table hover table striped table" data-bootgrid="true" data-ajax="true"
                   data-url="{{ $bootgridUrl }}">
                <thead>
                    <tr>
                        <th data-column-id="name">{{ trans('survey/list.name') }}</th>
                        <th data-width="100px" data-column-id="status">{{ trans("survey/list.status") }}</th>
                        <th data-width="125px" data-column-id="start_date">{{ trans("survey/list.date_from") }}</th>
                        <th data-width="125px" data-column-id="end_date">{{ trans("survey/list.date_till") }}</th>
                        <th data-width="100px" data-column-id="language">{{ trans("survey/list.language") }}</th>
                        <th data-width="200px" data-column-id="owner" data-sortable="false">{{ trans('survey/list.owner') }}</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
