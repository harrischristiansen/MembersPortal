<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>@yield('page-title') {{ env('ORG_NAME') }}</title>

        <meta name="author" content="Harris Christiansen">
        <meta name="description" content="
		@section('description')
			{{ env('ORG_NAME') }} - Members and Events Portal
		@show
        ">
        <meta name="keywords" content="{{ env('ORG_KEYWORDS') }}">

        <!-- BValidator -->
        <link href="/css/bvalidator.css" rel="stylesheet" type="text/css" />

        <!-- jQuery UI -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Bootstrap IE8 Support -->
	    <!--[if lt IE 9]>
	    	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    <!-- Fonts -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:300' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Lato:400,900" rel="stylesheet" type="text/css">

	    <!-- Portal Site CSS -->
	    <link rel="stylesheet" type="text/css" href="/css/purduehackers.css?v=5">

    </head>
    <body>
	    <script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-84137257-1', 'auto');
			ga('send', 'pageview');
		</script>

	    @include('navbar')

		<div id="pageWrap">

		@if(session()->has('msg'))
		<div class='section alert-section'>
			<div class='section-container'>
				<div class="alert alert-success" role="alert">{{ session()->get('msg') }}</div>
				<?php session()->forget('msg'); ?>
			</div>
		</div>
		@endif
        @if(session()->has('msg-red'))
		<div class='section alert-section'>
			<div class='section-container'>
				<div class="alert alert-danger" role="alert">{{ session()->get('msg-red') }}</div>
				<?php session()->forget('msg-red'); ?>
			</div>
		</div>
		@endif


		@yield('content')

		</div>

		@include('footer')

		<!-- jQuery / jQuery UI -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/jquery.cookie.js"></script>
		<!-- BValidator -->
		<script type="text/javascript" src="/js/jquery.bvalidator-yc.js"></script>
		<!-- Bootstrap JS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!-- Tablesorter -->
		<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
		<!-- EventID -->
		@if (isset($eventID) || isset($event))
		<script type="text/javascript">
			var eventID = {{ isset($eventID) ? $eventID : $event->id }};
		</script>
		@else
		<script type="text/javascript">
			var eventID = 0;
		</script>
		@endif
		<!-- Site JS -->
		<script type="text/javascript" src="/js/membersportal.js?v=3"></script>
		<!-- Page Specific JS -->
		@yield('customJS')
    </body>
</html>
