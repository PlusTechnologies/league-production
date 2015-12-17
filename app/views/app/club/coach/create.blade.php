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
                        @if($team->plan)
                        <td>{{$team->plan->name}}</td>
                        @else
                        <td>No plan</td>
                        @endif
                      </tr>
                      <tr>
                        <td class="text-right col-xs-3">Roster count:</td>
                        <td>{{count($team->members)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <hr>
              {{ Form::open(array('action' => array('CoachController@store', $team->id),"class"=>"form-horizontal",'id'=>'members','method' => 'post')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h4>Search User</h4>
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
                  <h4>Coach Information</h4>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">First name</label>
                    <div class="col-sm-9">
                      {{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'First name', 'readonly')) }}
                      {{ Form::hidden('user',null) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Last name</label>
                    <div class="col-sm-9">
                      {{ Form::text('lastname',null, array('class' => 'form-control', 'placeholder'=>'Last name','readonly')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Mobile</label>
                    <div class="col-sm-9">
                      {{ Form::text('mobile',null, array('class' => 'form-control', 'placeholder'=>'Position','readonly')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                      {{ Form::text('email',null, array('class' => 'form-control', 'placeholder'=>'Position','readonly')) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <hr />
                  <div class="form-group">
                    <div class="col-sm-12 text-right">
                      <button class="btn btn-primary btn-outline" type="submit" id="add-team">Create Coach</button>
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
  var dataSource = {{$usersData}};

  $("#player_auto").kendoAutoComplete({
    filter: "contains",
    dataTextField:"fullname",
    dataSource: dataSource,
    minLength: 1,
    template: function (a) {
      return '<div class="select-option row"><span class="avatar col-xs-2"><img src="'+a.user.profile.avatar+'" width=50 height=50/></span><span class="name col-xs-8">'+a.fullname + '</span></div>';
    },
    select: function (e) {
      var dataItem = this.dataItem(e.item.index());
      $("[name=firstname]").val(dataItem.user.profile.firstname);
      $("[name=lastname]").val(dataItem.user.profile.lastname);
      $("[name=mobile]").val(dataItem.user.profile.mobile);
      $("[name=email]").val(dataItem.user.email);
      $("[name=user]").val(dataItem.user.id);
    }
  }).data("kendoAutoComplete");

});
</script>
@stop