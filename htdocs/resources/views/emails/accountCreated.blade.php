<!DOCTYPE HTML>
<html><head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>Purdue Hackers Account Created</title>
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
	
	#message {
		margin-top: 15px;
		background-color: #EEEEEE;
		border-radius: 30px;
		padding: 20px;
		width: 500px;
		margin-left: auto;
		margin-right: auto;
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
		<img src="http://www.purduehackers.com/images/logo_white_square_300.png" id="headerImage">
	</div>
	
	<div id="message">
		<p>Welcome to Purdue Hackers, {{ $member->name }}! We hope you enjoyed attending {{ $event->name }}.</p>
	</div>
	
	<div id="footer">
		&copy; {{ date('Y') }} Purdue Hackers
	</div>
</body></html>