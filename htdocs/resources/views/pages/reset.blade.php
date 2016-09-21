@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Reset Your Password</h3>
	<div class="panel panel-default">
		<form method="post" action="/request-reset" class="panel-body validate">
			{{ csrf_field() }}
			<div class="input-group">
				<span class="input-group-addon" id="email">Email: </span>
				<input type="text" id="email" name="email" class="form-control" placeholder="Email" data-bvalidator="required,email">
				<span class="input-group-btn">
					<input class="btn btn-primary" type="submit" onclick="checkinMember();" value="Reset Password">
				</span>
    		</div>
		</form>
	</div>
</div></div>

@stop