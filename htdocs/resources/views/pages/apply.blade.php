@extends("app")

@section("content")

<div class="container">
	<h1>Apply: {{ $eventName }}</h1>
	<div class="panel panel-default">
		<form method="post" action="/apply" class="panel-body validate">
			{!! csrf_field() !!}
			@if(!$authenticatedMember->name)
			<h3>Account Info</h3><br>
			<label for="memberName">Full Name</label>
			<input type="text" name="memberName" id="memberName" placeholder="Full Name" class="form-control" data-bvalidator="required" data-bvalidator-msg="Please enter your full name">
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
			@endif
			<h3>Application</h3><br>
			<label for="gradYear">Graduation Year</label>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" class="form-control" data-bvalidator="required,number" data-bvalidator-msg="A graduation year is required" value="{{ $authenticatedMember->graduation_year }}">
			<br>
			<label for="gender">Gender</label>
			<select name="gender" id="gender" class="form-control" data-bvalidator="required">
				<option value="-">Select</option>
				<option value="Female" {{ $authenticatedMember->gender()=="Female" ? "selected":"" }}>Female</option>
				<option value="Male" {{ $authenticatedMember->gender()=="Male" ? "selected":"" }}>Male</option>
				<option value="Other" {{ $authenticatedMember->gender()=="Other" ? "selected":"" }}>Other</option>
				<option value="No" {{ $authenticatedMember->gender()=="No" ? "selected":"" }}>Prefer Not To Answer</option>
			</select>
			<br>
			<label for="dietary">Dietary Restrictions (if any)</label>
			<input type="text" name="dietary" id="dietary" placeholder="Dietary Restrictions" class="form-control" data-bvalidator="required" data-bvalidator-msg="Dietary Restrictions">
			<br>
			
			<input type="submit" value="Apply" class="btn btn-primary">
		</form>
	</div>
</div>

@stop