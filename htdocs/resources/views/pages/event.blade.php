@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $event->name }}
		@if (session()->get('authenticated_admin') == "true")
		<a href="/checkin/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Checkin</button></a>
		@elseif ($canApply)
			@if ($hasRegistered)
			<button type="button" class="btn btn-primary btn-sm pull-right">Registered</button>
			@else
			<a href="/apply/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Sign Up</button></a>
			@endif
		@elseif ($canRegister)
			@if ($hasRegistered)
			<button type="button" class="btn btn-primary btn-sm pull-right">Registered</button>
			@else
			<a href="/register/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Register</button></a>
			@endif
		@endif
	</h3>
	
	@if (session()->get('authenticated_admin') == "true") {{-- Edit Event --}}
	<div class="panel panel-default">
		<form method="post" action="/event/{{ $event->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="eventName">Event Name</label>
			<input type="text" name="eventName" id="eventName" placeholder="Event Name" value="{{ $event->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires name.">
			<br>
			<label for="date">Date</label>
			<input type="text" name="date" id="date" placeholder="Date" value="{{ $event->date() }}" class="form-control datepicker" data-bvalidator="required,date[yyyy-mm-dd]" data-bvalidator-msg="Event requires date/time.">
			<br>
			<label for="date">Time</label>
			<div class='form-inline'>
				<select name="hour" id="hour" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="">Hour</option>
					@for ($i = 0; $i < 24; $i++)
					<option value="{{$i}}" {{ $event->hour()==$i ? "selected":""}}>{{$i}}</option>
					@endfor
				</select>
				<select name="minute" id="minute" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="">Minute</option>
					@for ($i = 0; $i < 60; $i+=15)
					<option value="{{$i}}" {{ $event->minute()==$i ? "selected":""}}>{{$i}}</option>
					@endfor
				</select>
			</div>
			<br>
			<label for="location">Location</label>
			<input type="text" name="location" id="location" placeholder="Location" value="{{ $event->location }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires location.">
			<br>
			<label for="facebook">Facebook Event URL</label>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Event URL" value="{{ $event->facebook }}" class="form-control" data-bvalidator="url">
			<br>
			<input type="submit" value="Update Event" class="btn btn-primary">
		</form>
	</div>
	@else
	<div class="panel panel-default text-left">
		<div class="panel-body">
			<b>Event Name:</b> {{ $event->name }}<br>
			<b>Event Date:</b> {{ $event->dateFriendly() }}<br>
			<b>Location:</b> {{ $event->location }}<br>
			@if ($event->facebook)
			<b>Facebook Event:</b> <a href="{{ $event->facebook }}">{{ $event->facebook }}</a><br>
			@endif
		</div>
	</div>
	@endif
	
	<hr>
	
	<h3>Members Attended</h3>
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
				<td>{{ count($member->events) }}</td>
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
</div></div>

@stop