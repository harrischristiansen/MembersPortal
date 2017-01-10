@extends("app")

@section("content")

<div class='section about'>
	<div class='section-container'>
		<h1>Who Are We?</h1>
		<h3>
			We are a community of students who collaborate, learn, and build
			kick-ass technical projects.
		</h3>
		<div class='about-img'></div>
		<a href="//eepurl.com/MpyV1">
			<button class='buttn mailing-list'>Join Our Mailing List!</button>
		</a>
	</div>
</div>

<div class='section events'>
	<div class='section-container'>
		<h1>What We Do</h1>
		<div class='content left'>
			<h3>Hackathons</h3>
			<p>
				Hackathons are 36 hour coding competitions and the fastest way to build
				the project of your dreams. We send buses to hackathons across the
				country almost every week. While you're hacking, you can get to know
				representatives from companies like Google, Facebook, Microsoft, and more!
				Plus, every hackathon is filled to the brim with free food and free swag.
			</p>
			<h3>Ignite</h3>
			<p>
				Ignite is a one on one coding bootcamp run by the
				Purdue Hackers team. Incoming freshmen are each paired with an
				upperclassman mentor, and over the course of the semester they're guided
				through the process of creating an awesome technical project.
				Socials are held for the group and demos are shown off at the end
				of the semester. Applications will open in the Fall!
			</p>
			<h3>Social Events</h3>
			<p>
				Every few weeks Purdue Hackers hosts events where people
				hang out, work on projects, share ideas, and learn
				about cool new technologies. We strive to create an environment
				where it's easy to meet some of the students at the forefront of
				innovation at Purdue. We also invite speakers from cutting edge
				companies in the industry to share their experience with us.
			<p>
		</div>
		<div class='event-img'></div>
	</div>
</div>

<div class='section faq'>
	<div class='section-container'>
		<h1>FAQ</h1>
		<div class='faq-img'></div>
		<div class='content right'>
			<h3>What is hacking?</h3>
			<p>
				You&#39;re probably thinking of movies where hackers steal
				secrets from the FBI. That&#39;s not what we&#39;re about.
				To us, hacking means using cutting edge technology to create kick-ass projects.
			<p>
			<h3>I missed the callout!<br>Can I still join?</h3>
			<p>
				Absolutely! You can find all of our events in the Purdue Hackers
				facebook group. No prior knowledge or experience is needed, so come say hi!
			</p>
			<h3>What if I can&#39;t code?</h3>
			<p>
				No problem! As long as you&#39;re passionate about how
				technology can shape the future, you&#39;ll fit right in. We offer
				lots of events to help teach coding skills, including a semester-long
				coding bootcamp called Ignite.
			</p>
			<h3>What&#39;s up with the logo?</h3>
			<p>
				It&#39;s a glider from Conway&#39;s Game of Life. It&#39;s a
				universal symbol for hacking and was chosen because it&#39;s the
				only pattern in the game that can spread life to new areas.
			</p>
		</div>
	</div>
</div>

<div class='section sponsors'>
	<div class='section-container'>
		<h1>What's Up?</h1>
		<h3>Get instant access to some of the brightest minds and technical bad
		asses that inhabit the midwest.</h3>
		<div class='sponsor-img'></div>
		<a href="mailto:purduehackers@gmail.com">
			<button class='buttn sponsor-contact'>Get In Touch!</button>
		</a>
	</div>
</div>

{{--
<div class='section sponsors'>
	<div class='section-container'>
		<h1>Sponsors</h1><br><br>
		<img src="/images/PH/facebook.png" style="height:50px;"><br><br><br>
		<img src="/images/PH/ngc.png" style="height:50px;">
	</div>
</div>
--}}

@stop
