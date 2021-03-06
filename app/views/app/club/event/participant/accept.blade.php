@extends('layouts.account')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-6">
          <h1>We want you!</h1>
          <h3>You are close to become part of our team.</h3>
          <br><br>
          <p>Please review the information below:</p>
          <br><br>
          <div class="table-responsive">
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td class="text-right col-md-4"><b>Exclusive for:</b></td>
                <td class="col-md-8">{{$participant->player->firstname}} {{$participant->player->lastname}}</td>
              </tr>
              <tr>
                <td class="text-right col-md-4"><b>Club:</b></td>
                <td class="col-md-8">{{$participant->event->club->name}}</td>
              </tr>
              <tr>
                <td class="text-right"><b>Event:</b></td>
                <td>{{$participant->event->name}} | {{ $participant->event->date }} </td>
              </tr>
              <tr>
                <td class="text-right"><b>Fee:</b> </td>
                <td>{{$participant->due}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
          <div class="row">
            <div class="col-xs-12">
              <h4>Team's Term of services & Liability waiver </h4>
              <hr />
              <div class="form-group">
                <div class="col-sm-12 club-terms">
                  <small>
                    {{htmlspecialchars_decode($club->waiver)}}
                  </small>
                </div>
              </div>
            </div>
          </div>
          <hr><br>
        <p class="text-right">
          <a href="{{URL::action('ParticipantController@paymentSelect',$participant->id)}}"class="btn btn-success btn-outline">I Agree</a>
          <a href="{{URL::action('PlayerController@index')}}" class="btn btn-primary btn-outline">Cancel</a>
        </p>
          
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