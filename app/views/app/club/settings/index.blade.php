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
					<h2>Club</h2>
					<br>
					<p>Update the general information about your club, including user account credentials.</p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h2>Account Settings</h2>
					<p></p>
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
					{{Form::open(array('action' => array('UsersController@update'), 'class'=>'form-horizontal', 'method' => 'post')) }}
					<div class="row">
						<div class="col-xs-12">
							<h4>Account ID and Password</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									{{ Form::text('email', $user->email, array('class' => 'form-control', 'disabled'=>'disabled')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									{{ Form::password('password', array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Confirm Password</label>
								<div class="col-sm-9">
									{{ Form::password('password_confirmation', array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
									<a href="/" class="btn btn-default">Cancel</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
					{{Form::open(array('action' => array('ProfileController@update'), 'class'=>'form-horizontal', 'method' => 'post'))}}
					<div class="row">
						<div class="col-xs-12">
							<h4>Personal Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-9">
									{{ Form::text('firstname', $user->profile->firstname, array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-9">
									{{ Form::text('lastname', $user->profile->lastname, array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									{{ Form::text('mobile', $user->profile->mobile, array('class' => 'form-control mobile')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">DOB</label>
								<div class="col-sm-9">
									{{ Form::text('dob', $user->profile->dob, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Profile Picture</h4>
							<div class="form-group">
								<label class="col-sm-3 control-label">Avatar</label>
								<div class="col-sm-9">
									<div id="upimageclub">
										<img class="edit-org-logo" src="{{$user->profile->avatar}}">
									</div>
									<input type="hidden" id="croppic" name="avatar" value="{{$user->profile->avatar}}">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
									<a href="/" class="btn btn-default">Cancel</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
					{{Form::open(array('action' => array('ClubController@update', $club->id), 'class'=>'form-horizontal', 'method' => 'put'))}}
					<div class="row">
						<div class="col-xs-12">
							<h4>Club General Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Club Name</label>
								<div class="col-sm-9">
									<input type="Text" id="dnb" class="form-control" name="name" placeholder="Club Name" value="{{$club->name}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Address Line 1</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="add1" placeholder="Address" value="{{$club->add1}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">City</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="city" placeholder="City" value="{{$club->city}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">State</label>
								<div class="col-sm-9">
									{{ Form::select('state', State::all()->lists('name','short'), $club->state, array('class'=>'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Zip Code</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="zip" placeholder="Zip Code" value="{{$club->zip}}">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Club Contact Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Phone</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="contactphone" placeholder="Phone" value="{{$club->phone}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="contactemail" placeholder="Email" value="{{$club->email}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Website</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="website" placeholder="web" value="{{$club->website}}">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Club Terms of services and Libility waiver</h4>
							<p>All fields required</p>
							<div class="form-group">
								<div class="col-sm-12">
									{{ Form::textarea('waiver', htmlspecialchars_decode($club->waiver),array('class'=>'form-control', 'id'=>'editor')) }}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<h4>Club Brand</h4>
							<div class="form-group">
								<label class="col-sm-3 control-label">Logo</label>
								<div class="col-sm-9">
									<div id="upimageclub1" class='croppic-mockup'>
										<img class="edit-org-logo" src="{{$club->logo}}">
									</div>
									<input type="hidden" id="croppic1" name="logo" value="{{$club->logo}}">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
									<a href="/" class="btn btn-default">Cancel</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
					<div class="row">
						<div class="col-xs-12">
							<h4>Programs Settings</h4>
							<hr />
							<div class="form-group">
								<div class="col-sm-12 ">
									
									<a href="{{URL::action('ProgramController@create')}}" class="btn btn-success btn-outline">Create</a>
									<a href="{{URL::action('ProgramController@index')}}" class="btn btn-primary btn-outline">Manage Programs</a>
								</div>
							</div>
						</div>
					</div>
					<br><br>
					<div class="row">
						<div class="col-xs-12">
							<h4>Plans Settings</h4>
							<hr />
							<div class="form-group">
								<div class="col-sm-12 ">
									<a href="{{URL::action('PlanController@create')}}" class="btn btn-success btn-outline">Create</a>
									<a href="{{URL::action('PlanController@index')}}" class="btn btn-primary btn-outline">Manage Plans</a>
									
								</div>
							</div>
						</div>
					</div>
					<br><br>
					<div class="row">
						<div class="col-xs-12">
							<h4>Private Login page</h4>
							<hr />
							<div class="form-group">
								<div class="col-sm-12">
								{{ Form::text('name',Request::root()."/club/$club->id/account/login", array('class' => 'form-control block-input', 'readonly'=>"readonly")) }}
								</div>
							</div>
						</div>
					</div>
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
	$("#editor").kendoEditor();
});

var cropperOptions = {
	modal:true,
	doubleZoomControls:true,
	imgEyecandy:true,
	uploadUrl:'/api/image/upload',
	cropUrl:'/api/image/crop',
	outputUrlId:'croppic',
	onAfterImgUpload:   function(){ 
		$(".edit-org-logo").remove();
	},
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
var cropperOptions1 = {
	modal:true,
	doubleZoomControls:true,
	imgEyecandy:true,
	uploadUrl:'/api/image/upload',
	cropUrl:'/api/image/crop',
	outputUrlId:'croppic1',
	onAfterImgUpload:   function(){ 
		$(".edit-org-logo").remove();
	},
	onAfterImgCrop:     function(){ 
		console.log(cropperHeader['croppedImg']);
		var cropurl = $("#croppic1").val();
		//$('.user-pic').attr("src", cropurl);
		$(".cropControlRemoveCroppedImage").click(function(){
			$("#croppic1").val("/img/default-avatar.png");
			//$('.user-pic').attr("src", "/img/default-avatar.png");
		});
	},
	cropData:{
		"url": window.location.origin,
	}
}

var cropperHeader = new Croppic('upimageclub', cropperOptions);
var cropperHeader1 = new Croppic('upimageclub1', cropperOptions1);
</script>
@stop