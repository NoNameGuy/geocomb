@include('header')
<form method="POST" action="{{ route('home') }}" style="display: none;" >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="text" id = "latitude" name="latitude" value="{{$centerMapCoordinates->latitude}}" />
	<input type="text" id = "longitude" name="longitude" value="{{$centerMapCoordinates->longitude}}" />
	<button type="submit" id="landingHiddenSubmit">submit</button>
</form>
<br><br>
		<div class="container-fluid"  style="background-color: grey">
			<h2 class="center">Pesquisar Postos:</h2>
			<br><br><br>

			<div class="row">

				<!--DIV ESQUERDA-->
				<div class="col-lg-6">
						<div class="center-block" id="map"></div>
				</div>

				<!--Div direita-->
				<div class="col-lg-6">
					<div class="container-fluid">
					@if(!isset($stations))
					<br>
					<form method="post" action="{{route('home')}}">
						{{csrf_field()}}
						<div class="col-lg-6">
							<div class="form-group">
								<label for="district">Distrito(s): </label>
								<input class="form-control" id="district" name="district" type="text" @if(Auth::user() && isset($location)) value="$location" @endif>
							</div>
						</div>

						<br><br><br><br><br>

						<div class="row">
							<div class="checkbox-inline" id="landingFuelType">
								<div class="form-group"  style="text-align:center">
									<label style="font-weight:bold">Tipo de Combustível (Escolha 1): </label>
								</div>

								@if($fuels)
									@foreach($fuels as $fuel)
										<label><input type="checkbox" name="fuelType" value="{{$fuel->name}}">{{$fuel->name}}</label><br>
									@endforeach
								@endif

								<div class="col-lg-6">
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="diesel">Gasóleo</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="diesel_simple">Gasóleo Simples</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="diesel_colored">Gasóleo Colorido</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="diesel_special">Gasóleo Especial</label><br>
										<br><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_95">Gasolina 95</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_simple_95">Gasolina Simples 95</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_special_95">Gasolina Especial 95</label><br>
										<br><br>
								</div>

								<div class="col-lg-6">
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="gas_natural_compressed_kg">GNC KG</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="gas_natural_compressed_m3">GNC M3</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="gas_natural_liquified">GNL</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="gpl">GPL</label><br>
										<br><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_98">Gasolina 98</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_simple_98">Gasolina Simples 98</label><br>
									<label class="checkbox-inline"><input type="checkbox" name="fuelType" value="petrol_special_98">Gasolina Especial 98</label><br>
										<br><br>
								</div>
							</div>
						</div>

						<div class="form-group">

							<div class="col-lg-6">
								<br>

								<label for="brand">Marca (Opcional): </label>
								<input class="form-control" id="brand" name="brand" type="text">

								<br><br>

								<button class="btn btn-success btn-lg" id="landingSearch" type="submit">Pesquisar</button>
								<button class="btn btn-danger btn-lg" id="clearSearch" type="submit">Limpar Pesquisa</button>

								<br><br>

							</div>

						</div>
					</form>

					@else

					<form method="post" action="{{route('home')}}">
						{{csrf_field()}}
						<input type="hidden" name="district" value="">
						<input type="hidden" name="brand" value="">
						<button class="btn btn-success" id="landingBack">Back</button>
					</form>

					<h3 class="text-center">Mais baratas ()</h3>

					<div class="form-group">
						@foreach($stations as $key=>$station)

						<div class="well well-lg">

							<label for="nome">Nome: </label>
							{{$station->stationName}}<br>

							<label for="nome">Marca: </label>
							{{$station->stationBrand}}<br>

							<label for="nome">Preço: </label>
							{{$station->fuelPrice}}

							<!--<button type="button" class="btn btn-link" style="float:right">Detalhes</button>-->
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
			<br><br><br><br>
		</div>

		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap" ></script>

@include('footer')
