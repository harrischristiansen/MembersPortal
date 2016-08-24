@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Create Event</h3>
	<div class="panel panel-default">
		<form method="post" action="/event/-1" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="eventName">Event Name</label>
			<input type="text" name="eventName" id="eventName" placeholder="Name" class="form-control" data-bvalidator="required">
			<br>
			<label for="date">Date</label>
			<input type="text" name="date" id="date" placeholder="Date" class="form-control datepicker" data-bvalidator="required,date[yyyy-mm-dd]">
			<br>
			<label for="date">Time</label>
			<div class='form-inline'>
				<select name="hour" id="hour" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="" selected>Hour</option>
					@for ($i = 0; $i < 24; $i++)
					<option value="{{$i}}">{{$i}}</option>
					@endfor
				</select>
				<select name="minute" id="minute" class="form-control" data-bvalidator="required" data-bvalidator-msg="Event requires date/time.">
					<option value="" selected>Minute</option>
					@for ($i = 0; $i < 60; $i+=15)
					<option value="{{$i}}">{{$i}}</option>
					@endfor
				</select>
			</div>
			<br>
			<label for="location">Location</label>
			<input type="text" name="location" id="location" placeholder="Location" class="form-control" data-bvalidator="required">
			<br>
			<label for="facebook">Facebook Event URL</label>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Event URL" class="form-control" data-bvalidator="url">
			<br>
			<input type="submit" value="Create Event" class="btn btn-primary">
		</form>
	</div>
</div></div>

@stop