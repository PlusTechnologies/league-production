@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height">
              <h3>Add Coach</h3>
              <p>By adding a coach you will be allowing an existing user to have administrative rights over your team.</a>
              </div>
              <div class="col-xs-7 same-height col-xs-offset-1">
                <h3 class="">Add new Coach</h3>
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
                          <td class="text-right col-xs-3"><h4>Team:</h4></td>
                          <td><h4>{{$team->name}}</h4></td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3"><h4>Program:</h4></td>
                          <td><h4>{{$team->program->name}}</h4></td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3"><h4>Season:</h4></td>
                          <td><h4>{{$team->season->name}}</h4></td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3"><h4>Season Dues:</h4></td>
                          <td><h4>{{$team->due}}</h4></td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3"><h4>Early Bird:</h4></td>
                          <td><h4>{{$team->early_due}} before: {{$team->early_due_deadline}}</h4></td>
                        </tr>
                        <tr>
                          <td class="text-right col-xs-3"><h4>Roster count:</h4></td>
                          <td><h4>{{count($team->players)}}</h4></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                {{ Form::open(array('action' => array('MembersController@store', $team->id),"class"=>"form-horizontal",'id'=>'members','method' => 'post')) }}
                <div class="row">
                  <div class="col-xs-12">
                    <h4>Player</h4>
                    <p>Select a player from the list of users following your club.</p>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <select class="form-control" name="player_id" id="player">
                          <option value="">Select Player</option>
                          @foreach ($followers as $player) 
                          <option value="{{$player->userid}}"> {{$player->firstname}} {{$player->lastname}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4>Player Profile</h4>
                    <p>Player profile information will be display below.</p>
                    <div class="row">
                      <div class="col-sm-6">
                        <img src="" class=" pull-left profile-pic" width=150 height=150>
                      </div>
                      <div class="col-sm-6">
                        <p>
                          <b>Name:</b> <span class="name"></span><br />
                          <b>DOB:</b> <span class="dob"></span><br />
                          <b>Responsable:</b> <span class="user"></span><br />
                          <b>Email:</b> <span class="email"></span><br />
                          <b>Mobile:</b> <span class="mobile"></span>
                        </p>
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
url: "/api/player/"+player, // This is the URL to the API
}).done(function (data) {
// When the response to the AJAX request comes back render the chart with new data

$(".name").text(data.firstname + " " + data.lastname);
$(".dob").text(data.dob);
$(".user").text(data.users[0].firstname + " " + data.users[0].lastname + " ("+ data.users[0].pivot.relation+")" );
$(".email").text(data.users[0].email );
$(".mobile").text(data.users[0].mobile );
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