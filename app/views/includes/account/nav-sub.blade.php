<nav role="sub-navigation" class="account-subnavigation clear-fix ng-scope">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <ul class="nav navbar-nav navbar-left ng-scope col-sm-12" ng-controller="SubnavCtrl">
          <li class="{{ HTML::smart_link('account.club') }} col-xs-1">
            <a href="{{URL::action('AccountController@index')}}">
              <span class="icon-am retinaicon-essentials-006"></span> 
              <span class="subnav-link-name ng-scope" translate="">Overview</span>
            </a>
          </li>



          <!-- <li>
            <a href="">
              <span class="icon-am retinaicon-finance-001"></span> 
              <span class="subnav-link-name ng-scope">Payments</span>
            </a>
          </li>-->
          <li > 
            <a href="{{URL::action('PlayerController@index')}}">
              <span class="icon-am retinaicon-communication-006"></span>
              <span class="subnav-link-name ng-scope">Players</span>
            </a>
          </li>

          @if($user->teams->count() > 0)
          <li class="{{ HTML::smart_link('account.club.teams.index') }} col-xs-1">
            <a href="{{URL::action('TeamController@indexCoach')}}">
              <span class="icon-am retinaicon-business-012"></span> 
              <span class="subnav-link-name ng-scope">Teams</span>
            </a>
          </li>
          @endif

          <!-- <li >
            <a href="">
              <span class="icon-am retinaicon-business-042"></span>
              <span class="subnav-link-name ng-scope">Clubs</span>
            </a>
          </li>
          <li>
            <a href="">
              <span class="icon-am retinaicon-essentials-127"></span>
              <span class="subnav-link-name ng-scope">Games Schedule</span>
            </a>
          </li> -->
          <li>
            <a href="{{URL::action('AccountController@settings')}}">
              <span class="icon-am retinaicon-essentials-007"></span> 
              <span class="subnav-link-name ng-scope">Settings</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>