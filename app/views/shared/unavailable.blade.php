@extends('layouts.public')
@section('content')
<div class="backsplash-event">
	<div class="container">
		<div class="row mask-backgroud">
			<div class="col-md-12">
				<div class="col-md-5 backsplash-text">
					<br />
					<h1> 
						<span class="logo"> <img src="{{$club->logo}}" width="130"></span> 
					</h1>
					<h1 class="club-title"> 
						{{$club->name}}
					</h1>
				</div>
				<div class="col-md-6 col-md-offset-1 dark-backgroud">
					<h1>About Event </h1>
					<p>This link is closed, please contact the club administrator to add more players</p>
					<br>
					<br><br>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="section-even">
	<div class="container container-last">
		
	</div>
</div>
@stop
@section("script")
<script type="text/javascript">
</script>
@stop