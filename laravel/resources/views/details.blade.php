@include('header')

<br><br>
		<div class="container-fluid"  style="background-color: grey">
			<h2 class="center">Detalhes:</h2>
			<br><br><br>

			<div class="row">

				<!--DIV ESQUERDA-->
				<div class="col-lg-6">
						<div class="center-block" id="mapDetails"></div>
				</div>

				<!--Div direita-->
				<div class="col-lg-6">

					<div class="col-lg-12">

						<h3><strong>Nome: </h3></strong><br>
						<h3><strong>Marca: </h3></strong><br><br>

					</div>

					<div class="col-lg-6">
						<h4><strong>Gasóleo - </h4></strong><br>
						<h4><strong>Gasóleo Simples - </h4></strong><br>
						<h4><strong>Gasóleo Colorido - </h4></strong><br>
						<h4><strong>Gasóleo Especial - </h4></strong><br>
							<br><br>
						<h4><strong>Gasolina 95 - </h4></strong><br>
						<h4><strong>Gasolina Simples 95 - </h4></strong><br>
						<h4><strong>Gasolina Especial 95 - </h4></strong><br>
							<br><br>
					</div>

					<div class="col-lg-6">
						<h4><strong>GNC KG - </h4></strong><br>
						<h4><strong>GNC M3 - </h4></strong><br>
						<h4><strong>GNL - </h4></strong><br>
						<h4><strong>GPL - </h4></strong><br>
							<br><br>
						<h4><strong>Gasolina 98 - </h4></strong><br>
						<h4><strong>Gasolina Simples 98 - </h4></strong><br>
						<h4><strong>Gasolina Especial 98 - </h4></strong><br>
							<br><br>
					</div>

				</div>

			</div>

			<br><br><br><br>

		</div>

		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMapDetails" ></script>

@include('footer')
