<!DOCTYPE HTML>
<html><head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>{{ env('ORG_NAME') }} Account Created</title>
	<style>
	body {
		background-color: #eeda69;
	}
	
	#header {
		width: 100%;
		padding-top: 10px;
		text-align: center;
	}
	#headerImage {
		width: 80px;
		height: 80px;
	}
	
	.message {
		margin-top: 15px;
		background-color: #EEEEEE;
		border-radius: 18px;
		padding: 20px;
		width: 500px;
		margin-left: auto;
		margin-right: auto;
	}
	
	a {
		color: #0043bb;
		text-decoration: none;
	}
	
	a:hover {
		color: #4485ff;
	}
	
	#footer {
		padding: 5px;
		width: 500px;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
	}
	</style>
</head><body>
	<div id="header">
		<img src="{{ Request::root() }}/images/logo_white_square_300.png" id="headerImage">
	</div>
	
	@yield('content')
	
	<div id="footer">
		Sent by <a href="{{ Request::root() }}">{{ env('ORG_NAME') }}</a> (<a href="mailto:{{ env('MAIL_USERNAME') }}">{{ env('MAIL_USERNAME') }}</a>)
	</div>
</body></html>