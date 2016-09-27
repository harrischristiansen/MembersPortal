@extends("app")

@section("content")
<div class="section"><div class='section-container'>
	<h3>Members
		@if(session()->get('authenticated_admin') == "true")
		<button type="button" class="btn btn-primary btn-sm pull-right">{{ count($members) }} members</button>
		@endif
	</h3>
	<div class="panel panel-default">
		<table class="table table-bordered table-hover table-clickable panel-body sortableTable">
			<thead>
				<tr>
					@if(session()->get('authenticated_admin') == "true")
					<th>Picture</th>
					@endif
					<th>Name</th>
					<th>Year</th>
					<th>Joined</th>
					<th>Events Attended</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($members as $member)
			    <tr onclick="location.href='{{ URL::to('/member', $member->id) }}';">
				    @if(session()->get('authenticated_admin') == "true")
				    <td class="member-icon">
					    @if($member->picture)
					    <img src="{{ $member->picturePath() }}" class="member-icon">
					    @endif
				    </td>
				    @endif
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