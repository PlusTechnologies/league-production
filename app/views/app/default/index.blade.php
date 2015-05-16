@extends('layouts.default')
@section('content')
<div class="backsplash">
	<div class="container">
	<div class="row mask-backgroud">
		<div class="col-md-12">
			<div class="col-md-7 backsplash-text">
				<br />
				<span class="logo"><img src="/img/league-together-logo.svg" width="90"></span>
				<h1> 
					Registration & Payment Solutions
				</h1>
				<h4>FOR SPORT ORGANIZATIONS</h4>
				<br>
				<a href="/account/create" class="btn btn-primary btn-outline">Get Started</a>
			</div>
		</div>
	</div>
</div>
</div>

{{-- <div id="customers">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<p>Supported by</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<a href="http://www.cardflexnow.com/" target="_blank"><img src="/img/client/cardflex.png" alt="CardFlex"></a>
				<!-- <a href="http://www.uslacrosse.com" target="_blank"><img src="/img/client/uslacrosse.png" alt="Parcel"></a> -->
				<a href="https://www.facebook.com/CSquaredCompanies" target="_blank"><img src="/img/client/cc.jpg" alt="CSquaredCompanies"></a>
			</div>
		</div>
	</div>
</div> --}}
<div class="section-odd">
	<div class="container">
		<div class="row ">
			<div class="col-md-5">
				<h3>What we are good at</h3>
				<hr>
				<p>
					League Together is a secured on-line application designed under
					strict standards such as PCI DSS and SSAE 16 that allows sport organizations
					the ability to keep track of the financial aspects of running an sport club such as
					membership fee, camps and tryouts fees, registration, discounts etc.
				</p> 
				<p>
					League Together offers a variety of features to simplify the process
					of paying fees related to sport activities such us automatic recurring
					payments, early bird pricing and open/close registration
					dates.
				</p>
				<br>
				<br>
				<a href="/doc/shared/overview.pdf" class="btn btn-outline btn-primary">Download Feature List</a>
			</div>
			<div class="col-md-5 col-md-offset-2">
				<h3>Explore Features</h3>
				<hr>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-user"></i>
					</div>
					<div>
						<h4>Registration</h4>
					</div>
				</div>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-cc-visa"></i>
					</div>
					<div>
						<h4>Payment Processing</h4>
					</div>
				</div>
				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-refresh"></i>
					</div>
					<div>
						<h4>Recurring Payments</h4>
					</div>
				</div>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-reply"></i>
					</div>
					<div>
						<h4>Refunds</h4>
					</div>
				</div>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-users"></i>
					</div>
					<div>
						<h4>Roster</h4>
					</div>
				</div>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-weixin"></i>
					</div>
					<div>
						<h4>Announcements (Email & SMS)</h4>
					</div>
				</div>
			

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-map-marker"></i>
					</div>
					<div>
						<h4>Event Management</h4>
					</div>
				</div>

				<div class="feature">
					<div class="feature_icon">
						<i class="fa fa-calendar"></i>
					</div>
					<div>
						<h4>Calendar</h4>
					</div>
				</div>

				


				<br>
				<br>

			</div>
		</div>
	</div>
</div>
@stop