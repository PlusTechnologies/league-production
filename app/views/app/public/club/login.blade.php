@extends('layouts.default')
@section('style')
	{{HTML::style('css/account/default.css')}}
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="login-container">
        <div id="login-title">welcome</div>
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
							<p class="logo text-center"> <img src="{{$club->logo}}" width="90"></p> 
							<h3 class="text-center">{{$club->name}}</h3><br>
          </div>
        </div>
        <div class="form-box">
          {{Form::open(array('action' => array('ClubPublicController@doAccountLogin', $club->id), 'id'=>'login', 'method' => 'post')) }}
          @if(Session::has('error'))
          {{Form::text('email', '', array('id'=>'email','class'=>'login-error-top login-top','placeholder'=>'Email address', 'tabindex'=>'1'))}}
          {{Form::password('password', array('id'=>'password','class'=>'login-error-bottom login-bottom','placeholder'=>'Password', 'tabindex'=>'2')) }}
          @else
          {{Form::text('email', '', array('id'=>'email','class'=>'login-top','placeholder'=>'Email address', 'tabindex'=>'1')) }}
          {{Form::password('password', array('id'=>'password','class'=>'login-bottom','placeholder'=>'Password', 'tabindex'=>'2')) }}
          @endif       
          <p>
            <button class="btn btn-primary btn-outline login" type="submit">Login</button>
            <a class="pass-help" href="{{ URL::route('forgot') }}">
              Password help?
            </a>
          </p>
          {{Form::close()}}
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div id="login-help">
        <div class="text-center">
         Don’t have an account? 
         <a href="{{ URL::action('ClubPublicController@accountCreate', $club->id) }}">
           Sign up 
         </a>today.
       </div>
     </div>
   </div>
 </div>
</div>
@stop
@section("script")
<script type="text/javascript">
$(function () {
  //var spinner = $( ".spinner" ).spinner();
  $('#login input.login-error-top').on('focus', function(){
    $(this).removeClass('login-error-top');
  });
  $('#login input.login-error-bottom').on('focus', function(){
    $(this).removeClass('login-error-bottom');
  });
});
</script>
@stop