@extends('layouts.main')

@section('head')

	<link href="{{asset('..\vendor\kartik-v\bootstrap-star-rating\css\star-rating.css')}}" media="all" rel="stylesheet" type="text/css" />
	<style type="text/css">
		div.rating-xs {
	    	font-size: 1em;
		}
		div.rating-container {
			display: inline;
		}
	</style>
@stop

@section('content')

     <!--  google map -->
     <div id="map" style="width: 400px; height: 350px;"></div>
     @foreach ($placeResults['result'] as $place)
			@include('Places.placeitem', $place) 
     @endforeach
     

@stop 

@section('footer')
		<script src="{{asset('..\vendor\kartik-v\bootstrap-star-rating\js\star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/star-rating.js')}}" type="text/javascript"></script>

		<script type="text/javascript">
		
	   function initMap() {
		   <?php $i = 0; ?>
		   var locations = [];
		   var i = 1;
		   @foreach ($placeResults['result'] as $place)
		   @if (isset($place['name']) && isset($place['geometry']))
				var location = ['{{$place['name']}}', {{$place['geometry']['location']['lat']}}, {{$place['geometry']['location']['lng']}}, {{$i++}}];
				locations.push(location);
			@endif
			@endforeach
	
	
	    var map = new google.maps.Map(document.getElementById('map'), {
	      zoom: 10,
	      //TODO change to currenct client IP location
	      center: new google.maps.LatLng(44.9537029, -93.0899578),
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    });
	
	    var infowindow = new google.maps.InfoWindow();
	
	    var marker, i;
	
	    for (i = 0; i < locations.length; i++) {
	      infowindow.setContent(locations[i][3].toString());
	      marker = new google.maps.Marker({
		    label: locations[i][3].toString(),
	        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	        map: map
	      });
	
	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	          infowindow.open(map, marker);
	        }
	      })(marker, i));
	    }
	   }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{App\Libraries\Places::$GOOGLE_MAPS_API_KEY}}&callback=initMap" type="text/javascript"></script>
  
@stop
