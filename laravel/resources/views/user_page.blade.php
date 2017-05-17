@include('header')
    <div class="col-sm-8 text-center">
      <h1 class="center">Welcome, {{$name}}</h1>
      <br><br>


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

        
      <p>My vehicles</p>
      @foreach( $vehicles as $vehicle)
        <p>{{$vehicle->brand}}</p>
        <p>{{$vehicle->model}}</p>

      @endforeach

      <div class="btn-group">
        <button type="button" class="btn btn-primary btn-lg">Planear Percurso</button>
        <button type="button" class="btn btn-success btn-lg">Gerir Veiculos</button>
        <button type="button" class="btn btn-info btn-lg">Gerir Info</button>
      </div>

      <br><br><br>
<!--
      <div id="map" class="col-sm-6"></div>
      <script>
          function initMap() {
            var centerPortugal = {lat: 39.676944, lng: -8.1425};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 7,
              center: centerPortugal
            });
          }
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMap">
        </script>

      <div class="col-sm-6 text-left">

        <div class="form-group">
  				<label for="inputdefault">Inicio: </label>
  				<input class="form-control" id="inputdefault" type="text">
  			</div>

        <div class="form-group">
  				<label for="inputdefault">Destino: </label>
  				<input class="form-control" id="inputdefault" type="text">
  			</div>

        <div class="dropdown">
          <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Veiculos
          <span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>
          </ul>
        </div>

        <div class="center">

          <button type="button" class="btn btn-primary btn-lg">Success</button>
          <button type="button" class="btn btn-success btn-lg">Success</button>
          <button type="button" class="btn btn-info btn-lg">Success</button>

        </div>


      </div>
-->
    <div class="col-sm-6">
      <div class="list-group">
       <a href="#" class="list-group-item">First item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <button type="button" class="btn btn-link btn-lg">Adicionar Veiculo</a>
      </div>
    </div>

    <div class="col-sm-6 text-left">

      <div class="form-group">
        <label for="inputdefault">Marca: </label>
        <input class="form-control" id="inputdefault" type="text">
      </div>

      <div class="form-group">
        <label for="inputdefault">Modelo: </label>
        <input class="form-control" id="inputdefault" type="text">
      </div>

      <div class="form-group">
        <label for="inputdefault">Tipo de Combustível: </label>
        <input class="form-control" id="inputdefault" type="text">
      </div>

      <div class="form-group">
        <label for="inputdefault">Consumo(L/100): </label>
        <input class="form-control" id="inputdefault" type="text">
      </div>
        <div class="center">

          <button type="button" class="btn btn-success btn-lg">Editar</button>
          <button type="button" class="btn btn-success btn-lg">Guardar</button>
          <button type="button" class="btn btn-danger btn-lg">Remover</button>

        </div>

    </div>

  </div>
@include('footer')
