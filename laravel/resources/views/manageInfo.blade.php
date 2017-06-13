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

  <div id="divInfo">
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
@include('footer')
