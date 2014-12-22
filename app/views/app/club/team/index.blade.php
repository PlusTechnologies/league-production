@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Team Management</h2>
          <p>
            Review the most relevant information about your teams.
          </p>
          <br />
          <a href="{{URL::action('TeamController@create') }}" class="btn btn-primary btn-outline">Create Team</a>
        </div>
        <div class="col-sm-7 ">
          <div class="row">
            <div class="col-sm-4">
              <div class="tile blue">
                <h3 class="title"></h3>
                <p>YTD Sales</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="tile red">
                <h3 class="title"></h3>
                <p>Receivables</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="tile green">
                <h3 class="title"></h3>
                <p>Players</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <div class="row ">
        <div class="col-sm-12">
          <h3>Programs</h3>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <table class="table" id="grid">
            <thead>
              <tr>
                <th data-field="id">Program Name</th>
                <th data-field="date">Teams</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($program as $group)
              <tr>
                <td class="col-sm-2">{{$group->name}}</td>
                <td class="col-sm-2">
                  
                </td>
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
  });
});
</script>
@stop