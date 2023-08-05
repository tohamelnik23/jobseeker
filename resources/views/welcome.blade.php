@extends('layouts.app')
@section('title', 'Home | DiscoveGig')
@section('css')
<link rel="stylesheet" href="{{asset('plugins/dropzone/dropzone.css')}}" type="text/css">
<style>
/*search box css start here*/
.search-sec{
    padding: 2rem;
}
.search-slt{
    display: block;
    width: 100%;
    font-size: 1.875rem;
    line-height: 2.5;
    color: #55595c;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    height: calc(4rem + 2px) !important;
    border-radius:0;
}
.wrn-btn{
    width: 100%;
    font-size: 16px;
    font-weight: 400;
    text-transform: capitalize;
    height: calc(4rem + 2px) !important;
    border-radius:0;
}
@media (min-width: 992px){
    .search-sec{
        position: relative;
        top: -234px;
        background: rgba(26, 70, 104, 0.51);
		color:#FFF;
    }
} 
@media (max-width: 992px){
    .search-sec{
        background: #1A4668;
    }
}
</style>
@endsection
@section('content')
<div class="map_canvas hide"> </div>
<div class="container" style="margin-top: -20px;">
	<section>
		<div id="carouselExampleFade"  >
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="{!! asset('img/background/neilandme.jpg') !!}" class="d-block w-100" alt="...">
				</div>
			</div>
		</div>
	</section>
	<section class="search-sec">
		<div class="container">
			<h2 class = 'text-light text-center'>Get Stuff Done</h2>
			<form id="employeesearchform" method="get" class="form-horizontal" action="{{ route('search.employee') }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4 col-sm-12 p-0">
								<select class = "form-control search-slt input-lg" name = "sortyby_category[]">
									@foreach($categories as $category)
										@if($category->calculate_subcategories())
											<option value = "{!! $category->id !!}">{!! $category->industry !!}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12 p-0">
								<input type="text" class="form-control search-slt input-lg" placeholder = "Zip Code" id = "zipcode" name = "zip_code" value = "{!! $postal_code !!}"> 
							</div>
							<div class="col-lg-2 col-md-3 col-sm-12 p-0">
								<button type="submit" class="btn btn-danger wrn-btn">Search</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
@endsection
@section('javascript')
<!--script type="text/javascript" src="{{ asset('js/typeahead.bundle.min.js?'.time())}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script-->
<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2Lze4Darf903qJrfbrjAqyMhJGqvel0A&libraries=places" ></script>
<script src="{{ asset('/js/jquery.geocomplete.js') }}"></script--> 
<script> 
/*
	function getLocation() { 
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else {
			document.getElementById("zipcode").value = "Geolocation is not supported by this browser.";
		}
	} 
	function showPosition(position) {
			var lat = position.coords.latitude;
			var lang = position.coords.longitude; 
			const latlng = {
				lat: parseFloat(lat),
				lng: parseFloat(lang)
			}
			const geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				{
					location: latlng
				},
				(results, status) => {
					if (status === "OK") {
						if (results[0]) {
							//var address = results[0].formatted_address;
							//document.getElementById("address").value = address;
							console.log( results[0].address_components);
						} else {
							//window.alert("No results found");
						}
					} else {
						//window.alert("Geocoder failed due to: " + status);
					}
				}
			);

	}

	getLocation();


	$(document).ready( function (){
		$('input.typeahead').typeahead({
			source:  function (query, process) {
			return $.get("{{route('skillsemployer')}}", { query: query }, function (data) {
					console.log(data);
					data = $.parseJSON(data);
					return process(data);
				});
			}
		});
	});
	$(function(){
		$("#address").geocomplete({
		map: ".map_canvas",
		details: "form",
		types: ["geocode", "establishment"],
		});
	});
		var address = document.getElementById("address").value;
		var lat = document.getElementById("lat").value;
		var lng = document.getElementById("lng").value;
		if(address == '' && lat=='' && lng==''){
			getLocation();
		}
		function getLocation() {

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				document.getElementById("address").value = "Geolocation is not supported by this browser.";
			}
		}

		function showPosition(position) {
			var lat = position.coords.latitude;
			var lang = position.coords.longitude;
			document.getElementById("lat").value = lat;
			document.getElementById("lng").value = lang;
			const latlng = {
			lat: parseFloat(lat),
			lng: parseFloat(lang)
			}
			const geocoder = new google.maps.Geocoder();
			geocoder.geocode(
				{
					location: latlng
				},
				(results, status) => {
					if (status === "OK") {
					if (results[0]) {
						var address = results[0].formatted_address;
						document.getElementById("address").value = address;
					} else {
						//window.alert("No results found");
					}
					} else {
					//window.alert("Geocoder failed due to: " + status);
					}
				}
			);
		}
	*/
</script>
@stop
