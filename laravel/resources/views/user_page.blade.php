@include('header')
    <div class="col-sm-8 text-left">
      <h1 class="center">Welcome {{--$name--}}</h1>
      

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
    </div>

@include('footer')