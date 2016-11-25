@extends("app")

@section("page-title")
Projects - 
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ isset($allProjects) ? "All Projects" : "Your Projects" }}
		
		@can ('admin')
			@if(isset($allProjects))
			<a href="{{ action('ProjectController@getIndex') }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">My Projects</button></a>
			@else
			<a href="{{ action('ProjectController@getAll') }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">All Projects</button></a>
			@endif
		@endif
		@can ('permission', 'credentials')
		<a href="{{ action('CredentialController@getIndex') }}" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">Credentials</button></a>
		@endcan
		<a href="{{ action('ProjectController@getCreate') }}" class="pull-right"><button type="button" class="btn btn-primary btn-sm">+ New Project</button></a>
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Team Members</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($projects as $project)
			    <tr onclick="location.href='{{ action('ProjectController@getProject', $project->id) }}';">
			    	<td>{{ $project->name }}</td>
			    	<td>{{ $project->members->implode("name",", ") }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop