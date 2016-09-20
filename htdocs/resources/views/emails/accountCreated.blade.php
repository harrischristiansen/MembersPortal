@extends("email")

@section("content")

<div class="message">
	<p>Hi {{ $member->name }},</p>
	
	<p>Welcome to the Purdue Hackers community!</p>
	
	<p>Purdue Hackers is Purdue's largest community for tinkerers, makers, and technology lovers (The word "hacker" refers to someone who "hacks something together" or creates something). Throughout the school year, the Purdue Hackers community takes part in organizing and attending countless awesome events. These events include tech talks by various companies, hack nights, workshops, social nights, going to hackathons all over the country, and more!</p>
	
	<p>We hope you enjoyed attending {{ $event->name }} on {{ $event->dateMonthDay() }} and look forward to seeing you at a future Purdue Hackers event!</p>
	
	<p><b>To finish setting up your Purdue Hackers account (and set your password), click here: <a href="{{ action('PortalController@getReset', [$member->id, $member->reset_token()]) }}">{{ action('PortalController@getReset', [$member->id, $member->reset_token()]) }}</a></b></p>
	
	<p>Also, be sure to join the Purdue Hackers Facebook group and "like" the Facebook page to stay connected with the rest of community and stay updated on all things Purdue Hackers:</p>
	
	<p>Purdue Hackers Page: <a href="https://www.facebook.com/purduehackers">https://www.facebook.com/purduehackers</a></p>
	<p>Purdue Hackers Group: <a href="https://www.facebook.com/groups/purduehackers/">https://www.facebook.com/groups/purduehackers/</a></p>
</div>

@stop