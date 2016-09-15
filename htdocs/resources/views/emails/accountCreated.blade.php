@extends("email")

@section("content")

<div class="message">
	<p>Hi {{ $member->name }},</p>
	<p>Welcome to Purdue Hackers! We hope you enjoyed attending {{ $event->name }}.</p>
	<p>A Purdue Hackers account has been created for you. To set your password: <a href="#">click here</a></p>
</div>

@stop