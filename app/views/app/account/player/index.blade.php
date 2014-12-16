@extends('layouts.account')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Players</h2>
          <p>
            Review the most relevant information about your players.
          </p>
          <br />
          <a href="{{URL::action('PlayerController@create')}}" class="btn btn-primary btn-outline">Add Player</a>    
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">{{$players->count()}}</h3>
                <p>Total Players</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
          <h3>Players</h3>
          <hr />
          <table class="table table-striped" id="grid">
            <thead>
              <tr>
                <th class="col-sm-2" data-field="date">Created</th>
                <th class="col-sm-4" data-field="name">Name</th>
                <th class="col-sm-2" data-field="e_date">DOB</th>
                <th class="col-sm-1" data-field="fee">Position</th>
                <th class="col-sm-2" data-field="status">Relationship</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($players as $item)
              <tr class="clickable" data-id="">
                <td class="col-sm-2">{{$item->created_at}}</td>
                <td class="col-sm-4">{{$item->firstname}} {{$item->lastname}}</td>
                <td class="col-sm-2">{{$item->dob}}</td>
                <td class="col-sm-1">{{$item->position}}</td>
                <td class="col-sm-2">{{$item->relation}}</td>
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
      "iDisplayLength": 5
  });

});
</script>
@stop