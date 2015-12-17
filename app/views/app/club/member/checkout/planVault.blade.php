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
							{{ Form::open(array('action' => array('MemberController@paymentStore', $member->id), 'class'=>'form-horizontal p','method' => 'post')) }}
							<button class="btn btn-success btn-lg btn-outline process pull-right" type="submit">Place Order</button>
							{{Form::close()}}
						</div>
					</div>
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


					<div class="row">
						<div class="col-sm-12">
							<h1>Payment Schedule</h1>
							<table class="table">
								<thead>
									<tr>
										<th>Pay From</th>
										<th>When</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody>
									@for ($x = 1; $x < $member->plan->recurrences + 1; $x++)
									<tr>
										<td>{{$vault->customer_vault->customer->cc_number}}</td>
										<td>{{Carbon::create(Carbon::now()->addMonths($x)->year, Carbon::now()->addMonths($x)->month, $member->plan->getOriginal('on'), 0)->format('m/d/Y');}}</td>
										<td>{{$member->plan->recurring}}</td>
									</tr>
									@endfor
									
								</tbody>
							</table>
							<br>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<p>Payment Method</p>
							<div class="row">
								<div class="col-xs-12">
									<div class="table-responsive">
										<table class="table table-condensed">
											<thead>
												<tr>
													<th class="col-md-3"></th>
													<th class="col-md-9"></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="text-right"><b>Registered Player:</b></td>
													<td>{{$player->firstname}} {{$player->lastname}}</td>
												</tr>
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
													<td>{{$vault->customer_vault->customer->address_1}}<br>
														{{$vault->customer_vault->customer->city}}, 
														{{$vault->customer_vault->customer->state}}
														{{$vault->customer_vault->customer->postal_code}}<br></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">	
										
										{{ Form::open(array('action' => array('MemberController@paymentRemoveCartItem', $member->id),'method' => 'post')) }}
										<a href="/account/player" class="btn btn-primary btn-outline ">Edit Player</a>
										<a href="/account/settings" class="btn btn-primary btn-outline ">Edit Credit Card</a>
										<button type="submit" class="btn btn-danger btn-outline ">Cancel</button>
										{{Form::close()}}
									</div>
								</div>
							</div>
						</div>
						<br>
						<br>
						<br>

						<p class = "mini text-muted">
						By clicking "Place Order", you, {{$user->profile->firstname}} {{$user->profile->lastname}}, authorize schedule payments of {{$member->plan->recurring}} + 5% (services fee) to your Credit Card account ending in {{$vault->customer_vault->customer->cc_number}}, up to the total amount of this plan {{$member->plan->total}} minus ${{number_format($subtotal,2)}} paid today . <br> <br>

						Any scheduled transactions can be cancelled prior to the payment date by going to the Payment History page or by calling the your club representative at {{$member->team->club->phone}} | {{$member->team->club->name}}.
						<br> <br>
						If you would like a copy of the details for this payment, you can click the "Print" link above. After you click "Place Order" you will see a confirmation page that your payment has been complete and subsequent payment scheduled.
					</p>

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
		$( "form.p" ).submit(function( event ) {
			$('.process').prop('disabled', true);
			$('.process').text('');
			$('.process').html('<i class="fa fa-refresh fa-spin"></i>');
		});

	});


	</script>
	@stop