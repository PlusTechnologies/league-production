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
					<h2>Update User</h2>
					<br>
					<p>
						<b class="text-danger">Important:</b> This page is intended for administrator and club owners only. <br><br>
						<b>Instructions:</b> <br>
						Step 1 - Edit information. <br>
						Step 2 - Save.
					</p>
					<p>Privacy questions?</p>
					<p>Click here for the <a href="">Privacy Policy</a></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h2>User Account</h2>
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
					{{Form::open(array('action' => array('UsersController@update', $editUser->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
					<div class="row">
						<div class="col-xs-12">
							<h4>Account ID and Password</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									{{ Form::text('email', $editUser->email, array('class' => 'form-control')) }}
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
							<div class="form-group">
								<label class="col-sm-3 control-label">Role</label>
								<div class="col-sm-9">
									@foreach($editUser->roles as $role)
									@if($role->id == 1 )
										{{ Form::select('role', Role::lists('name','id'), $role->id, array('class' => 'form-control', 'disabled'=>'disabled')) }}
									@else
										{{ Form::select('role', array_diff(Role::lists('name','id'), array('name'=>'administrator')) , $role->id, array('class' => 'form-control')) }}
									@endif
									@endforeach
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
									<a href="{{ URL::action('FollowerController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
					{{Form::open(array('action' => array('ProfileController@update', $editUser->id), 'class'=>'form-horizontal', 'method' => 'post'))}}
					<div class="row">
						<div class="col-xs-12">
							<h4>Personal Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-9">
									{{ Form::text('firstname', $editUser->profile->firstname, array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-9">
									{{ Form::text('lastname', $editUser->profile->lastname, array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									{{ Form::text('mobile', $editUser->profile->mobile, array('class' => 'form-control mobile')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">DOB</label>
								<div class="col-sm-9">
									{{ Form::text('dob', $editUser->profile->dob, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
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
										<img class="edit-org-logo" src="{{$editUser->profile->avatar}}">
									</div>
									<input type="hidden" id="croppic" name="avatar" value="{{$editUser->profile->avatar}}">
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
									<a href="{{ URL::action('FollowerController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
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
	$('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/contact/" + $(this).data("id") + /edit/);
  });
   $('#grid').DataTable({
      "aLengthMenu": [[10, 25, 75, -1], [10, 25, 75, "All"]],
      "iDisplayLength": 10,
      "bSort": false
  });

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