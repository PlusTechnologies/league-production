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
					<h2>Payment Settings</h2>
					<br>
					<p>Update the general information about your account, including credentials and profile information.</p>
				</div>
				<div class="col-md-7 same-height col-md-offset-1">
					<h2>Edit Payment Information</h2>
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
					{{ Form::open(array('action' => array('AccountController@vaultUpdate', $vault->customer_vault->customer->customer_vault_id ), 'class'=>'form-horizontal','method' => 'post')) }}
					<p>Credit Card</p>
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="col-sm-3 control-label">Card</label>
								<div class="col-sm-9">
									{{Form::text('card', '', array('id'=>'card','class'=>'form-control card-mask','placeholder'=>'Valid Card Number','tabindex'=>'1', 'autofocus')) }}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Exp. month</label>
								<div class="col-sm-9">
									{{Form::selectMonth('month', null, array('class'=>'form-control'));}}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Exp. Year</label>
								<div class="col-sm-9">
									{{ Form::selectRange('year', 2015, 2035, null, array('class'=>'form-control'));}}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">CVC</label>
								<div class="col-sm-9">
									{{Form::text('cvv', '', array('id'=>'cvc','class'=>'form-control','placeholder'=>'CV','tabindex'=>'4')) }}
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">	
							<p>Billing Address</p>
							<div class="form-group">
								<label class="col-sm-3 control-label" >Street Address</label>
								<div class="col-sm-9">
									{{Form::text('address', '', array('id'=>'address','class'=>'form-control','placeholder'=>'eg. 80 Dolphin St','tabindex'=>'2')) }}
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">City</label>
								<div class="col-sm-9">
									{{Form::text('city','',array('id'=>'city','class'=>'form-control','placeholder'=>'eg. New York','tabindex'=>'2')) }}
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
								<label class="col-sm-3 control-label">Billing Zip</label>
								<div class="col-sm-9">
									{{Form::text('zip', '', array('id'=>'zip','class'=>'form-control','placeholder'=>'eg. 83401','tabindex'=>'5')) }}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<hr />
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<button class="btn btn-primary btn-outline vault">Save</button>
									<a href="{{URL::action('AccountController@settings')}}" class="btn btn-default">Cancel</a>

								</div>
							</div>
						</div>
					</div>

					{{Form::close()}}

					<div class="row">
						<div class="col-xs-12">
							<h4>Current Information</h4>
							<hr />
							<table class="table table-condensed table-user-information">
								<thead>
									<tr>
										<th class="col-md-3"></th>
										<th class="col-md-9"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-right"><b>Credit Card:</b></td>
										<td>{{$vault->customer_vault->customer->cc_number}}</td>
									</tr>
									<tr>
										<td class="text-right"><b>Exp Date:</b></td>
										<td> {{substr_replace($vault->customer_vault->customer->cc_exp, '/', -2, 0)}}</td>
									</tr>
									<tr>
										<td class="text-right"><b>Billing Address:</b></td>
										<td>
											{{$vault->customer_vault->customer->address_1}}<br>
											{{$vault->customer_vault->customer->city}}, 
											{{$vault->customer_vault->customer->state}}
											{{$vault->customer_vault->customer->postal_code}}<br>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
$(document).ready(function() {

	$(".card-mask").kendoMaskedTextBox({
		mask: "0000 0000 0000 0000"
	});

});


</script>
@stop