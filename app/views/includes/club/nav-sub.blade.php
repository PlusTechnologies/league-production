<nav role="sub-navigation" class="account-subnavigation clear-fix ng-scope">
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <ul class="nav navbar-nav navbar-left ng-scope col-sm-12" ng-controller="SubnavCtrl">
          <li class="{{ HTML::smart_link('account.club') }} col-xs-1">
            <a href="{{URL::action('ClubController@index')}}">
              <span class="icon-am retinaicon-essentials-006"></span> 
              <span class="subnav-link-name ng-scope" translate="">Overview</span>
            </a>
          </li>
          <li class="{{ HTML::smart_link('account.club.teams.index') }} col-xs-1">
            <a href="{{URL::action('TeamController@index')}}">
              <span class="icon-am retinaicon-communication-006"></span> 
              <span class="subnav-link-name ng-scope">Teams</span>
            </a>
          </li>
          <li class="{{ HTML::smart_link('account.club.event.index') }} col-xs-1">
            <a href="{{URL::action('EventoController@index')}}">
              <span class="icon-am retinaicon-essentials-042"></span>
              <span class="subnav-link-name ng-scope">Events</span>
            </a>
          </li>
          <li class="{{ HTML::smart_link('account.club.follower.index') }} col-xs-1">
            <a href="{{URL::action('FollowerController@index')}}">
              <span class="icon-am retinaicon-social-brands-035"></span>
              <span class="subnav-link-name ng-scope">Followers</span>
            </a>
          </li>
          <li class="{{ HTML::smart_link('account.club.accounting.index') }} col-xs-1">
            <a href="{{URL::action('AccountingController@index')}}">
              <span class="icon-am retinaicon-business-026"></span>
              <span class="subnav-link-name ng-scope">Accounting</span>
            </a>
          </li>
          <li class="{{ HTML::smart_link('account.club.settings') }} col-xs-1">
            <a href="{{URL::action('ClubController@settings')}}">
              <span class="icon-am retinaicon-essentials-007"></span> 
              <span class="subnav-link-name ng-scope">Settings</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>