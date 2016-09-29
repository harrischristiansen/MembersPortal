@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Checkin - {{ $event->name }}
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> {{ $event->name }}</button></a>
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
			<div class="input-group">
				<span class="input-group-addon" id="memberAttendedTitle">Number Events Attended: </span>
				<input type="text" id="memberAttended" class="form-control" placeholder="Number Events Attended" readonly>
    		</div>
		</div>
	</div>
	<div id="checkinAlerts">
	</div>
</div></div>

@stop