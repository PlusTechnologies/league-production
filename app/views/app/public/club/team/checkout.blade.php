@extends('layouts.public')
@section('content')
<div class="container container-last">
	<div id="same-height-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-5 signup-col same-height">
					<br />
					<h1 class="text-center"> 
						<span class="logo"> <img src="{{$club->logo}}" width="90"></span> 
					</h1>
					<h1 class="text-center"> 
						{{$club->name}}
					</h1>
					<h4 class="text-center">Team {{$team->name}}</h4>
					<br><br><br>
				</div>
				<div class="col-md-6 col-md-offset-1 same-height">
					<div class="row">
						<div class="col-sm-12">
							<h1>Order Summary</h1>
							<table class="table">
								<thead>
									<tr>
										<th>Description</th>
										<th>Qty</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									@foreach(Cart::contents() as $item)
									<tr>
										<td>{{$item->name}}</td>
										<td class="text-center">{{$item->quantity}}</td>
										<td class="text-right" >${{number_format($item->price,2) }}</td>
									</tr>
									@endforeach
									@if(Session::has('discount'))
									<tr class="text-right">
										<td colspan="2" class="text-right">Discount</td>
										<td>${{$discount}}</td>
										{{Form::hidden('discount', Session::get('discount')['id'] ) }}
									</tr>
									@endif
									<tr class="text-right">
										<td colspan="2" class="text-right">Subtotal</td>
										<td>${{number_format($subtotal,2)}}</td>
									</tr>
									<tr class="text-right">
										<td colspan="2" >Processing fees</td>
										<td>${{number_format($service_fee,2)}}</td>
									</tr>
									<tr class="sum-total text-right">
										<td colspan="2"><h2></h2>Total</td>
										<td><h2>${{number_format($cart_total,2)}}</h2></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<h1>Payment Information</h1>

							@if(Session::has('error'))
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="alert alert-dismissable">
											<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
											<p class="text-danger">{{Session::get('error')}}</p>
										</div>
									</div>
								</div>
							</div>
							@endif

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

							{{ Form::open(array('action' => array('ClubPublicController@PaymentValidateTeam', $club->id, $team->id), 'class'=>'form-horizontal','method' => 'post')) }}
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
							@if($vault)
							<button class="btn btn-primary btn-outline btn-sm process pull-right" type="submit">Place Order</button>
							@else
							<button class="btn btn-primary btn-outline vault pull-right">Verify Payment</button>
							@endif
							{{Form::close()}}
						</div>
					</div>
					<br>
					<br>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
@section("script")
<script type="text/javascript">
$(document).ready(function() { 
	$(".card-mask").kendoMaskedTextBox({
		mask: "0000 0000 0000 0000"
	});
});


</script>
@stop