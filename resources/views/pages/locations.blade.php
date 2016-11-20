@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ env('ORG_NAME') }} Around The Globe
		<a href="{{ action('LocationController@getMap') }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">Map</button></a>
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Location</th>
					<th>City</th>
					<th># Members</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($locations as $location)
			    <tr onclick="location.href='{{ action('LocationController@getLocation', $location->id) }}';">
			    	<td>{{ $location->name }}</td>
					<td>{{ $location->city }}</td>
			    	<td>{{ $location->members()->count() }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop