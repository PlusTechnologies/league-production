@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h3>Confirmation, are you sure?</h3>
          <p>We will remove all information related to this player permanatly.</p>
          <br />
          {{Form::open(array('action' => array('PlayerController@destroy', $player->id), 'class'=>'form-horizontal', 'method' => 'delete')) }}
          <button type="submit" class="btn btn-danger btn-outline">Delete</button>
          <a href="{{URL::action('PlayerController@index')}}" class="btn btn-primary btn-outline">Cancel</a>
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