@extends('layouts.default')
@section('style')
{{HTML::style('css/account/default.css')}}
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-container">
                <div id="login-title">Forgot Password</div>
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
                <div class="form-box">
                    {{Form::open(array('action' => array('UsersController@doForgotPassword'),'method' => 'post')) }}
                    {{Form::text('email', Input::old('email'), array('id'=>'email','class'=>'login-top','placeholder'=>'Email address', 'tabindex'=>'1')) }}
                    <p>
                        <button class="btn btn-primary btn-outline login" type="submit">Reset</button>
                    </p>
                    {{ Form::close() }}
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