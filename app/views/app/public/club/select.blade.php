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
					<h4 class="text-center">{{$event->name}}</h4>
					<h4 class="text-center">{{$event->date}}</h4>
					<br><br><br>
				</div>
				<div class="col-md-6 col-md-offset-1 same-height">
					<h1>Select player participate</h1>
					<br>
					{{ Form::open(array('action' => array('ClubPublicController@doSelectPlayer', $club->id, $event->id),'method' => 'post')) }}
					<div class="row">
						<div class="col-xs-12">
							
							@foreach(Cart::contents() as $key=>$item)
							<div class="form-group">
								<label class="col-sm-3 control-label">Player</label>
								<div class="col-sm-9">
									{{ Form::select('player', $players,'', array('class' => 'form-control') ) }}
									{{ Form::hidden('item', $key) }}
								</div>
							</div>
							@endforeach
							
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-default btn-outline pull-right" href=""> <i class="fa fa-shop fa-lg"></i> Complete Registration</button>
					{{ Form::close() }}
					<br>
					<br><br>
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