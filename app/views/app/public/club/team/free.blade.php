@extends('layouts.public')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 receipt-bg">
      <div class="maintitle-receipt">
        <h2 class="text-center">Receipt</h2>
        <span class="border"></span>
      </div>
      <div class="row">
        <div class="col-sm-4 col-md-offset-4 text-center receipt-icon">
          <h2><i class="retinaicon-finance-028"></i></h2>
          Thank you
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <p> Congratulation, your player was added to the team!
          </p>
        </div>
        @foreach($products as $item)
        <div class="col-md-8 col-md-offset-2">
          <p>{{$item->name}}
            <span class="pull-right">{{money_format('%.2n',$item->price)  }}</span>
          </p>
        </div>
        @endforeach
        <div class="col-md-8 col-md-offset-2">
          <hr>
          <p>Subtotal
            <span class="pull-right">{{money_format("%.2n",0)  }}</span>
          </p>
        </div>
        <div class="col-md-8 col-md-offset-2">
          <p>Processing fees
            <span class="pull-right">{{money_format("%.2n",0)}}</span>
          </p>
        </div>
        <div class="col-md-8 col-md-offset-2">
          <br>
          <h5>Total <span class="pull-right" >{{money_format('%.2n',0)  }}</span></h5>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="row">
            <div class="col-md-6">
              <label><b>Invoice to:</b></label>

              <p>{{$user->profile->firstname}} {{$user->profile->lastname}} <br>
                {{$user->email}}<br>
                {{$user->mobile}}
              </p>
              
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="row space-top-2"></div>
      <div class="row">
        <p class="text-center">
          <small>
            <em>Copyright &copy; 2014 League Together, All rights reserved.</em><br>
            <br>
            <br>
            <br>
          </small>
        </p>
      </div>
    </div>
  </div>

</div>
@stop

