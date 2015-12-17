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
					<h3>New Club</h3>
					<p></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>Create Club Owner Account</h3>
					<p></p>
					{{Form::open(array('action' => array('AdministratorClubController@store'), 'class'=>'form-horizontal', 'method' => 'post')) }}
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
								<label class="col-sm-3 control-label">Confirm Password</label>
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
								<label class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="firstname" placeholder="First Name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="lastname" placeholder="Last Name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control mobile" name="mobile" placeholder="Mobile">
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
							<h4>Club General Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">Club Name</label>
								<div class="col-sm-9">
									<input type="Text" id="dnb" class="form-control" name="name" placeholder="Club Name">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Address Line 1</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="add1" placeholder="Address">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">City</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="city" placeholder="City">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">State</label>
								<div class="col-sm-9">
									<select id="st-pro" name="state" required class="form-control">
										<option value="" selected="selected">Please select</option>
										<option value="AL">Alabama</option>
										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="AA">Armed Forces Americas</option>
										<option value="AE">Armed Forces Europe</option>
										<option value="AP">Armed Forces Pacific</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>
										<option value="DE">Delaware</option>
										<option value="DC">Dist Of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="GU">Guam</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>
										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>
										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="PR">Puerto Rico</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>
										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="VI">Virgin Islands</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Zip Code</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="zip" placeholder="Zip Code">
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
									<input type="Text" class="form-control mobile" name="contactphone" placeholder="Phone">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="contactemail" placeholder="Email">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Website</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="website" placeholder="web">
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<h4>Club Terms of services and Liability waiver</h4>
							<p>All fields required</p>
							<div class="form-group">
								<div class="col-sm-12">
									{{ Form::textarea('waiver', null,array('class'=>'form-control', 'id'=>'editor')) }}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Club CardFlex Credentials</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">User</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="processor_user" placeholder="User">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									<input type="Text" class="form-control" name="processor_pass" placeholder="Password">
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
									<div id="upimageclub"></div>
									<input type="hidden" id="croppic" name="logo" value="/img/default-avatar.png">
								</div>
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
	$("#editor").kendoEditor();
});

var cropperOptions = {
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