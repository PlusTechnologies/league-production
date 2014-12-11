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
                <th data-field="player">Player</th>
                <th data-field="description">Description</th>
                <th data-field="last">Card</th>
                <th data-field="amount">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-sm-2">10/15/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s1wZ</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
              <tr>
               <td class="col-sm-2">10/14/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s11Z</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
              <tr>
               <td class="col-sm-2">10/15/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s1wZ</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
              <tr>
               <td class="col-sm-2">10/14/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s11Z</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
              <tr>
               <td class="col-sm-2">10/15/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s1wZ</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
              <tr>
                <td class="col-sm-2">10/14/2014 12:13:13pm</td>
                <td class="col-sm-2">432QjsuQT5s11Z</td>
                <td class="col-sm-1">Josh Doe</td>
                <td class="col-sm-4">Membership</td>
                <td class="col-sm-1">Ending 2312</td>
                <td class="col-sm-2">$1,300.00</td>
              </tr>
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