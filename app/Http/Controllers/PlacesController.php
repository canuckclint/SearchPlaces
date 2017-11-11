<?php

namespace App\Http\Controllers;

use App\Libraries\Places;
use App\Libraries\YelpPlaces;


class PlacesController extends Controller
{
	protected function Index()
	{
		$client_ip = $_SERVER['REMOTE_ADDR'];
		
		//debug
		if($client_ip == '::1')
			$client_ip = '75.72.7.215';
		
		$geoResult = app('geocoder')->geocode($client_ip)->get();
		$geoCoords = $geoResult->first()->getCoordinates();
		
		$placeResults = Places::getResults($geoCoords->getLatitude(), $geoCoords->getLongitude());
		
		
		return view("Places\list", ['placeResults' =>$placeResults]);
		
	}
	
	protected function Place($placeId)
	{
		$placeDetails = Places::getDetails($placeId);
		
		$yelpPlaceDetails = (new YelpPlaces())->getPlaceMatch($placeDetails['result']);
		
		
			
		$placeDetails['result']['pType'] = 'google';
		
		if(!empty($yelpPlaceDetails))
			$yelpPlaceDetails['pType'] = 'yelp';
		
		return view("Places\place", ['placeDetails' => $placeDetails['result'],
				'yPlaceDetails' => $yelpPlaceDetails
		]);
	}
	
	
}

?>