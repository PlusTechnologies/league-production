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
					<h1>Update Player</h1>
					<br><br>
					<p>
						<b class="text-danger">Important:</b> This page is intended for parents or legal guardian only. <br><br>
						<b>Instructions:</b> <br>
						Step 1 - Add new player information. <br>
						Step 2 - Save.
					</p>
					<p>Privacy questions?</p>
					<p>Click here for the <a href="">Privacy Policy</a></p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h3>Update Player</h3>
					<p></p>
					{{Form::open(array('action' => array('PlayerController@update', $player->id), 'class'=>'form-horizontal', 'method' => 'Put')) }}
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
							<h4>Player Information</h4>
							<p>All fields required</p>
							<div class="form-group">
								<label class="col-sm-3 control-label">First name</label>
								<div class="col-sm-9">
									{{ Form::text('firstname',$player->firstname, array('class' => 'form-control', 'placeholder'=>'First name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last name</label>
								<div class="col-sm-9">
									{{ Form::text('lastname',$player->lastname, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email </label>
								<div class="col-sm-9">
									{{ Form::text('email',$player->email, array('class' => 'form-control', 'placeholder'=>'Email')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									{{ Form::text('mobile',$player->mobile, array('class' => 'form-control mobile', 'placeholder'=>'Mobile')) }}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Player's position</label>
								<div class="col-sm-9">
									{{ Form::select('position', ['attack' => 'Attack','midfield' => 'Midfield','defense' => 'Defense','LSM' => 'LSM','goalie' => 'Goalie'],$player->position,array('class' => 'form-control', 'placeholder'=>'Position')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Relationship</label>
								<div class="col-sm-9">
									{{ Form::text('relation',$player->relation, array('class' => 'form-control', 'placeholder'=>'Ex. father, mother, legal guardian, etc.')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">DOB</label>
								<div class="col-sm-9">
									{{ Form::text('dob',$player->dob, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Gender</label>
								<div class="col-sm-9">
									{{Form::select('gender', array('M' => 'Male', 'F' => 'Female'),$player->gender, array('class'=>'form-control'));}}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">School </label>
								<div class="col-sm-9">
									{{ Form::text('school',$player->school, array('class' => 'form-control', 'placeholder'=>'School Name')) }}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Graduation class</label>
								<div class="col-sm-9">
									{{ Form::selectRange('year', 2015, 2035, $player->year, array('class'=>'form-control'));}}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">US Lacrosse #</label>
								<div class="col-sm-9">
									{{ Form::text('laxid',$player->laxid, array('class' => 'form-control', 'placeholder'=>'US Lax ID')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">USL # exp. date</label>
								<div class="col-sm-9">
									{{ Form::text('laxid_exp',$player->laxid_exp, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Prefer Uniform #</label>
								<div class="col-sm-9">
									{{ Form::text('uniform',$player->uniform, array('class' => 'form-control', 'placeholder'=>'Uniform #')) }}
									<span id="helpBlock" class="help-block"># is not guaranteed </span>
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label">Roster picture</label>
								<div class="col-sm-9">
									<div id="upimageclub">
										<img class="edit-org-logo" src="{{$player->avatar}}">
									</div>
									<input type="hidden" id="croppic" name="avatar" value="/img/default-avatar.png">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<h4>Player Preferences</h4>
							<p>All fields required</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn btn-primary btn-outline">Save</button>
									<a href="{{URL::action('PlayerController@delete', $player->id)}}" class="btn btn-danger btn-outline">Delete</a>
									<a href="{{URL::action('PlayerController@index')}}" class="btn btn-default">Cancel</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}

					<div class="row">
						<div class="col-xs-12">
							<hr />
							<h4>Contacts Information</h4>

							<br>
							<div class="table-responsive">
								<table class="table table-striped" id="grid">
									<thead>
										<tr>
											<td class="col-sm-4">Name</td>
											<td class="col-sm-4">Relationship</td>
											<td class="col-sm-4">Mobile</td>
										</tr>
									</thead>
									<tbody>
										@foreach($player->contacts as $contact)
										<tr class="clickable" data-id="{{$contact->id}}">
											<td>{{$contact->firstname}} {{$contact->lastname}}</td>
											<td>{{$contact->relation}}</td>
											<td>{{$contact->mobile}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
									<a href="{{URL::action('ContactController@create')}}" class="btn btn-success btn-outline">Add New</a>
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