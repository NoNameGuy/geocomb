@include('header')


<form method="POST" action="{{ route('home') }}" style="display: none;" >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="text" id = "latitude" name="latitude" value="{{$centerMapCoordinates->latitude}}" />
	<input type="text" id = "longitude" name="longitude" value="{{$centerMapCoordinates->longitude}}" />
	<button type="submit" id="landingHiddenSubmit">submit</button>
</form>

<div class="col-sm-8 text-left">
	<h1 class="center">Preço dos Combustíveis</h1>

	<a href="{{action('LandingController@getJsonOpenMaps')}}">Fetch data station</a>

	<hr>

	<h2 class="center">Pesquisar Postos:</h2>
	<div class="row">
		<!--Div esquerda-->
		<div class="col-sm-6">
		  <div id="map" style="width: 500px;height: 500px;"></div>
		</div>

		<!--Div direita-->
		<div class="col-sm-6">
			@if(!isset($stations))
			<br>
			<form method="post" action="{{route('home')}}">
				{{csrf_field()}}
				<div class="form-group">
					<label for="inputdistrict">Distrito(s): </label>
					<input class="form-control" id="inputdistrict" name="district" type="text">
				</div>
				<br>
				<div class="checkbox" id="landingFuelType">

					<label><input type="radio" name="fuelType" value="diesel">Gasóleo</label><br>
					<label><input type="radio" name="fuelType" value="diesel_simple">Gasóleo Simples</label><br>
					<label><input type="radio" name="fuelType" value="diesel_colored">Gasóleo Colorido</label><br>
					<label><input type="radio" name="fuelType" value="diesel_special">Gasóleo Especial</label><br>
					<br><br>
					<label><input type="radio" name="fuelType" value="petrol_95">Gasolina 95</label><br>
					<label><input type="radio" name="fuelType" value="petrol_simple_95">Gasolina Simples 95</label><br>
					<label><input type="radio" name="fuelType" value="petrol_special_95">Gasolina Especial 95</label><br>
					<br><br>
					<label><input type="radio" name="fuelType" value="petrol_98">Gasolina 98</label><br>
					<label><input type="radio" name="fuelType" value="petrol_simple_98">Gasolina Simples 98</label><br>
					<label><input type="radio" name="fuelType" value="petrol_special_98">Gasolina Especial 98</label><br>
					<br><br>
					<label><input type="radio" name="fuelType" value="gas_natural_compressed_kg">GNC KG</label><br>
					<label><input type="radio" name="fuelType" value="gas_natural_compressed_m3">GNC M3</label><br>
					<label><input type="radio" name="fuelType" value="gas_natural_liquified">GNL</label><br>
					<label><input type="radio" name="fuelType" value="gpl">GPL</label><br>
					<br>
					<br>

				</div>

				<div class="form-group">
					<label for="brand">Marca (Opcional): </label>
					<input class="form-control" id="brand" name="brand" type="text">
					<button id="landingSearch" type="submit">Search...</button>
				</div>
			</form>
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

	@else
	<form method="post" action="{{route('home')}}">
		{{csrf_field()}}
		<input type="hidden" name="district" value="">
		<input type="hidden" name="brand" value="">
		<button id="landingBack">Back</button>
	</form>
	<h3 class="text-center">Mais baratas ()</h3>
	<div class="form-group">
		@foreach($stations as $key=>$station)
		<div class="well well-lg">
			<label for="nome">Nome: </label>
			{{$station->stationName}}<br>
			<label for="nome">Preço: </label>
			{{$station->fuelPrice}}
			<button type="button" class="btn btn-link" style="float:right">Detalhes</button>
			<img src="../../files/{{$key+1}}.jpg" style="float:right"></img>
			<br>
			<label for="nome">Serviços: </label>
			{{$station->services}}<br>
		</div>
		@endforeach
	</div>
	@endif
		</div>
	</div>
</div>
<script async defer
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap" >
          </script>
@include('footer')
