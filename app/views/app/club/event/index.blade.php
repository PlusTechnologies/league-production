@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Event Management</h2>
          <p>
            Review the most relevant information about your club.
          </p>
          <br />
          <a href="{{URL::action('EventoController@create')}}" class="btn btn-primary btn-outline">Create Event</a>    
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">${{$sales->ytdSalesEvents($club->id)}}</h3>
                <p>YTD Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile green">
                <h3 class="title">{{$events->count()}}</h3>
                <p>Events</p>
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
          <table class="table table-striped table-condensed" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2" data-field="date">Created</th>
                <th class="col-sm-1" data-field="id">Type</th>
                <th class="col-sm-6" data-field="name">Name</th>
                <th class="col-sm-1" data-field="e_date">Date</th>
                <th class="col-sm-1" data-field="fee">Fee</th>
                <!-- <th class="col-sm-1" data-field="status">Status</th> -->
                <th class="col-sm-1" data-field="fee">Capacity</th>
              </tr>
            </thead>
            <tbody>
              @foreach($events as $event)
              <tr class="clickable" data-id="{{$event->id}}">
                <td>{{$event->created_at}}</td>
                <td>{{$event->type->name}}</td>
                @if($event->parent)
                <td> {{$event->parent->name}} : {{$event->name}}</td>
                @else
                <td>{{$event->name}}</td>
                @endif
                <td>{{$event->date}}</td>
                <td>{{$event->fee}}</td>
                <!-- <td>{{$event->status['name']}}</td> -->
                <td>{{$event->participants->count()}} of {{$event->max}}</td>
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
    window.location = ("/account/club/event/" + $(this).data("id"));
  });
  $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5,
      "tableTools": {
            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
        },
      "order": [[ 0, "desc" ]]
  });
});
</script>
@stop