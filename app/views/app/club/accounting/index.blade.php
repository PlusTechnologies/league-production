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
                <h3 class="title">${{$sales->ytdSales($club->id)}}</h3>
                <p>YTD Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile green">
                <h3 class="title">${{$sales->arSales($club->id)}}</h3>
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
                      <option value="2">Scheduled Payments</option>
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
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="tile purple">
                      <h3 class="title" id="result_sales"></h3>
                      <p>Total Sales</p>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          
          <br>
          {{Form::open(array('action' => array('ExportController@report' ),'id'=>'exportDates','method' => 'post')) }}
          {{Form::hidden('expFrom')}}
          {{Form::hidden('expTo')}}
          {{Form::hidden('expType')}}
          <h3>
            Details
            <span>
              <div class="btn-group pull-right">
                <button type="button" class="btn btn-default btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Export &nbsp; <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#" id="export" > <i class="fa fa-file-excel-o"> </i>&nbsp; Excel</a></li>
                  <li><a href="javascript:;"onclick="alert('Coming soon..')" > <i class="fa fa-download"> </i>&nbsp; QuickBooks</a></li>
                </ul>
              </div> 
            </span>
          </h3>
          {{Form::close()}}
          <hr />
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
      loadData();
    } else {
      alert('Please make sure you entered valid dates');
    }


  });

  $('#export').click(function(e){
    e.preventDefault();
    if (validator.validate()) {
      exportData();
    } else {
      alert('Please make sure you entered valid dates');
    }
  });

  function newTable(){
    $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5,
    });
  }

  function exportData(){

    var from = $( "input[name=from]" ).val();
    var to = $( "input[name=to]" ).val();
    var type = $( "select[name=type]" ).val();

    $( "input[name=expFrom]").val(from);
    $( "input[name=expTo]").val(to);
    $( "input[name=expType]").val(type);


    if(from =="" || to==""){
      alert("Please enter a date in the form fields");
      return false;
    }

    $("#exportDates").submit();

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
      $('#grid').dataTable().fnDestroy();
      $("#targetData").html("");
      var totalsum =0;
      console.log(type);
      
      switch(type) {
        case "1":
        $("#grid thead tr").html("");
        $("#grid thead tr").html('<th class="col-md-2">Date</th><th class="col-md-2">Transaction</th><th>Player</th><th>Type</th><th>Total</th>');
        $.each(data, function(item, dt) {
          $("#targetData").append('<tr data-id="'+ dt.transaction +'" class="clickable"><td>'+ dt.created_at +'</td><td>'+ dt.transaction +'</td><td>'+ dt.player.lastname +", "+ dt.player.firstname+'</td><td>'+ dt.type +'</td><td>$'+ kendo.toString(dt.subtotal, "n") +'</td></tr>');
          totalsum += parseFloat(dt.subtotal);
        });

        kendo.culture("en-US");
        var sumtotal =  kendo.toString(totalsum, "n");
        $("#result_sales").html("");
        $("#result_sales").html("$" +  sumtotal);

        $('#grid').delegate('tbody > tr', 'click', function (e) {
          window.location = ("/account/club/accounting/transaction/" + $(this).data("id"));
        });

        newTable();

        break;
        case "2":
        $("#grid thead tr").html("");
        $("#grid thead tr").html('<th class="col-md-2">Date</th><th class="col-md-4">Description</th><th>Player</th><th>Amount</th><th>Total</th>');
        $.each(data, function(item, dt) {
          $("#targetData").append('<tr data-id="'+ dt.id +'" class="clickable"><td>'+ dt.date +'</td><td>'+ dt.description +'</td><td>'+ dt.member.lastname +", "+ dt.member.firstname+'</td><td>$'+ kendo.toString(dt.subtotal, "n") +'</td><td>$'+ kendo.toString(dt.total, "n") +'</td></tr>');
          totalsum += parseFloat(dt.subtotal);
        });

        kendo.culture("en-US");
        var sumtotal =  kendo.toString(totalsum, "n");
        $("#result_sales").html("");
        $("#result_sales").html("$" +  sumtotal);

        $('#grid').delegate('tbody > tr', 'click', function (e) {
          window.location = ("/account/club/plan/schedule/" + $(this).data("id") + "/edit");
        });

        newTable();

        break;
        
        default:
        break;
      }

    });

request.fail(function( jqXHR, textStatus ) {
  alert( "Request failed: " + textStatus );
});
}


});
</script>
@stop