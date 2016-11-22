@extends("app")

@section("page-title")
Login - 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Login</h3>
	
	<div class="panel panel-default">
		<form method="post" action="{{ action('AuthController@postLogin') }}" class="panel-body validate">
			{!! csrf_field() !!}
			<input type="text" name="email" id="email" placeholder="Email" value="{{ old('email') }}" data-bvalidator="required,email">
			<input type="password" name="password" id="password" placeholder="Password" data-bvalidator="required">
			<br><br>
			<input type="submit" value="Sign In">
			<br><br>
			<input type="checkbox" name="remember"> Remember Me
			<br><br>
			Forgot your password? <a href="{{ action('AuthController@getForgot') }}">Click Here</a>
		</form>
	</div>
</div></div>

@stop