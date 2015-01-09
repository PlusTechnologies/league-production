@extends('layouts.public')
@section('content')
<div class="backsplash-event">
	<div class="container">
		<div class="row mask-backgroud">
			<div class="col-md-12">
				<div class="col-md-5 backsplash-text">
					<br />
					<h1 class="text-center"> 
						<span class="logo"> <img src="{{$club->logo}}" width="90"></span> 
					</h1>
					<h1 class="club-title"> 
						{{$club->name}}
					</h1>
					<h4 class="club-subtitle">Team {{$team->name}}</h4>
				</div>
				<div class="col-md-6 col-md-offset-1 dark-backgroud">
					<h1>About the Team </h1>
					<p>{{$team->description}}</p>
					<br>
					<a  class="btn btn-default btn-outline" href="{{URL::action('ClubPublicController@paymentSelectTeam', array($club->id, $team->id))}}"> Register Player</a>
					<br><br>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="section-even">
	<div class="container container-last">
		<div class="row ">
			<div class="col-md-6">
				<h3>Team {{$team->name}}</h3>
				<div class="table-responsive">
					<table class="table table-user-information">
            <tbody>
              <tr>
                <td class="text-right col-md-4"><b>Club:</b></td>
                <td class="col-md-8">{{$team->club->name}}</td>
              </tr>
              <tr>
                <td class="text-right"><b>Team:</b></td>
                <td>{{$team->name}} | {{$team->program->name}} </td>
              </tr>
              <tr>
                <td class="text-right"><b>Season:</b></td>
                <td>{{$team->season->name}}</td>
              </tr>
              <tr>
                <td class="text-right"><b>Membership Due:</b> </td>
                <td>{{$team->due}}</td>
              </tr>
            </tbody>
          </table>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a  class="btn btn-default btn-outline" href="{{URL::action('ClubPublicController@paymentSelectTeam', array($club->id, $team->id))}}"> Register Player</a>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<br>
				<p>Shared registration link</p>
				{{ Form::text('name',Request::root()."/club/$club->id/team/$team->id", array('class' => 'form-control block-input')) }}
				<br>
			</div>
		</div>
	</div>
</div>
@stop
@section("script")
<script type="text/javascript">
</script>
@stop