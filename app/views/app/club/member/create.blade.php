@extends('layouts.club')
@section('style')
{{HTML::style('css/helpers/croppic.css')}}
@stop
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
              <br><br>
              <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#existing" aria-controls="existing" role="tab" data-toggle="tab">Registered Player</a></li>
                  <li role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">New Player</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="existing">

                    <br><br>
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
                            {{ Form::text('firstname',null, array('class' => 'form-control', 'placeholder'=>'First name', 'readonly')) }}
                            {{ Form::hidden('player',null) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Last name</label>
                          <div class="col-sm-9">
                            {{ Form::text('lastname',null, array('class' => 'form-control', 'placeholder'=>'Last name','readonly')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Player's position</label>
                          <div class="col-sm-9">
                            {{ Form::text('position',null, array('class' => 'form-control', 'placeholder'=>'Position','readonly')) }}
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
                            {{ Form::text('email',null, array('class' => 'form-control', 'placeholder'=>'Primary Email', 'readonly')) }}
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
                  <div role="tabpanel" class="tab-pane" id="new">
                    <br><br>
                    {{Form::open(array('action' => array('MemberController@storeNewUser', $club->id), 'class'=>'form-horizontal', 'method' => 'post')) }}
                    <div class="row">
                      <div class="col-xs-12">
                        <h4>Account ID and Password</h4>
                        <p>All fields required</p>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Email</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Confirm password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <h4>Personal Information</h4>
                        <p>All fields required</p>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">First name</label>
                          <div class="col-sm-9">
                            <input type="Text" class="form-control" name="firstname" placeholder="First name">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Last name</label>
                          <div class="col-sm-9">
                            <input type="Text" class="form-control" name="lastname" placeholder="Last name">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Mobile</label>
                          <div class="col-sm-9">
                            <input class="form-control mobile" name="mobile" placeholder="Mobile">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">DOB</label>
                          <div class="col-sm-9">
                            {{ Form::text('dob',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
                            <span id="helpBlock" class="help-block"><small>This is required so that we can comply with the Children’s Online Privacy Protection Act and other age restrictions.</small></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <h4>Player Information</h4>
                        <p>All fields required</p>
                        <br>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">First name</label>
                          <div class="col-sm-9">
                            {{ Form::text('firstname_p',null, array('class' => 'form-control', 'placeholder'=>'First name')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Last name</label>
                          <div class="col-sm-9">
                            {{ Form::text('lastname_p',null, array('class' => 'form-control', 'placeholder'=>'Last name')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Email </label>
                          <div class="col-sm-9">
                            {{ Form::text('email_p',null, array('class' => 'form-control', 'placeholder'=>'Email')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Mobile</label>
                          <div class="col-sm-9">
                            {{ Form::text('mobile_p',null, array('class' => 'form-control mobile', 'placeholder'=>'Mobile')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Player's position</label>
                          <div class="col-sm-9">
                            {{ Form::select('position', ['attack' => 'Attack','midfield' => 'Midfield','defense' => 'Defense','LSM' => 'LSM','goalie' => 'Goalie'],null,array('class' => 'form-control', 'placeholder'=>'Position')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Relationship</label>
                          <div class="col-sm-9">
                            {{ Form::text('relation',null, array('class' => 'form-control', 'placeholder'=>'Ex. father, mother, legal guardian, etc.')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">DOB</label>
                          <div class="col-sm-9">
                            {{ Form::text('dob_p',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Gender</label>
                          <div class="col-sm-9">
                            {{Form::select('gender', array('M' => 'Male', 'F' => 'Female'),null, array('class'=>'form-control'));}}
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-3 control-label">School </label>
                          <div class="col-sm-9">
                            {{ Form::text('school',null, array('class' => 'form-control', 'placeholder'=>'School Name')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Graduation class</label>
                          <div class="col-sm-9">
                            {{ Form::selectRange('year', 2015, 2035, null, array('class'=>'form-control'));}}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">US Lacrosse #</label>
                          <div class="col-sm-9">
                            {{ Form::text('laxid',null, array('class' => 'form-control', 'placeholder'=>'US Lax ID')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">USL # exp. date</label>
                          <div class="col-sm-9">
                            {{ Form::text('laxid_exp',null, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Prefer uniform #</label>
                          <div class="col-sm-9">
                            {{ Form::text('uniform',null, array('class' => 'form-control', 'placeholder'=>'Uniform #')) }}
                            <span id="helpBlock" class="help-block"># is not guaranteed </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12"> 
                        <h4>Address</h4>
                        <p>All fields required</p>
                        <div class="form-group">
                          <label class="col-sm-3 control-label" >Street Address</label>
                          <div class="col-sm-9">
                            {{Form::text('address', '', array('id'=>'address','class'=>'form-control','placeholder'=>'eg. 80 Dolphin St','tabindex'=>'2')) }}
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">City</label>
                          <div class="col-sm-9">
                            {{Form::text('city','',array('id'=>'city','class'=>'form-control','placeholder'=>'eg. New York','tabindex'=>'2')) }}
                          </div>

                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">State</label>
                          <div class="col-sm-9">
                            {{ Form::select('state', State::all()->lists('name','short'), '', array('class'=>"form-control")) }} 
                          </div>

                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Zip</label>
                          <div class="col-sm-9">
                            {{Form::text('zip', '', array('id'=>'zip','class'=>'form-control','placeholder'=>'eg. 83401','tabindex'=>'5')) }}
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12">
                         <div class="form-group">
                          <label class="col-sm-3 control-label">Roster picture</label>
                          <div class="col-sm-9">
                            <div id="upimageclub"></div>
                            <input type="hidden" id="croppic" name="avatar" value="/img/default-avatar.png">
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-xs-12">
                        <hr />
                        <div class="form-group">
                          <div class="col-sm-12 text-right">
                            
                            <button type="submit" class="btn btn-primary btn-outline">Create Member</button>
                            <a href="/" class="btn btn-default">Cancel</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{Form::close()}}
                  </div>
                </div>

              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section('script')
{{ HTML::script('js/helpers/croppic.min.js')}}
<script type="text/javascript">

$(document).ready(function () {
  $(".datepicker").kendoDatePicker();
  $(".datepicker").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });

  $(".mobile").kendoMaskedTextBox({
    mask: "(999) 000-0000"
  });

  $("#deadline").kendoDatePicker();
  $("#deadline").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });
  $(".dollar").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });

  var dataSource = {{$players}};

  $("#player_auto").kendoAutoComplete({
    filter: "contains",
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
      $("[name=email]").val(dataItem.user.email);
      $("[name=player]").val(dataItem.id);
    }
  }).data("kendoAutoComplete");

});

var cropperOptions = {
  doubleZoomControls:true,
  imgEyecandy:true,
  modal:true,
  uploadUrl:'/api/image/upload',
  cropUrl:'/api/image/crop',
  outputUrlId:'croppic',
  onAfterImgUpload:   function(){ console.log(cropperHeader) },
  onAfterImgCrop:     function(){ 
    console.log(cropperHeader['croppedImg']);
    var cropurl = $("#croppic").val();
    $('.user-pic').attr("src", cropurl);
    $(".cropControlRemoveCroppedImage").click(function(){
      $("#croppic").val("/img/default-avatar.png");
      $('.user-pic').attr("src", "/img/default-avatar.png");
    });
  },
  cropData:{
    "url": window.location.origin,
  }
}
var cropperHeader = new Croppic('upimageclub', cropperOptions);

</script>
@stop

