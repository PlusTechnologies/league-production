@extends('layouts.club')
@section('style')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h3>Confirmation, are you sure?</h3>
          <p>We will refund this transaction, this action cannot be reverse.</p>

          <br>
          {{Form::open(array('action' => array('AccountingController@doRefund', $payment->transaction), 'class'=>'', 'method' => 'post')) }}
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

          @if(Session::has('error'))
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="alert alert-dismissable">
                  <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                  <p class="text-danger">{{Session::get('error')}}</p>
                </div>
              </div>
            </div>
          </div>
          @endif


          <h4>Refund</h4>
          <p>Eligible amount: {{$payment->subtotal}}</p>
          <div class="form-group">
              <label class="control-label">Amount:</label>
            {{ Form::text('amount',null, array('class' => 'dollar', 'placeholder'=>'Refund amount')) }}
          </div>

          <div class="row">
            <div class="col-xs-12">
              <hr />
              <div class="form-group">
                <button type="submit" class="btn btn-danger btn-outline">Refund</button>
                  <a href="{{URL::action('AccountingController@transaction', $payment->transaction)}}" class="btn btn-primary btn-outline">Cancel</a>

              </div>
            </div>
          </div> 
          {{Form::close()}}
        </div>
        <div class="col-sm-7">
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section('script')
<script type="text/javascript">

$(function () {

  $(".dollar").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });


});
</script>
@stop
