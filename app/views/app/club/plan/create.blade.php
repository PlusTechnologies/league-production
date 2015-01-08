@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height">
              <h3>Create Payment Plan</h3>
              <p></p>
            </div>
            <div class="col-xs-7 same-height col-xs-offset-1">
              <hr>
              <h3 class="">New Payment Plan.</h3>
              <p>All fields are required</p>
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
              @if(Session::has('warning'))
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="alert alert-dismissable">
                      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                      <p class="text-danger">{{Session::get('warning')}}</p>
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
              {{ Form::open(array('action' => array('PlanController@store'),"class"=>"form-horizontal",'id'=>'new_team','method' => 'post')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h4>Billing Plan</h4>
                  <p>Membership default information.</p>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Plan Name</label>
                    <div class="col-sm-9">
                      {{Form::text('name', '', array('class'=>'form-control','placeholder'=>'Name', 'tabindex'=>'2')) }}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Full amount</label>
                    <div class="col-sm-9">
                      {{ Form::text('total',null, array('class' => 'dollar')) }}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Initial amount</label>
                    <div class="col-sm-9">
                      {{ Form::text('initial',null, array('class' => 'dollar')) }}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Recurring</label>
                    <div class="col-sm-9">
                      {{ Form::text('recurring',null, array('class' => 'dollar')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Frequency</label>
                    <div class="col-sm-9">
                      {{ Form::select('frequency_id', $frequency,3, array('class' => 'form-control') ) }}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">On day</label>
                    <div class="col-sm-9">
                      {{ Form::selectRange('on', 1, 31, 1, array('class'=>'form-control','tabindex'=>'2')) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <hr />
                  <div class="form-group">
                    <div class="col-sm-12 text-right">

                      <button type="submit" class="btn btn-primary btn-outline">Create Plan</button>
                      <a href="{{ URL::action('PlanController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
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
  $("#start, #end").kendoDatePicker({
    min: new Date()
  });
  $("#start, #end").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });
  $("#amount, .dollar").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });
});



</script>
@stop