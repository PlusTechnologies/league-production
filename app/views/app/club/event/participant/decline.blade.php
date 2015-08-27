@extends('layouts.account')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-6">
          <h1>We want you!</h1>
          <h3>Confirmation, you are sure?</h3>
          <p>We will remove this information permanatly, no refunds will be made at this point, and you will no longer have access to this page.</p>
          <br>
          <hr>

          {{ Form::open(array('action' => array('ParticipantController@doDecline', $participant->id),"class"=>"form-horizontal",'id'=>'participants','method' => 'post')) }}
          <p class="text-right">
            <button class="btn btn-danger btn-outline" type="submit">Decline Event</button>
            <a href="{{URL::action('PlayerController@index')}}" class="btn btn-primary btn-outline">Cancel</a>
          </p>
          {{Form::close()}}          
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