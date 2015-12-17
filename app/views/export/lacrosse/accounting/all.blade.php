<table class="table table-striped table-condensed" id="grid">
  
  <tbody>
    <tr>
      <td><img src="img/league-together-logo.jpg" /> </td>
      <td valign="middle" ><h2>LEAGUETOGETHER</h2></td>
    </tr>
  </tbody>
</table>
<table class="table table-striped table-condensed" id="grid">
  <thead>
    <tr>
      <th>Date</th>
      <th>Type</th>
      <th>Condition</th>
      <th>Response</th>
      <th>Transaction</th>
      <th>Description</th>
      <th>Card Number</th>
      <th>Card First Name</th>
      <th>Card Last Name</th>
      <th>Email</th>
      <th>Mobile</th>
      <th>Amount</th>
      <th>Service</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($payments as $payment)
    <tr>
      <td>{{date('Y-m-d H:i:s', strtotime($payment->action->date))}}</td>
      <td>{{$payment->condition}}</td>
      <td>{{$payment->action->action_type}}</td>
      <td>{{$payment->action->response_text}}</td>
      <td>{{$payment->transaction_id}}</td>
      <td>{{$payment->order_description}}</td>
      <td>{{$payment->cc_number}}</td>
      <td>{{$payment->first_name}}</td>
      <td>{{$payment->last_name}}</td>
      <td>{{$payment->email}}</td>
      <td>{{$payment->phone}}</td>
      @if($payment->action->action_type == 'refund')
      <td>{{$payment->action->amount }}</td>
      <td>0.00</td>
      @else
      <td></td>
      <td>{{$payment->merchant_defined_field}}</td>
      @endif
      <td>{{$payment->action->amount}}</td>
    </tr>
    @endforeach

  </tbody>
</table>
<br>
<br>