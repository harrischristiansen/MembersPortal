@extends("email")

@section("content")

<div class="message">
	<p>Hi {{ $member->name }},</p>
	
	{!! $msg !!}
</div>

@stop