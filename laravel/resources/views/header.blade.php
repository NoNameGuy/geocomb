<!DOCTYPE html>
<html lang="en">
<head>
	<title>!GeoComb!</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/style.css') }}">
  <script src="{{asset('js/bootstrap3-typeahead.js')}}"></script>
<script async defer
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap" >
          </script>

	</head>
					<body><nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="{{route('home')}}">Home</a></li>
        <li><a href="#">About</a></li>
        @if (Auth::user())
          <li><a href="{{route('userpage')}}">My Area</a></li>
        @endif
      </ul>
      <ul class="nav navbar-nav navbar-right">







@if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
{{--
                                        <li><a href="{{route('users.view', [Auth::user()->id])}}"><i class="fa fa-btn fa-user"></i>My Profile</a></li>
                                        <li><a href="{{route('advertisements.create')}}"><i class="fa fa-btn fa-plus"></i>Create Advertisement</a></li>
                                        <li><a href="{{route('bids.list')}}"><i class="fa fa-btn fa-credit-card"></i>My Bids</a></li>
                                @if (Auth::user()->admin)
                                        <li><a href="{{route('admin.list')}}"><i class="fa fa-btn fa-briefcase"></i>Admin Page</a></li>
                                @endif
                                        <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
--}}
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif














      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">

    </div>
