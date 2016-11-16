@extends("app")

@section("page-title")
Projects - 
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>{{ isset($allProjects) ? "All Projects" : "Your Projects" }}
		
		@if(session()->get('authenticated_admin') == "true")
			@if(isset($allProjects))
			<a href="/projects" class="pull-left"><button type="button" class="btn btn-primary btn-sm">My Projects</button></a>
			@else
			<a href="/projects-all" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">All Projects</button></a>
			<a href="/credentials" class="pull-left marginR"><button type="button" class="btn btn-primary btn-sm">Credentials</button></a>
			@endif
		@endif
		<a href="/project-new" class="pull-right"><button type="button" class="btn btn-primary btn-sm">+ New Project</button></a>
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
			    <tr onclick="location.href='{{ action('PortalController@getProject', $project->id) }}';">
			    	<td>{{ $project->name }}</td>
			    	<td>{{ $project->members->implode("name",", ") }}</td>
			    </tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div></div>
@stop