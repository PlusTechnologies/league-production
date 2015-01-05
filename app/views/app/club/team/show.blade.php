@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1 app-frame">
      <div class="row">
        <div class="col-sm-5">
          <h2>{{$team->name}}</h2>
          <h4>{{$team->program->name}} | {{$team->season->name}} </h4>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                {{ Form::open(array('action' => array('TeamController@destroy',$team->id), 'method' => 'delete', 'class'=>'btn-trash')) }}
              <div class="col-md-4"><a href="{{$team->id}}/edit" class="btn btn-primary btn-outline btn-block"> Edit Team</a></div>
              <div class="col-md-4"><a href="{{URL::action('MemberController@create', $team->id)}}" class="btn btn-success btn-outline btn-block"> Add Player</a></div>
              <div class="col-md-4"><button class="btn btn-danger btn-outline btn-block" type="submit"></i>Delete</button></div>
              {{ Form::close() }}
              </div>
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
          <table class="table table-condensed table-striped" id="grid">
            <thead>
              <tr>
                <th>Added on</th>
                <th>Player</th>
                <th class="col-sm-1">Method</th>
                <th class="col-sm-2">Status</th>
                <th class="col-sm-1">Remove</th>
                
              </tr>
            </thead>
            <tbody>

              @foreach ($members as $member)
              <tr class="clickable" data-id="{{$member->player->id}}">
                <td>{{$member->created_at}}</td>
                <td>{{$member->firstname}} {{$member->lastname}}</td>
                <td>{{$member->method}}</td>
                <td>{{$member->status}}</td>
                <td class="text-right"><a href="{{URL::action('MemberController@delete',array($team->id, $member->id))}}" class="btn btn-sm btn-danger btn-delete pop-up"><i class="fa fa-trash-o"></i> <small>Remove</small></a></td>
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
  $(function () {

  $('#grid').delegate('tbody > tr', 'click', function (e) {
    var data = $(this).data("id");
    if(data){
      window.location = ("/account/club/player/" + data );
    }
    return false;
    
  });

  $('#grid tbody > tr').find('td:last').on('click', function(e) {
        e.stopPropagation();
  });
  $('#grid').DataTable({
    "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
    "iDisplayLength": 5,
    dom: 'T<"clear">lfrtip',
    tableTools: {
            "aButtons": ["print" ]
    }
  });
  
});
});
</script>
@stop