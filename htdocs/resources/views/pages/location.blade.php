@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>{{ $location->name }}</h3>
	
	<div class="panel panel-default">
	@if (session()->get('authenticated_admin') == "true") {{-- Edit Location Information --}}
		<form method="post" action="/location/{{ $location->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="locationName">Location Name</label>
			<input type="text" name="locationName" id="locationName" placeholder="Location Name" value="{{ $location->name }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="Please name is required.">
			<br>
			<label for="city">Location City</label>
			<input type="text" name="city" id="city" placeholder="City" value="{{ $location->city }}" class="form-control" data-bvalidator="required" data-bvalidator-msg="A city is required.">
			<br>
			<input type="submit" value="Update Location" class="btn btn-primary">
		</form>
	@else {{-- View Location Profile --}}
		<div class="panel-body text-left">
			<b>Location Name:</b> {{ $location->name }}<br>
			<b>City:</b> {{ $location->city }}<br>
		</div>
	@endif
	</div>
	
	<hr>
	
	<h3>Members</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body">
		<thead>
			<tr>
				<th>Name</th>
				<th>Start Date</th>
				<th>End Date</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($members as $member)
		    <tr onclick="location.href='{{ URL::to('/member', $member->member->id) }}';">
		    	<td>{{ $member->member->name }}</td>
				<td>{{ $member->date_start }}</td>
				<td>{{ $member->date_end }}</td>
		    </tr>
		@empty
			<tr>
				<td>No Members</td>
				<td></td>
				<td></td>
			</tr>
		@endforelse
		</tbody>
		</table>
	</div>
</div></div>

@stop