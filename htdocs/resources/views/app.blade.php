<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    
        <title>Purdue Hackers | Membership Portal</title>
        
        <meta name="author" content="Harris Christiansen">
        <meta name="description" content="Purdue Hackers - Members and Events Portal">
        <meta name="keywords" content="college, university, purdue, hackers, member, members, membership, events, hackathon, hack-a-thon, mlh, boilermake, boilermaker, anvil, boilercamp, lafayette, lawson, LWSN, computer, science">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,900" rel="stylesheet" type="text/css">
        
        <!-- BValidator -->
        <link href="/css/bvalidator.css" rel="stylesheet" type="text/css" />
        
        <!-- jQuery UI -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        
        <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
		<!-- Bootstrap Optional theme -->
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"> -->
		
		<!-- Bootstrap IE8 Support -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	    
	    <!-- Purdue Hackers Site CSS -->
	    <link rel="stylesheet" href="/css/purduehackers.css">

    </head>
    <body style="padding-top: 51px;">
	    
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">Purdue Hackers</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="/members">Members</a></li>
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
		
		<div class="container-fluid">
			@if(session()->has('msg'))
				<br><div class="container"><div class="alert alert-success" role="alert">{{ session()->get('msg') }}</div></div>
				<?php session()->forget('msg'); ?>
			@endif
			@yield('content')
		</div>
		
                
		
		<!-- jQuery / jQuery UI -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<!-- BValidator -->
		<script type="text/javascript" src="/js/jquery.bvalidator-yc.js"></script>
		<!-- Bootstrap JS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!-- Site JS -->
		<script type="text/javascript">
			$('.validate').bValidator();
			$(".datepicker").datepicker();
		</script>
		<!-- Page Specific JS -->
		@yield('customJS')
    </body>
</html>