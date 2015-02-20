@extends('layouts.club')
@section('style')
{{HTML::style('css/helpers/croppic.css')}}
@stop
@section('content')
<div class="container container-last">
   <div id="same-height-wrapper">
      <div class="row">
         <div class="col-md-10 col-md-offset-1">
            <div class="col-md-4 signup-col same-height">
               <h3>Transaction Details</h3>
               <p></p>
            </div>
            <div class="col-md-7 same-height col-md-offset-1">
               <h3>Details</h3>
               <p></p>
               {{Form::open(array('action' => array('EventoController@store'), 'class'=>'form-horizontal', 'method' => 'post')) }}
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
                     <h4>General Information</h4>
                     <br>
                     <div class="table-responsive">
                        <table class="table table-user-information">
                           <tbody>
                              <tr>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tbody>
                           <tbody>
                              <tr>
                                 <td><b>Amount</b></td>
                                 @if(count($action) > 1)

                                 @foreach($action as $item)
                                 @if($item->action_type == 'sale' || $item->action_type == 'refund' )
                                 <td>${{$item->amount}}</td>
                                 @endif
                                 @endforeach

                                 @else
                                 <td>${{$action->amount}}</td>
                                 @endif
                              </tr>
                              <tr>
                                 <td><b>Type</b></td>
                                 @if(count($action) > 1)

                                 @foreach($action as $item)
                                 @if($item->action_type == 'sale' || $item->action_type == 'refund' )
                                 <td>{{$item->action_type}}</td>
                                 @endif
                                 @endforeach

                                 @else
                                 <td>{{$action->action_type}}</td>
                                 @endif
                              </tr>

                              <tr>
                                 <td><b>Service Fee</b></td>
                                 <td>${{$transaction->merchant_defined_field}}</td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td>{{$transaction->condition}}</td>
                              </tr>
                              <tr>
                                 <td><b>Description</b></td>
                                 <td>{{$transaction->order_description}}</td>
                              </tr>

                              <tr>
                                 <td><b>Card name</b></td>
                                 <td>{{$transaction->first_name}} {{$transaction->last_name}}</td>
                              </tr>
                              <tr>
                                 <td><b>Method</b></td>
                                 <td>{{$transaction->cc_number}}</td>
                              </tr>
                              <tr>
                                 <td><b>Date</b></td>
                                 <td>{{$payment->created_at}}</td>
                              </tr>

                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <h4>History {{count($action)}}</h4>
                     <br>
                     
                     <table class="table table-user-information table-striped" id="grid">
                        <thead>
                           <tr>
                              <td>Date</td>
                              <td>ID</td>
                              <td>Player</td>
                              <td>Type</td>
                              <td>Total</td>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($history as $item)
                           <tr data-id="{{$item->transaction}}">
                              <td>{{$item->created_at}}</td>
                              <td>{{$item->transaction}}</td>
                              <td>{{$item->player->firstname}} {{$item->player->lastname}}</td>
                              <td>{{$item->type}}</td>
                              <td>{{$item->subtotal}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <hr />
                     <div class="form-group">
                        <div class="col-sm-12 text-right">
                           <a href="{{URL::action('ClubController@index')}}" class="btn btn-default">Cancel</a>

                           @if($transaction->action->action_type == 'sale')
                           <a href="{{URL::action('AccountingController@refund', $payment->transaction)}}" class="btn btn-danger btn-outline">Refund</a>
                           @endif

                           
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

$(function () {
   $('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/club/accounting/transaction/" + $(this).data("id"));
 });
   $('#grid').DataTable({
      "aLengthMenu": [[10, 25, 75, -1], [10, 25, 75, "All"]],
      "iDisplayLength": 10,
      "aoColumns": [
      null,
      { "bSortable": false },
      { "bSortable": false },
      { "bSortable": false },
      { "bSortable": false }
      ]
   });

});
</script>

@stop