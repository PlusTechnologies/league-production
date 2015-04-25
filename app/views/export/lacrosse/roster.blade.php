<table class="table table-striped table-condensed" id="grid">
  <thead>
    <tr>
      <th>Added on</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>DOB</th>
      <th>Position</th>
      <th>Uniform #</th>
      <th>School</th>
      <th>Class</th>
      <th>Lax ID</th>
      <th>Lax Exp</th>
      <th>User First Name</th>
      <th>User Last Name</th>
      <th>Relationship</th>
      <th>Email</th>
      <th>Mobile</th>

      <th>Address</th>
      <th>City</th>
      <th>State</th>
      <th>Zip</th>

      <th>Contact 1 First Name</th>
      <th>Contact 1 Last Name</th>
      <th>Contact 1 Relationship</th>
      <th>Contact 1 Email</th>
      <th>Contact 1 Mobile</th>

      <th>Contact 2 First Name</th>
      <th>Contact 2 Last Name</th>
      <th>Contact 2 Relationship</th>
      <th>Contact 2 Email</th>
      <th>Contact 2 Mobile</th>

      <th>Contact 3 First Name</th>
      <th>Contact 3 Last Name</th>
      <th>Contact 3 Relationship</th>
      <th>Contact 3 Email</th>
      <th>Contact 3 Mobile</th>

      <th>Contact 4 First Name</th>
      <th>Contact 4 Last Name</th>
      <th>Contact 4 Relationship</th>
      <th>Contact 4 Email</th>
      <th>Contact 4 Mobile</th>

      <th>Contact 5 First Name</th>
      <th>Contact 5 Last Name</th>
      <th>Contact 5 Relationship</th>
      <th>Contact 5 Email</th>
      <th>Contact 5 Mobile</th>

    </tr>
  </thead>
  <tbody>
    @foreach($members as $member)
    <tr>
      <td>{{$member->created_at}}</td>
      <td>{{$member->player->firstname}}</td>
      <td>{{$member->player->lastname}}</td>
      <td>{{$member->player->dob}}</td>
      <td>{{$member->player->position}}</td>
      <td>{{$member->player->uniform}}</td>
      <td>{{$member->player->school}}</td>
      <td>{{$member->player->year}}</td>
      <td>{{$member->player->laxid}}</td>
      <td>{{$member->player->laxid_exp}}</td>
      <td>{{$member->player->user->profile->firstname}}</td>
      <td>{{$member->player->user->profile->lastname}}</td>
      <td>{{$member->player->relation}}</td>
      <td>{{$member->player->user->email}}</td>
      <td>{{$member->player->user->profile->mobile}}</td>
      <td>{{$member->player->address}}</td>
      <td>{{$member->player->city}}</td>
      <td>{{$member->player->state}}</td>
      <td>{{$member->player->zip}}</td>

      <td>{{$member->player->user->profile->firstname}}</td>
      <td>{{$member->player->user->profile->lastname}}</td>
      <td>{{$member->player->relation}}</td>
      <td>{{$member->player->user->email}}</td>
      <td>{{$member->player->user->profile->mobile}}</td>




      @foreach($member->player->contacts as $contact)
      <td>{{$contact->firstname}}</td>
      <td>{{$contact->lastname}}</td>
      <td>{{$contact->relation}}</td>
      <td>{{$contact->email}}</td>
      <td>{{$contact->mobile}}</td>
      @endforeach

    </tr>
    @endforeach
  </tbody>
</table>
