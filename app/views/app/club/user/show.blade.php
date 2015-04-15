@extends('layouts.club')
@section('style')
{{HTML::style('css/helpers/croppic.css')}}
@stop
@section('content')
<div class="container container-last">
	<div id="same-height-wrapper">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="col-md-4 signup-col same-height">
					<h3></h3>
					<p class="text-center"><img src="{{$editUser->profile->avatar}}" width="200" height="200"></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>User profile</h3>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-user-information">
								<tbody>
									<tr>
										<td><b>Name:</b></td>
										<td>{{$editUser->profile->firstname}} {{$editUser->profile->lastname}}</td>
									</tr>
									<tr>
										<td><b>Email:</b></td>
										<td>{{$editUser->email}}</td>
									</tr>
									<tr>
										<td><b>Date of birth:</b></td>
										<td>{{$editUser->profile->dob}}</td>
									</tr>
									<tr>
										<td><b>Mobile:</b></td>
										<td>{{$editUser->profile->mobile}}</td>
									</tr>
									<tr>
										<td><b>Roles:</b></td>
										@foreach($editUser->roles as $role)
										<td>{{$role->name}}</td>
										@endforeach
									</tr>
								</tbody>
							</table>
							<hr>
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<a href="{{URL::action('ClubController@userEdit', $editUser->id)}}" class="btn btn-default btn-outline">Edit User</a>
								</div>
							</div>
						</div>
					</div>					

				</div>
			</div>
		</div>

	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
</script>

@stop