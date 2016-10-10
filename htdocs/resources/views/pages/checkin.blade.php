@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Checkin - {{ $event->name }}
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm marginR"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Event</button></a>
		@if (isset($checkinPhone))
		<a href="/checkin/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Don't Include Phone #</button></a>
		@else
		<a href="/checkin-phone/{{ $event->id }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Include Phone #</button></a>
		@endif
	</h3>
	<div class="panel panel-default">
		<div class="panel-body">
			{{ csrf_field() }}
			<div class="input-group">
				<span class="input-group-addon" id="memberNameTitle">Name: </span>
				<input type="text" id="memberName" name="memberName" class="form-control membersautocomplete" placeholder="Member Name">
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button" onclick="checkinMember();">Checkin</button>
				</span>
    		</div>
    		<br>
			<div class="input-group">
				<span class="input-group-addon" id="memberEmailTitle">Email: </span>
				<input type="text" id="memberEmail" name="memberEmail" class="form-control membersautocomplete" placeholder="Member Email" >
    		</div>
    		<br>
    		@if (isset($checkinPhone))
			<div class="input-group">
				<span class="input-group-addon" id="memberPhoneTitle">Cell Phone #: </span>
				<input type="text" id="memberPhone" name="memberPhone" class="form-control membersautocomplete" placeholder="Cell Phone Number">
    		</div>
    		<br>
    		@endif
			<div class="input-group">
				<span class="input-group-addon" id="memberAttendedTitle">Number Events Attended: </span>
				<input type="text" id="memberAttended" class="form-control" readonly>
				<span id="hasRegistered" class="input-group-btn"></span>
    		</div>
    		<br>
			<button class="btn btn-primary" type="button" onclick="checkinMember();" style="float: right;">Checkin</button>
		</div>
	</div>
	<div id="checkinAlerts">
	</div>
</div></div>

@stop