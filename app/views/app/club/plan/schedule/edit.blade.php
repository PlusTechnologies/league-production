@extends('layouts.club')
@section('content')
<div class="container container-last">
  <div id="same-height-wrapper">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="col-md-4 signup-col same-height">
          <h3>Edit Schedule Payment</h3>
          <p></p>
        </div>
        <div class="col-md-7 same-height col-md-offset-1">
          <h3>Update Schedule</h3>
          <p></p>
          {{Form::open(array('action' => array('PlanScheduleController@update', $schedule->id), 'class'=>'form-horizontal', 'method' => 'put')) }}
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
          <div class="row">
            <div class="col-xs-12">
              <h4>Schedule</h4>
              <p>General Information.</p>

              <div class="form-group">
                <label class="col-sm-3 control-label">Date</label>
                <div class="col-sm-9">
                  {{ Form::text('date',$schedule->date, array('class' => 'form-control datepicker', 'placeholder'=>'MM/DD/YYYY')) }}
                  <span id="helpBlock" class="help-block">Payment will be process at 6:00am CT.</span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Amount</label>
                <div class="col-sm-9">
                  {{ Form::text('amount', $schedule->total, array('class' => 'disabled dollar', 'disabled'=>'disabled')) }}
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-9">
                  {{ Form::text('description', $schedule->description, array('class' => 'form-control disabled', 'disabled'=>'disabled')) }}
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Authorized by</label>
                <div class="col-sm-9">
                  {{ Form::text('accepted_by', $schedule->member->accepted_by, array('class' => 'form-control disabled', 'disabled'=>'disabled')) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Authorized Date</label>
                <div class="col-sm-9">
                  {{ Form::text('accepted_on', $schedule->member->accepted_on, array('class' => 'form-control disabled', 'disabled'=>'disabled')) }}
                </div>
              </div><a href=""></a>

            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <hr />
              <div class="form-group">
                <div class="col-sm-12 text-right">
                  <button type="submit" class="btn btn-primary btn-outline">Update Payment</button>
                  <a href="{{URL::action('AccountingController@index')}}" class="btn btn-default">Cancel</a>
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
@stop
@section('script')
<script type="text/javascript">

$(document).ready(function () {
  $(".datepicker").kendoDatePicker();
  $(".datepicker").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });

  $("#amount, .dollar").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });
});
</script>
@stop