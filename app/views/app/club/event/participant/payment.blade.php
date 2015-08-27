@extends('layouts.account')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-6">
          <h1>Select one option:</h1>
          <br><br>
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
          @if($notice)
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="alert alert-dismissable">
                  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                  <p class="text-success">{{$notice}}</p>
                </div>
              </div>
            </div>
          </div>
          @endif
          <div class="row">
            <div class="col-sm-6">
              {{Form::open(array('action' => array('ParticipantController@paymentSelect', $participant->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
              {{Form::hidden('type','full') }}
              <button type="submit" class="tile text-left btn btn-outline btn-success ">
                <h3 class="title">{{$price}}</h3>
                Full Payment
              </button>
              {{Form::close()}}
            </div>
            <div class="col-sm-6">
              {{Form::open(array('action' => array('ParticipantController@paymentSelect', $participant->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
              {{Form::hidden('type','plan') }}
              <button type="submit" class="tile btn btn-outline btn-warning ">
                <h3 class="title">{{$participant->plan->initial}}*</h3> 
                + {{$participant->plan->recurring}} per month
              </button>
              {{Form::close()}}
            </div>
          </div>
          <p class="text-muted"><small>* Please read carefully our terms and conditions for this services and refund policy. </small></p>
          <div class="col-sm-6"></div>
        </div>
        <div class="col-sm-7">
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
        </div>
      </div>
    </div>
  </div>
</div>
@stop