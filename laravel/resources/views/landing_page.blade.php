@include('header')

<script type="text/javascript">

	window.onload = getLocation;
	function getLocation() {
		if(document.getElementById("latitude").value==="" || document.getElementById("longitude").value===""){
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition);
		    } else {
		        alert("Geolocation is not supported by this browser.");
		    }
		}
	}
	function showPosition(position) {
	    document.getElementById("latitude").value =position.coords.latitude;
	    document.getElementById("longitude").value =position.coords.longitude;
	    submitForm();
	}
	function submitForm(){
		document.getElementById("submit").click();

	}
</script>
<form method="POST" action="{{ url('/showGpsCoordinates') }}" style="display: none;" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" id = "latitude" name="latitude" value="{{$centerMapCoordinates->latitude}}" />
        <input type="text" id = "longitude" name="longitude" value="{{$centerMapCoordinates->longitude}}" />
        <button type="submit" id="submit">submit</button>
</form>




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
							var coordinates = {"latitude":{{$centerMapCoordinates->latitude}}, "longitude":{{$centerMapCoordinates->longitude}}};
							//console.log(coordinates);
							
							var pt = {lat: coordinates.latitude, lng:  coordinates.longitude};
							var map = new google.maps.Map(document.getElementById('map'), {
								zoom: 10,
								center: pt
							});

							
							var myLatLng = {"lat": 39.7495, "lng":-8.8077};
							var marker = new google.maps.Marker({
	          					position: myLatLng,
					        	map: map,
					          	title: 'Fuel Station'
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
				$( function() {
					 var brandsName = <?php echo json_encode($brandsName); ?>
					//var availableTags = districtsName;
					$( "#brand" ).autocomplete({
						source: brandsName
					});
				} );
			</script>

			</div>
		</div>
  </div>

@include('footer')