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
          <form class="form-horizontal " role="form" id="DateRange">
            <div class="row">
              <div class="col-xs-5">
                <div class="form-group">
                  <label class="col-md-3 control-label">Report</label>
                  <div class="col-md-9">
                    <select class="form-control" name="type">
                      <option value="1">All activity</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label">From <span class="k-invalid-msg" data-for="from"></span></label>
                  <div class="col-md-9">
                    <input type="Text" class="form-control datepicker" name="from" placeholder="DD/MM/YY" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label">To  <span class="k-invalid-msg" data-for="to"></span></label>
                  <div class="col-md-9">
                    <input type="Text" class="form-control datepicker" name="to" placeholder="DD/MM/YY" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12 ">
                    <button type="submit" id="load" class="btn btn-primary btn-outline pull-right">Generate</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <hr>
          <br>
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-md-2">Date</th>
                <th class="col-md-2">Transaction</th>
                <th>Player</th>
                <th>Type</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody id="targetData">

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
  var validator = $(".datepicker").kendoValidator().data("kendoValidator");
  validator.hideMessages();

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
  $('#load').click(function(e){
    e.preventDefault();
    if (validator.validate()) {
      loadData(function(){
        newTable();
      });
    } else {
      alert('Please make sure you entered valid dates');
    }

    
  });
  function newTable(){
    $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5,
      "tableTools": {
        "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
      }
    });
  }

  function loadData(){

    var from = $( "input[name=from]" ).val();
    var to = $( "input[name=to]" ).val();
    var type = $( "select[name=type]" ).val();

    if(from =="" || to==""){
      alert("Please enter a date in the form fields");
      return false;
    }

    var request = $.ajax({
      url: "accounting/report",
      type: "POST",
      data: { from: from, to:to, type:type },
      dataType: "json"
    });

    request.done(function( data ) {

     $.each(data, function(item, dt) {

       $("#targetData").append('<tr data-id="'+ dt.transaction +'"><td>'+ dt.created_at +'</td><td>'+ dt.transaction +'</td><td>'+ dt.player.firstname +'</td><td>'+ dt.type +'</td><td>'+ dt.total +'</td></tr>');
       $('#grid').dataTable().fnDestroy();
       
     });


   });

    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
  }


});
</script>
@stop