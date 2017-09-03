<!DOCTYPE html>
<html lang="en">
<head>
	<title>GeoComb</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link	href="{{ URL::asset('css/style.css') }}" rel="stylesheet">

<script
			  src="https://code.jquery.com/jquery-1.12.4.min.js"
			  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
			  crossorigin="anonymous"></script>
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{asset('js/bootstrap3-typeahead.js')}}"></script>

	</head>

<body>

<!-- Fixed navbar-->
		<div class="navbar navbar-inverse navbar-static-top">
				<div class="container">
					<nav class="navbar navbar-main">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>

						<div class="navbar-collapse collapse">

							<ul class="nav navbar-nav navbar-left">
								<a class="navbar-brand" href="{{route('home')}}"><img src="../../files/gasico.png" style="heihgt: 35px; width: 35px;"></img></a>
								<li><a href="{{route('home')}}"><h4>GeoComb - O Preço dos Combustíveis</h4></a></li>
							</ul>

						<ul class="nav navbar-nav navbar-right">
							@if (Auth::user())
	          		<li><a href="{{route('planRoute')}}"><h4>My Area</h4></a></li>
	        		@endif

							@if (Auth::guest())
								<li><a href="{{ route('login') }}"><h4>Login</h4></a></li>
								<li><a href="{{ route('register') }}"><h4>Registar</h4></a></li>
							@else
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										{{ Auth::user()->name }} <span class="caret"></span>
	                </a>
									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="{{ route('logout') }}" onclick="event.preventDefault();
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
						</div><!--/.nav-collapse -->
						</nav>
					</div>
				</div>
