<script type="text/javascript">
	window.onload = getLocation;
	function getLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition);
	    } else {
	        alert("Geolocation is not supported by this browser.");
	    }
	}
	function showPosition(position) {
	    document.getElementById("latitude").value =position.coords.latitude;
	    document.getElementById("longitude").value =position.coords.longitude;
	}
</script>
<form method="POST" action="{{ url('/showGpsCoordinates') }}"  >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" id = "latitude" name="latitude" value="{{$coordinates->latitude}}" />
        <input type="text" id = "longitude" name="longitude" value="{{$coordinates->longitude}}" />
        <button type="submit">submit</button>
        <!--<a href="{{action('LandingController@receiveGPSCoordinates')}}">send</a>
-->
</form>

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

    </div>
    <div class="col-sm-8 text-left">
      <h1 class="center">Preço dos Combustiveis</h1>

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
							var coordinates = {"latitude":{{$coordinates->latitude}}, "longitude":{{$coordinates->longitude}}};
							//console.log(coordinates);
							var pt = {lat: coordinates.latitude, lng:  coordinates.longitude};
							var map = new google.maps.Map(document.getElementById('map'), {
								zoom: 10,
								center: pt
							});
						}
					</script>
					<script async defer
					src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap">
					</script>


			</div>
			<div class="col-sm-6">
			<br>
			<div class="form-group">
				<label for="inputdefault">Distrito(s): </label>
				<input class="form-control" id="inputdefault" type="text">
			</div>
			<br>
			<script>
				$( function() {
					 var districtsName = <?php echo json_encode($districtsName); ?>
					//var availableTags = districtsName;
					$( "#inputdefault" ).autocomplete({
						source: districtsName
					});
				} );
			</script>
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
		    <input class="form-control" id="brand" type="text">
		  </div>
			<br>
			<script>
			var $input = $(".typeahead");
			var brandName = <?php echo json_encode($brandsName); ?>
			$input.typeahead({
			  source: brandName,
			  autoSelect: true
			});
			</script>

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
