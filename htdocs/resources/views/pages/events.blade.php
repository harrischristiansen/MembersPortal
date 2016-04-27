@extends("app")

@section("content")
<div class="container">
	<h1>Events | Purdue Hackers
		<a href="/event-new" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Add Event</button></a>
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
		    <tr onclick="location.href='{{ URL::to('/event', $event->id) }}';">
		    	<td>{{ $event->name }}</td>
				<td>{{ $event->time }}</td>
		    	<td>{{ $event->location }}</td>
		    	<td>0</td>
		    </tr>
		@endforeach
		</tbody>
		</table>
	</div>
</div>
@stop