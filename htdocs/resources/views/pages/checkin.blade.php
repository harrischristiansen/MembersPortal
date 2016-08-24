@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Checkin - {{ $event->name }}</h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<input type="text" name="memberName" id="memberName" placeholder="Name" class="form-control membersautocomplete">
			<hr>
			<div class="input-group">
				<span class="input-group-addon" id="selectedMemberTitle">Selected Member: </span>
				<input type="text" id="selectedMember" class="form-control" placeholder="Selected Member" readonly>
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button" onclick="checkinMember();">Checkin</button>
				</span>
    		</div>
    		<br>
			<div class="input-group">
				<span class="input-group-addon" id="selectedMemberTitle">Email: </span>
				<input type="text" id="selectedEmail" class="form-control" placeholder="Selected Member Email" readonly>
    		</div>
    		<br>
			<div class="input-group">
				<span class="input-group-addon" id="selectedMemberTitle">Number Events Attended: </span>
				<input type="text" id="selectedNumber" class="form-control" placeholder="Number Events Attended" readonly>
    		</div>
		</div>
	</div>
	<div id="checkinAlerts">
	</div>
</div></div>

@stop