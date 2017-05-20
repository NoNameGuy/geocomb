@include('header')


<form method="POST" action="{{ url('/showGpsCoordinates') }}" style="display: none;" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="text" id = "latitude" name="latitude" value="{{$centerMapCoordinates->latitude}}" />
    <input type="text" id = "longitude" name="longitude" value="{{$centerMapCoordinates->longitude}}" />
    <button type="submit" id="submit">submit</button>
</form>

    <div class="col-sm-8 text-left">
      <h1 class="center">Preço dos Combustíveis</h1>

			<a href="{{action('LandingController@fetchStationData')}}">Fetch data station</a>

			<hr>

			<h2 class="center">Pesquisar Postos:</h2>
		<div class="row">
			<div class="col-sm-6">

				<div id="map"></div>
					
		


			</div>
			<div class="col-sm-6">
			<br>
			<div class="form-group">
				<label for="inputdistrict">Distrito(s): </label>
				<input class="form-control" id="inputdistrict" type="text">
			</div>
			<br>
			<div class="checkbox">
			  <label><input type="checkbox" name="fuelType" value="diesel">Gasóleo</label><br>
				<label><input type="checkbox" name="fuelType" value="diesel_simple">Gasóleo Simples</label><br>
				<label><input type="checkbox" name="fuelType" value="diesel_colored">Gasóleo Colorido</label><br>
				<label><input type="checkbox" name="fuelType" value="diesel_pecial">Gasóleo Especial</label><br>
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
		    <label for="brand">Marca (Opcional): </label>
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
