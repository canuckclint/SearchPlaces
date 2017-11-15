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
			$client_ip = '65.110.6.36';
		
		$geoResult = app('geocoder')->geocode($client_ip)->get ();
	    $geoCoords = $geoResult->first()->getCoordinates();
	
		$geoLocation = array('locationStr' => '', 'coords' => '');
		$locationTerm = '';
		if($geoResult->count() > 0 && $geoResult->first()->getAdminLevels()->count() > 0)
		{
			$locationTerm = $geoResult->first()->getLocality() . ', ' . $geoResult->first()->getAdminLevels()->first()->getCode();
				
			$coords = [$geoCoords->getLatitude(), $geoCoords->getLongitude()];
			
			$geoLocation = array('locationStr' => $locationTerm, 'coords' => $coords);
		}
		
		return $geoLocation;
		

	}
	
	protected function Index($includeResults = true)
	{	
		$searchInput = session('searchInput');
		
		if(is_array($searchInput))
		{
			$searchTerm = $searchInput['searchTerm'];
			$locationTerm = $searchInput['locationTerm'];
			$locationId = $searchInput['locationId'];
			
			if(empty($locationId))
			{
				$geoResult = $this->getClientGeoLocation();
				$locationTerm = $geoResult['locationStr'];
				$locationId = '';

				$lat = $geoResult['coords'][0];
				$long = $geoResult['coords'][1];
			}
			else 
			{
				//found locationId
				//get place to get lat/long
				$placeDetails = Places::getDetails($locationId);
					
				$locationId = $placeDetails['result']['place_id'];
				//$locationTerm = $this->getLocationTermFromAddressComps($placeDetails['result']['address_components']);
				
				$lat =  $placeDetails['result']['geometry']['location']['lat'];
				$long = $placeDetails['result']['geometry']['location']['lng'];
			}
		}
		else 
		{
			$geoResult = $this->getClientGeoLocation();
			$locationTerm = $geoResult['locationStr'];
			$locationId = '';
			$searchTerm = '';
			

			$lat = $geoResult['coords'][0];
			$long = $geoResult['coords'][1];
			
		}
		
		//set searchInput session
		$searchInput = ['locationId' => $locationId, 'searchTerm' => $searchTerm, 'locationTerm' => $locationTerm];
		session(['searchInput' => $searchInput, 'coords' => [$lat, $long]]);
		
		$placeResults = Places::getSearchResults($lat, $long, $searchTerm);
		
		if(is_array($placeResults['result']))
		{
			return view("Places\list", ['placeResults' =>$placeResults, 
					'locationTerm' => $locationTerm, 
					'searchTerm' => $searchTerm,
					'locationId' => $locationId, 
					'coords' => [$lat, $long]]);
		}
		else 
		{
			
			return view("layouts\main.blade.php");
		}
		
	}
	
	protected function Place($placeId)
	{
		//get the input
		$searchInput = session('searchInput');
		if(!is_array($searchInput))
		{
			return $this->index(false);
		}
		$coords = session('coords');
		
		$geoResult = $this->getClientGeoLocation();
		$placeDetails = Places::getDetails($placeId);
		
		$yelpPlaceDetails = (new YelpPlaces())->getPlaceMatch($placeDetails['result']);
			
		$placeDetails['result']['pType'] = 'google';
		
		if(!empty($yelpPlaceDetails))
			$yelpPlaceDetails['pType'] = 'yelp';
		
		return view("Places\place", ['placeDetails' => $placeDetails['result'],
				'locationTerm' => $searchInput['locationTerm'],
				'searchTerm' => $searchInput['searchTerm'],
				'locationId' => $searchInput['locationId'],
				'yPlaceDetails' => $yelpPlaceDetails,
				'coords' => $coords
		]);
	}
	
	protected function search(Request $req) {
		$locationId = $req->input('locationId');
		$searchTerm = $req->input('searchTerm');
		$locationTerm = $req->input('location');
		
		if(!empty($locationId))
		{
			
			$placeDetails = Places::getDetails($locationId);
			
			$lat =  $placeDetails['result']['geometry']['location']['lat'];
			$long = $placeDetails['result']['geometry']['location']['lng'];
			
			
			//$locationTerm = $this->getLocationTermFromAddressComps($placeDetails['result']['address_components']);
			$locationId = $placeDetails['result']['place_id'];
			
			$placeResults = Places::getSearchResults($lat, $long, $searchTerm);

			//post submitted, store in session
			$searchInput = ['locationId' => $locationId, 'searchTerm' => $searchTerm, 'locationTerm' => $locationTerm];
			session(['searchInput' => $searchInput, 'coords' => [$lat, $long]]);
			
			if(isset($placeResults['result'])) {
				return view("Places\list", ['placeResults' =>$placeResults,
						'locationTerm' => $locationTerm,
						'locationId' => $locationId,
						'searchTerm' => $searchTerm,
						'coords' => [$lat, $long]]);
			}
			
			
			return $this->index(false);
		} 
		else 
		{
			//post submitted, store in session
			$searchInput = ['locationId' => $locationId, 'searchTerm' => $searchTerm, 'locationTerm' => $locationTerm];
			session(['searchInput' => $searchInput]);
			
			return $this->index(false);
		}
		
	}
	
	private function getLocationTermFromAddressComps($ac) {
		$locality =  '';
		$state = '';
		foreach($ac as $address_component)
		{
			
			// Check types is set then get first element (may want to loop through this to be safe,
			// rather than getting the first element all the time)
			if(isset($address_component['types']) && $address_component['types'][0] == 'locality')
			{
				$locality = $address_component['long_name'];
			}
			if(isset($address_component['types']) && $address_component['types'][0] == 'administrative_area_level_1')
			{
				$state = $address_component['short_name'];
			}
			
		}
		return $locality . ', ' . $state;
	}
}

?>