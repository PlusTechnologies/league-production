@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Follower Management</h2>
          <p>
            View detail information about the users following your club.
          </p>
          <br />
          <a href="{{URL::action('FollowerController@create')}}" class="btn btn-primary btn-outline">Add Follower</a>    
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-6">
              <div class="tile blue">
                <h3 class="title">{{number_format(count($followers))}}</h3>
                <p>Follower Users</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="tile green">
                <h3 class="title">{{number_format(count($players))}}</h3>
                <p>Follower Players</p>
              </div>
            </div>
          </div>
        </div><!-- end of col-sm-7 row -->
      </div><!-- end of first row -->
      <br>
      <div class="row">
        <div class="col-md-12">
          <h3>Register Users</h3>
          <hr />
          <table class="table table-striped table-condensed" id="grid">
            <thead>
              <tr>
                <th>Created</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
              </tr>
            </thead>
            <tbody>
              @foreach($followers as $follower)
              <tr class="clickable" data-id="{{$follower->user->id}}">
                <td>{{$follower->user->created_at}}</td>
                <td>{{$follower->user->profile->lastname}}, {{$follower->user->profile->firstname}}</td>
                <td>{{$follower->user->email}}</td>
                <td>{{$follower->user->profile->mobile}}</td>
                <td>
                  @foreach( $follower->user->roles as $role )
                  {{$role->name}}
                  @endforeach
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
    window.location = ("/account/club/user/" + $(this).data("id"));
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