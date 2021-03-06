@include('header')
<div class="col-lg-12 text-center">
  <h1 class="center">Bem-Vindo, {{$name}}</h1>
  <br><br>

  <div class="btn-group">
    <a id="percurso" type="button" class="btn btn-primary btn-lg" href="{{route('planRoute')}}">Planear Percurso</a>
    <a id="veiculos" type="button" class="btn btn-success btn-lg" href="{{route('manageVehicles')}}">Gerir Veiculos</a>
    <a id="info" type="button" class="btn btn-info btn-lg" href="{{route('manageInfo')}}">Gerir Info</a>
  </div>

  <br><br>

  <div class="row">

    <div class="col-lg-12" id="divVeiculos" style="background-color: grey">

      <br><br>

      <div class="col-lg-6">

        <div class="list-group" id="vehicleList" style="color:black">

          @if($vehicles)

          <ul>
            @foreach($vehicles as $vehicle)

            <li class="list-group-item {{Auth::user()->preferredVehicle==$vehicle->vehicle_id ? 'active': ''}}"
              value="{{$vehicle->vehicle_id}}">{{$vehicle->brand}} - {{$vehicle->model}}
              <a class="btn btn-success" href="{{ route('editVehicle',$vehicle->vehicle_id)}}">Editar</a>
              <a class="btn btn-danger" href="{{ route('removeVehicle',$vehicle->vehicle_id)}}">Remover</a>
            </li>

            @endforeach

          </ul>

          @endif

        </div>

      </div>

      <div class="col-lg-6">
        <div class="container-fluid">
          @if(Request::url() == 'http://geocomb.app/userpage/vehicles')

          <form method="POST" action="{{ route('addvehicle') }}">
            @else

            <form method="POST" action="{{ route('postEditVehicle',$selectedVehicle->id) }}">
          @endif
              {{ csrf_field() }}

              <div class="form-group">
                <label for="brand">Marca: </label>
                <input class="form-control" id="txtBrand" name="brand" type="text"
                value="@if(isset($selectedVehicle)){{$selectedVehicle->brand}}@endif">
              </div>

              <div class="form-group">
                <label for="model">Modelo: </label>
                <input class="form-control" id="txtModel"  name="model" type="text"
                value="@if(isset($selectedVehicle)){{$selectedVehicle->model}}@endif">
              </div>

              <div class="form-group">
                <label for="fuel">Tipo de Combustível: </label>
                @if(isset($allFuels))
                  @foreach($allFuels as $fuel)
                    <label>@if($fuel->name == "petrol_95") Gasolina 95 @elseif($fuel->name == "petrol_95_simple") Gasolina 95 Simples @elseif($fuel->name == "petrol_98") Gasolina 98 @elseif($fuel->name == "petrol_98_simple") Gasolina 98 Simples @elseif($fuel->name == "diesel") Diesel @elseif($fuel->name == "diesel_simple") Diesel Simples @elseif($fuel->name == "gpl") GPL @endif<input type="checkbox" name="upFuelType[]" value="{{$fuel->name}}"
                      @if(isset($vehicleFuels))
                        @foreach($vehicleFuels as $vFuel)
                          @if($fuel->name==$vFuel->name) checked
                          @endif
                        @endforeach
                      @endif     ></label>
                    @endforeach
                  @endif
                </div>

                <div class="form-group">
                  <label for="consumption">Consumo(L/100): </label>
                  <input class="form-control" id="txtConsumption" type="number" name="consumption" step="0.1"
                    value="@if(isset($selectedVehicle)){{$selectedVehicle->consumption}}@endif">
                  </div>

                  <div class="form-group">
                    <label for="favoriteVehicle">Preferred Vehicle </label>
                    <input type="checkbox" name="favoriteVehicle">
                  </div>

                  <div class="center">
                    <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
</div>
@include('footer')
