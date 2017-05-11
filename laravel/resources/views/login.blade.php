@include('header')
    <div class="col-sm-8 text-left">
      <h1 class="center">Login</h1>
     
		<form method="POST" action="{{url('/login')}}" autocomplete="on">
			{{ csrf_field() }}
			<input type="email" name="email" placeholder="Email"><br>
			<input type="password" name="password" placeholder="Password"><br>
			<a href="{{action('Auth\LoginController@login')}}"><button name="Login">Login</button></a>

		</form>
    </div>

@include('footer')