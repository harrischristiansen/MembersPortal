@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ $event->name }} - Applications
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Event</button></a>
		<button type="button" class="btn btn-primary btn-sm pull-right">{{ count($applications) }} applications</button>
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Year</th>
					<th>Applied On</th>
					<th>Dietary</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($applications as $application)
			    <tr onclick="location.href='{{ URL::to('/member', $application->member->id) }}';">
			    	<td>{{ $application->member->name }}</td>
					<td>{{ $application->member->graduation_year }}</td>
			    	<td>{{ $application->created_at->format('M j, Y') }}</td>
			    	<td>{{ $application->dietary }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop