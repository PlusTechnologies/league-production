@extends('layouts.club')
@section('content')
<div class="container container-last">
  <div id="same-height-wrapper">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="col-md-4 signup-col same-height">
          <h3>Edit Plan</h3>
          <p></p>
        </div>
        <div class="col-md-7 same-height col-md-offset-1">
          <h3>Update Plan</h3>
          <p></p>
          {{Form::open(array('action' => array('PlanController@update', $plan->id), 'class'=>'form-horizontal', 'method' => 'put')) }}
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
          <div class="row">
            <div class="col-xs-12">
              <h4>Billing Plan</h4>
              <p>Membership default information.</p>
              <div class="form-group">
                <label class="col-sm-3 control-label">Plan Name</label>
                <div class="col-sm-9">
                  {{Form::text('name', $plan->name, array('class'=>'form-control','placeholder'=>'Name', 'tabindex'=>'1')) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Full amount</label>
                <div class="col-sm-9">
                  {{ Form::text('total',$plan->total, array('class' => 'dollar','tabindex'=>'2')) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Initial amount</label>
                <div class="col-sm-9">
                  {{ Form::text('initial',$plan->initial, array('class' => 'dollar','tabindex'=>'3')) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Recurring</label>
                <div class="col-sm-9">
                  {{ Form::text('recurring',$plan->recurring, array('class' => 'dollar','tabindex'=>'4')) }}
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Frequency</label>
                <div class="col-sm-9">
                  {{ Form::select('frequency_id', $frequency,$plan->frequency_id, array('class' => 'form-control', 'tabindex'=>'5') ) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">On day</label>
                <div class="col-sm-9">
                  {{ Form::selectRange('on', 1, 31, $plan->getOriginal('on'), array('class'=>'form-control','tabindex'=>'6')) }}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <hr />
              <div class="form-group">
                <div class="col-sm-12 text-right">
                  <button type="submit" class="btn btn-primary btn-outline">Update Plan</button>
                  <a href="{{ URL::action('PlanController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
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