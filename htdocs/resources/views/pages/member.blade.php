@extends("app")

@section("content")

<div class="container">
	<h1>{{ $member->name }}</h1>
	<div class="panel panel-default">
		<div class="panel-body">
			Name: {{ $member->name }}<br>
			Public Email: {{ $member->email_public }}<br>
			Description: {{ $member->description }}<br>
		</div>
	</div>
	<hr>
	<h1>Attended Events</h1>
	<div class="panel panel-default">
		@if(session()->get('authenticated_admin') == "true")
		<table class="table table-bordered table-hover table-clickable panel-body">
		@else
		<table class="table table-bordered panel-body">
		@endif
		<thead>
			<tr>
				<th>Event</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($events as $event)
			@if(session()->get('authenticated_admin') == "true")
		    <tr onclick="location.href='{{ URL::to('/event', $event->id) }}';">
			@else
			<tr>
			@endif
		    	<td>{{ $event->name }}</td>
				<td>{{ $event->time }}</td>
		    </tr>
		@empty
			<tr>
				<td>No Events Attended</td>
				<td></td>
			</tr>
		@endforelse
		</tbody>
		</table>
	</div>
</div>

@stop