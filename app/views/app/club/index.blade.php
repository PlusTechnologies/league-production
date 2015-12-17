@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Overview </h2>
          <p>
            Review the most relevant information about your club.
          </p>
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">${{$sales->ytdSales($club->id)}}</h3>
                <p>YTD Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile red">
                <h3 class="title">${{$sales->arSales($club->id)}}</h3>
                <p>Receivables</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row">
        <div class="col-md-12">
          <h3>Recent Payments</h3>
          <hr />
          <table class="table table-striped table-condensed" id="grid">
            <thead>
              <tr>
                <th class="col-md-2">Date</th>
                <th class="col-md-2">Transaction</th>
                <th>Player</th>
                <th>Type</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payments as $payment)
              <tr data-id="{{$payment->transaction}}" class="clickable">
                <td>{{$payment->created_at}}</td>
                <td>{{$payment->transaction}}</td>
                <td>{{$payment->player->firstname}} {{$payment->player->lastname}}</td>
                <td>{{$payment->type}}</td>
                <td>${{number_format($payment->subtotal, 2)}}</td>
              </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
@stop
@section("script")
<script type="text/javascript">
$(function () {
  $('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/club/accounting/transaction/" + $(this).data("id"));
  });
  $('#grid').DataTable({
      "aLengthMenu": [[10, 25, 75, -1], [10, 25, 75, "All"]],
      "iDisplayLength": 10,
      "order": [[ 0, "desc" ]]
  });
});
</script>
@stop