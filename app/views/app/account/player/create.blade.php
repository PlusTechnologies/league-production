@extends('layouts.account')
@section('style')
{{HTML::style('css/helpers/croppic.css')}}
@stop
@section('content')
<div class="container container-last">
	<div id="same-height-wrapper">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="col-md-4 signup-col same-height">
					<h1>Add Player</h1>
					<br><br>
					<p>
						<b class="text-danger">Important:</b> This page is intended for parents or legal guardian only. <br><br>
						<b>Instructions:</b> <br>
						Step 1 - Add new player information. <br>
						Step 2 - Click save.
					</p>
					<p>Privacy questions?</p>
					<p>Click here for the <a href="">Privacy Policy</a></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>New Player</h3>
					<p></p>
					{{Form::open(array('action' => array('PlayerController@store'), 'class'=>'form-horizontal', 'method' => 'post')) }}
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
					<div class="row">
						<div class="col-xs-12">
							<h4>Player Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First name</label>
								<div class="col-sm-9">
									{{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'First name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last name</label>
								<div class="col-sm-9">
									{{ Form::text('lastname',null, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Player's position</label>
								<div class="col-sm-9">
									{{ Form::text('position',null, array('class' => 'form-control', 'placeholder'=>'Position')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Relationship</label>
								<div class="col-sm-9">
									{{ Form::text('relation',null, array('class' => 'form-control', 'placeholder'=>'Ex. father, mother, legal guardian, etc.')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">DOB</label>
								<div class="col-sm-9">
									{{ Form::text('dob',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Gender</label>
								<div class="col-sm-9">
									{{Form::select('gender', array('M' => 'Male', 'F' => 'Female'),null, array('class'=>'form-control'));}}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Graduation year</label>
								<div class="col-sm-9">
									{{ Form::selectRange('year', 2015, 2035, null, array('class'=>'form-control'));}}
									<span id="helpBlock" class="help-block"><small>High School Graduation Year</small></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Roster picture</label>
								<div class="col-sm-9">
									<div id="upimageclub"></div>
									<input type="hidden" id="croppic" name="avatar" value="/img/default-avatar.png">
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<h4>Player Preference</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email </label>
								<div class="col-sm-9">
									{{ Form::text('email','', array('class' => 'form-control', 'placeholder'=>'Email')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									{{ Form::text('mobile','', array('class' => 'form-control mobile', 'placeholder'=>'Mobile')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">School </label>
								<div class="col-sm-9">
									{{ Form::text('school','', array('class' => 'form-control', 'placeholder'=>'School Name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">US Lacrosse #</label>
								<div class="col-sm-9">
									{{ Form::text('laxid','', array('class' => 'form-control', 'placeholder'=>'US Lax ID')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">USL # exp. date</label>
								<div class="col-sm-9">
									{{ Form::text('laxid_exp','', array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Prefer Uniform #</label>
								<div class="col-sm-9">
									{{ Form::text('uniform','', array('class' => 'form-control', 'placeholder'=>'Uniform #')) }}
									<span id="helpBlock" class="help-block"># is not guaranteed </span>
								</div>
							</div>

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
							<br>
							<div class="radio">
								<label>
									<input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>
									I Agree
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="optionsRadios" id="optionsRadios2" value="0">
									I Disagree
								</label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<a href="{{URL::action('PlayerController@index')}}" class="btn btn-default">Cancel</a>
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
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
{{ HTML::script('js/helpers/croppic.min.js')}}
<script type="text/javascript">
$(document).ready(function() {
	$(".datepicker").kendoDatePicker();
	$(".datepicker").bind("focus", function () {
		$(this).data("kendoDatePicker").open();
	});

	$(".mobile").kendoMaskedTextBox({
		mask: "(999) 000-0000"
	});

});

var cropperOptions = {
	modal:true,
	doubleZoomControls:true,
	imgEyecandy:true,
	uploadUrl:'/api/image/upload',
	cropUrl:'/api/image/crop',
	outputUrlId:'croppic',
	onAfterImgUpload:   function(){ console.log(cropperHeader) },
	onAfterImgCrop:     function(){ 
		console.log(cropperHeader['croppedImg']);
		var cropurl = $("#croppic").val();
		$('.user-pic').attr("src", cropurl);
		$(".cropControlRemoveCroppedImage").click(function(){
			$("#croppic").val("/img/default-avatar.png");
			$('.user-pic').attr("src", "/img/default-avatar.png");
		});
	},
	cropData:{
		"url": window.location.origin,
	}
}
var cropperHeader = new Croppic('upimageclub', cropperOptions);
</script>
@stop