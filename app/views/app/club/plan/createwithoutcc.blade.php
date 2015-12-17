@extends('layouts.club.default')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-xs-10 col-sm-offset-1">
      <div id="same-height-wrapper">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-xs-4 signup-col same-height">
              <h3>Create Payment Plan</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In odio tortor, hendrerit nec sapien at, sollicitudin accumsan lorem.</p>
            </div>
            <div class="col-xs-7 same-height col-xs-offset-1">
              <h2 class="text-right text-danger"> Balance Due:  {{$member->due}}</h2>
              <hr>
              <h3 class="">Create New Recurring Plan.</h3>
              <p>All fields are required</p>
              <br>
              @if($errors->has())
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="alert alert-warning alert-dismissable">
                      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                      <ul>
                        @foreach ($errors->all() as $error) 
                        <li>{{$error}}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              {{ Form::open(array('action' => array('PlanController@validation',$team->id, $member->id),"class"=>"form-horizontal",'id'=>'new_team','method' => 'post')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h4>Credit Card Information</h4>
                  <p>Team Membership default information.</p>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Card Number</label>
                    <div class="col-sm-9">
                      {{Form::text('card', '', array('id'=>'card','class'=>'form-control','placeholder'=>'Valid Card Number','tabindex'=>'1')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Expiration Month</label>
                    <div class="col-sm-9">
                      {{ Form::selectMonth('month','',array('class'=>'form-control', 'tabindex'=>'2')) }}

                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">Expiration Year</label>
                    <div class="col-sm-9">
                      {{ Form::selectYear('year', 2015, 2025, '', array('class'=>'form-control', 'tabindex'=>'3')) }}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">CVV</label>
                    <div class="col-sm-9">
                      {{Form::text('cvv', '', array('id'=>'cvc','class'=>'form-control','placeholder'=>'CVV','tabindex'=>'4')) }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <h4>Billing Address</h4>
                  <p>Team Membership default information.</p>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Street Address</label>
                    <div class="col-sm-9">
                      {{Form::text('address1', '', array('id'=>'address','class'=>'form-control','placeholder'=>'eg. 80 Dolphin St','tabindex'=>'5')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">City</label>
                    <div class="col-sm-9">
                      {{Form::text('city','',array('id'=>'city','class'=>'form-control','placeholder'=>'eg. New York','tabindex'=>'6')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">State</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="state">
                        <option value="AL">Alabama</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Billing Zip</label>
                      <div class="col-sm-9">
                        {{Form::text('zip', '', array('id'=>'zip','class'=>'form-control','placeholder'=>'eg. 83401','tabindex'=>'8')) }}
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <div class="col-sm-12 text-right">
                      <button class="btn btn-primary btn-block" type="submit" id="add-team">Authorize Payment</button>
                    </div>
                  </div>
                </div>
              </div>
              {{ Form::close() }}
              

              {{ Form::open(array('action' => array('TeamController@store'),"class"=>"form-horizontal",'id'=>'new_team','method' => 'post')) }}
              <div class="row">
                <div class="col-xs-12">
                  <h4>Subscription Plan</h4>
                  <p>Team Membership default information.</p>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Frequency</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="frequency" id="frequency">
                        @foreach ($frequency as $i)
                        <option value="{{$i->id}}">{{$i->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Start</label>
                    <div class="col-sm-9">
                      {{Form::text('start', '', array('id'=>'start','class'=>'form-control kendo-datepicker','placeholder'=>'Start', 'tabindex'=>'2')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">End</label>
                    <div class="col-sm-9">
                      {{Form::text('end', '', array('id'=>'end','class'=>'form-control kendo-datepicker','placeholder'=>'End', 'tabindex'=>'3')) }}
                    </div>
                  </div>
                  <hr>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <h4>Plan Summary</h4>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Frequency</th>
                        <th>Start</th>
                        <th>End</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Recurrences</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="result_frecuency"></td>
                        <td class="result_start"></td>
                        <td class="result_end "></td>
                        <td class="result_subtotal text-center"></td>
                        <td class="result_total text-center"></td>
                        <td class="result_recurrences text-center"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <h4>Payment Schedule Dates</h4>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Next Payment</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody class ="sc_dates">
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <hr />
                  <div class="form-group">
                    <div class="col-sm-12 text-right">
                      <a href="" class="btn btn-primary btn-block btn-success load-summary" data-mem="{{$member->id}}">Preview Plan</a>
                      <a href="{{ URL::action('TeamController@index') }}" class="btn btn-primary btn-block" >Cancel</a>
                    </div>
                  </div>
                </div>
              </div>
              {{ Form::close() }}
            </div>
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

  dateTimePicker = new DateTimePicker();

  $("#start, #end").kendoDatePicker({
    min: new Date(2015, 0, 1);
  });
  $("#start, #end").bind("focus", function () {
    $(this).data("kendoDatePicker").open();
  });
  $("#amount").kendoNumericTextBox({
    format: "c",
    decimals: 2
  });


  function summaryData(fre, sta, end, mem) {

    $.ajax({
      type: "GET",
      dataType: 'json',
      url: "/api/plan", // This is the URL to the API
      data: { frequency: fre, start:sta, end:end, member:mem}
      }).done(function (data) {
      $(".result_frecuency").text(data.frequency);
      $(".result_start").text(data.start);
      $(".result_end").text(data.end);
      $(".result_subtotal").text(data.subtotal);
      $(".result_total").text(data.total);
      $(".result_recurrences").text(data.recurrences);
      $(".sc_dates").html("");
      $.each( data.dates, function( key,value ) {
        $(".sc_dates").append("<tr><td>"+ value+ "</td><td>" + data.total + "</td></tr>");;
      });
      }).fail(function () {
      alert("Error occured, please check the dates.");
      }).always(function () {

      });
    } //end of function

//Plan Summary
$('.load-summary').click(function(e){
  e.preventDefault();
  var el = $(this);
  mem = el.data('mem');
  fre = $("#frequency").val();
  sta = $("#start").val();
  end = $("#end").val();


  if(!sta || !end || !fre){
    alert("Please complete all the fields")
    return false;
  }
//type = el.attr('data-type');
summaryData(fre, sta, end, mem) ;
});

});
</script>
@stop