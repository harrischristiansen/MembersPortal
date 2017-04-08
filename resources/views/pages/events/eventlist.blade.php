@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>Events
		<a href="{{ action('HackathonController@getIndex') }}" class="pull-left"><button type="button" class="btn btn-info btn-sm marginR">Upcoming Hackathons</button></a>
		<a href="https://goo.gl/forms/rTv3z2tRk0qRBsYT2" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Submit Event Suggestion</button></a>
		@can ('permission','events')
		<a href="{{ action('HomeController@getAnvilWifi') }}" class="pull-left"><button type="button" class="btn btn-info btn-sm">Anvil Wifi</button></a>
		<a href="{{ action('EventController@getCreate') }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm marginR">+ Add Event</button></a>
		@endcan
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Location</th>
					<th># Attended</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($events as $event)
			    <tr onclick="location.href='{{ action('EventController@getEvent', $event->id) }}';">
			    	<td>{{ $event->name }}</td>
					<td>{{ $event->event_time->format('M j, Y') }}</td>
			    	<td>{{ $event->location }}</td>
			    	<td>{{ count($event->members) }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop
