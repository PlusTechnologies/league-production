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
					<h1>Update Contact</h1>
					<br><br>
					<p>
						<b class="text-danger">Important:</b> This page is intended for parents or legal guardian only. <br><br>
						<b>Instructions:</b> <br>
						Step 1 - Update new contact information. <br>
						Step 2 - Click save.
					</p>
					<p>Privacy questions?</p>
					<p>Click here for the <a href="">Privacy Policy</a></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>Edit Contact</h3>
					<p></p>
					{{Form::open(array('action' => array('ContactController@update', $contact->id), 'class'=>'form-horizontal', 'method' => 'put')) }}
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
							<h4>Contact Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First name</label>
								<div class="col-sm-9">
									{{ Form::text('firstname',$contact->firstname, array('class' => 'form-control', 'placeholder'=>'First name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last name</label>
								<div class="col-sm-9">
									{{ Form::text('lastname',$contact->lastname, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email </label>
								<div class="col-sm-9">
									{{ Form::text('email',$contact->email, array('class' => 'form-control', 'placeholder'=>'Email')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Secondary Email </label>
								<div class="col-sm-9">
									{{ Form::text('second_email',$contact->second_email, array('class' => 'form-control', 'placeholder'=>'Email')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									{{ Form::text('mobile',$contact->mobile, array('class' => 'form-control mobile', 'placeholder'=>'Mobile')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Relationship</label>
								<div class="col-sm-9">
									{{ Form::text('relation',$contact->relation, array('class' => 'form-control', 'placeholder'=>'Ex. father, mother, legal guardian, etc.')) }}
								</div>
							</div>
							<br>
							<div class="form-group">
								<label class="col-sm-3 control-label">Contact picture</label>
								<div class="col-sm-9">
									<div id="upimageclub">
										<img class="edit-org-logo" src="{{$contact->avatar}}">
									</div>
									<input type="hidden" id="croppic" name="avatar" value="{{$contact->avatar}}">
								</div>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
									<a href="{{URL::action('ContactController@delete', $contact->id)}}" class="btn btn-danger btn-outline">Delete</a>
									<a href="{{URL::action('PlayerController@index')}}" class="btn btn-default">Cancel</a>
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