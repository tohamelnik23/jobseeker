@extends('layouts.app')
@section('title', 'Phone Verification')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Phone Verification</div> 
                <div class="card-body">
                    <form method="POST" action="{!!  route('forget.form.phone', $token )!!}">
                        @csrf 
                        <div class="form-group clearfix">
                            <p> We are sending a verification code to your phone. Enter it bellow to reset your password. </p> 
                        </div>
                        <div class="form-group clearfix">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-right">Verification Code</label> 
                            <div class="col-md-6">
                                <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" value="{{   old('verification_code') }}" required autocomplete="verification_code" autofocus>
                                @error('verification_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 
                        <div class="form-group clearfix mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
