@extends("app")

@section("content")

<div class="container">
	<h1>Checkin - {{ $event->name }}</h1>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="input-group">
				<input type="text" name="memberName" id="memberName" placeholder="Name" class="form-control" aria-describedby="checkinBtn">
				<span class="input-group-addon" id="checkinBtn"><button class="btn btn-primary">Checkin</button></span>
			</div>
		</div>
	</div>
</div>

@stop