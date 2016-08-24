<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">{{ env('ORG_NAME') }}</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				@if(session()->get('authenticated_member') == "true" && session()->get('authenticated_admin') != "true")
				<li><a href="/member/{{ session()->get('member_id') }}">Profile</a></li>
				@endif
				<li><a href="/members">Members</a></li>
				<li><a href="/map">Map</a></li>
				@if(session()->get('authenticated_member') == "true")
					<li><a href="/events">Events</a></li>
					@if(session()->get('authenticated_admin') == "true")
						<li><a href="/anvil-wifi">Anvil Wifi</a></li>
					@endif
					<li><a href="/logout">Logout</a></li>
				@else
					<li><a href="/login">Login</a></li>
					<li><a href="/join">Join</a></li>
				@endif
			</ul>
		</div>
	</div>
</nav>