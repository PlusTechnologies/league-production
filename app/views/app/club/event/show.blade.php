@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>{{$event->name}}</h2>
          <br>
          <a href="{{URL::action('EventoController@edit', $event->id)}}" class="btn btn-primary btn-outline">Edit</a> 
          <a href="mailto:{{$emailList}}" class="btn btn-primary btn-outline">
            Announcement
          </a> 
          <a href="{{URL::action('EventoController@duplicate', $event->id)}}" class="btn btn-primary btn-outline">Duplicate</a> 
          <a href="{{URL::action('EventoController@delete', $event->id)}}" class="btn btn-danger btn-outline">Delete</a>
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">${{number_format($event->participants->sum('total'), 2)}}</h3>
                <p>Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile red">
                <h3 class="title">{{$event->participants->count()}}</h3>
                <p>Registered</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row">
        <div class="col-md-12">
          <h3>Event Details</h3>
          <div class="row">
            <div class="col-md-8">
              <div class="table-responsive">
                <table class="table table-striped ">
                  <thead>
                    <tr>
                      <th class="col-md-4"></th>
                      <th class="col-md-2"></th>
                      <th class="col-md-3"></th>
                      <th class="col-md-3"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-right"><b>Event date:</b></td>
                      @if($event->end == $event->date || !$event->end)
                      <td colspan='3'>{{$event->date}}</td>
                      @else
                      <td colspan='3'>{{$event->date}} to {{$event->end}}</td>
                      @endif
                    </tr>
                    @if($schedule->count() > 0)
                    <tr>
                      <td class="text-right"><b>Event schedule:</b></td>
                      <td colspan='3'>
                       @foreach($schedule as $date => $item)
                       @foreach($item as $time)
                       {{$time->startTime}} to {{$time->endTime}} &nbsp; | &nbsp; {{$date}}<br>
                       @endforeach
                       @endforeach
                     </td>
                   </tr>
                   @endif

                   <tr>
                    <td class="text-right"><b>Registration fee:</b></td>
                    <td>{{$event->fee}}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><b>Open registration:</b></td>
                    <td >{{$event->open}}</td>
                    <td class="text-right"><b>Close registration:</b></td>
                    <td>{{$event->close}}</td>
                  </tr>
                  @if($event->early_fee)
                  <tr>
                    <td class="text-right"><b>Early registration:</b></td>
                    <td>{{$event->early_fee}}</td>
                    <td class="text-right"><b>Before:</b></td>
                    <td>{{$event->early_deadline}}</td>
                  </tr>
                  @endif
                  <tr>
                    <td class="text-right"><b>Location:</b></td>
                    @if($event->location)
                    <td colspan='3'>{{$event->location}}</td>
                    @else
                    <td colspan='3'>TBD</td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
            <p>Shared registration link</p>
            {{ Form::text('name',Request::root()."/club/$club->id/event/$event->id", array('class' => 'form-control block-input')) }}
            <br>
          </div>

          <div class="col-md-4">
            @if($event->location)
            <div id="map_canvas"> </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="row print-area">
      <div class="col-md-12">
        <h3>Registered Players</h3>
        <hr />
        <table class="table table-striped" id="grid">
          <thead>
            <tr>
              <th class="col-sm-2">Created</th>
              <th class="col-sm-2">Transaction ID</th>
              <th class="col-sm-3">Player</th>
              <th class="col-sm-2">Position</th>
              <th class="col-sm-2">Amount</th>
              <th class="col-sm-1">Remove</th>
            </tr>
          </thead>
          <tbody>
            @foreach($event->participants as $item)
            <tr class="clickable" data-id="{{$item->playerid}}">
              <td>{{$item->created_at}}</td>
              <td>{{$item->transaction}}</td>
              <td>{{$item->pfirstname}} {{$item->plastname}}</td>
              <td>{{$item->position}}</td>
              <td>${{number_format($item->total, 2) }}</td>
              <td class="text-right"><a href="{{URL::action('ParticipantController@delete', array($event->id, $item->paymentid))}}" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-trash-o"></i></a></td>
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
    window.location = ("/account/club/player/" + $(this).data("id"));
  });
  $('#grid').DataTable({
    "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
    "iDisplayLength": 5,
    dom: 'T<"clear">lfrtip',
    tableTools: {
            "aButtons": ["print" ]
    }
  });
  
});
</script>
@include('shared.geomap')
@stop