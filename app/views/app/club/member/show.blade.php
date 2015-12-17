@extends('layouts.club.default')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height ">
              <h2>Player's Profile</h2>
              <hr>
              <img src="{{$member->Player->avatar}}" width=200 class="member-photo">
              <h3>{{$member->Player->firstname}} {{$member->Player->lastname}}</h3>
              <hr>
              <p>
              Position:   <span class="pull-right">{{$member->Player->position}} </span><br>
              DOB:        <span class="pull-right">{{$member->Player->dob}}      </span><br>
              Gender:     <span class="pull-right">{{$member->Player->gender}}   </span><br>
              Graduation: <span class="pull-right">{{$member->Player->year}}     </span><br>
              Lax ID:     <span class="pull-right">{{$member->Player->laxid}}    </span>
              </p>
              <hr>
              <p>
                Responsable: 
                  <span class="pull-right">
                    <a href="mailto:{{$player->User[0]->email}}">{{$player->User[0]->firstname}} {{$player->User[0]->lastname}}</a>
                  </span>
                <br>
                Relationship: 
                <span class="pull-right">{{$player->User[0]->pivot->relation}}</span>
                <br>
                Contact: 
                <span class="pull-right">{{$player->User[0]->mobile}}</span>
              </p>
              <hr>
            </div>
            <div class="col-xs-7 same-height col-xs-offset-1">

              <div class="row">
                <div class="col-xs-12">
                  <h3 class="">Payment Due</h3>
                  <p>Please read below information about current player membership status.</p>
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <tr>
                        <th>Added</th>
                        <th>Description</th>
                        <th>Regular</th>
                        <th>Early Bird</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{$member->created_at}}</td>
                        <td class="col-xs-3">Membership</td>
                        <td>{{$member->due}}</td>
                        <td>{{$member->early_due}}</td>
                        <td>{{$member->payment_complete}}</td>
                      </tr>
                      <tr>
                      </tr>
                    </tbody>
                    <small class="text-muted">*Early Registration Deadline {{$member->early_due_deadline}}</small>
                  </table>
                  <hr>
                  <a href="{{URL::action('MembersController@edit', array($team->id, $member->id))}}" class="btn btn-primary">Edit Payment Due</a>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-xs-12">
                  <h3 class="">Schedule Payments (Recurring Payment)</h3>
                  <p>Please read below information about current player membership status.</p>
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <tr>
                        <th>Next Payment</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($plan))
                       @foreach($plan->schedulepayments as $schedule)
                      <tr>
                        <td>{{$schedule->date}}</td>
                        <td>{{$schedule->total}}</td>
                        <td>{{$schedule->status}}</td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
              <hr>
              @if(isset($plan))
              {{ Form::open(array('action' => array('PlanController@destroy', $team->id, $member->id, $plan->id),"class"=>"form-horizontal",'id'=>'members','method' => 'DELETE')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h3 class="">Remove Plan</h3>
                  <p>Be aware that this action will remove all scheduled payments from this member, payments incurred will not be remove or transfer to other teams.</p>
                  <hr>
                  <button type="submit" class="btn btn-danger">Remove Plan</button>
                </div>
              </div>
              {{ Form::close() }}
              @else
              <a href="{{URL::action('PlanController@create', array($team->id, $member->id))}}" class="btn btn-primary">Create plan</a>
              @endif
              



              <br>
              <div class="row">
                <div class="col-xs-12">
                  <h3 class="">Payments History</h3>
                  <p>Please read below information about current player membership status.</p>
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Transaction</th>
                        <th>Credit Card</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{$member->created_at}}</td>
                        <td>2jaskdfh298347d</td>
                        <td>Ending 3344</td>
                        <td>{{$member->due}}</td>
                        <td>{{$member->payment_complete}}</td>
                      </tr>
                      <tr>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <hr>
              {{ Form::open(array('action' => array('MembersController@destroy', $team->id, $member->id),"class"=>"form-horizontal",'id'=>'members','method' => 'DELETE')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h3 class="">Remove Player</h3>
                  <p>Be aware that this action will remove the player from this team, payments incurred will not be remove or transfer to other teams. Any remaining recurring payments scheduled will be removed.</p>
                  <hr>
                  <button type="submit" class="btn btn-danger">Remove Player</button>
                </div>
              </div>
              {{ Form::close() }}

            </div> <!-- End Inner large column-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section('script')
<script type="text/javascript">

$(document).ready(function () {

});
</script>
@stop