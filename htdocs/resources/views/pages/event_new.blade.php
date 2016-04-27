@extends("app")

@section("content")

<div class="container">
	<h1>Create Event</h1>
	<div class="panel panel-default">
		<form method="post" action="/event/-1" class="panel-body validate">
			{!! csrf_field() !!}
			<input type="text" name="eventName" id="eventName" placeholder="Name" class="form-control" data-bvalidator="required">
			<Br>
			<input type="text" name="date" id="date" placeholder="Date" class="form-control datepicker" data-bvalidator="required,date[mm/dd/yyyy]">
			<br>
			<input type="text" name="location" id="location" placeholder="Location" class="form-control" data-bvalidator="required">
			<br>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Event URL" class="form-control">
			<br>
			<input type="submit" value="Create Event" class="btn btn-primary">
		</form>
	</div>
</div>

@stop