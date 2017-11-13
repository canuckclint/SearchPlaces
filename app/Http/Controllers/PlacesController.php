<?php

namespace App\Http\Controllers;

use App\Libraries\Places;
use App\Libraries\YelpPlaces;
use Illuminate\Http\Request;

class PlacesController extends Controller
{
	
	protected function getClientGeoLocation () 
	{
		$client_ip = $_SERVER['REMOTE_ADDR'];
		
		//debug
		if($client_ip == '::1')
			$client_ip = '75.72.7.215';
		
		$geoResult = app('geocoder')->geocode($client_ip)->get ();
	    $geoCoords = $geoResult->first()->getCoordinates();
	
		$geoLocation = array('locationStr' => '', 'coords' => '');
		$clientLocation = '';
		if($geoResult->count() > 0 && $geoResult->first()->getAdminLevels()->count() > 0)
		{
			$clientLocation = $geoResult->first()->getLocality() . ', ' . $geoResult->first()->getAdminLevels()->first()->getCode();
				
			$coords = [$geoCoords->getLatitude(), $geoCoords->getLongitude()];
			
			$geoLocation = array('locationStr' => $clientLocation, 'coords' => $coords);
		}
		
		return $geoLocation;
		

		//session(['key' => 'value']);
	}
	
	protected function Index()
	{	
		$geoResult = $this->getClientGeoLocation();
		$placeResults = Places::getResults($geoResult['coords'][0], $geoResult['coords'][1]);
		
		
		return view("Places\list", ['placeResults' =>$placeResults, 
				'clientLocation' => $geoResult['locationStr'], 
				'coords' => $geoResult['coords']]);
		
	}
	
	protected function Place($placeId)
	{
		$geoResult = $this->getClientGeoLocation();
		$placeDetails = Places::getDetails($placeId);
		
		$yelpPlaceDetails = (new YelpPlaces())->getPlaceMatch($placeDetails['result']);
			
		$placeDetails['result']['pType'] = 'google';
		
		if(!empty($yelpPlaceDetails))
			$yelpPlaceDetails['pType'] = 'yelp';
		
		return view("Places\place", ['placeDetails' => $placeDetails['result'],
				'clientLocation' => $geoResult['locationStr'],
				'yPlaceDetails' => $yelpPlaceDetails
		]);
	}
	
	protected function search(Request $req) {
		$placeId = $req->input('locationId');
		$searchTerm = $req->input('searchTerm');
		$location = $req->input('location');
				
		if(!empty($placeId))
		{
			$placeDetails = Places::getDetails($placeId);
			
			$lat =  $placeDetails['result']['geometry']['location']['lat'];
			$long = $placeDetails['result']['geometry']['location']['lng'];
			
			$placeResults = Places::getSearchResults($lat, $long, $searchTerm);
			
			return view("Places\list", ['placeResults' =>$placeResults,
					'clientLocation' => $location,
					'searchTerm' => $searchTerm,
					'coords' => [$lat, $long]]);
		} 
		else 
		{
			return 'Snag! Place not found!';
		}
		
	}
}

?>