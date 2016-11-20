@extends("app")

@section("page-title")
Checkin - {{ $event->nameShort() }} - 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Checkin - {{ $event->nameShort() }}
		<a href="{{ action('EventController@getEvent', $event->id) }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm marginR"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Event</button></a>
		@if (isset($checkinPhone))
		<a href="{{ action('EventController@getCheckin', $event->id) }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Don't Include Phone #</button></a>
		@else
		<a href="{{ action('EventController@getCheckinPhone', $event->id) }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Include Phone #</button></a>
		@endif
	</h3>
	<div class="panel panel-default">
		<div id="checkinForm" class="panel-body validate" autocomplete="off">
			<div class="input-group">
				<span class="input-group-addon" id="memberNameTitle">Name: </span>
				<input type="text" id="memberName" name="memberName" class="form-control membersautocomplete" placeholder="Member Name" autocomplete="off" data-bvalidator="required" data-bvalidator-msg="Please enter your full name">
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button" onclick="checkinMember();">Checkin</button>
				</span>
    		</div>
    		<br>
			<div class="input-group">
				<span class="input-group-addon" id="memberEmailTitle">Email: </span>
				<input type="text" id="memberEmail" name="memberEmail" class="form-control membersautocomplete" placeholder="Member Email" data-bvalidator="required,email" data-bvalidator-msg="An email is required for your account.">
    		</div>
    		<br>
    		@if (isset($checkinPhone))
			<div class="input-group">
				<span class="input-group-addon" id="memberPhoneTitle">Cell Phone #: </span>
				<input type="text" id="memberPhone" name="memberPhone" class="form-control membersautocomplete" placeholder="Cell Phone Number" data-bvalidator="minlength[10]" data-bvalidator-msg="Please enter a valid cell phone # (with area code)">
    		</div>
    		<span class="pull-left" style="font-size: 9px">Your phone number is kept private and is only used for notifications.</span>
    		<br>
    		@endif
			<div class="input-group">
				<span class="input-group-addon" id="memberAttendedTitle">Number Events Attended: </span>
				<input type="text" id="memberAttended" class="form-control" readonly>
				<span id="hasRegistered" class="input-group-btn"></span>
    		</div>
    		<br>
    		@if (isset($checkinPhone))
			<div class="input-group">
				<span class="input-group-addon" id="graduationYearTitle">Graduation Year: </span>
				<input type="text" id="graduationYear" class="form-control" readonly>
    		</div>
    		<br>
    		@endif
			<button class="btn btn-primary" type="button" onclick="checkinMember();" style="float: right;">Checkin</button>
		</div>
	</div>
	<div id="checkinAlerts">
	</div>
</div></div>

@stop