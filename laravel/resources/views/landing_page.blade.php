<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
					<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
					<title>!GeoComb!</title>
					<!-- Bootstrap -->
          <link href="css/bootstrap.min.css" rel="stylesheet">
					<link rel="stylesheet" type="text/css" href="{{asset('css/style.css') }}">
						<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
						<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
						<!--[if lt IE 9]>
						<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
						<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
						<![endif]-->
					</head>
					<body>
						<h1>Preço dos Combustiveis</h1>

						<a href="{{action('LandingController@fetchData')}}">Fetch data Aveiro</a>
						<a href="{{action('LandingController@fetchStationData')}}">Fetch data 165954 station</a>
						<a href="{{action('LandingController@mapsApi')}}">Maps Api</a>

						<div class="divPesquisaPostos">
							<h2>Pesquisar Postos:</h2>
							<div class="divEsqHome">
                <p> Por Localização </p>
								<select>
									<?php echo "Distritos: ";
                      foreach ($districts as $key => $district) {
                          echo "

          									<option> $district->name </option>";

                      }
                  ?>
								</select>
							</div>
							<div class="divDrtHome">
                <p> Por Combustivel </p>
                <select>
                  <?php echo "Distritos: ";
                      foreach ($districts as $key => $district) {
                          echo "

                            <option> $district->name </option>";

                      }
                  ?>
                </select>
              </div>
						</div>

          <div class="divPostosBaratosProx">
							<div class="divEsqHome">
                <h4> Os 5 Mais Baratos: </h4>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
              </div>
							<div class="divDrtHome">
                <h4> Os 5 Mais Próximos: </h4>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
                <p> Os 5 Mais Baratos: </p>
              </div>
						</div>
						<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
						<!-- Include all compiled plugins (below), or include individual files as needed -->
						<script src="js/bootstrap.min.js"></script>
					</body>
				</html>
