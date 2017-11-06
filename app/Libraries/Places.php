<?php


namespace App\Libraries;

use Mills\GooglePlaces\googlePlaces;


class Places {
	private $apiKey = 'AIzaSyDrUhUNs0C7BEbWncvhT9-dUFhFC4jIRlU';
	
	public static function getResults($lat, $long) {
		$apiKey = 'AIzaSyDrUhUNs0C7BEbWncvhT9-dUFhFC4jIRlU';
		$googlePlaces = new googlePlaces ( $apiKey );
		
		// Set the client ip current longitude and the latitude of the location you want to search near for places
		$googlePlaces->setLocation ( $lat . ',' . $long );
		
		$googlePlaces->setRadius ( 5000 );
		$results = $googlePlaces->search ();
		
		return $results;
		
	}
}
?>