@extends('layouts.default')
@section('style')
	{{HTML::style('css/account/default.css')}}
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="login-container">
        <div id="login-title">Reset Password</div>
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
        <div class="form-box">
          {{Form::open(array('action' => array('UsersController@doResetPassword'),'id'=>'login','method' => 'post')) }}
          {{Form::hidden('token', $token) }}
          {{Form::password('password', array('class'=>'login-top','placeholder'=>'Password', 'tabindex'=>'1')) }}
          {{Form::password('password_confirmation', array('class'=>'login-bottom','placeholder'=>'Confirm Password', 'tabindex'=>'2')) }}
          <p>
            <button class="btn btn-primary btn-outline login" type="submit">Save</button>
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
         <a href="{{ URL::route('create') }}">
           Sign up 
         </a>today.
       </div>
     </div>
   </div>
 </div>
</div>
@stop