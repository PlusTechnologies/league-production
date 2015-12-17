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
					<h1>Select Details</h1>
					<br>
					{{ Form::open(array('action' => array('ClubPublicController@doSelectPlayer', $club->id, $event->id),'method' => 'post', 'class'=>'form-horizontal p')) }}
					<div class="row">
						<div class="col-xs-12">
							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Player</label>
								<div class="col-sm-9">
									{{ Form::select('player', $players,'', array('class' => 'form-control') ) }}
									@foreach(Cart::contents() as $key=>$item)
									{{ Form::hidden('item', $key) }}
									@endforeach
								</div>
							</div>

							@if($child->count() > 0)
							<div class="form-group">
								<label class="col-sm-3 control-label">Sub-Group</label>
								<div class="col-sm-9">
									{{ Form::select('event', $child->lists('name','id'),'', array('class' => 'form-control') ) }}
								</div>
							</div>
							@endif
							
							
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<h4>Team's Term of services & Liability waiver </h4>
							<hr />
							<div class="form-group">
								<div class="col-sm-12 club-terms">
									<small>
										{{htmlspecialchars_decode($club->waiver)}}
									</small>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-default process btn-outline pull-right"> <i class="fa fa-shop fa-lg"></i> Complete Registration</button>
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
$( "form.p" ).submit(function( event ) {
			$('.process').prop('disabled', true);
			$('.process').text('');
			$('.process').html('<i class="fa fa-refresh fa-spin"></i>');
		});

</script>
@stop