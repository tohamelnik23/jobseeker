@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div> 
                <div class="card-body">  
                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div> 
                    @endif 
                    @if(Session::has('success'))
                        <div class="alert alert-success"><em> {!! session('success') !!}</em></div>
                    @endif

                    <form id="forgot_password" method="POST" action="{{route('forget.save')}}" >
                        <p class = "text-dark"> To reset your password, enter the email or phone you use to sign in to site. We will then send you a new password. </p> 
                        @csrf
                        <div class="form-group clearfix mar-top"> 
                            <label for="exampleInputEmail1"> Email / Phone<star>*</star></label>
                            <input type="text" class="form-control" required name="email" value="{{old('email')}}" placeholder="Enter email">
                            <label for="email" id="email-error" class="error" ></label>
                        </div>

                        <div class="form-group clearfix mar-top text-center"> 
                            <button type="submit" class="btn   btn-primary"><i class="fa fa-unlock"> </i> Retrieve Password  </button> 
                            <a  class="btn btn-link" href="{{ url()->previous() }}"> ← Back to Login </a>  
                        </div> 
                    </form>  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
