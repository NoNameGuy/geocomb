@include('header')
<meta name="_token" content="{{ csrf_token() }}">
<div class="col-lg-12 text-center">
  <h1 class="center">Bem-Vindo, {{$name}}</h1>
  <br><br>

  <div class="btn-group">
    <a id="percurso" type="button" class="btn btn-primary btn-lg" href="{{route('planRoute')}}">Planear Percurso</a>
    <a id="veiculos" type="button" class="btn btn-success btn-lg" href="{{route('manageVehicles')}}">Gerir Veiculos</a>
    <a id="info" type="button" class="btn btn-info btn-lg" href="{{route('manageInfo')}}">Gerir Info</a>
  </div>

  <br><br>
@if($searchingFlag == true)
  <div class="row">

    <div class="col-lg-12" id="divPercurso" style="background-color: grey">

      <br><br>

      <div class="col-lg-6">

        <div class="center-block" id="mapUP"></div>

        <br><br>

      </div>

      <div class="col-lg-6">
        <div class="container-fluid">

          <form method="post" action="{{route('selectVehicle')}}">
            {{csrf_field()}}
            <div class="col-lg-6">
              <label for="inputdefault">Veiculos:</label>
              <select name="upSelectVehicle" id="upSelectVehicle" onchange="this.form.submit()" >
                @if(isset($vehicles))

                  @foreach($vehicles as $vehicle)

                  <option value="{{$vehicle->vehicleId}}" @if(isset($vehicleData) && $vehicle->vehicleId==$vehicleData->id ) selected @endif>{{$vehicle->brand}} {{$vehicle->model}}</option>

                  @endforeach

                @endif
              </select>

            </div>

          </form>

          <br><br>

          <div class="col-lg-6">

            <div class="form-group">

              <label for="inputdefault">Inicio: </label>
              <input class="form-control" id="upOrigin" name="upOrigin" type="text">

            </div>

            <div class="form-group">

              <label for="inputdefault">Destino: </label>
              <input class="form-control" id="upDestination" name="upDestination" type="text">

            </div>
            <div class="form-group">

              <label for="inputdefault">Evitar Estradas Pagas: </label>
              <input class="form-control" id="upPaidRoads" name="upPaidRoads" type="checkbox">

            </div>

            <label>Autonomia (km):<br><input type="number" name="upAutonomyKm" id="upAutonomyKm"></label><br>

            <br>

            <button id="upSearch" class="btn btn-primary btn-lg">Pesquisar</button>
            <button id="cleanSearch" class="btn btn-danger btn-lg">Limpar Pesquisa</button><br><br>
            <button id="btnFeelingLucky" type="button" class="btn btn-primary btn-lg" >Pesquisa Automática</button>

            <form id="formEmailLink" method="post" action="{{route('sendRouteEmail')}}">
              {{csrf_field()}}
              <input type="hidden" id="routeLink" name="link">
            </form>

            <button id="sendRouteEmail" type="button" class="btn btn-info btn-lg" >Envio P/ Email</button>

          </div>

          <!--<div class="col-lg-6">

            <label>Autonomia (km):<input type="number" name="upAutonomyKm" id="upAutonomyKm"></label><br>
            <p>Ou</p>
            <label>Autonomia (l):<input type="number" name="upAutonomyL" id="upAutonomyL"></label>
            <label>Consumo (l/km):<input type="number" name="upConsumption" id="upConsumption" value="@if(isset($vehicleData)){{$vehicleData->consumption}}@endif"></label>

          </div>-->
        </div>
      </div>

    <!--  <div class="col-lg-6 center">
        <br>

        <button  id="upSearch" class="btn btn-primary btn-lg">Pesquisar</button>
        <button  id="cleanSearch" class="btn btn-danger btn-lg">Limpar Pesquisa</button><br><br>

      </div>-->
    </div>

  </div>

</div>
@elseif($searchingFlag == false)
  <p>Searching</p>
  <div class="col-lg-6">
    <div class="center-block" id="mapUP"></div>
    <br><br>
  </div>
  <a id="upNewSearch" class="btn btn-primary btn-lg" href="{{ route('planRoute') }}">Nova Pesquisa</a>

@endif

</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s&callback=initMapUP&libraries=places" ></script>

@include('footer')
