@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1 app-frame">
      <div class="row">
        <div class="col-sm-12">
          <h2>{{$team->name}}
            <span>
              <div class="btn-group pull-right">
                <button type="button" class="btn btn-default btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{URL::action('MemberController@create', $team->id)}}"> <i class="fa fa-user fa-fw"> </i> Invite</a></li>
                  <li><a href="{{URL::action('TeamController@edit', $team->id)}}" > <i class="fa fa-pencil fa-fw"></i> Edit</a></li>
                  <li><a href="{{URL::action('TeamController@delete', $team->id)}}"> <i class="fa fa-trash-o fa-fw"></i> Delete</a></li>
                  <li class="divider"></li>
                  <li><a href="#" data-toggle="modal" data-target=".modal"> <i class="fa fa-bell-o fa-fw"></i>  Announcement</a></li>
                </ul>
              </div> 
            </span>
          </h2>
          <p>{{$team->program->name}} | {{$team->season->name}}</p>
          <hr> 
          <a href='{{Request::root()."/club/$club->id/team/$team->id"}}' target="_blank">Share <i class="fa fa-share-square-o fa-fw"> </i></a>
          
          <!-- <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <br>
                  <p>Shared registration link</p>
                  {{ Form::text('name',Request::root()."/club/$club->id/team/$team->id", array('class' => 'form-control', 'readonly'=>'readonly')) }}
                  <br>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div><!-- end of first row -->
      <br>
      <br>
      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-4">
              <div class="tile blue">
                <h3 class="title">${{number_format($members->sum('due'), 2)}}</h3>
                <p>Sales</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="tile red">
                <h3 class="title">{{$members->count()}}</h3>
                <p>Receivable</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="tile green">
                <h3 class="title">{{$members->count()}}</h3>
                <p>Members</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div>
      <div class="row">
        <div class="col-md-12">
          <h3>
            Roster
            <span>
              <div class="btn-group pull-right">
                <button type="button" class="btn btn-default btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="javascript:;" onclick="alert('Coming soon..')" > <i class="fa fa-download"> </i> Excel</a></li>
                  <li><a href="javascript:;"onclick="alert('Coming soon..')" > <i class="fa fa-print"> </i> Print</a></li>
                </ul>
              </div> 
            </span>
          </h3>
          <hr>
          <table class="table table-condensed table-striped" id="grid">
            <thead>
              <tr>
                <th>Added on</th>
                <th>Player</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($members as $member)
              <tr class="clickable" data-id="{{$member->player->id}}">
                <td>{{$member->created_at}}</td>
                <td>{{$member->firstname}} {{$member->lastname}}</td>
                <td>{{$member->method}}</td>
                <td>${{number_format($member->due, 2)}}</td>
                <td>{{$member->status}}</td>
                <td class="text-center">
                  <a href="{{URL::action('MemberController@delete',array($team->id, $member->id))}}" class="text-danger text-center btn-delete pop-up">
                    <i class="fa fa-trash"></i>
                  </a>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="exampleModalLabel">Team Announcement</h3>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Subject (Required)</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Share with (Required)</label>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="">
                Players 
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="">
                Family
              </label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-primary" href="javascript:;" onclick="alert('Coming soon..')">Send message</a>
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
      "order": [[ 0, "desc" ]],
      "order": [[ 5, null ]],
      dom: 'T<"clear">lfrtip',
      tableTools: {
        "aButtons": ["print" ]
      },"aoColumns": [
      null,
      null,
      null,
      null,
      null,
      { "bSortable": false }]

    });

  });
});
</script>
@stop