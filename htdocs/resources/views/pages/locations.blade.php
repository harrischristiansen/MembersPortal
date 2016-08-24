@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ env('ORG_NAME') }} Around The Globe</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body" >
		<thead>
			<tr>
				<th>Location</th>
				<th>City</th>
				<th># Members</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($locations as $location)
		    <tr onclick="location.href='{{ URL::to('/location', $location->id) }}';">
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