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
					<p class="text-center"><img src="{{$player->avatar}}" width="200" height="200"></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>Player profile</h3>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-user-information">
								<tbody>
									<tr>
										<td><b>Name:</b></td>
										<td>{{$player->firstname}} {{$player->lastname}}</td>
									</tr>
									<tr>
										<td><b>Date of birth:</b></td>
										<td>{{$player->dob}}</td>
									</tr>
									<tr>
										<td><b>Gender:</b></td>
										<td>{{$player->gender}}</td>
									</tr>
									<tr>
										<td><b>High School Graduation:</b></td>
										<td>{{$player->year}}</td>
									</tr>
									<tr>
										<td><b>Position:</b></td>
										<td>{{$player->position}}</td>
									</tr>
									<tr>
										<td><b>School:</b></td>
										<td>{{$player->school}}</td>
									</tr>
									<tr>
										<td><b>US Lacrosse #:</b></td>
										<td>{{$player->laxid}}</td>
									</tr>
									<tr>
										<td><b>USL # expiration date:</b></td>
										<td>{{$player->laxid_exp}}</td>
									</tr>
									<tr>
										<td><b>Prefer Uniform #:</b></td>
										<td>{{$player->uniform}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					<h3>Linked to user</h3>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-user-information">
								<tbody>
									<tr>
										<td><b>Name:</b></td>
										<td>{{$player->user->profile->firstname}} {{$player->user->profile->lastname}}</td>
									</tr>
									<tr>
										<td><b>Relationship:</b></td>
										<td>{{$player->relation}}</td>
									</tr>
									<tr>
										<td><b>Email:</b></td>
										<td><a href="mailto:{{$player->user->email}}">{{$player->user->email}}</a></td>
									</tr>
									<tr>
										<td><b>Mobile:</b></td>
										<td>{{$player->user->profile->mobile}}</td>
									</tr>
									<tr>
										<td><b>Member since:</b></td>
										<td>{{$player->user->created_at}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<h3>Player Statistics</h3>
					<br>
					<div class="row">
						<div class="col-md-12">
							<p class="muted"> Coming soon!</p>
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