@extends('layouts.main')



@section('content')

     <!--  google map -->
     <div id="map" style="width: 500px; height: 400px;"></div>

     @foreach ($placeResults['result'] as $place)
		@include('Places.placeitem', $place) 	
     @endforeach
     

@stop 

@section('footer')
		<script src="..\vendor\kartik-v\bootstrap-star-rating\js\star-rating.js" type="text/javascript"></script>
		<script>
	$(document).on('ready', function(){
	 	$('.input-rating').rating({  min: 0,
	        max: 5,
	        step: 1,
	        size: 'xs',
	        displayOnly: true,
	        readOnly: true
	});
	 	});
	</script>

		<script type="text/javascript">
	   function initMap() {
	    var locations = [
	      ['Bondi Beach', -33.890542, 151.274856, 4],
	      ['Coogee Beach', -33.923036, 151.259052, 5],
	      ['Cronulla Beach', -34.028249, 151.157507, 3],
	      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
	      ['Maroubra Beach', -33.950198, 151.259302, 1]
	    ];
	
	    var map = new google.maps.Map(document.getElementById('map'), {
	      zoom: 10,
	      center: new google.maps.LatLng(-33.92, 151.25),
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    });
	
	    var infowindow = new google.maps.InfoWindow();
	
	    var marker, i;
	
	    for (i = 0; i < locations.length; i++) {  
	      marker = new google.maps.Marker({
	        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	        map: map
	      });
	
	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	          infowindow.setContent(locations[i][0]);
	          infowindow.open(map, marker);
	        }
	      })(marker, i));
	    }
	   }
  </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ZF8rTYyGnGAWxIGDdDTxiYKd6ANaAr4&callback=initMap" type="text/javascript"></script>
  
@stop
