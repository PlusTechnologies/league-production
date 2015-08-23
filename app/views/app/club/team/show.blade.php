@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-12">
          <h2>{{$team->name}}
            <span>
              <div class="btn-group pull-right">
                <button type="button" class="btn btn-default btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Actions &nbsp; <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  
                  <li><a href="{{URL::action('TeamController@edit', $team->id)}}" > <i class="fa fa-pencil fa-fw"></i>&nbsp;Edit</a></li>
                  <li><a href="{{URL::action('TeamController@duplicate', $team->id)}}"> <i class="fa fa-files-o fa-fw"></i>&nbsp;Duplicate</a></li>
                  <li><a href="{{URL::action('TeamController@delete', $team->id)}}"> <i class="fa fa-trash-o fa-fw"></i>&nbsp;Delete</a></li>
                  <li><a href='{{Request::root()."/club/$club->id/team/$team->id"}}' target="_blank"> <i class="fa fa-share-square-o fa-fw"> </i>&nbsp;Share</a></li>
                  <li><a href="{{URL::action('ExportController@team', $team->id)}}" > <i class="fa fa-download"> </i>&nbsp;Export Roster</a></li>
                  <li class="divider"></li>
                  <li><a href="#" data-toggle="modal" data-target=".modal"> <i class="fa fa-bell-o fa-fw"></i>  Announcement</a></li>
                  <li><a href="{{URL::action('SubController@create', $team->id)}}" > <i class="fa fa-group"> </i>&nbsp; Add Group</a></li>
                  <li class="divider"></li>
                  <li><a href="{{URL::action('MemberController@create', $team->id)}}"> <i class="fa fa-user fa-fw"> </i>&nbsp;Invite</a></li>
                  <li><a href="{{URL::action('CoachController@create', $team->id)}}"> <i class="fa fa-heart fa-fw"> </i>&nbsp;Coach</a></li>
                </ul>
              </div> 
            </span>
          </h2>
          <p>{{$team->program->name}} | {{$team->season->name}}</p>
          <hr> 
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
      <div class="row">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-4">
              <div class="tile blue">
                <h3 class="title">${{number_format($sales->sum('price'), 2)}}</h3>
                <p>Sales</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="tile red">
                <h3 class="title">${{number_format($receivable->sum('subtotal'), 2)}}</h3>
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
          <h3>Team Details</h3>
          <div class="row">
            <div class="col-md-8">
              <div class="table-responsive">
                <table class="table table-striped ">
                  <thead>
                    <tr>
                      <th class="col-md-4"></th>
                      <th class="col-md-2"></th>
                      <th class="col-md-3"></th>
                      <th class="col-md-3"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-right"><b>Team name:</b></td>
                      <td colspan='4'>{{$team->name}}</td>
                    </tr>

                    <tr>
                      <td class="text-right"><b>Program:</b></td>
                      <td colspan='4'>{{$team->program->name}}</td>
                    </tr>

                    <tr>
                      <td class="text-right"><b>Season:</b></td>
                      <td colspan='4'>{{$team->season->name}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Dues:</b></td>
                      <td colspan='4'>{{$team->due}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Early registration:</b></td>
                      <td colspan='4'> {{$team->early_due}} before: {{$team->early_due_deadline}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Plan:</b></td>
                      @if($team->plan)
                      <td colspan='4'>{{$team->plan->name}}</td>
                      @else
                      <td colspan='4'>No plan</td>
                      @endif
                    </tr>

                    <tr>
                      <td class="text-right"><b>Roster count:</b></td>
                      <td colspan='4'>{{count($team->members)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p>Shared registration link</p>
              {{ Form::text('name',Request::root()."/club/$club->id/team/$team->id", array('class' => 'form-control block-input')) }}
              <br>
            </div>

            <div class="col-md-4">
              @if($team->club->add1)
              <div id="map_canvas"> </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <br><br>

      <div class="row">
        <div class="col-md-12">

        </div>
        <div class="col-md-12">
          <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#roster" aria-controls="home" role="tab" data-toggle="tab">Roster</a></li>
              <li role="presentation"><a href="#announcements" aria-controls="announcement" role="tab" data-toggle="tab">Announcements</a></li>
              <li role="presentation"><a href="#coach" aria-controls="profile" role="tab" data-toggle="tab">Coach</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="roster">

                <div class="clearfix"></div>
                <br><br> 
                <table class="table table-condensed table-striped" id="grid">
                  <thead>
                    <tr>
                      <th>Player</th>
                      <th>Name </th>
                      <th>Position</th>
                      <th>Uniform</th>
                      <th>Method</th>
                      <th>Amount</th>
                      <th class="col-sm-2">Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($members as $member)
                    <tr class="clickable" data-id="{{$member->player->id}}">
                      <td><img src="{{$member->player->avatar}}" width="60" class="roster-img"></td>
                      <td>{{$member->player->lastname}}, {{$member->player->firstname}}</td>
                      <td>{{$member->player->position}}</td>
                      <td>{{$member->player->uniform}}</td>
                      <td>{{$member->method}}</td>
                      <td>${{number_format($member->due, 2)}}</td>
                      @if($member->status == 1)
                      <td>Accepted</td>
                      @endif
                      @if($member->status == 2)
                      <td>Declined</td>
                      @endif
                      @if(!$member->status)
                      <td>Waiting for reponse</td>
                      @endif
                      <td class="text-center">
                        <a href="{{URL::action('MemberController@delete',array($team->id, $member->id))}}" class="text-danger text-center btn-delete pop-up btn-lg">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane" id="announcements">
                <div class="clearfix"></div>
                <br><br>
                <table class="table table-condensed table-striped" id="grid2">
                  <thead>
                    <tr>
                      <th class="col-md-2">Sent</th>
                      <th class="col-md-2">Subject</th>
                      <th class="col-md-3">Message</th>
                      <th class="col-md-2">To</th>
                      <th class="col-md-3">Mobiles</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($announcements as $announcement)
                    <tr data-id="{{$member->player->id}}">
                      <td class="col-md-2">{{$announcement->created_at}}</td>
                      <td class="col-md-2">{{$announcement->subject}}</td>
                      <td class="col-md-3">{{$announcement->message}}</td>
                      <td class="col-md-2">
                        @foreach (unserialize($announcement->to_email) as $email)
                        <a href="mailto:{{$email['email']}}">{{ ucwords(strtolower ($email['name']))}}</a><br>
                        @endforeach
                      </td>
                      <td class="col-md-3">
                        @foreach (unserialize($announcement->to_sms) as $sms)
                        {{$sms['mobile']}} - {{ ucwords(strtolower ($sms['name']))}}<br>
                        @endforeach
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane" id="coach">
                <div class="clearfix"></div>
                <br><br>
                <table class="table table-condensed table-striped" id="grid2">
                  <thead>
                    <tr>
                      <th class="col-md-1">Coach</th>
                      <th class="col-md-3">Name</th>
                      <th class="col-md-3">Email</th>
                      <th class="col-md-2">Mobile</th>
                      <th class="col-md-2">Added on</th>
                      <th class="col-md-1"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($coaches as $coach)
                    <tr class="clickable" data-id="{{$coach->user->id}}">
                      <td><img src="{{$coach->user->profile->avatar}}" width="60" class="roster-img"></td>
                      <td>{{$coach->user->profile->lastname}}, {{$coach->user->profile->firstname}}</td>
                      <td>{{$coach->user->email}}</td>
                      <td>{{$coach->user->profile->mobile}}</td>
                      <td>{{$coach->created_at}}</td>
                      <td class="text-center">
                        <a href="{{URL::action('CoachController@delete',array($team->id, $coach->id))}}" class="text-danger text-center btn-delete pop-up btn-lg">
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
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="exampleModalLabel">Team Announcement</h3>
      </div>
      <div class="modal-body">
        <div class="modelSuccess">Message Sent!</div>
        <div class="modelForm">
          {{ Form::open(array('action' => array('TeamController@doAnnouncement',$team->id ),'id'=>'message','method' => 'post')) }}
          <div class="form-group">
            <label for="recipient-name" class="control-label">Subject (Required)</label>
            {{Form::text('subject', '', array('class'=>'form-control','placeholder'=>'Subject', 'tabindex'=>'1')) }}
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message (Required)</label>
            {{ Form::textarea('message', null, array('class' => 'form-control','size' => '20x5')) }}
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Share with (Optional)</label>
            <div class="checkbox">
              <label>
                {{ Form::checkbox('players') }}
                Players 
              </label>
            </div>
            <div class="checkbox">
              <label>
                {{ Form::checkbox('family') }}
                Family
              </label>
            </div>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Send Text Message (Optional)</label>
            <div class="checkbox">
              <label>
                {{ Form::checkbox('sms') }}
                SMS 
              </label>
              <span id="helpBlock" class="help-block">The first 140 character will be sent as sms to your receiptians. Standard sms rates will apply.</span>
            </div>
          </div>
          <hr>
          <div class="form-group">
            <button type="submit" class="btn btn-primary process" >Send message</button>
          </div>

          <div class="form-group">
            <div id='result'></div>
          </div>
          {{Form::close()}}
        </div>

      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>

@stop
@section("script")
<script type="text/javascript">

$(function () {

  $('#messageModal').on('hidden.bs.modal', function (e) {
    $('.modelForm').show();
    $('.modelSuccess').hide();
    location.reload();
  })

  $('#messageModal').on('show.bs.modal', function (e) {
    $('.modelForm').show();
    $('.modelSuccess').hide();
  })

  function resetForm(formid) {
   $('#' + formid + ' :input').each(function(){  
     $(this).val('').attr('checked',false).attr('selected',false);
   });
 }

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
  "iDisplayLength": 10,
  "order": [[ 1, "asc" ]],
  // dom: 'T<"clear">lfrtip',
  // tableTools: {
  //   "aButtons": ["print" ]},
  "aoColumns": [
  { "bSortable": false },
  null,
  null,
  null,
  null,
  null,
  null,
  { "bSortable": false }]
});

 $('#grid2').DataTable({
  "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
  "iDisplayLength": 10,
  "order": [[ 0, "desc" ]],
  // dom: 'T<"clear">lfrtip',
  // tableTools: {
  //   "aButtons": ["print" ]},
  "aoColumns": [
  { "bSortable": false },
  { "bSortable": false },
  { "bSortable": false },
  { "bSortable": false },
  { "bSortable": false }]
});


 $("#message").submit(function( event ) {
  event.preventDefault();
    //disabled button
    $('.process').prop('disabled', true);
    $('.process').text('');
    $('.process').html('<i class="fa fa-refresh fa-spin"></i>');

    var $form = $( this ),
    terms = $form.serializeArray(),
    subject = $('input[name="subject"]').val();
    message = $('input[name="message"]').val();
    player = $('input[players]').prop('checked'),
    family = $('input[family]').prop('checked'),
    url = $form.attr( "action" );


    if(subject =="" || message==""){
      alert(" Please enter all required fields");
      $('.process').prop('disabled', false);
      $('.process').html('');
      $('.process').text('Send message');

      return false;
    }

    var request = $.ajax({
      url:url,
      type: "POST",
      data: terms,
      dataType: "json"
    });

    request.done(function( data ) {
      $('.modelForm').hide();
      $('.modelSuccess').show();
      $('.process').prop('disabled', false);
      $('.process').html('');
      $('.process').text('Send message');
      resetForm("message");
    });

    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
      $('.process').prop('disabled', false);
      $('.process').html('');
      $('.process').text('Send message');
      
    });

    return;

  });

});

</script>
@stop