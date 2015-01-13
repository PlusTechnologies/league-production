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
                                 <td>Amount</td>
                                 <td>${{$transaction->transaction->action->amount}}</td>
                              </tr>
                              <tr>
                                 <td>Service Fee</td>
                                 <td>${{$transaction->transaction->merchant_defined_field}}</td>
                              </tr>
                              <tr>
                                 <td>Status</td>
                                 <td>{{$transaction->transaction->condition}}</td>
                              </tr>
                              <tr>
                                 <td>Description</td>
                                 <td>{{$transaction->transaction->order_description}}</td>
                              </tr>

                              <tr>
                                 <td>Card name</td>
                                 <td>{{$transaction->transaction->first_name}} {{$transaction->transaction->last_name}}</td>
                              </tr>
                              <tr>
                                 <td>Method</td>
                                 <td>{{$transaction->transaction->cc_number}}</td>
                              </tr>
                              <tr>
                                 <td>Date</td>
                                 <td>{{$payment->created_at}}</td>
                              </tr>

                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <h4>History</h4>
                     <br>
                     <div class="table-responsive">
                        <table class="table table-user-information" id="grid">
                           <tbody>
                              <tr>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tbody>
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
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <hr />
                     <div class="form-group">
                        <div class="col-sm-12 text-right">
                           <a href="{{URL::action('ClubController@index')}}" class="btn btn-default">Cancel</a>
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
      "iDisplayLength": 10
  });

});
</script>

@stop