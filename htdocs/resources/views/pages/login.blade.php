@extends("app")

@section("content")

<div class="titlePage">
	<h1 class="titlePageTitle">{{ env('ORG_NAME') }}</h1>
	<p class="titlePageText">Login</p>
	
	<form method="post" action="/login">
		{!! csrf_field() !!}
		<input type="text" name="email" id="email" placeholder="Email">
		<input type="password" name="password" id="password" placeholder="Password">
		<br><br>
		<input type="submit" value="Sign In">
	</form>
	
</div>

@stop