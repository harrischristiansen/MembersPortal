<nav class="navbar navbar-default navbar-fixed-top">
	<div class="nav-container container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        	<span class="sr-only">Toggle navigation</span>
                        	<span class="icon-bar"></span>
                        	<span class="icon-bar"></span>
                        	<span class="icon-bar"></span>
                        </button>
			<a id='nav-brand' class="navbar-brand" href="/">
			<div class="nav-logo"></div>
			@if(session()->get('authenticated_admin') != "true")
			<span class="nav-name">{{ env('ORG_NAME') }}</span>
			@endif
			</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav navbar-right">
				@if (session()->get('authenticated_member') == "true")
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
