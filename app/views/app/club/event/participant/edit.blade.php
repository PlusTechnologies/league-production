@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height">
              <h3>Update Player Participant</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In odio tortor, hendrerit nec sapien at, sollicitudin accumsan lorem.</a>
              </div>
              <div class="col-xs-7 same-height col-xs-offset-1">
                <h3 class="">Update Player Participant</h3>
                <p><b>Instructions:</b> Please read carefully all the instructions to succefully build your team roster. All fields are required</p>
                <br>
                @if($errors->has())
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="alert alert-dismissable">
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
                @if(Session::has('notice'))
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="alert alert-dismissable">
                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                        <p class="text-success">{{Session::get('notice')}}</p>
                      </div>
                    </div>
                  </div>
                </div>
                @endif

              
                {{ Form::open(array('action' => array('ParticipantController@update', $event->id, $participant->id),"class"=>"form-horizontal",'id'=>'members','method' => 'put')) }}

                <div class="row">
                  <div class="col-xs-12">
                    <h4>Event details</h4>
                    <p>Update Event for this player.</p>

                    <p>
                      <b>Player:</b> {{$participant->player->firstname}} {{$participant->player->lastname}} <br>
                    </p>

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Event</label>
                      <div class="col-sm-9">
                        {{ Form::select('event_id', $club->events->lists('name', 'id'), $event->id, array('class' => 'form-control', 'tabindex'=>'8') ) }}
                      </div>
                    </div>

                  </div>
                </div>
                
                {{-- <div class="row">
                  <div class="col-xs-12">
                    <h4>Membership Payment details</h4>
                    <p>Add a custome payment for this player.</p>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Early Bird Dues</label>
                      <div class="col-sm-9">
                        {{Form::text('early_due',$member->early_due , array('class'=>'','id'=>'early_due','placeholder'=>'Early Bird Dues', 'tabindex'=>'3')) }}
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Deadline</label>
                      <div class="col-sm-9">

                        {{Form::text('early_due_deadline', $member->early_due_deadline, array('id'=>'early_deadline','class'=>'form-control kendo-datepicker','placeholder'=>'Deadline', 'tabindex'=>'3')) }}

                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Team Dues</label>
                      <div class="col-sm-9">
                        {{Form::text('due', $member->due, array('id'=>'due','placeholder'=>'Team Dues','tabindex'=>'4')) }}
                      </div>
                    </div>
                  </div>
                </div> --}}

                <div class="row">
                  <div class="col-xs-12">
                    <hr />
                    <div class="form-group">
                      <div class="col-sm-12 text-right">
                        <a href="{{URL::action('EventoController@show', $event->id) }}" class="btn btn-default" >Cancel</a>
                        <button type="submit" class="btn btn-primary btn-outline">Update Participant</button>
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