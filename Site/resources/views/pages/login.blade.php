@extends("app")

@section("content")

<div class="titlePage">
	<p class="titlePageSub">Members</p>
	<h1 class="titlePageTitle">Purdue Hackers</h1>
	
	<form method="post" action="/login">
		{!! csrf_field() !!}
		<input type="password" name="password" id="password" placeholder="Password">
		<input type="submit" value="Sign In">
	</form>
	
</div>

@stop