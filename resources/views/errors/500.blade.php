@extends("layouts.app")
@section('title', '500')
@push('css')
	<style type="text/css">
		#page-content .error-code {
		    font-size: 120px;
		    font-weight: 400;
		    margin-bottom: 50px;
		}
	</style>
@endpush
@section('content')   
    <div class="col-md-12"> 
        <div class="pad-top col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3  col-xs-10 col-xs-offset-1">
            <a class="dj dk dl dm" href="/" >
                <img alt="" style = "width:100%;" role="presentation" src="{!!  asset('img/500.png')  !!}" class="dn do">
            </a>  
            <div class="dp dq dr ds dt mar-top" style = "    font-family: UberMove-Medium, sans-serif; font-size: 36px;    line-height: 44px; padding-bottom: 8px;   color: #000;">Ooops we encountered an error.</div>
        
            <div class="cd bt cs dk dt" style = "font-size: 16px;    font-family: UberMoveText-Regular, sans-serif; padding-bottom: 24px; color: #000;">System administrators have been notified.  Please go back and try again.</div>
        
            <a class="btn btn-dark btn-block" href="{{ route('home') }}" style = " font-family: UberMoveText-Medium, sans-serif;     font-size: 18px;   background: #000000;   padding: 12px 16px;">Find Gig</a>
        </div>   
    </div> 
@endsection

