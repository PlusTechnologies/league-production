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
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In odio tortor, hendrerit nec sapien at, sollicitudin accumsan lorem.</p>
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
                        <td class="text-right col-xs-3">Plan:</td>
                        <td>{{$team->plan->name}}</td>
                      </tr>
                      <tr>
                        <td class="text-right col-xs-3">Roster count:</td>
                        <td>{{count($team->players)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <hr>
              {{ Form::open(array('action' => array('MemberController@store', $team->id),"class"=>"form-horizontal",'id'=>'members','method' => 'post')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h4>Search Player</h4>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Search</label>
                    <div class="col-sm-9">
                      {{ Form::text('search',null, array('class' => 'form-control', 'placeholder'=>'Name',"id"=>"player_auto")) }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <h4>Player Information</h4>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">First name</label>
                    <div class="col-sm-9">
                      {{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'First name', 'disabled'=>'disabled')) }}
                      {{ Form::hidden('player',null) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Last name</label>
                    <div class="col-sm-9">
                      {{ Form::text('lastname',null, array('class' => 'form-control', 'placeholder'=>'Last name','disabled'=>'disabled')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Player's position</label>
                    <div class="col-sm-9">
                      {{ Form::text('position',null, array('class' => 'form-control', 'placeholder'=>'Position','disabled'=>'disabled')) }}
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
                      {{ Form::text('email',null, array('class' => 'form-control', 'placeholder'=>'Primary Email', 'disabled'=>'disabled')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Secondary email</label>
                    <div class="col-sm-9">
                      {{ Form::text('second_email',null, array('class' => 'form-control', 'placeholder'=>'Secondary Email')) }}
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-xs-12">
                  <h4>Custom Membership details (optional)</h4>
                  <p>Add a custome payment for this player.</p>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Early Bird Dues</label>
                    <div class="col-sm-9">
                      {{Form::text('early_due', '', array('class'=>'dollar','placeholder'=>'Early Bird Dues', 'tabindex'=>'5')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Deadline</label>
                    <div class="col-sm-9">
                      {{Form::text('early_due_deadline', '', array('id'=>'deadline','class'=>'form-control kendo-datepicker','placeholder'=>'MM/DD/YYYY', 'tabindex'=>'6')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Dues</label>
                    <div class="col-sm-9">
                      {{Form::text('due', '', array('class'=>'dollar','placeholder'=>'Team Dues','tabindex'=>'7')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Payment Plan</label>
                    <div class="col-sm-9">
                      {{ Form::select('plan_id', [null=>'Please Select']+ $plan,'', array('class' => 'form-control', 'tabindex'=>'8') ) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <hr />
                  <div class="form-group">
                    <div class="col-sm-12 text-right">
                      <button class="btn btn-primary btn-outline" type="submit" id="add-team">Create Member</button>
                      <a href="{{ URL::action('TeamController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
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
  $(".dollar").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });

  var dataSource = {{$players}};

  $("#player_auto").kendoAutoComplete({
    dataTextField:"fullname",
    dataSource: dataSource,
    minLength: 1,
    template: function (a) {
      return '<div class="select-option row"><span class="avatar col-xs-2"><img src="'+a.avatar+'" width=50 height=50/></span><span class="name col-xs-8">'+a.firstname + ' ' + a.lastname + '</span></div>';
    },
    select: function (e) {
      var dataItem = this.dataItem(e.item.index());
      $("[name=firstname]").val(dataItem.firstname);
      $("[name=lastname]").val(dataItem.lastname);
      $("[name=position]").val(dataItem.position);
      $("[name=email]").val(dataItem.useremail);
      $("[name=player]").val(dataItem.id);
    }
  }).data("kendoAutoComplete");

});
</script>
@stop