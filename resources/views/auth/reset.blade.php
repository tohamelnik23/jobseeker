@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="container"> 
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>
                <div class="card-body"> 
                    <div class="row userInfo">
                        <div class="col-md-8 col-md-offset-2"> 
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

                            <form id="reset_password" method="POST" action="{{route('reset.save')}}" >
                                @csrf
                                <div class="form-group"> 
                                    <input type="password" class="form-control password_fields" required name="newpassword" value="{{old('newpassword')}}" placeholder="New password">
                                    <label for="newpassword" id="newpassword-error" class="error" style="display:none;"></label>
                                </div>

                                <div class="form-group"> 
                                    <input type="password" class="form-control password_fields" required name="confirmpassword" value="{{old('confirmpassword')}}" placeholder="Confirm password">
                                    <label for="confirmpassword" id="confirmpassword-error" class="error" style="display:none;"></label>
                                </div>
                                <input type="hidden" name="code" value="{{$code}}">
                                <input type="hidden" name="uid" value="{{$uid}}">

                                <div class="form-group clearfix text-center"> 
                                    <button type="submit" class="btn btn-primary"> Submit</button>
                                    <button type="button" class="btn btn-primary" id="generatepassword"><i class="fa fa-unlock"> </i> Generate Password</button>
                                </div> 
                            </form>

                        </div>
                    </div> 
                </div>
            </div>  
            <!--/row end--> 
        </div> 
    </div>
    <!--/row-->
    <div style="clear:both"></div>
	<!-- Modal --> 
</div> 
<div class="modal  fade" id="passwordgen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h4 class="modal-title-site text-center">Generate Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div>
                         <input type="text" name="" class="form-control password_fields" id="myInput">
                    </div>
                </div>
                <div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" onclick="generatepassword()">Copy</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-left">
                    <ul style="list-style-type: square; padding-inline-start: 40px;">
                        <li>Please copy this password for future use</li>
						<li>Do not share your password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{!!asset('assets/pages/reset-password.js?'.time())!!}"></script>
<script type="text/javascript">
    var actionUrl ="{{route('generate.password')}}";
    
    $("#generatepassword").click(function(){
        

    });

</script>
@stop