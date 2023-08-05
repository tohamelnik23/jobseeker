@extends('layouts.app')
@section('title', 'Login')
@section('content')
<style> 
    .cls-content .cls-content-sm { 
        width: 480px;
        padding: 20px;
        background: #fff; 
    }
    @media only screen and (max-width: 600px){
        .cls-content .cls-content-sm {
            width: 100%; 
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div> 
                <div class="card-body">
					@if ($message = Session::get('success'))
						<div class="alert alert-success alert-block">
							<button type="button" class="close" data-dismiss="alert">×</button>	
								<strong>{{ $message }}</strong>
						</div>
					@endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group  @if ($errors->has('email')) has-error @endif clearfix">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email/ Phone</label> 
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group  clearfix">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> 
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <div class="col-md-6 offset-md-4"> 
                                <div class="checkbox ">
                                    <input id="rememberme-checkbox" class="magic-checkbox" name = "remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="rememberme-checkbox"> {{ __(' Remember Me') }} </label> 
                                </div> 
                            </div>
                        </div> 

                        <div class="form-group clearfix   mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button> 
                                @if (Route::has('forget.form'))
                                    <a class="btn btn-link" href="{{ route('forget.form') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
