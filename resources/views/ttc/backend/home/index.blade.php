@extends('ttc.backend.layout.master')

@section('content')
    @errors

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Recently created surveys</h2>
                </div>

                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th width="20%">Created</th>
                                <th>Status</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent as $survey)
                                <tr>
                                    <td>
                                        {{ $survey->created_at->format('F jS') }}
                                    </td>
                                    <td>
                                        {{ ucfirst($survey->status) }}
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('survey.show',[$survey->id]) }}">{{ $survey->name }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Open surveys</h2>
                </div>

                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th width="20%">Created</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($open as $survey)
                                <tr>
                                    <td>
                                        {{ $survey->created_at->format('F jS') }}
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('survey.show',[$survey->id]) }}">{{ $survey->name }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
