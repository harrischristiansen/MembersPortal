@extends("app")

@section("content")

<div class="container">
	<h1>Checkin - {{ $event->name }}</h1>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="input-group">
				<input type="text" name="memberName" id="memberName" placeholder="Name" class="form-control">
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button">Checkin</button>
				</span>
			</div>
		</div>
	</div>
</div>

@stop