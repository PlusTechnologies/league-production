@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1 app-frame">
      <div class="row">
        <div class="col-sm-5">
          <h2>Team {{$team->name}}</h2>
          <p>Program: {{$team->program->name}}</p>
          <p>Season: {{$team->season->name}}</p>
          <hr>
          <p>
            Review the most relevant information about your club.
          </p>
          <div class="row">
            <div class="col-md-12">
              {{ Form::open(array('action' => array('TeamController@destroy',$team->id), 'method' => 'delete', 'class'=>'btn-trash')) }}
                <a href="{{$team->id}}/edit" class="btn btn-primary btn-outline"> Edit Team</a>
                <a href="{{URL::action('MemberController@create', $team->id)}}" class="btn btn-success btn-outline"> Add Player</a>
                <button class="btn btn-danger btn-outline" type="submit"></i>Delete Team</button>
              {{ Form::close() }}
            </div>
          </div>
        </div>
        <div class="col-sm-7 ">
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row ">
        <div class="col-sm-12">
          <h3>Roster</h3>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <table class="table" id="grid">
            <thead>
              <tr>
                <th data-field="date">Added on</th>
                <th data-field="player">Player</th>
                <th data-field="payment">Payment Status</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($team->members as $player)
              <tr>
                <td>{{$player->pivot->created_at}}</td>
                <td><a href="{{URL::action('MembersController@show', array($team->id, $player->id)  )}}">{{$player->firstname}} {{$player->lastname}}</a></td>
                <td>complete</td>
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
  $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5,
  });
});
</script>
@stop