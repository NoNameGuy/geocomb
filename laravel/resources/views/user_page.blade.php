@include('header')
    <div class="col-sm-8 text-center">
      <h1 class="center">Welcome, {{$name}}</h1>
      <br><br>


      <p>Add vehicle</p>
      <form method="POST" action="{{ route('addvehicle') }}" autocomplete="on">
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
      <div class="list-group" id="vehicleList">
       @foreach($vehicles as $vehicle)
        <a href="#" class="list-group-item {{Auth::user()->preferredVehicle==$vehicle->vehicle_id ? 'active': ''}}" value="{{$vehicle->id}}" onclick="var brand={{$vehicle->brand}}, {{$vehicle->model}}, {{$vehicle->fuel}}, {{$vehicle->consumption}}, {{Auth::user()->preferredVehicle==$vehicle->id? 1 : 0}};updateVehiclePage(brand)">{{$vehicle->brand}} - {{$vehicle->model}}</a>
       @endforeach
       <!--<a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>
       <a href="#" class="list-group-item">Second item</a>-->
       <button type="button" class="btn btn-link btn-lg" id="addVehicle">Adicionar Veiculo</a>
      </div>
    </div>

    <div class="col-sm-6 text-left">
     <form method="POST" action="{{ route('addvehicle') }}" autocomplete="on">
      {{ csrf_field() }}
        
      <div class="form-group">
        <label for="brand">Marca: </label>
        <input class="form-control" id="txtBrand" name="brand" type="text">
      </div>

      <div class="form-group">
        <label for="model">Modelo: </label>
        <input class="form-control" id="txtModel"  name="model" type="text">
      </div>

      <div class="form-group">
        <label for="fuel">Tipo de Combustível: </label>
        <input class="form-control" id="txtFuelType" name="fuel" type="text">
      </div>

      <div class="form-group">
        <label for="consumption">Consumo(L/100): </label>
        <input class="form-control" id="txtConsumption" type="number" name="consumption" step="0.1">
      </div>
      <div class="form-group">
        <label for="favoriteVehicle">Preferred Vehicle </label>
        <input type="checkbox" name="favoriteVehicle">
      </div>
        <div class="center">

          <button type="button" class="btn btn-success btn-lg">Editar</button>
          <button type="submit" class="btn btn-success btn-lg">Guardar</button>
          <button type="button" class="btn btn-danger btn-lg">Remover</button>

        </div>
       </form>
    </div>
    <script type="text/javascript">
      $(" #vehicleList>a").click(function(){
       /*{{-- @foreach($vehicles as $vehicle)
          @if($vehicle->id == $(this).val())
            $("#txtBrand").val("{{$vehicle->brand}}");  
            $("#txtModel").val('');  
            $("#txtFuelType").val('');  
            $("#txtConsumption").val('');
          @endif
        @endforeach--}}*/
      });

      $("#addVehicle").click(function(){
        $("#txtBrand").val('');  
        $("#txtModel").val('');  
        $("#txtFuelType").val('');  
        $("#txtConsumption").val('');  
      });
      
    </script>

  </div>
@include('footer')
