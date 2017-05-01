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

				<div id="map"></div>
					<script>
						function initMap() {
							var pt = {lat: 39.676944, lng:  -8.1425};
							var map = new google.maps.Map(document.getElementById('map'), {
								zoom: 7,
								center: pt
							});
						}
					</script>
					<script async defer
					src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap">
					</script>


			</div>
			<div class="col-sm-6">

			<div class="checkbox">
			  <label><input type="checkbox" value="">Gasóleo</label><br>
				<label><input type="checkbox" value="">Gasóleo Simples</label><br>
				<label><input type="checkbox" value="">Gasóleo Colorido</label><br>
				<label><input type="checkbox" value="">Gasóleo Especial</label><br>
				<br><br>
				<label><input type="checkbox" value="">Gasolina 95</label><br>
				<label><input type="checkbox" value="">Gasolina Simples 95</label><br>
				<label><input type="checkbox" value="">Gasolina Especial 95</label><br>
				<br><br>
				<label><input type="checkbox" value="">Gasolina 98</label><br>
				<label><input type="checkbox" value="">Gasolina Simples 98</label><br>
				<label><input type="checkbox" value="">Gasolina Especial 98</label><br>
				<br><br>
				<label><input type="checkbox" value="">GNC KG</label><br>
				<label><input type="checkbox" value="">GNC M3</label><br>
				<label><input type="checkbox" value="">GNL</label><br>
				<label><input type="checkbox" value="">GPL</label><br>
				<br>
				<br>
			</div>

			<div class="form-group">
		    <label for="inputdefault">Marca (Opcional): </label>
		    <input class="form-control" id="inputdefault" type="text">
		  </div>

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
