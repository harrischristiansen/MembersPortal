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
		<form method="post" action="/event/{{ $event->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="eventName">Event Name</label>
			<input type="text" name="eventName" id="eventName" placeholder="Event Name" value="{{ $event->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires name.">
			<br>
			<label for="date">Date</label>
			<input type="text" name="date" id="date" placeholder="Date" value="{{ $event->time }}" class="form-control datepicker" data-bvalidator="required,date[mm/dd/yyyy]" data-bvalidator-msg="Event requires date/time.">
			<br>
			<label for="location">Location</label>
			<input type="text" name="location" id="location" placeholder="Location" value="{{ $event->location }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires location.">
			<br>
			<label for="facebook">Facebook Event URL</label>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Event URL" value="{{ $event->facebook }}" class="form-control" data-bvalidator="url">
			<br>
			<input type="submit" value="Update Event" class="btn btn-primary">
		</form>
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
	
	<h1>Members Checked In</h1>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body">
		<thead>
			<tr>
				<th>Member</th>
				<th># Attended Events</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($members as $member)
		    <tr onclick="location.href='{{ URL::to('/member', $member->id) }}';">
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
	
	@if(session()->get('authenticated_admin') == "true")
	<a href="/event-delete/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-danger btn-sm">Delete Event</button></a>
	@endif
</div>

@stop