@extends('layouts.account')
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
              
            </div>
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">$00.00</h3>
                <p>Account Balance</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row">
        <div class="col-md-12">
          <h3>Recent Payments</h3>
          <hr />
          <table class="table" id="grid">
            <thead>
              <tr>
                <th data-field="date">Date</th>
                <th data-field="id">Transaction</th>
                <th data-field="amount">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payment as $item)
              <tr>
                <td class="col-sm-2">{{$item->created_at}}</td>
                <td class="col-sm-2">{{$item->transaction}}</td>
                <td class="col-sm-2">{{$item->total}}</td>
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
$("#grid").kendoGrid({
    scrollable: true,
    sortable: true,
    pageable: {
      pageSize: 5
    }
  });
});
</script>
@stop