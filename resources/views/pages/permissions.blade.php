@extends("app")

@section("page-title")
Permissions - 
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>Permissions</h3>
	<div class="panel panel-default">
		<table class="table table-bordered panel-body table-hover table-clickable sortableTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th># Users</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($permissions as $permission)
			    <tr onclick="location.href='{{ action('PermissionController@getPermission', $permission->id) }}';">
			    	<td>{{ $permission->name }}</td>
					<td>{{ $permission->description }}</td>
			    	<td>{{ count($permission->members) }}</td>
			    </tr>
			@endforeach
			<form method="post" action="{{ action('PermissionController@postIndex') }}" class="panel-body validate">
				{!! csrf_field() !!}
			    <tr>
			    	<td><input type="text" name="permission_name" placeholder="Permission Name" class="form-control" data-bvalidator="required"></td>
			    	<td><input type="text" name="description" placeholder="Description" class="form-control"></td>
			    	<td><input type="submit" value="Add Credential" class="btn btn-sm btn-primary"></td>
			    </tr>
			</form>
			</tbody>
		</table>
	</div>
</div></div>
@stop