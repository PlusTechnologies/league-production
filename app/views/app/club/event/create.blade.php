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
					<h3>Create Event</h3>
					<p></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>Create New Event</h3>
					<p></p>
					{{Form::open(array('action' => array('EventoController@store'), 'class'=>'form-horizontal', 'method' => 'post')) }}
					@if($errors->has())
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="alert alert-dismissable">
									<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
									<ul>
										@foreach ($errors->all() as $error) 
										<li class="text-danger">{{$error}}</li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if(Session::has('notice'))
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="alert alert-dismissable">
									<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
									<p class="text-success">{{Session::get('notice')}}</p>
								</div>
							</div>
						</div>
					</div>
					@endif
					<div class="row">
						<div class="col-xs-12">
							<h4>General Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									{{ Form::text('name','', array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Type</label>
								<div class="col-sm-9">
									{{ Form::select('type', $types,'', array('class' => 'form-control') ) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Address</label>
								<div class="col-sm-9">
									{{ Form::text('location',null, array('id'=>'searchGoogleApi','class' => 'form-control')) }}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Calendar Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Start Date</label>
								<div class="col-sm-9">
									{{ Form::text('date',null, array('class' => 'form-control datepicker','placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">End Date</label>
								<div class="col-sm-9">
									{{ Form::text('end',null, array('class' => 'form-control datepicker','placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<h4>Payment Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Fee</label>
								<div class="col-sm-9">
									{{ Form::text('fee',null, array('class' => 'dollar')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Early Bird Fee</label>
								<div class="col-sm-9">
									{{ Form::text('early_fee',null, array('class' => 'dollar')) }}
									<span id="helpBlock" class="help-block">Optional - Leave blank and no discount will be provided.</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Dealine</label>
								<div class="col-sm-9">
									{{ Form::text('early_deadline',null, array('class' => 'form-control datepicker','placeholder'=>'MM/DD/YYYY')) }}
									<span id="helpBlock" class="help-block">Last day of early bird. Required when early bird fee present.</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Registration Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Open</label>
								<div class="col-sm-9">
									{{ Form::text('open',null, array('class' => 'form-control datepicker','placeholder'=>'MM/DD/YYYY')) }}
									<span id="helpBlock" class="help-block">First date to register for the event</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Close</label>
								<div class="col-sm-9">
									{{ Form::text('close',null, array('class' => 'form-control datepicker','placeholder'=>'MM/DD/YYYY')) }}
									<span id="helpBlock" class="help-block">Last date to register for the event</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Max. Participant</label>
								<div class="col-sm-9">
									{{ Form::text('max',null, array('class' => 'form-control')) }}
									<span id="helpBlock" class="help-block">Max. number of participants</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Notes (Optional)</label>
								<div class="col-sm-9">
									{{ Form::textarea('notes' ,null ,array('size' => '30x5', 'class' => 'form-control')) }}
									<span id="helpBlock" class="help-block">This information will be display at the time of registration.</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Status</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									{{ Form::select('status', ['Unavailable','Available'],'', array('class' => 'form-control') ) }}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<a href="{{URL::action('EventoController@index')}}" class="btn btn-default">Cancel</a>
									<button type="submit" class="btn btn-primary btn-outline">Create Event</button>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places') }}
<script type="text/javascript">

$(function () {
	$(".datepicker").kendoDatePicker();
	$(".timepicker").kendoTimePicker();
	$(".datepicker").bind("focus", function () {
		$(this).data("kendoDatePicker").open();
	});

	$(".timepicker").bind("focus", function () {
		$(this).data("kendoTimePicker").open();
	});

	$(".dollar").kendoNumericTextBox({
		format: "c",
		decimals: 2
	});

	//address suggest
	$(function() {
		var input = document.getElementById('searchGoogleApi');
		var autocomplete = new google.maps.places.Autocomplete(input);
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			$('#searchGoogleApi').val(place.formatted_address);
			alert(place.formatted_address);
		});
	});

});
</script>

@stop