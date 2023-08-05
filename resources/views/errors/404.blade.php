@extends("layouts.app")
@section('title', 'Page Not Found')
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
 	<div id = "page-content" class="text-center  no-padding">
	    <h1 class="error-code text-info">404</h1>
	    <p class="h4 text-uppercase text-bold">Page Not Found!</p>
	    <div class="pad-btm">
	        Sorry, but the page you are looking for has not been found on our server.
	    </div> 
	    <hr class="new-section-sm bord-no">
	    <div class="pad-top"><a class="btn btn-primary" href="{{ route('home') }}">Return Home</a></div>  
	</div>   
@endsection