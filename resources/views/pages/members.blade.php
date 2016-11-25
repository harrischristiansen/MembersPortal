@extends("app")

@section("page-title")
Members - 
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>Members
		@can ('permission', 'members')
		<a href="{{ action('ReportsController@getMembers') }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">Graphs</button></a>
		<a href="{{ action('LocationController@getMap') }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm">Map</button></a>
		<a href="#" class="pull-right"><button type="button" class="btn btn-primary btn-sm">{{ count($members) }} members</button></a>
		@can ('permission', 'permissions')
		<a href="{{ action('PermissionController@getIndex') }}" class="pull-right marginR"><button type="button" class="btn btn-primary btn-sm">Edit Permissions</button></a>
		@endcan
		@endcan
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					@can('permission', 'members')
					<th>Picture</th>
					@endcan
					<th>Name</th>
					<th>Year</th>
					<th>Joined</th>
					<th>Events Attended</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($members as $member)
			    <tr onclick="location.href='{{ $member->profileURL() }}';">
				    @can('permission', 'members')
				    <td class="member-icon">
					    @if($member->picture)
					    <img src="{{ $member->picturePath() }}" class="member-icon">
					    @endif
				    </td>
				    @endcan
			    	<td>{{ $member->name }}</td>
					<td>{{ $member->graduation_year }}</td>
			    	<td>{{ $member->created_at->format('M j, Y') }}</td>
			    	<td>{{ $member->publicEventCount() }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop