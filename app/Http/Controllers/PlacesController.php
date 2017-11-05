<?php

namespace App\Http\Controllers;

//require_once base_path("app/Libraries/mills/google-places/src/mills/google-places/googlePlaces.php");

use Mills\GooglePlaces\googlePlaces;

class PlacesController extends Controller
{
	protected function getResults()
	{
		$apiKey = 'AIzaSyDrUhUNs0C7BEbWncvhT9-dUFhFC4jIRlU';
		$googlePlaces = new googlePlaces ( $apiKey );
		
		// Set the longitude and the latitude of the location you want to search near for places
		$latitude = '-33.8804166';
		$longitude = '151.2107662';
		$googlePlaces->setLocation ( $latitude . ',' . $longitude );
		
		$googlePlaces->setRadius ( 5000 );
		$results = $googlePlaces->search ();
		
		$res = app('geocoder')->geocode('Los Angeles, CA')->get();
		
		print_r($results);
		
		return $results;
		
	}
	
}

?>