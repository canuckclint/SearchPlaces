<?php


namespace App\Libraries;

use Mills\GooglePlaces\googlePlaces;




class Places {	
// 	public static $GOOGLE_MAPS_API_KEY = '';

	public static $GOOGLE_MAPS_API_KEY = 'AIzaSyDEgqkcGDKEQYw4ARPLIojUU8WN-AmYxx8';
	
	
	
	public static function getResults($lat, $long) {
		
		$googlePlaces = new googlePlaces ( self::$GOOGLE_MAPS_API_KEY );
		
		// Set the client ip current longitude and the latitude of the location you want to search near for places
		$googlePlaces->setLocation ( $lat . ',' . $long );
		
		$googlePlaces->setRadius ( 5000 );
		$results = $googlePlaces->search ();
		
		return $results;
		
	}
}
?>