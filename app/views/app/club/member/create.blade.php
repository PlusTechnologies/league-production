@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height">
              <h3>Add Player</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In odio tortor, hendrerit nec sapien at, sollicitudin accumsan lorem.</a>
              </div>
              <div class="col-xs-7 same-height col-xs-offset-1">
                <h3 class="">Add new player</h3>
                <p><b>Instructions:</b> Please read carefully all the instructions to succefully build your team roster. All fields are required</p>
                <br>
                @if($errors->has())
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="alert alert-default alert-dismissable">
                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                        <ul>
                          @foreach ($errors->all() as $error) 
                          <li class="text-danger">{{$error}}</li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                @endif

                <div class="row">
                  <div class="col-xs-12">
                    <h4>Team details</h4>
                    <p>Default team details.</p>

                    <table class="table table-bordered table-condensed">
                      <tbody>
                        <tr>
                          <td class="text-right col-xs-3">Team:</td>
                          <td>{{$team->name}}</td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3">Program:</td>
                          <td>{{$team->program->name}}</td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3">Season:</td>
                          <td>{{$team->season->name}}</td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3">Season Dues:</td>
                          <td>{{$team->due}}</td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3">Early Bird:</td>
                          <td>{{$team->early_due}} before: {{$team->early_due_deadline}}</td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3">Roster count:</td>
                          <td>{{count($team->players)}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4>Search Player</h4>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Search</label>
                      <div class="col-sm-9">
                        {{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'Name')) }}
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                {{ Form::open(array('action' => array('MemberController@store', $team->id),"class"=>"form-horizontal",'id'=>'members','method' => 'post')) }}
                <div class="row">
                  <div class="col-xs-12">
                    <h4>Player Information</h4>
                    <p>All fields required</p>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">First name</label>
                      <div class="col-sm-9">
                        {{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'First name')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Last name</label>
                      <div class="col-sm-9">
                        {{ Form::text('lastname',null, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Player's position</label>
                      <div class="col-sm-9">
                        {{ Form::text('position',null, array('class' => 'form-control', 'placeholder'=>'Position')) }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-12">
                    <h4>Invite Information</h4>
                    <p>All fields required</p>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Email</label>
                      <div class="col-sm-9">
                        {{ Form::text('email',null, array('class' => 'form-control', 'placeholder'=>'Primary Email')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Secondary email</label>
                      <div class="col-sm-9">
                        {{ Form::text('second',null, array('class' => 'form-control', 'placeholder'=>'Secondary Email')) }}
                      </div>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-xs-12">
                    <h4>Custom Payment details (optional)</h4>
                    <p>Add a custome payment for this player.</p>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Early Bird Dues</label>
                      <div class="col-sm-9">
                        {{Form::text('early_due', '', array('class'=>'','id'=>'early_due','placeholder'=>'Early Bird Dues', 'tabindex'=>'3')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Deadline</label>
                      <div class="col-sm-9">

                        {{Form::text('early_due_deadline', '', array('id'=>'early_deadline','class'=>'form-control kendo-datepicker','placeholder'=>'Deadline', 'tabindex'=>'3')) }}

                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Team Dues</label>
                      <div class="col-sm-9">
                        {{Form::text('due', '', array('id'=>'due','placeholder'=>'Team Dues','tabindex'=>'4')) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <hr />
                    <div class="form-group">
                      <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-block" type="submit" id="add-team">Create</button>
                        <a href="{{ URL::action('TeamController@index') }}" class="btn btn-default btn-block" >Cancel</a>
                      </div>
                    </div>
                  </div>
                </div>
                {{ Form::close() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop
  @section('script')
  <script type="text/javascript">

  $(document).ready(function () {
    $("#early_deadline").kendoDatePicker();
    $("#early_deadline").bind("focus", function () {
      $(this).data("kendoDatePicker").open();
    });
    $("#early_due, #due").kendoNumericTextBox({
      format: "c",
      decimals: 2
    });
    function requestData(player) {
      $.ajax({
        type: "GET",
        dataType: 'json',
        url: "/api/player/" + player, // This is the URL to the API
      }).done(function (data) {
      // When the response to the AJAX request comes back render the chart with new data
      $(".name").text(data.firstname + " " + data.lastname);
      $(".dob").text(data.dob);
      $(".user").text(data.user[0].firstname + " " + data.user[0].lastname + " ("+ data.user[0].pivot.relation+")" );
      $(".email").text(data.user[0].email );
      $(".mobile").text(data.user[0].mobile );
      $(".profile-pic").attr( "src", data.avatar );
    }).fail(function () {
      alert("We have encouter an error, please contact support at support@leaguetogether.com")

    }).always(function () {
      // No matter if request is successful or not, stop the spinner

    });
  }

  $( "#player" ).change(function () {
    var player = $('select[name=player_id]').val();
    if (!player){
      return false;
    }
    requestData(player);
  })
});
</script>
@stop