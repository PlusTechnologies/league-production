@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Accounting</h2>
          <p>Easily generate transactions reports.</p><br />
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">${{number_format($payment->sum('subtotal'), 2)}}</h3>
                <p>YTD Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile green">
                <h3 class="title">${{number_format($payment->sum('subtotal'), 2)}}</h3>
                <p>Receivables</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->

      <br>
      <div class="row">
        <div class="col-md-12">
          <h3>Transactions</h3>
          <hr />
          <form class="form-inline " role="form">
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label">Report</label>
                  <div class="">
                    <select class="form-control">
                      <option value="1">All activity</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">From</label>
                  <div class="">
                    <input type="Text" class="form-control datepicker" name="name" placeholder="DD/MM/YY">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">To</label>
                  <div class="">
                    <input type="Text" class="form-control datepicker" name="name" placeholder="DD/MM/YY">
                  </div>
                </div>
                <div class="form-group pull-right">
                  <label class="control-label"></label>
                  <div class="">
                    <button type="submit" class="btn btn-primary btn-outline">Generate</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <br>
          <hr>
          <br>
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2" data-field="date">Created</th>
                <th class="col-sm-1" data-field="id">Type</th>
                <th class="col-sm-3" data-field="name">Name</th>
                <th class="col-sm-2" data-field="e_date">Date</th>
                <th class="col-sm-1" data-field="fee">Fee</th>
                <th class="col-sm-1" data-field="status">Status</th>
                <th class="col-sm-1" data-field="fee">Capacity</th>
              </tr>
            </thead>
            <tbody>

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
  $(".datepicker").kendoDatePicker();
  $(".datepicker").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });
  $('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/club/event/" + $(this).data("id"));
  });
  $('#grid').DataTable({
    "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
    "iDisplayLength": 5,
    "tableTools": {
      "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
    }
  });
});
</script>
@stop