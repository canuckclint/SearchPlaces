<<<<<<< HEAD
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
	<!--  google map -->
     <div id="map" class="placeMap"></div>
     
	<div style="clear: both" />
@stop

{{-- var_dump($placeDetails['result']) --}}
@section('footer')
		<script src="{{asset('vendor\kartik-v\bootstrap-star-rating\js\star-rating.js')}}" type="text/javascript"></script>
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
		
			<script type="text/javascript">
		
		   function initMap() {
			   <?php $i = 1; ?>
			   var locations = [];
			   @php $place = $placeDetails @endphp
			   @if (isset($place['name']) && isset($place['geometry']))
					var location = ['{{$place['name']}}', {{$place['geometry']['location']['lat']}}, {{$place['geometry']['location']['lng']}}, {{$i++}}];
					locations.push(location);
				@endif
		
			
			    var map = new google.maps.Map(document.getElementById('map'), {
			      zoom: 10,
			      center: new google.maps.LatLng({{$coords[0]}}, {{$coords[1]}}),
			      mapTypeId: google.maps.MapTypeId.ROADMAP
			    });
		
		
			    function createInfoWindow(map, marker, i) {
			    	var infowindow =  new google.maps.InfoWindow({
				    	  content: locations[i][0]
				      });
		
		
				      google.maps.event.addListener(marker, 'click', function() {
					          infowindow.open(map, marker);
				      });
				}
		
				
				
			    var marker, i;
				
			    for (i = 0; i < locations.length; i++) {
				      marker = new google.maps.Marker({
					    label: locations[i][3].toString(),
				        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				        map: map
				      });
		
				      createInfoWindow(map, marker, i);
			    }
			   }
	  </script>
		  <script src="https://maps.googleapis.com/maps/api/js?key={{App\Libraries\Places::$GOOGLE_MAPS_API_KEY}}&callback=initMap&libraries=places" type="text/javascript"></script>
		 <script src="{{asset('js/jquery.geocomplete.js')}}" type="text/javascript"></script>
		 <script src="{{asset('js/listPlaces.js')}}" type="text/javascript"></script>
@stop
=======
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
	<!--  google map -->
     <div id="map" class="placeMap"></div>
     
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
		
			<script type="text/javascript">
		
		   function initMap() {
			   <?php $i = 1; ?>
			   var locations = [];
			   @php $place = $placeDetails @endphp
			   @if (isset($place['name']) && isset($place['geometry']))
					var location = ['{{$place['name']}}', {{$place['geometry']['location']['lat']}}, {{$place['geometry']['location']['lng']}}, {{$i++}}];
					locations.push(location);
				@endif
		
			
			    var map = new google.maps.Map(document.getElementById('map'), {
			      zoom: 10,
			      center: new google.maps.LatLng({{$coords[0]}}, {{$coords[1]}}),
			      mapTypeId: google.maps.MapTypeId.ROADMAP
			    });
		
		
			    function createInfoWindow(map, marker, i) {
			    	var infowindow =  new google.maps.InfoWindow({
				    	  content: locations[i][0]
				      });
		
		
				      google.maps.event.addListener(marker, 'click', function() {
					          infowindow.open(map, marker);
				      });
				}
		
				
				
			    var marker, i;
				
			    for (i = 0; i < locations.length; i++) {
				      marker = new google.maps.Marker({
					    label: locations[i][3].toString(),
				        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				        map: map
				      });
		
				      createInfoWindow(map, marker, i);
			    }
			   }
	  </script>
		  <script src="https://maps.googleapis.com/maps/api/js?key={{App\Libraries\Places::$GOOGLE_MAPS_API_KEY}}&callback=initMap&libraries=places" type="text/javascript"></script>
		 <script src="{{asset('js/jquery.geocomplete.js')}}" type="text/javascript"></script>
		 <script src="{{asset('js/listPlaces.js')}}" type="text/javascript"></script>
@stop
>>>>>>> b7ce69728455668dfdace80bcb4dbd78a0dcac54
