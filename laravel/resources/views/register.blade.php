@include('header')
    <div class="col-sm-8 text-left">
      <h1 class="center">Register</h1>
      
      <form method="POST" action="{{url('/register')}}" autocomplete="on">
      {{ csrf_field() }}
        <input type="text" name="name" placeholder="Name"><br>
  		  <input type="email" name="email" placeholder="Email"><br>
  		  <input type="password" name="password" placeholder="Password"><br>
  		  <a href="{{action('Auth\RegisterController@register')}}"><button name="Register">Register</button></a>
      </form>

    </div>

@include('footer')
