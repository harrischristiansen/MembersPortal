@extends("app")

@section("content")

<div class="section"><div class='section-container'>
	<h3>Message: {{ $event->name }}
		<a href="/event/{{ $event->id }}" class="pull-left"><button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Event</button></a>
	</h3>
	
	<div class="panel panel-default">
		<form method="post" action="/event-message/{{ $event->id }}" class="panel-body validate">
			{!! csrf_field() !!}
			<label for="method">Send Method:</label>
			<select name="method" id="method" class="form-control" data-bvalidator="required">
				<option value="" selected> - SELECT - </option>
				<option value="email">Email</option>
				<option value="sms">SMS</option>
			</select>
			<br>
			<label for="target">Send To:</label>
			<select name="target" id="target" class="form-control" data-bvalidator="required">
				<option value="" selected> - SELECT - </option>
				<option value="all">All Members</option>
				<option value="att">Members who attended {{ $event->name }}</option>
				<option value="reg">Members registered for {{ $event->name}}</option>
				<option value="both">Attended and Registered Members</option>
				<option value="not">Members Not Registered for {{ $event->name}}</option>
			</select>
			<br>
			<label for="subject">Subject (email only)</label>
			<input type="text" name="subject" id="subject" placeholder="Subject (email only)" class="form-control" data-bvalidator="required">
			<br>
			<label for="message">Message</label>
			<textarea name="message" id="message" placeholder="Message" class="form-control" data-bvalidator="required" rows="12"></textarea>
			<br>
			<input type="submit" value="Send Message" class="btn btn-primary">
		</form>
	</div>
	<table class="panel panel-default table table-bordered sortableTable">
		<thead>
			<tr>
				<th>Placeholder</th>
				<th>Example</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>@{{name}}</td>
				<td>Member Name</td>
			</tr>
			<tr>
				<td>@{{setpassword}}</td>
				<td>Link to set/reset account password</td>
			</tr>
			<tr>
				<td>@{{register}}</td>
				<td>Link to register for {{ $event->name }}</td>
			</tr>
			<tr>
				<td>@{{link}}http://www.google.com/@{{link-text}}Google@{{/link}}</td>
				<td>Link to URL (email only)</td>
			</tr>
		</tbody>
	</table>
</div></div>

@stop