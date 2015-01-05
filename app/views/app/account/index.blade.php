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
                <p>Balance Due</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row">
        <div class="col-md-12">
          <h3>Recent Payments</h3>
          <hr />
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2">Date</th>
                <th class="col-sm-2">Transaction</th>
                <th class="col-sm-6">Description</th>
                <th class="col-sm-1 text-right">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payment as $item)
              <tr>
                <td>{{$item->created_at}}</td>
                <td>{{$item->transaction}}</td>
                <td> 
                  @foreach($item->items as $data)
                  {{$data->description}}
                  @endforeach
                </td>
                <td class="text-right">{{$item->total}}</td>
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
  $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5
  });
});
</script>
@stop