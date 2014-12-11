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
          <a href="{{URL::action('EventoController@invite', $event->id)}}" class="btn btn-primary btn-outline">Invite</a> 
          <a href="{{URL::action('EventoController@duplicate', $event->id)}}" class="btn btn-primary btn-outline">Duplicate</a> 
          <a href="{{URL::action('EventoController@delete', $event->id)}}" class="btn btn-danger btn-outline">Delete</a>
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">$95,000.00</h3>
                <p>Sales</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile red">
                <h3 class="title">50</h3>
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
                <table class="table">
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
                      <td>{{$event->date}}</td>
                      <td class="text-right"><b>Event time:</b></td>
                      <td>{{$event->startTime}} -  {{$event->endTime}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Registration fee:</b></td>
                      <td>{{$event->fee}}</td>
                      <td class="text-right"><b>Early registration:</b></td>
                      <td>{{$event->early_fee}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Early registration deadline:</b></td>
                      <td>{{$event->early_deadline}}</td>
                      <td class="text-right"><b>Open registration:</b></td>
                      <td>{{$event->open}}</td>
                    </tr>
                    <tr>
                      <td class="text-right"><b>Close registration:</b></td>
                      <td>{{$event->close}}</td>
                      <td class="text-right"><b>Location:</b></td>
                      <td>{{$event->location}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p>Shared registration link</p>
              {{ Form::text('name',Request::root()."/club/$club->id/event/$event->id", array('class' => 'form-control block-input')) }}
              <br>
            </div>
            <div class="col-md-4">
              <div id="map_canvas"> </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h3>Registered Players</h3>
          <hr />
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2">Created</th>
                <th class="col-sm-2">Transaction ID</th>
                <th class="col-sm-2">User</th>
                <th class="col-sm-1">Relationship</th>
                <th class="col-sm-2">Player</th>
                <th class="col-sm-1">Amount</th>
                <th class="col-sm-1">Method</th>
              </tr>
            </thead>
            <tbody>
              <tr class="clickable" data-id="">
                  <td>2014-12-07 04:43:51</td>
                  <td>asjdfk234hkjhsadjhf</td>
                  <td>Don Smith</td>
                  <td>Father</td>
                  <td>Antony Smith</td>
                  <td>$350.50</td>
                  <td>Visa 7336</td>
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
  $('#grid').delegate('tbody > tr', 'click', function (e) {
    window.location = ("/account/club/event/" + $(this).data("id"));
  });
  $('#grid').DataTable({
    "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
    "iDisplayLength": 5
  });
});
</script>
@include('shared.geomap')
@stop