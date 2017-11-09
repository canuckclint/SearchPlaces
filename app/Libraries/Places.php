<?php


namespace App\Libraries;

use Mills\GooglePlaces\googlePlaces;




class Places {	
// 	public static $GOOGLE_MAPS_API_KEY = '';

	public static $GOOGLE_MAPS_API_KEY = 'AIzaSyD4ZF8rTYyGnGAWxIGDdDTxiYKd6ANaAr4';
	
	
	
	public static function getResults($lat, $long) {
		
		$googlePlaces = new googlePlaces ( self::$GOOGLE_MAPS_API_KEY );
		
		// Set the client ip current longitude and the latitude of the location you want to search near for places
		$googlePlaces->setLocation ( $lat . ',' . $long );
		
		$googlePlaces->setRadius ( 5000 );
		$results = $googlePlaces->search ();
		
		return $results;
		
	}
	


	public static function getDetails($placeId) {
		$googlePlaces = new googlePlaces ( self::$GOOGLE_MAPS_API_KEY );
		
		// Set the place id
		$googlePlaces->setPlaceId($placeId);
		
		$results = $googlePlaces->details ();
		
		return $results;
		
	}
	

	public static function getPhotoURL($photoref, $maxwidth=200, $maxheight=200) {
	
		$googlePlaces = new googlePlaces ( self::$GOOGLE_MAPS_API_KEY );
	
		// Set the client ip current longitude and the latitude of the location you want to search near for places
		return $googlePlaces->photo($photoref, $maxwidth, $maxheight );

	
	}
}
?>