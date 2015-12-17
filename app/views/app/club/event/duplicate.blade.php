@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h3>Confirmation, are you sure?</h3>
          <p>We will duplicate this information, no players registered will be copy.</p>
          <br />
          {{Form::open(array('action' => array('EventoController@duplicate', $event->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
          <button type="submit" class="btn btn-danger btn-outline">Duplicate Event</button>
          <a href="{{URL::action('EventoController@index')}}" class="btn btn-primary btn-outline">Cancel</a>
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