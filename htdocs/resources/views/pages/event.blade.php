@extends("app")

@section("content")

<div class="container">
	<h1>{{ $event->name }}
		@if(session()->get('authenticated_admin') == "true")
		<a href="/checkin/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Checkin</button></a>
		@endif
	</h1>
	
	<div class="panel panel-default">
	@if (session()->get('authenticated_admin') == "true") {{-- Edit Event --}}
	Edit Event
	@else
		<div class="panel-body">
			Name: {{ $event->name }}<br>
			Date: {{ $event->date }}<br>
			Location: {{ $event->location }}<br>
			Facebook: {{ $event->facebook }}<br>
		</div>
	@endif
	</div>
	
	<hr>
	
	@if(session()->get('authenticated_admin') == "true")
	<h1>Attended Members</h1>
	<div class="panel panel-default">
		@if(session()->get('authenticated_admin') == "true")
		<table class="table table-bordered table-hover table-clickable panel-body">
		@else
		<table class="table table-bordered panel-body">
		@endif
		<thead>
			<tr>
				<th>Member</th>
				<th># Attended Events</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($members as $member)
			@if(session()->get('authenticated_admin') == "true")
		    <tr onclick="location.href='{{ URL::to('/member', $member->id) }}';">
			@else
			<tr>
			@endif
		    	<td>{{ $member->name }}</td>
				<td>0</td>
		    </tr>
		@empty
			<tr>
				<td>No Members Attended</td>
				<td></td>
			</tr>
		@endforelse
		</tbody>
		</table>
	</div>
	@endif
	
	@if(session()->get('authenticated_admin') == "true")
	<a href="/event-delete/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-danger btn-sm">Delete Event</button></a>
	@endif
</div>

@stop