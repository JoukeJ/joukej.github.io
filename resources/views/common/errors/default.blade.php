@extends('ttc.backend.layout.master')

@section('content')
    <h3>Error - 500</h3>
    {{ $exception->getMessage() }}
@endsection