@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Member - {{ $member->name }}</h3>
	
	
	@if ($member->id == session()->get('member_id') || session()->get('authenticated_admin') == "true" || isset($setPassword) ) {{-- Edit Profile --}}
	<div class="panel panel-default">
		<form method="post" action="/member/{{ $member->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="memberName">Full Name</label>
			<input type="text" name="memberName" id="memberName" placeholder="Full Name" value="{{ $member->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Please enter your full name">
			<br>
			<label for="email">Account Email</label>
			<input type="text" name="email" id="email" placeholder="Email" value="{{ $member->email }}" class="form-control" data-bvalidator="required,email" data-bvalidator-msg="An email is required for your account.">
			<br>
			@if (isset($setPassword))
			<label for="password">Password</label>
			<input type="password" name="password" id="password" placeholder="Password" class="form-control" data-bvalidator="required" data-bvalidator-msg="A password is required">
			<input type="hidden" name="reset_token" value="{{ $reset_token }}">
			<br>
			<label for="confirmPassword">Confirm Password</label>
			<input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" class="form-control" data-bvalidator="required,equalto[password]" data-bvalidator-msg="Password does not match">
			<br>
			@endif
			<label for="email_public">Public Email</label>
			<input type="text" name="email_public" id="email_public" placeholder="Public Email" value="{{ $member->email_public }}" class="form-control" data-bvalidator="email" data-bvalidator-msg="Please enter a valid email address. (Optional)">
			<br>
			<label for="description">Public Note</label>
			<textarea name="description" id="description" class="form-control" placeholder="Description">{{ $member->description }}</textarea>
			<br>
			<label for="gradYear">Year of Graduation</label>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" value="{{ $member->graduation_year}}" class="form-control" data-bvalidator="required,number" data-bvalidator-msg="A graduation year is required">
			<br>
			<input type="submit" value="Update Profile" class="btn btn-primary pull-left">
			@if (!isset($setPassword))
			<a href="/reset/{{ $member->id }}/{{ $reset_token_valid }}" class="btn btn-warning pull-right">Reset Password</a>
			@endif
		</form>
	</div>
	@else {{-- View Profile --}}
	<div class="panel panel-default text-left">
		<div class="panel-body">
			<b>Name:</b> {{ $member->name }}<br>
			<b>Email:</b> {{ $member->email_public }}<br>
			<b>Description:</b> {{ $member->description }}<br>
		</div>
	</div>
	@endif
	
	<hr>
	
	<h3>Locations</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body">
		<thead>
			<tr>
				<th>Location</th>
				<th>City</th>
				<th>Start Date</th>
				<th>End Date</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($locations as $location)
		    <tr onclick="location.href='{{ URL::to('/location', $location->location->id) }}';">
		    	<td>{{ $location->location->name }}</td>
		    	<td>{{ $location->location->city }}</td>
				<td>{{ $location->date_start }}</td>
				<td>{{ $location->date_end }}
					@if ($member->id == session()->get('member_id') || session()->get('authenticated_admin') == "true")
					<a href="{{ URL::to('/location-record-delete', $location->id) }}" class="btn btn-sm btn-danger pull-right">Remove</a>
					@endif
				</td>
		    </tr>
		@empty
			<tr>
				<td>No Locations</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		@endforelse
		
		@if ($member->id == session()->get('member_id') || session()->get('authenticated_admin') == "true")
		<form method="post" action="/location-record-new/{{ $member->id }}" class="panel-body validate">
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
		    <tr onclick="location.href='{{ URL::to('/event', $event->id) }}';">
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