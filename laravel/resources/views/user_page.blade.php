<!DOCTYPE html>
<html lang="en">
<head>
	<title>!GeoComb!</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/style.css') }}">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

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
        <li class="active"><a href="{{action('LandingController@index')}}">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="{{action('Auth\LoginController@index')}}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <li><a href="{{action('Auth\RegisterController@index')}}"><span class="glyphicon glyphicon-log-in"></span> Register</a></li>
        <li><a href="{{action('Auth\LoginController@logout')}}"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">
      <!--<p><a href="#">Link</a></p>
      <p><a href="#">Link</a></p>
      <p><a href="#">Link</a></p>-->
    </div>
    <div class="col-sm-8 text-left">
      <h1 class="center">Welcome {{$name}}</h1>
      

      <p>Add vehicle</p>
      <form method="POST" action="{{ url('/add_vehicle') }}" autocomplete="on">
      {{ csrf_field() }}
        <input type="text" name="brand" placeholder="Brand"><br>
        <input type="text" name="model" placeholder="Model"><br>
        <input type="text" name="color" placeholder="Color"><br>
        <input type="text" name="fuel" placeholder="Fuel"><br>
        <input type="number" step="0.1" name="consumption" placeholder="Consumption"><br>
        <a href="{{action('UserPageController@add')}}"><button name="Add">Add</button></a>
      </form>
        
    </div>

    <div class="col-sm-2 sidenav">
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
