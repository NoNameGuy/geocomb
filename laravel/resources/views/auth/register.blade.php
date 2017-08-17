@include('header')
<div class="col-lg-12" style="background-color: grey">

  <h1 style="text-align:center">Registar</h1>

      <div class="panel-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
          {{ csrf_field() }}

          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-lg-4 control-label">Nome</label>

              <div class="col-lg-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-lg-4 control-label">E-Mail</label>

              <div class="col-lg-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-lg-4 control-label">Password</label>

              <div class="col-lg-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-lg-4 control-label">Confirmar Password</label>

              <div class="col-lg-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>

            <div class="form-group">
              <div class="col-lg-6 col-lg-offset-4">
                <button type="submit" class="btn btn-success">
                  Registar
                </button>
              </div>
            </div>
          </form>
        </div>
</div>

@include('footer')
