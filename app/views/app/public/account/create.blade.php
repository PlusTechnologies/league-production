@extends('layouts.public')
@section('style')
{{HTML::style('css/helpers/croppic.css')}}
@stop
@section('content')
<div class="container container-last">
	<div id="same-height-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-4 signup-col same-height">
					<h1>New Account</h1>
					<br><br>
					<p>
						<b class="text-danger">Important:</b> This page is intended for parents or legal guardian only. <br><br>
						<b>Instructions:</b> <br>
						Step 1 - Create your personal account. <br>
						Step 2 - Active account using confirmation email. <br>
						Step 3 - Proceed to register to an event.
					</p>
					<p>Privacy questions? Click here for the <a href="">Privacy Policy</a></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<p class="logo text-center"> <img src="{{$club->logo}}" width="90"></p> 
					<h3 class="text-center">{{$club->name}}</h3><br>
					<h3>Create New Account</h3>
					<p></p>
					{{Form::open(array('action' => array('ClubPublicController@accountStore', $club->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
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
							<h4>Account ID and Password</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="email" placeholder="Email">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="password" placeholder="Password">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Confirm password</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Personal Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First name</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="firstname" placeholder="First name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last name</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="lastname" placeholder="Last name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									<input class="form-control mobile" name="mobile" placeholder="Mobile">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">DOB</label>
								<div class="col-sm-9">
									{{ Form::text('dob',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
									<span id="helpBlock" class="help-block"><small>This is required so that we can comply with the Children’s Online Privacy Protection Act and other age restrictions.</small></span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Player Information</h4>
							<p>You can link multiple players to your profiles, saving you time when you like to register for an event.
								To add more players, login to your account a visit the "Players" link in the navigation bar.</p>
								<p>All fields required</p>
								<div class="form-group">
									<label class="col-sm-3 control-label">First name</label>
									<div class="col-sm-9">
										{{ Form::text('firstname_p',null, array('class' => 'form-control', 'placeholder'=>'First name')) }}
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Last name</label>
									<div class="col-sm-9">
										{{ Form::text('lastname_p',null, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
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
										{{ Form::text('dob_p',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
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
										<a href="/" class="btn btn-default">Cancel</a>
										<button type="submit" class="btn btn-primary btn-outline">Create Account</button>
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
		doubleZoomControls:true,
		imgEyecandy:true,
		modal:true,
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