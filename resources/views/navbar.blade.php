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
			<span class="nav-name">{{ env('ORG_NAME') }}</span>
			</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav navbar-right">
				@if (Auth::check())
					<li><a href="{{ action('MemberController@getMember', Auth::user()->username) }}">Profile</a></li>
				@endif
				<li><a href="{{ action('MemberController@getIndex') }}">Members</a></li>
				<li><a href="{{ action('EventController@getIndex') }}">Events</a></li>
				<li><a href="{{ action('HomeController@getCalendar') }}">Calendar</a></li>
				@if(Auth::check())
					<li><a href="{{ action('AuthController@getLogout') }}">Logout</a></li>
				@else
					<li><a href="{{ action('AuthController@getLogin') }}">Login</a></li>
					<li><a href="{{ action('AuthController@getJoin') }}">Join</a></li>
				@endif
			</ul>
		</div>
	</div>
</nav>
