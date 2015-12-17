@extends('layouts.club')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="row">
        <div class="col-sm-5">
          <h2>Plans Management</h2>
          <p>
            Review the most relevant information about your Plans.
          </p>
          <br />
          <a href="{{URL::action('PlanController@create') }}" class="btn btn-primary btn-outline">Create Plan</a>
        </div>
        <div class="col-sm-7 ">

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
                <th>Created</th>
                <th>Plan Name</th>
                <th>Total amount</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($plans as $plan)
              <tr class="clickable" data-id="{{$plan->id}}">
                <td>{{$plan->created_at}}</td>
                <td>{{$plan->name}}</td>
                <td>{{$plan->total}}</td>
                <td class="text-right"><a href="{{URL::action('PlanController@delete', array($plan->id))}}" class="btn btn-sm btn-danger btn-delete pop-up"><i class="fa fa-trash-o"></i> <small>Remove</small></a></td>
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
    window.location = ("/account/club/plan/" + $(this).data("id") + "/edit");
  });
  $('#grid').DataTable({
      "aLengthMenu": [[5, 25, 75, -1], [5, 25, 75, "All"]],
      "iDisplayLength": 5,
  });
});
</script>
@stop