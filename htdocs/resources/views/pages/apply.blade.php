@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Sign Up: {{ $event->name }}</h3>
	<div class="panel panel-default">
		<form method="post" action="/apply/{{ $event->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="major">Major</label>
			<select name="major" id="major" class="form-control" data-bvalidator="required">
				<option value="">Select</option>
				@foreach ($majors as $major)
				<option value="{{ $major->id }}" {{ $authenticatedMember->major_id==$major->id ? "selected":"" }}>{{ $major->name }}</option>
				@endforeach
			</select>
			<br>
			<label for="gender">Gender</label>
			<select name="gender" id="gender" class="form-control" data-bvalidator="required">
				<option value="">Select</option>
				<option value="Female" {{ $authenticatedMember->gender=="Female" ? "selected":"" }}>Female</option>
				<option value="Male" {{ $authenticatedMember->gender=="Male" ? "selected":"" }}>Male</option>
				<option value="Other" {{ $authenticatedMember->gender=="Other" ? "selected":"" }}>Other</option>
				<option value="No" {{ $authenticatedMember->gender=="No" ? "selected":"" }}>Prefer Not To Answer</option>
			</select>
			<br>
			<label for="tshirt">T-Shirt Size</label>
			<select name="tshirt" id="tshirt" class="form-control" data-bvalidator="required">
				<option value="">Select</option>
				<option value="Small" {{ $authenticatedMember->tshirt=="Small" ? "selected":"" }}>Small</option>
				<option value="Medium" {{ $authenticatedMember->tshirt=="Medium" ? "selected":"" }}>Medium</option>
				<option value="Large" {{ $authenticatedMember->tshirt=="Large" ? "selected":"" }}>Large</option>
				<option value="XL" {{ $authenticatedMember->tshirt=="XL" ? "selected":"" }}>Extra Large</option>
			</select>
			<br>
			<label for="dietary">Dietary Restrictions (if any)</label>
			<input type="text" name="dietary" id="dietary" placeholder="Dietary Restrictions" class="form-control">
			<br>
			
			<input type="submit" value="Sign Up" class="btn btn-primary">
		</form>
	</div>
</div></div>

@stop