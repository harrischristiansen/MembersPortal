@extends("app")

@section("page-title")
Join 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Join {{ env('ORG_NAME') }}</h3>
	<div class="panel panel-default">
		<form method="post" action="{{ action('AuthController@postJoin') }}" class="panel-body validate">
			{!! csrf_field() !!}
			<p class="text-muted text-center">Fields marked with an * are required</p>

			<label for="memberName">Full Name *
				<div class="text-right pull-right"><span style="font-size: 8px;">(Restrict your profile to only members)</span> Private Profile</div>
			</label>
			<div class="input-group">
				<input type="text" name="memberName" id="memberName" placeholder="Full Name" value="{{ old('name') }}" class="form-control" data-bvalidator="regex[\w+\s\w+],required" data-bvalidator-msg="Please enter your full name">
				<span class="input-group-addon" id="privateProfileGroup">
					<input type="checkbox" name="privateProfile" id="privateProfile" value="true">
				</span>
			</div>
			<br>
			<label for="email">Account Email *
				<div class="text-right pull-right"><span style="font-size: 8px;">(Stop receiving auto-generated emails)</span> Unsubscribe</div>
			</label>
			<div class="input-group">
				<input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" class="form-control" data-bvalidator="required,email" data-bvalidator-msg="An email is required for your account.">
				<span class="input-group-addon" id="unsubscribedGroup">
					<input type="checkbox" name="unsubscribed" id="unsubscribed" value="true">
				</span>
			</div>
			<br>
			<label for="password">Password *</label>
			<input type="password" name="password" id="password" placeholder="Password" class="form-control" data-bvalidator="required" data-bvalidator-msg="A password is required">
			<br>
			<label for="confirmPassword">Confirm Password *</label>
			<input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form-control" data-bvalidator="required,equalto[password]" data-bvalidator-msg="Password does not match">
			<br>
			<label for="gradYear">Year of Graduation *</label>
			<input type="number" name="gradYear" id="gradYear" placeholder="Graduation Year" class="form-control" data-bvalidator="required,number,between[1900:2100]" data-bvalidator-msg="Please provide your expected year of graduation">
			<br>
			<br>
			<br>
			<label for="picture">Profile Picture (JPG or PNG)</label>
			<input type="file" name="picture" id="picture" class="form-control">
			<br>
			<label for="phone">Cell Phone Number (private, only for text notifications)</label>
			<input type="text" name="phone" id="phone" placeholder="Cell Phone Number" class="form-control" data-bvalidator="minlength[10]" data-bvalidator-msg="Please enter a valid cell phone # (with area code)">
			<br>
			<label for="email_public">Public Email</label>
			<input type="text" name="email_public" id="email_public" placeholder="Public Email" class="form-control" data-bvalidator="email" data-bvalidator-msg="Please enter a valid email address. (Optional)">
			<br>
			<label for="description">Public Message</label>
			<textarea name="description" id="description" class="form-control" placeholder="Public Message"></textarea>
			<br>
			<label for="major">Major</label>
			<select name="major" id="major" class="form-control">
				<option value="">Select</option>
				@isset($majors)
					@foreach ($majors as $major)
						<option value="{{ $major->id }}">{{ $major->name }}</option>
					@endforeach
				@endisset
			</select>
			<br>
			<label for="gender">Gender</label>
			<select name="gender" id="gender" class="form-control">
				<option value="">Select</option>
				<option value="Female">Female</option>
				<option value="Male">Male</option>
				<option value="Other" >Other</option>
				<option value="No">Prefer Not To Answer</option>
			</select>
			<br>
			<br>
			<br>
			<label for="facebook">Facebook Profile</label>
			<input type="text" name="facebook" id="facebook" placeholder="Facebook Profile" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Facebook Profile.">
			<br>
			<label for="github">Github Profile</label>
			<input type="text" name="github" id="github" placeholder="Github Profile" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Github Profile.">
			<br>
			<label for="linkedin">LinkedIn Profile</label>
			<input type="text" name="linkedin" id="linkedin" placeholder="LinkedIn Profile" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your LinkedIn Profile.">
			<br>
			<label for="devpost">Devpost Profile</label>
			<input type="text" name="devpost" id="devpost" placeholder="Devpost Profile" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Devpost Profile.">
			<br>
			<label for="website">Personal Website</label>
			<input type="text" name="website" id="website" placeholder="Personal Website" class="form-control" data-bvalidator="url" data-bvalidator-msg="Please enter a valid URL to your Personal Website.">
			<br>
			<label for="resume">Resume (PDF)</label>
			<input type="file" name="resume" id="resume" class="form-control">
			<br>
			<input type="submit" value="Join" class="btn btn-primary">
		</form>
	</div>
</div></div>

@stop