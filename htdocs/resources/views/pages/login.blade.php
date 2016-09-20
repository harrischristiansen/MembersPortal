@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Login</h3>
	
	<div class="panel panel-default">
		<form method="post" action="/login" class="panel-body">
			{!! csrf_field() !!}
			<input type="text" name="email" id="email" placeholder="Email">
			<input type="password" name="password" id="password" placeholder="Password">
			<br><br>
			<input type="submit" value="Sign In">
			<br><br>
			Forgot your password? <a href="/request-reset">Click Here</a>
		</form>
	</div>
</div></div>

@stop