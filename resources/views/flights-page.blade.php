@extends('layout')

@section('title')
	VC Flights
@endsection

@section('content')
	
	<h1 class="fw-bold">All Flights</h1>
	<p>All flights served by <span class="fw-bold">VC Airlines</span></p>
	<table class="table mt-3">
		<thead>
			<tr>
				<th>ID</th>
				<th>Departure</th>
				<th>Arival</th>
				<th>Departs on</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($flights as $flight)
				<tr>
						<td>{{ $flight->flight_number }}</td>
						<td class="fw-bold">{{ $airport->name }}</td>
						<td>{{ $airport->city }}</td>
						<td>{{ $airport->country }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection