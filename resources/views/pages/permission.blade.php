@extends("app")

@section("page-title")
Permission: {{ $permission->name }} - 
@stop

@section("content")

<div class="section"><div class='section-container'>
	<h3>Permission: {{ $permission->name }}
		<a href="{{ action('PermissionController@getIndex') }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Permissions</button></a>
	</h3>
	
	<div class="panel panel-default text-left">
		<div class="panel-body">
			<div id="profile_intro_text">
				<div id="profile_name">{{ $permission->name }}</div>
				<div id="profile_major">{{ $permission->description }}</div>
				<div id="profile_badges">
					<div class="profile_badge"><div class="profile_badge_title">Users</div>{{ count($members) }}</div>
				</div>
			</div>
		</div>
	</div>
	
	<hr>
	
	<h3>Users</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Member</th>
					<th>Date Added</th>
					<th>Added By</th>
				</tr>
			</thead>
			<tbody>
			@forelse ($members as $member)
			    <tr onclick="location.href='{{ $member->profileURL() }}';">
			    	<td>{{ $member->name }}</td>
			    	<td>{{ $member->graduation_year }}</td>
					<td>{{ $member->recorded_by->name }}</td>
			    </tr>
			@empty
				<tr>
					<td>No Users</td>
					<td></td>
					<td></td>
				</tr>
			@endforelse
			<form method="post" action="{{ action('PermissionController@postAdd',$permission->id) }}" class="panel-body validate">
				{!! csrf_field() !!}
			    <tr>
			    	<td><input type="text" id="memberEmail" name="member_name" placeholder="Add User" class="form-control membersautocomplete" data-bvalidator="required"></td>
			    	<td></td>
			    	<td><input type="submit" value="Add User" class="btn btn-sm btn-primary"></td>
			    </tr>
			</form>
			</tbody>
		</table>
	</div>
	
	<a href="{{ action('PermissionController@getDelete', $permission->id) }}" class="pull-right"><button class="btn btn-sm btn-danger pull-right">Delete Permission</button></a>
	
</div></div>

@stop