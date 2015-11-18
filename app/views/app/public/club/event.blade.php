@extends('layouts.public')
@section('content')
<div class="backsplash-event">
	<div class="container">
		<div class="row mask-backgroud">
			<div class="col-md-12">
				<div class="col-md-5 backsplash-text">
					<br />
					<h1> 
						<span class="logo"> <img src="{{$club->logo}}" width="130"></span> 
					</h1>
					<h1 class="club-title"> 
						{{$club->name}}
					</h1>
					<b class="club-subtitle">{{$event->name}}</b>
					@if($event->end == $event->date || !$event->end)
					<p class="club-subtitle">{{$event->date}}</p>
					@else
					<p class="club-subtitle">{{$event->date}} to {{$event->end}}</p>
					@endif
				</div>
				<div class="col-md-6 col-md-offset-1 dark-backgroud">
					<h1>About the Event</h1>
					<p>{{$event->description}}</p>
					<br>
					{{ Form::open(array('action' => array('ClubPublicController@addEventCart', $club->id, $event->id),'method' => 'post')) }}
					<button type="submit" class="btn btn-success btn-outline" href=""> <i class="fa fa-plus fa-lg"></i>&nbsp; Register Player</button>
					<a class="btn btn-default btn-outline" href="{{URL::action('CalendarController@create',$event->id )}}"> <i class="fa fa-calendar-o fa-lg"></i> &nbsp; Add to calendar</a>
					{{ Form::close() }}
					<br><br>
					@if($event->max <= $event->participants()->where('status', '=', 1)->get()->count())
					<b class="text-danger">** Your player will be assign to our waiting list **</b>
					<br><br>
					@endif
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
								<th class="col-md-3"></th>
								<th class="col-md-3"></th>
								<th class="col-md-3"></th>
								<th class="col-md-3"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-right"><b>Event date:</b></td>
								@if($event->end == $event->date || !$event->end)
								<td colspan='3'>{{$event->date}}</td>
								@else
								<td colspan='3'>{{$event->date}} to {{$event->end}}</td>
								@endif
							</tr>
							@if($schedule->count() > 0)
							<tr>
								<td class="text-right"><b>Event schedule:</b></td>
								<td colspan='3'>
									@foreach($schedule as $date => $item)
									@foreach($item as $time)
									{{$time->startTime}} to {{$time->endTime}} &nbsp; | &nbsp; {{$date}}<br>
									@endforeach
									@endforeach
								</td>
							</tr>
							@endif
							
							<tr>
								<td class="text-right"><b>Registration fee:</b></td>
								<td>{{$event->fee}}</td>
							</tr>
							<tr>
								<td class="text-right"><b>Open registration:</b></td>
								<td >{{$event->open}}</td>
								<td class="text-right"><b>Close registration:</b></td>
								<td>{{$event->close}}</td>
							</tr>
							@if($event->early_fee)
							<tr>
								<td class="text-right"><b>Early registration:</b></td>
								<td>{{$event->early_fee}}</td>
								<td class="text-right"><b>Before:</b></td>
								<td>{{$event->early_deadline}}</td>
							</tr>
							@endif
							<tr>
								<td class="text-right"><b>Location:</b></td>
								@if($event->location)
								<td colspan='3'>{{$event->location}}</td>
								@else
								<td colspan='3'>TBD</td>
								@endif
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-md-6">

						{{ Form::open(array('action' => array('ClubPublicController@addEventCart', $club->id, $event->id),'method' => 'post')) }}
						<button type="submit" class="btn btn-primary btn-outline btn-block" href=""> <i class="fa fa-plus fa-lg"></i> Register Player</button>
						{{ Form::close() }}

					</div>
					<div class="col-md-6">
						<a class="btn btn-primary btn-outline btn-block" href="{{URL::action('CalendarController@create',$event->id )}}"> <i class="fa fa-calendar-o fa-lg"></i> Add to calendar</a>
					</div>
				</div>
				
				

			</div>
			<div class="col-md-6">
				<br>
				@if($event->location)
				<div id="map_canvas"> </div>
				@endif
{{-- 				<br>
				<p>Shared registration link</p>
				{{ Form::text('name',Request::root()."/club/$club->id/event/$event->id", array('class' => 'form-control block-input')) }} --}}
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