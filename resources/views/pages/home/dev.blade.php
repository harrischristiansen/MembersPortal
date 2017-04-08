@extends("app")
@section("page-title", "Dev Team | ")

@section("content")

<div class='section about'>
	<div class='section-container'>
		<h1>Purdue Hackers Dev Team</h1>
		<h3>
			We are a community of students who collaborate, learn, and build
			kick-ass technical projects.
		</h3>
		<div class='about-img'></div>
	</div>
</div>

<div class='section events'>
	<div class='section-container'>
		<h1>What We Do</h1>
		<div class='content left'>
			<h3><a href="http://www.purduehackers.com/">PurdueHackers.com</a></h3>
			<p>
				PurdueHackers.com is a web application for
				Purdue Hackers to manage their members, events, and attendance.
				It also has the opportunity to provide useful features to the Purdue Hackers community,
				such as showcasing projects and helping students find team-mates for school, hackathon, and personal projects.
			</p>
			<h3>Mobile Apps</h3>
			<p>
				A lot of students have expressed interest in Purdue Hackers having an ios/android app.
				Purdue Hackers web application already provides the necessary accounts/data to serve users,
				we just need someone who can build these apps and hook them up to our current API.
			</p>
			<h3>Other Projects</h3>
			<p>
				There are tons of other development projects which Purdue Hackers organizes.
				This includes technical workshops, and events such as Purdue Hackers Battleship and Purdue Hackers Tron.
				Purdue Hackers is a community that is open to ideas for future events. If you have an idea,
				come to the next weekly PH Organizer Meeting (Mondays 6:30pm, LWSN B148) and present it.
			<p>
		</div>
		<div class='event-img'></div>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<hr>
		<h1 style="font-size: 5em; color: #e6d36a;">Web-Dev</h1>
		<hr>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<h1>Resources</h1>
		<div class="list-group">
			<a href="https://github.com/harrischristiansen/MembersPortal" class="list-group-item" target="_blank"><b>Project Github Repository:</b> The master repository. Fork your repository from here, and submit pull requests to push your code live.</a>
			<a href="https://github.com/harrischristiansen/MembersPortal/issues" class="list-group-item" target="_blank"><b>GitHub Issues:</b> Submit feature requests, bugs, or anything else to be tracked here.</a>
			<a href="https://discordapp.com/invite/Vkns8pZ" class="list-group-item" target="_blank"><b>Discord Group Chat:</b> General Purdue Hackers Open Organizer/WebDev Group Chat.</a>
		</div>
		<hr>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<h1>The Stack</h1>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="https://laravel.com" target="_blank">
					<img src="https://confluence.jetbrains.com/download/attachments/57288110/laravel.png" style="max-width: 180px;">
				</a>
			</li>
			<li class="list-group-item">
				<a href="http://php.net/manual/en/intro-whatis.php" target="_blank">
					<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/200px-PHP-logo.svg.png" style="max-width: 120px;">
				</a>
				<a href="https://getcomposer.org" target="_blank">
					<img src="https://getcomposer.org/img/logo-composer-transparent5.png" style="max-width: 90px;">
				</a>
				<a href="https://www.mamp.info/en/" target="_blank">
					<img src="http://learn2wordpress.com/images/mamplogo.png" style="max-width: 170px;">
				</a>
			</li>
			<li class="list-group-item">
				<a href="https://www.w3schools.com/html/" target="_blank">
					<img src="https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png" style="max-width: 120px;">
				</a>
				<a href="https://www.w3schools.com/css/" target="_blank">
					<img src="http://david-rodenas.com/assets/images/css3.png" style="max-width: 86px;">
				</a>
				<a href="https://www.codecademy.com/learn/javascript" target="_blank">
					<img src="https://blog.falafel.com/wp-content/uploads/2015/01/JS6_Logo.png" style="max-width: 120px;">
				</a>
			</li>
		</ul>
		<hr>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<h1>Local Environment Setup</h1>
		<ul class="list-group">
			<li class="list-group-item">Download and Install <a href="https://www.mamp.info/en/" target="_blank">MAMP</a></li>
			<li class="list-group-item">Set MAMP Directory to `./public`</li>
			<li class="list-group-item">Create MySQL Database (use a GUI tool such as <a href="http://www.sequelpro.com/" target="_blank">Sequel Pro</a>)</li>
			<li class="list-group-item">Download and Install <a href="https://getcomposer.org/" target="_blank">Composer</a></li>
			<li class="list-group-item">Execute `composer install` in the project directory (`php composer.phar install` if using composer.phar)</li>
			<li class="list-group-item">Execute `php artisan key:generate` in the project directory</li>
			<li class="list-group-item">Open `.env` - Fill in your database config and ORG/ADMIN information</li>
			<li class="list-group-item">Execute `php artisan migrate` in the project directory</li>
			<li class="list-group-item">Execute `mkdir -p storage/framework/sessions` and `mkdir -p storage/framework/views` in the project directory</li>
			<li class="list-group-item">Execute `chmod -R 757 storage` in the project directory</li>
			<li class="list-group-item">Start MAMP Servers</li>
			<li class="list-group-item"><b> ----- Additional ----- </b></li>
			<li class="list-group-item">Apply <a href="/db.sql" target="_blank">our initial db.sql file</a> to your database to fill it with necessary permissions and some test data.</li>
			<li class="list-group-item">Refer to <a href="#" target="_blank">[Devin's Page]</a> for more info, and credentials for pre-created accounts.</li>
		</ul>
		<hr>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<h1>Introduction To The Purdue Hackers Project</h1>
		<ul class="list-group">
			<li class="list-group-item"><b>app/Http/routes.php</b>: Primary URL resolver. Controller URLs are resolved using method name (E.G. EventController::getMessage() is a get request to the EventController(/events) method getMessage(/message) -> URL of /events/message)</li>
			<li class="list-group-item"><b>app/Http/Controllers/</b>: Main code for each portion of the site. Given a specific request, performs logic and returns a view.</li>
			<li class="list-group-item"><b>app/Http/Requests/</b>: Validators for different request types (E.G. LoggedInRequest requires a request to be authenticated, else it will return to the login page.)</li>
			<li class="list-group-item"><b>app/Models/</b>: Various models (objects) which we use for manipulating and storing data in our database. (E.G. Each user who signs up has their own Member object)</li>
			<li class="list-group-item"><b>database/migrations</b>: Laravel migration files for making changes to the database. If you wish to add a new model or field, you will need to write a migration that does so.</li>
			<li class="list-group-item"><b>public/css</b>: CSS files served at purduehackers.com/css/</li>
			<li class="list-group-item"><b>public/js</b>: Javascript files served at purduehackers.com/js/</li>
			<li class="list-group-item"><b>resources/views/pages</b>: The HTML templates for all pages on the website.</li>
		</ul>
		<hr>
	</div>
</div>

<div class='section'>
	<div class='section-container'>
		<h1>Introduction To Web Dev, PHP, and Laravel</h1>
		<div class="list-group">
			<a href="#" class="list-group-item" target="_blank">Will be added after the meeting</a>
		</div>
		<hr>
	</div>
</div>

@stop
