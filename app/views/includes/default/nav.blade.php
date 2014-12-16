<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><span>League</span><span class="logo-red">together&#0153;</span></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#camps-tryouts">Features</a></li>
            <li><a href="#management">About</a></li>
            <li><a href="#communications">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if(!Auth::check())
            <li><a href="{{ URL::route('create') }}">Create Account</a></li>
            <li><a href="{{ URL::route('login') }}">Login</a></li>
            @else
            @if(Auth::user()->hasRole('administrator'))
            <li><a href="/account/administrator/club/create">Create account</a></li>
            <li><a href="{{ URL::action('logout') }}">Logout</a></li>
            @endif
            @if(Auth::user()->hasRole('club owner'))
            <li><a href="/account/club">Account</a></li>
            <li><a href="{{ URL::action('logout') }}">Logout</a></li>
            @endif
            @if(Auth::user()->hasRole('default'))
            <li><a href="/account/">Account</a></li>
            <li><a href="{{ URL::action('logout') }}">Logout</a></li>
            @endif
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>
  <div class="bottom-border"></div>
</nav>