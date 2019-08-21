@extends('layout.master')
@section('content')
    <div class="col-md-8 col-md-offset-2 col-sm-12">
        <form role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <div class="panel panel-default">
                <div class="panel-heading">
                    LOGIN
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('img/logo1.png') }}" alt="logo" class="img-responsive"/>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label>Email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="email" value="{{ old('email') }}" required/>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label>Password</label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="Password" required/>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary pull-right">Entrar</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
@endsection