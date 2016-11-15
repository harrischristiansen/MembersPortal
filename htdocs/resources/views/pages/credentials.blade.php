@extends("app")

@section("page-title")
Credentials - 
@stop

@section("content")
<div class="section"><div class='section-container'>
	<h3>Credentials</h3>
	<div class="panel panel-default">
		<table class="table table-bordered panel-body sortableTable">
			<thead>
				<tr>
					<th>Site</th>
					<th>Username</th>
					<th>Password</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($credentials as $credential)
			    <tr>
			    	<td>{{ $credential->site }}</td>
					<td>{{ $credential->username }}</td>
			    	<td class="obscure">{{ decrypt($credential->password) }}</td>
			    	<td>
				    	{{ $credential->description }}
				    	<a href="{{ action('CredentialController@getDelete', $credential->id) }}"><button class="btn btn-xs btn-danger pull-right">Delete</button></a>
				    	</td>
			    </tr>
			@endforeach
			<form method="post" action="{{ action('CredentialController@postIndex') }}" class="panel-body validate">
				{!! csrf_field() !!}
			    <tr>
			    	<td><input type="text" name="site" placeholder="Site" class="form-control" data-bvalidator="required,url"></td>
					<td><input type="text" name="username" placeholder="Username" class="form-control" data-bvalidator="required"></td>
			    	<td><input type="text" name="password" placeholder="Password" class="form-control" data-bvalidator="required"></td>
			    	<td>
				    	<input type="text" name="description" placeholder="Description" class="form-control"><br>
				    	<input type="submit" value="Add Credential" class="btn btn-sm btn-primary">
			    	</td>
			    </tr>
			</form>
			</tbody>
		</table>
	</div>
</div></div>
@stop