@include('header')
<div class="col-sm-8 text-center">
  <h1 class="center">Welcome, {{$name}}</h1>
  <br><br>

  <div class="btn-group">
    <a id="percurso" type="button" class="btn btn-primary btn-lg" href="{{route('planRoute')}}">Planear Percurso</a>
    <a id="veiculos" type="button" class="btn btn-success btn-lg" href="{{route('manageVehicles')}}">Gerir Veiculos</a>
    <a id="info" type="button" class="btn btn-info btn-lg" href="{{route('manageInfo')}}">Gerir Info</a>
  </div>

  <br><br>

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
    <label>Autonomia (km):<input type="number" name="upAutonomyKm" id="upAutonomyKm"></label><br>
    <label>Autonomia (l):<input type="number" name="upAutonomyL" id="upAutonomyL"></label>
    <label>Consumo (l/km):<input type="number" name="upConsumption" id="upConsumption"></label>

<br><br>
    <div class="center">

      <button  id="upSearch" class="btn btn-primary btn-lg">Pesquisar</button>

    </div>
  </div>
</div>


</div>
  <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMapUP" >
            </script>
@include('footer')
