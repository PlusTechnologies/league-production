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
					<h4 class="club-subtitle">{{$event->name}}</h4>
					<h4 class="club-subtitle">{{$event->date}}</h4>
				</div>
				<div class="col-md-6 col-md-offset-1 dark-backgroud">
					<h1>About Event </h1>
					<p>{{$event->description}}</p>
					<br>
					{{ Form::open(array('action' => array('ClubPublicController@addEventCart', $club->id, $event->id),'method' => 'post')) }}
          	<button type="submit" class="btn btn-default btn-outline" href=""> <i class="fa fa-plus fa-lg"></i> Register Player</button>
          {{ Form::close() }}
          <br>
					<a class="btn btn-default btn-outline" href=""> <i class="fa fa-calendar-o fa-lg"></i> Add to calendar</a>
					<br><br><br>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="section-even">
	<div class="container container-last">
		<div class="row ">
			<div class="col-md-6">
				<h3>{{$event->name}}</h3>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="col-md-4"></th>
								<th class="col-md-2"></th>
								<th class="col-md-3"></th>
								<th class="col-md-3"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-right"><b>Event date:</b></td>
								<td>{{$event->date}}</td>
								<td class="text-right"><b>Event time:</b></td>
								<td>{{$event->startTime}} -  {{$event->endTime}}</td>
							</tr>
							<tr>
								<td class="text-right"><b>Registration fee:</b></td>
								<td>{{$event->fee}}</td>
								<td class="text-right"><b>Open registration:</b></td>
								<td>{{$event->open}}</td>
								
							</tr>
							<tr>
								<td class="text-right"><b>Early registration:</b></td>
								<td>{{$event->early_fee}}</td>
								<td class="text-right"><b>Early registration deadline:</b></td>
								<td>{{$event->early_deadline}}</td>
							</tr>
							<tr>
								<td class="text-right"><b>Close registration:</b></td>
								<td>{{$event->close}}</td>
								<td class="text-right"><b>Location:</b></td>
								<td>{{$event->location}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-md-6">
					<a class="btn btn-primary btn-outline btn-block" href=""> <i class="fa fa-plus fa-lg"></i> Register Player</a>
				</div>
				<div class="col-md-6">
					<a class="btn btn-primary btn-outline btn-block" href=""> <i class="fa fa-calendar-o fa-lg"></i> Add to calendar</a>
				</div>
				</div>
				
				

			</div>
			<div class="col-md-5 col-md-offset-1">
				<br>
				<div id="map_canvas"> </div>
				<br>
				<p>Shared registration link</p>
				{{ Form::text('name',Request::root()."/club/$club->id/event/$event->id", array('class' => 'form-control block-input')) }}
				<br>
			</div>
		</div>
	</div>
</div>
@stop
@section("script")
@include('shared.geomap')
<script type="text/javascript">
</script>
@stop