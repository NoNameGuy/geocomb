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
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
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
      <h1 class="center">Preço dos Combustiveis</h1>
      <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>-->
			<a href="{{action('LandingController@fetchData')}}">Fetch data Aveiro</a>
			<a href="{{action('LandingController@fetchStationData')}}">Fetch data 165954 station</a>
			<a href="{{action('LandingController@mapsApi')}}">Maps Api</a>
			<a href="{{action('LandingController@fetchStationID')}}">STATION_ID</a>

			<hr>

			<h2 class="center">Pesquisar Postos:</h2>
		<div class="row">
			<div class="col-sm-6">
        <p class="center"> Por Localização :</p>
				<br>
				<select>
					<?php echo "Distritos: ";
            foreach ($districts as $key => $district) {
            	echo "<option> $district->name </option>";
            }
          ?>
				</select>

				<div id='mapDiv'></div>
					<script type="text/javascript"
				src="http://js.sapo.pt/Snippets/Maps.js?lat=39.50&lon=-8.09&zoom=6.5&height=650&width=450&divid=mapDiv">
					</script>


        {!! Form::open(['url' => '/', 'class' => 'form-horizontal']) !!}


          <input type="text" name="location" placeholder="Current Location" />
          <input type="text" name="radius" placeholder="Radius: 5km" />

          {!! Form::submit("Pesquisar")  !!}
        {!! Form::close()  !!}

			</div>
			<div class="col-sm-6">
				<p class="center"> Por Combustível :</p>
				<br>
				<select>
					<?php echo "Distritos: ";
            foreach ($districts as $key => $district) {
            	echo "<option> $district->name </option>";
            }
          ?>
				</select>
				</div>
			</div>
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
