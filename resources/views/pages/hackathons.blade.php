@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>Upcoming Hackathons</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover panel-body sortableTable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>Location</th>
					<th>Apply By</th>
				</tr>
			</thead>
			<tbody>
				<tr>
			    	<td><a href="http://www.boilermake.org" target="_blank">Boilermake</a></td>
					<td>January 20-22, 2017</td>
			    	<td>Purdue University</td>
			    	<td>Dec 2016</td>
			    </tr>
				<tr>
			    	<td><a href="http://2017w.pennapps.com" target="_blank">PennApps</a></td>
					<td>January 20-22, 2017</td>
			    	<td>UPenn</td>
			    	<td>Dec 2016</td>
			    </tr>
				<tr>
			    	<td><a href="https://www.treehacks.com" target="_blank">Tree Hacks</a></td>
					<td>February 17-19, 2017</td>
			    	<td>Stanford</td>
			    	<td>November 18, 2016</td>
			    </tr>
				<tr>
			    	<td><a href="http://hackillinois.org" target="_blank">Hack Illinois</a></td>
					<td>February 24-26, 2017</td>
			    	<td>UIUC</td>
			    	<td>Dec 2016</td>
			    </tr>
			@foreach ($hackathons as $hackathon)
			    <tr>
			    	<td><a href="{{ $hackathon->website }}" target="_blank">{{ $hackathon->name }}</a></td>
					<td>{{ $hackathon->date }}</td>
			    	<td>{{ $hackathon->location }}</td>
			    	<td>{{ $hackathon->apply }}
				    	@can('permission', 'events')
				    	<a href="{{ action('HackathonController@getDelete', $hackathon->id) }}"><button class="btn btn-xs btn-danger pull-right">Remove</button></a>
				    	@endcan
				    </td>
			    </tr>
			@endforeach
			</tbody>
			@can('permission', 'events')
			<form method="post" action="{{ action('HackathonController@postIndex') }}" class="panel-body validate">
				{!! csrf_field() !!}
			    <tr>
			    	<td><input type="text" name="name" placeholder="Name" class="form-control" data-bvalidator="required"><br>
				    	<input type="text" name="website" placeholder="Website" class="form-control" data-bvalidator="required,url">
			    	</td>
					<td><input type="text" name="date" placeholder="Date" class="form-control" data-bvalidator="required"></td>
			    	<td><input type="text" name="location" placeholder="Location" class="form-control"></td>
			    	<td>
				    	<input type="text" name="apply" placeholder="Apply By" class="form-control"><br>
				    	<input type="submit" value="Add Credential" class="btn btn-sm btn-primary">
			    	</td>
			    </tr>
			</form>
			@endcan
		</table>
	</div>
</div></div>
@stop