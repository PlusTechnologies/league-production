
<nav class="nav-user">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="org-thumb">
          {{HTML::image($user->profile->avatar, $user->profile->firstname, array('class'=>'','width'=>85));}}
          <span class="user-name-title">{{$user->profile->firstname}} {{$user->profile->lastname}}</span>
        </div>
      </div>
    </div>
  </div>
</nav>



