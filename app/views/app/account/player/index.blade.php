@extends('layouts.account')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Players</h2>
          <p>
            Review the most relevant information about your players.
          </p>
          <br />
          <a href="{{URL::action('PlayerController@create')}}" class="btn btn-primary btn-outline">Add Player</a>    
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">{{$players->count()}}</h3>
                <p>Total Players</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
          <h3>Players</h3>
          <hr />
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2" data-field="date">Created</th>
                <th class="col-sm-2" data-field="name">Name</th>
                <th class="col-sm-2" data-field="e_date">DOB</th>
                <th class="col-sm-1" data-field="fee">Position</th>
                <th class="col-sm-2" data-field="status">Relationship</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($players as $item)
              <tr class="clickable" data-id="{{$item->id}}">
                <td>{{$item->created_at}}</td>
                <td>{{$item->firstname}} {{$item->lastname}}</td>
                <td>{{$item->dob}}</td>
                <td>{{$item->position}}</td>
                <td>{{$item->relation}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h3>Invites</h3>
          <hr />
          <table class="table table-striped" id="grid1">
            <thead>
              <tr>
                <th class="">Created</th>
                <th class="">Player</th>
                <th class="">Club</th>
                <th class="">Team/Event</th>
                <th class="">Cost</th>
                <th class="text-right"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($invites as $item)
              <tr class="clickable" data-id="{{$item->id}}">
                <td>{{$item->created_at}}</td>
                <td>{{$item->firstname}} {{$item->lastname}}</td>
                @if($item->event_id)
                  <td>{{$item->event->club->name}}</td>
                  <td>{{$item->event->name}}</td>
                @else
                  <td>{{$item->team->club->name}}</td>
                  <td>{{$item->team->name}}</td>
                @endif

                
                <td>{{$item->due}}</td>
                <td class="text-right" >
                  @if($item->event_id)
                    <a href="{{URL::action('ParticipantController@accept',$item->id)}}" class="btn btn-success btn-outline">Accept</a>
                    <a href="{{URL::action('ParticipantController@decline',$item->id)}}" class="btn btn-danger btn-outline">Decline</a>
                  @else
                    <a href="{{URL::action('MemberController@accept',$item->id)}}" class="btn btn-success btn-outline">Accept</a>
                    <a href="{{URL::action('MemberController@decline',$item->id)}}" class="btn btn-danger btn-outline">Decline</a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@stop
@section("script")

<script type="text/javascript">
$(function () {
  $('#grid, #grid1').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/player/" + $(this).data("id") +"/edit");
  });
  $('#grid, #grid1').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5
  });

});
</script>
@stop