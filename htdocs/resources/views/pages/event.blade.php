@extends("app")

@section("page-title")
{{ $event->nameShort() }} - 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $event->nameShort() ?: "Create Event" }}
		@if ($event->id != 0)
		
		@if (session()->get('authenticated_admin') == "true")
			<a href="{{ action('ReportsController@getEvent', $event->id) }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">Graphs</button></a>
			@if (count($applications))
			<a href="/applications/{{ $event->id }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">{{ count($applications) }} {{ $requiresApplication ? "Applications" : "Registrations" }}</button></a>
			@endif
			<a href="/checkin/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Checkin</button></a>
			<a href="/event-message/{{ $event->id }}" class="pull-right marginR"><button type="button" class="btn btn-primary btn-sm">Send Message</button></a>
		@elseif ($requiresApplication)
			@if ($hasRegistered)
			<button type="button" class="btn btn-primary btn-sm pull-right">Registered</button>
			@else
			<a href="/apply/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Sign Up</button></a>
			@endif
		@elseif (session()->get('authenticated_member'))
			@if ($hasRegistered)
			<button type="button" class="btn btn-primary btn-sm pull-right">Registered</button>
			@else
			<a href="/register/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Register</button></a>
			@endif
		@endif
		
		@endif
	</h3>
	
	@if (session()->get('authenticated_admin') == "true") {{-- Edit Event --}}
	<div class="panel panel-default">
		<form method="post" action="/event/{{ $event->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="privateEvent" class="text-right">Private Event ?</label>
			<div class="input-group">
				<span class="input-group-addon" id="eventNameAria">Event Name</span>
				<input type="text" name="eventName" id="eventName" placeholder="Event Name" value="{{ $event->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires name." aria-describedby="eventNameAria">
				<span class="input-group-addon" id="privateEventAria">
					<input type="checkbox" name="privateEvent" id="privateEvent" value="true" {{ $event->privateEvent ? "checked" : "" }}>
				</span>
			</div>
			<br>
			<label for="date">Date<span class="pull-right">Requires Application?</span></label>
			<div class="input-group">
				<input type="text" name="date" id="date" placeholder="Date" value="{{ $event->date() }}" class="form-control datepicker" data-bvalidator="required,date[yyyy-mm-dd]" data-bvalidator-msg="Event requires date/time.">
				<span class="input-group-addon" id="applicationAria">
					<input type="checkbox" name="requiresApplication" id="requiresApplication" value="true" {{ $event->requiresApplication ? "checked" : "" }}>
				</span>
			</div>
			<br>
			<label for="date">Time</label>
			<div class='form-inline'>
				<select name="hour" id="hour" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="">Hour</option>
					@for ($i = 0; $i < 24; $i++)
					<option value="{{$i}}" {{ ($event->hour()!="-" && $event->hour()==$i) ? "selected":""}}>{{$i}}</option>
					@endfor
				</select>
				<select name="minute" id="minute" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="">Minute</option>
					@for ($i = 0; $i < 60; $i+=15)
					<option value="{{ sprintf("%02d", $i) }}" {{ ($event->minute()!="-" && $event->minute()==$i) ? "selected":""}}>{{ sprintf("%02d", $i) }}</option>
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
	
	@if(count($members) > 0)
	
	<hr>
	
	<h3>Members Attended</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Member</th>
					<th>Year</th>
					<th># Attended Events</th>
					@if (session()->get('authenticated_admin') == "true" && $requiresApplication)
					<th>Checked In By</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@forelse ($members as $member)
			    <tr onclick="location.href='{{ URL::to('/member', $member->id) }}';">
			    	<td>{{ $member->name }}</td>
			    	<td>{{ $member->graduation_year }}</td>
					<td>{{ count($member->events) }}</td>
					@if (session()->get('authenticated_admin') == "true" && $requiresApplication)
					<td style="color: #FFFFFF; background-color: {{ count($event->applications()->where('member_id',$member->id)->get()) ? "green":"red" }};">{{ $member->recorded_by->name }}</td>
					@endif
			    </tr>
			@empty
				<tr>
					<td>No Members Attended</td>
					<td></td>
					<td></td>
				</tr>
			@endforelse
			</tbody>
		</table>
	</div>
	
	@endif
	
	@if(session()->get('authenticated_admin') == "true" && $event->id != 0)
	<a href="/event-delete/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-danger btn-sm">Delete Event</button></a>
	@endif
	@if (session()->get('authenticated_member') && $hasRegistered)
	<a href="/unregister/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-danger btn-sm">Unregister for event</button></a>
	@endif
</div></div>

@stop