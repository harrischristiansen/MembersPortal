@extends("app")

@section("content")

<div class="titlePage row">
	<img class="titleImage" src="/images/logo_square_200.png" alt="{{ env('ORG_NAME') }}">
	<p class="titlePageSub">Members</p>
	<h1 class="titlePageTitle">{{ env('ORG_NAME') }}</h1>
	<br>
</div>

@stop