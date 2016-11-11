@extends("app")

@section("page-title")
Join 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Join {{ env('ORG_NAME') }}</h3>
	<div class="panel panel-default">
		<form method="post" action="/join" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="memberName">Full Name</label>
			<input type="text" name="memberName" id="memberName" placeholder="Full Name" class="form-control" data-bvalidator="regex[\w+\s\w+],required" data-bvalidator-msg="Please enter your full name">
			<br>
			<label for="email">Email</label>
			<input type="text" name="email" id="email" placeholder="Email" class="form-control" data-bvalidator="required,email" data-bvalidator-msg="Please enter your email">
			<br>
			<label for="password">Password</label>
			<input type="password" name="password" id="password" placeholder="Password" class="form-control" data-bvalidator="required" data-bvalidator-msg="A password is required">
			<br>
			<label for="confirmPassword">Confirm Password</label>
			<input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" class="form-control" data-bvalidator="required,equalto[password]" data-bvalidator-msg="Password does not match">
			<br>
			<label for="gradYear">(Expected) Year of Graduation</label>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" class="form-control" data-bvalidator="required,number,between[1900:2100]" data-bvalidator-msg="Please provide your expected year of graduation">
			<br>
			<input type="submit" value="Join" class="btn btn-primary">
		</form>
	</div>
</div></div>

@stop