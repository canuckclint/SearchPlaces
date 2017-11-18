@extends('layouts.main')

@section('head')

	<link href="{{asset('vendor\kartik-v\bootstrap-star-rating\css\star-rating.css')}}" media="all" rel="stylesheet" type="text/css" />
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
     <div id="map" style="width: 300px; height: 250px; margin-top: 20px;"></div>
     
     @php $placeIdx=0; @endphp
   
     @foreach ($placeResults['result'] as $place)
			@include('Places.placeitem', ['place' => $place, 'placeIdx' => $placeIdx]) 
		@php $placeIdx++; @endphp
     @endforeach
@stop 

@section('footer')
		<script src="{{asset('vendor\kartik-v\bootstrap-star-rating\js\star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/star-rating.js')}}" type="text/javascript"></script>
		

		<script type="text/javascript">
		
		   function initMap() {
			   <?php $i = 1; ?>
			   var locations = [];
			   @foreach ($placeResults['result'] as $place)
			   @if (isset($place['name']) && isset($place['geometry']))
					var location = ['{{$place['name']}}', {{$place['geometry']['location']['lat']}}, {{$place['geometry']['location']['lng']}}, {{$i++}}];
					locations.push(location);
				@endif
				@endforeach
		
		
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