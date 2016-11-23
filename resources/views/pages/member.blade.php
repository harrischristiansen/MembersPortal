@extends("app")

@section("page-title")
{{ $member->name }} - 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Member - {{ $member->name }}
		@if (Gate::allows('member-matches', $member) || Gate::allows('admin') || isset($setPassword) )
		<a href="{{ action('MemberController@getMemberEdit', $member->username) }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Edit</button></a>
		@endif
	</h3>
	
	<div class="panel panel-default text-left">
		<div class="panel-body">
			@if ($member->picture)
			<img src="{{ $member->picturePath() }}" id="profile_image">
			@endif
			<div id="profile_intro_text">
				<div id="profile_name">{{ $member->name }}</div>
				@if ($member->email_public)
				<a id="profile_email" href="mailto:{{ $member->email_public }}">{{ $member->email_public }}</a>
				@endif
				<div id="profile_major">{{ $member->major->name }} Class of {{ $member->graduation_year }}</div>
				<div id="profile_badges">
					<div class="profile_badge"><div class="profile_badge_title">Events</div>{{ count($member->events) }}</div>
					<div class="profile_badge"><div class="profile_badge_title">Projects</div>{{ count($member->projects) }}</div>
					<div class="profile_badge"><div class="profile_badge_title">Jobs</div>{{ count($locations) }}</div>
				</div>
			</div>
		</div>
	</div>
	
	@if ($member->description)
	<div class="panel panel-default text-left">
		<div class="panel-body">
			{!! nl2br(e($member->description)) !!}
		</div>
	</div>
	@endif
	
	@if ($member->facebook || $member->github || $member->linkedin || $member->devpost || $member->website)
	<div class="panel panel-default text-left">
		<div class="panel-body">
			@if ($member->facebook)
			<b>Facebook Profile:</b> <a href="{{ $member->facebook }}">{{ $member->facebook }}</a><br>
			@endif
			@if ($member->github)
			<b>Github Profile:</b> <a href="{{ $member->github }}">{{ $member->github }}</a><br>
			@endif
			@if ($member->linkedin)
			<b>LinkedIn Profile:</b> <a href="{{ $member->linkedin }}">{{ $member->linkedin }}</a><br>
			@endif
			@if ($member->devpost)
			<b>Devpost Profile:</b> <a href="{{ $member->devpost }}">{{ $member->devpost }}</a><br>
			@endif
			@if ($member->website)
			<b>Personal Website:</b> <a href="{{ $member->website }}">{{ $member->website }}</a><br>
			@endif
		</div>
	</div>
	@endif
	
	<hr>
	
	<h3>Job History</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body">
		<thead>
			<tr>
				<th>Company</th>
				<th>City</th>
				<th>Start Date</th>
				<th>End Date</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($locations as $location)
		    <tr onclick="location.href='{{ action('LocationController@getLocation', $location->location->id) }}';">
		    	<td>{{ $location->location->name }}</td>
		    	<td>{{ $location->location->city }}</td>
				<td>{{ $location->date_start }}</td>
				<td>{{ $location->date_end }}
					@if ($member->id == session()->get('member_id') || Gate::allows('admin'))
					<a href="{{ action('LocationController@getDelete', $location->id) }}" class="btn btn-sm btn-danger pull-right">Remove</a>
					@endif
				</td>
		    </tr>
		@empty
			<tr>
				<td>No Job History</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		@endforelse
		
		@if ($member->id == session()->get('member_id') || Gate::allows('admin'))
		<form method="post" action="{{ action('LocationController@postCreate', $member->id) }}" class="panel-body validate">
			{!! csrf_field() !!}
			<tr>
				<td><input type="text" name="locationName" id="locationName" placeholder="Location Name" class="form-control locationsautocomplete" data-bvalidator="required" data-bvalidator-msg="Location Name Required."></td>
				<td><input type="text" name="city" id="city" placeholder="City" class="form-control citiesautocomplete" data-bvalidator="required" data-bvalidator-msg="City Required."></td>
				<td><input type="text" name="date_start" id="date_start" placeholder="Start Date" class="form-control datepicker" data-bvalidator="required,date[yyyy-mm-dd]" data-bvalidator-msg="Start Date Required."></td>
				<td><input type="text" name="date_end" id="date_end" placeholder="End Date" class="form-control datepicker" data-bvalidator="required,date[yyyy-mm-dd]" data-bvalidator-msg="End Date Required.">
					<br>
					<input type="submit" value="Add Location Record" class="btn btn-primary pull-right">
				</td>
			</tr>
		</form>
		@endif
		
		</tbody>
		</table>
	</div>
	
	<hr>
	
	<h3>Events Attended</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body">
		<thead>
			<tr>
				<th>Event</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($events as $event)
		    <tr onclick="location.href='{{ action('EventController@getEvent', $event->id) }}';">
		    	<td>{{ $event->name }}</td>
				<td>{{ $event->dateFriendly() }}</td>
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
</div></div>

@stop