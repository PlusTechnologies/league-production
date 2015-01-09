@extends('layouts.public')
@section('content')
<div class="container container-last">
	<div id="same-height-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-5 signup-col same-height">
					<br />
					<h1 class="text-center"> 
						<span class="logo"> <img src="{{$club->logo}}" width="90"></span> 
					</h1>
					<h1 class="text-center"> 
						{{$club->name}}
					</h1>
					<h4 class="text-center">Team {{$team->name}}</h4>
					<br><br><br>
				</div>
				<div class="col-md-6 col-md-offset-1 same-height">
					<h1>Select one option:</h1>
					<br><br>
					@if(Session::has('error'))
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="alert alert-dismissable">
									<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
									<p class="text-danger">{{Session::get('error')}}</p>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($notice)
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="alert alert-dismissable">
									<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
									<p class="text-success">{{$notice}}</p>
								</div>
							</div>
						</div>
					</div>
					@endif
					<div class="row">
						<div class="col-sm-6">
							{{Form::open(array('action' => array('ClubPublicController@doPaymentSelectTeam', $club->id,$team->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
							{{Form::hidden('type','full') }}
							<button type="submit" class="tile text-left btn btn-block btn-outline btn-success ">
								<h3 class="title">{{$price}}</h3>
								<p>Full Payment</p>

							</button>
							{{Form::close()}}
						</div>
						<div class="col-sm-6">
							{{Form::open(array('action' => array('ClubPublicController@doPaymentSelectTeam', $club->id,$team->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
							{{Form::hidden('type','plan') }}
							<button type="submit" class="tile btn btn-block btn-outline btn-warning ">
								<h3 class="title">{{$team->plan->initial}}*</h3> 
								<p>+ {{$team->plan->recurring}} per month</p>
							</button>
							{{Form::close()}}
						</div>
					</div>
					<p class="text-muted"><small>* Please read carefully our terms and conditions for this services and refund policy. </small></p>
					<div class="col-sm-6"></div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
@section("script")
<script type="text/javascript">
</script>
@stop