@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $project->name ? "Project: ".$project->name : "Create Project"}}</h3>
	<div class="panel panel-default">
		<form method="post" action="/project/{{ $project->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="name">Project Name</label>
			<input type="text" name="name" id="name" placeholder="Project Name" class="form-control" data-bvalidator="required" value="{{ $project->name }}">
			<br>
			<label for="description">Description</label>
			<textarea name="description" id="description" placeholder="Project Description" class="form-control" data-bvalidator="required" rows="4">{{ $project->description }}</textarea>
			<br>
			<input type="submit" value="{{ $project->name ? 'Save' : 'Create'}} Project" class="btn btn-primary">
		</form>
	</div>
	<h3>Team Members</h3>
</div></div>

@stop