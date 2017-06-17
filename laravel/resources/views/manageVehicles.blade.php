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

  <div id="divVeiculos">
    <div class="col-sm-6">
      <div class="list-group" id="vehicleList">
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

    <div class="col-sm-6 text-left">
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
        <div class="col-sm-4">
          <label><input type="checkbox" name="fuelType" value="diesel">Gasóleo</label><br>
          <label><input type="checkbox" name="fuelType" value="diesel_simple">Gasóleo Simples</label><br>
          <label><input type="checkbox" name="fuelType" value="diesel_colored">Gasóleo Colorido</label><br>
          <label><input type="checkbox" name="fuelType" value="diesel_special">Gasóleo Especial</label><br>
          <br><br>
          <label><input type="checkbox" name="fuelType" value="petrol_95">Gasolina 95</label><br>
          <label><input type="checkbox" name="fuelType" value="petrol_simple_95">Gasolina Simples 95</label><br>
          <label><input type="checkbox" name="fuelType" value="petrol_special_95">Gasolina Especial 95</label><br>
          <br><br>
        </div>
        <div class="col-sm-8">
          <label><input type="checkbox" name="fuelType" value="gas_natural_compressed_kg">GNC KG</label><br>
          <label><input type="checkbox" name="fuelType" value="gas_natural_compressed_m3">GNC M3</label><br>
          <label><input type="checkbox" name="fuelType" value="gas_natural_liquified">GNL</label><br>
          <label><input type="checkbox" name="fuelType" value="gpl">GPL</label><br>
          <br><br>
          <label><input type="checkbox" name="fuelType" value="petrol_98">Gasolina 98</label><br>
          <label><input type="checkbox" name="fuelType" value="petrol_simple_98">Gasolina Simples 98</label><br>
          <label><input type="checkbox" name="fuelType" value="petrol_special_98">Gasolina Especial 98</label><br>
          <br><br>
        </div>
        <select class="form-control" id="txtFuelType" name="fuel">
          @if(isset($fuelTypes))
            @for($i=1;$i<count($fuelTypes); $i++)
              <option value="{{$fuelTypes[$i]->Field}}" @if(isset($selectedVehicle) && $selectedVehicle->fuel==$fuelTypes[$i]->Field) selected @endif>{{$fuelTypes[$i]->Field}}</option>
            @endfor
          @endif
        </select>
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

</div>
@include('footer')
