@extends("app")

@section("content")
<div class="container">
	<h1>
		@if ($checkin == true)
		Checkin
		@else
		Events | {{ env('ORG_NAME') }}
		@endif
		
		@if(session()->get('authenticated_admin') == "true")
		<a href="/event-new" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Add Event</button></a>
		@endif
	</h1>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body" >
		<thead>
			<tr>
				<th>Name</th>
				<th>Date</th>
				<th>Location</th>
				<th># Checked-in</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($events as $event)
			@if ($checkin == true)
		    <tr onclick="location.href='{{ URL::to('/checkin', $event->id) }}';">
			@else
		    <tr onclick="location.href='{{ URL::to('/event', $event->id) }}';">
			@endif
		    	<td>{{ $event->name }}</td>
				<td>{{ $event->event_time->format('M j, Y') }}</td>
		    	<td>{{ $event->location }}</td>
		    	<td>{{ count($event->members) }}</td>
		    </tr>
		@endforeach
		</tbody>
		</table>
	</div>
</div>
@stop