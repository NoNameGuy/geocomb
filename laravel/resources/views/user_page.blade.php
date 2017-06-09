@include('header')
    <div class="col-sm-8 text-center">
      <h1 class="center">Welcome, {{$name}}</h1>
      <br><br>

      <div class="btn-group">
        <button id="percurso" type="button" class="btn btn-primary btn-lg">Planear Percurso</button>
        <button id="veiculos" type="button" class="btn btn-success btn-lg">Gerir Veiculos</button>
        <button id="info" type="button" class="btn btn-info btn-lg">Gerir Info</button>
      </div>

      <script>
        $('#veiculos').click(function() {
          $('#divPercurso').hide();
          $('#divInfo').hide();
          $('#divVeiculos').toggle('slow', function() {
          // Animation complete.
          });
        });
        $('#percurso').click(function() {
           $('#divVeiculos').hide();
           $('#divInfo').hide();
          $('#divPercurso').toggle('slow', function() {
          // Animation complete.
          });
        });
        $('#info').click(function() {
          $('#divVeiculos').hide();
          $('#divPercurso').hide();
          $('#divInfo').toggle('slow', function() {
          // Animation complete.
          });
        });
      </script>

      <br><br><br>


      <div id="divPercurso">
        <div id="mapUP" class="col-sm-6" style="width: 300px;height: 500px;"></div>
        <!--<script>
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
-->
        <div class="col-sm-6 text-left">
          <form method="post" action="{{route('sendTripData')}}">
            {{csrf_field()}}
          <div class="form-group">
    				<label for="inputdefault">Inicio: </label>
    				<input class="form-control" id="upOrigin" name="upOrigin" type="text">
    			</div>

          <div class="form-group">
    				<label for="inputdefault">Destino: </label>
    				<input class="form-control" id="upDestination" name="upDestination" type="text">
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
          <input type="checkbox" name="highway" id="upHighway">Autoestrada</input>
          <label>Autonomia (km):<input type="number" name="autonomyKm" ></label><br>
          <label>Autonomia (l):<input type="number" name="autonomyL" ></label>
          <label>Consumo (l/km):<input type="number" name="consumption" ></label>

<br><br>
          <div class="center">

            <button type="submit" id="upSearch" class="btn btn-primary btn-lg">Pesquisar</button>
          </form>
          </div>
        </div>
      </div>

<div id="divVeiculos">
    <div class="col-sm-6">
      <div class="list-group" id="vehicleList">
       @if($vehicles)
       @foreach($vehicles as $vehicle)
        <a href="{{ route('editVehicle',$vehicle->id)}}"class="list-group-item {{Auth::user()->preferredVehicle==$vehicle->vehicle_id ? 'active': ''}}" value="{{$vehicle->id}}" onclick="var brand={{$vehicle->brand}}, {{$vehicle->model}}, {{$vehicle->fuel}}, {{$vehicle->consumption}}, {{Auth::user()->preferredVehicle==$vehicle->id? 1 : 0}};updateVehiclePage(brand)">{{$vehicle->brand}} - {{$vehicle->model}}</a>
       @endforeach
      @endif
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

          <button type="submit" class="btn btn-primary btn-lg">Adicionar</button>
          <button type="button" class="btn btn-success btn-lg">Editar</button>
          <button type="button" class="btn btn-danger btn-lg" formaction="{{--route('deletevehicle',$v->id)--}}">Remover</button>

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

<div id="divInfo" style="display:none">
    <div class="col-sm-6">

      <div class="form-group">
        <label>Nome: </label>
        <input class="form-control" id="txtName" name="name" type="text">
      </div>

      <div class="form-group">
        <label>Email: </label>
        <input class="form-control" id="txtEmail" name="email" type="text">
      </div>

      <a href="#" class="btn btn-success">Guardar Alterações</a>

    </div>

<form id="form-change-password" role="form" method="POST" action="{{ route('editPass') }}" novalidate class="form-horizontal">
    <div class="col-sm-6">

      <div class="form-group">
        <label for="current-password" class="col-sm-4 control-label">Password Antiga: </label>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Password">
      </div>

      <div class="form-group">
        <label for="password" class="col-sm-4 control-label">Password Nova: </label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Nova Password">
      </div>

      <div class="form-group">
        <label for="password_confirmation" class="col-sm-4 control-label">Confirmar Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password">
      </div>

      <button type="submit" class="btn btn-danger">Alterar Password</a>

    </div>
  </form>
  </div>

  </div>
  <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMapUP" >
            </script>
@include('footer')
