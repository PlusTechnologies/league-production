@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Events</h2>
          <p>
            Review the most relevant information about your club.
          </p>
          <br />
          <a href="{{URL::action('EventoController@create')}}" class="btn btn-primary btn-outline">Create Event</a>    
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">$95,000.00</h3>
                <p>YTD Sales</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
          <h3>Event History</h3>
          <hr />
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2" data-field="date">Created</th>
                <th class="col-sm-1" data-field="id">Type</th>
                <th class="col-sm-4" data-field="name">Name</th>
                <th class="col-sm-2" data-field="e_date">Date</th>
                <th class="col-sm-1" data-field="fee">Fee</th>
                <th class="col-sm-2" data-field="status">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($events as $event)
              <tr class="clickable" data-id="{{$event->id}}">
                <td class="col-sm-2">{{$event->created_at}}</td>
                <td class="col-sm-1">{{$event->type->name}}</td>
                <td class="col-sm-4">{{$event->name}}</td>
                <td class="col-sm-2">{{$event->date}}</td>
                <td class="col-sm-1">{{$event->fee}}</td>
                <td class="col-sm-2">{{$event->status['name']}}</td>
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
<script id="rowTemplate" type="text/x-kendo-tmpl">
<tr class="clickable" data-id="#:id #">
  <td class="col-sm-2">#: created_at #</td>
  <td class="col-sm-1">#: type.name #</td>
  <td class="col-sm-4">#: name #</td>
  <td class="col-sm-2">#: date #</td>
  <td class="col-sm-1">#: fee #</td>
  <td class="col-sm-2">#: status #</td>
</tr>
</script>
<script type="text/javascript">
$(function () {
  $('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/club/event/" + $(this).data("id"));
  });
  $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5
  });

  // $("#grid").kendoGrid({
  //   dataSource: {{$events}},
  //   scrollable: true,
  //   sortable: true,
  //   pageable: {
  //     pageSize: 5
  //   },
  //   rowTemplate: kendo.template($("#rowTemplate").html()),
  // });
});
</script>
@stop