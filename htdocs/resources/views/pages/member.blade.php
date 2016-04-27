@extends("app")

@section("content")

<div class="container">
	<h1>{{ $member->name }}</h1>
	
	<div class="panel panel-default">
	@if ($member->id == session()->get('member_id') || session()->get('authenticated_admin') == "true") {{-- Edit Profile --}}
		<form method="post" action="/member/{{ $member->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<input type="text" name="memberName" id="memberName" placeholder="Name" value="{{ $member->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Please enter your name">
			<br>
			<input type="text" name="email" id="email" placeholder="Email" value="{{ $member->email }}" class="form-control" data-bvalidator="required,email" data-bvalidator-msg="Please enter your email">
			<br>
			<input type="text" name="email_public" id="email_public" placeholder="Public Email" value="{{ $member->email_public }}" class="form-control" data-bvalidator="email" data-bvalidator-msg="Please enter a valid email address. (Optional)">
			<br>
			<textarea name="description" id="description" class="form-control" placeholder="Description">{{ $member->description }}</textarea>
			<br>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" value="{{ $member->graduation_year}}" class="form-control" data-bvalidator="required,number" data-bvalidator-msg="A graduation year is required">
			<br>
			<input type="submit" value="Update Profile" class="btn btn-primary">
		</form>
	@else {{-- View Profile --}}
		<div class="panel-body">
			Name: {{ $member->name }}<br>
			Public Email: {{ $member->email_public }}<br>
			Description: {{ $member->description }}<br>
		</div>
	@endif
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