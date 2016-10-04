@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $event->name }}
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> {{ $event->name }}</button></a>
	</h3>
	
	<div class="panel panel-default text-left">
		<div class="panel-body">
			<b>Event Name:</b> {{ $event->name }}<br>
			<b>Event Date:</b> {{ $event->dateFriendly() }}<br>
			<b>Location:</b> {{ $event->location }}<br>
			@if ($event->facebook)
			<b>Facebook Event:</b> <a href="{{ $event->facebook }}">{{ $event->facebook }}</a><br>
			@endif
		</div>
	</div>
</div></div>

@stop