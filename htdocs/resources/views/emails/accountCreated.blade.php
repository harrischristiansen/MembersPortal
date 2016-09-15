@extends("email")

@section("content")

<div class="message">
	<p>Hi {{ $member->name }},</p>
	<p>Welcome to {{ env('ORG_NAME') }}! We hope you enjoyed attending {{ $event->name }} on {{ $event->date() }}.</p>
	<p>A {{ env('ORG_NAME') }} account has been created for you. To set your password: <a href="#">click here</a></p>
</div>

@stop