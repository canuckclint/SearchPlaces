@extends('layouts.main')

@section('head')
   	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
	<link href="{{asset('..\vendor\kartik-v\bootstrap-star-rating\css\star-rating.css')}}" media="all" rel="stylesheet" type="text/css" />
	<style type="text/css">
		div.rating-xs {
	    	font-size: 1em;
		}
		div.rating-container {
			display: inline;
		}
		@if (empty($yPlaceDetails))
			div#googlePlace {
				width: 800px !important; 
			}
					
		@endif
	</style>


	<link href="{{asset('css/place.css')}}" rel="stylesheet">
	<link href="{{asset('css/blueimp-gallery/blueimp-gallery.css')}}" rel="stylesheet">
@append

@section('content')
	<div id="googlePlace">
		@include('Places\placecontent')
	</div>
	@if(!empty($yPlaceDetails))
		<div id="yelpPlace">
	
			@include('Places\placecontent', ['placeDetails' => $yPlaceDetails])
	
		</div>
	@endif
	<div style="clear: both" />
@stop

{{-- var_dump($placeDetails['result']) --}}
@section('footer')
		<script src="{{asset('..\vendor\kartik-v\bootstrap-star-rating\js\star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/blueimp-gallery/blueimp-gallery.min.js')}}" type="text/javascript"></script>
		<script>
			$(document).on('ready', function() {
				$('div.photolinks').each(function() { 
					$(this)[0].onclick = function (event) {
					    event = event || window.event;
					    var target = event.target || event.srcElement,
					        link = target.src ? target.parentNode : target,
					        options = {index: link, event: event},
					        links = this.getElementsByTagName('a');
					    blueimp.Gallery(links, options);
					};
				});

				$('div#yelpPlace').hide();
				$('div#yelpPlace').show('slide',{ direction: "right" }, 1200);
			});
		</script>
		  <script src="https://maps.googleapis.com/maps/api/js?key={{App\Libraries\Places::$GOOGLE_MAPS_API_KEY}}&libraries=places" type="text/javascript"></script>
		 <script src="{{asset('js/jquery.geocomplete.js')}}" type="text/javascript"></script>
		 <script src="{{asset('js/listPlaces.js')}}" type="text/javascript"></script>
@stop
