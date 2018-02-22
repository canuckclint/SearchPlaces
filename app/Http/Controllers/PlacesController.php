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
		if(isset($placeResults['result']) && is_array($placeResults['result']))
		{
			return view("Places/list", ['placeResults' =>$placeResults, 
					'locationTerm' => $locationTerm, 
					'searchTerm' => $searchTerm,
					'locationId' => $locationId, 
					'coords' => [$lat, $long]]);
		}
		else 
		{
			
			return view("layouts/main");
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
		
		return view("Places/place", ['placeDetails' => $placeDetails['result'],
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
				//add distances
				foreach($placeResults['result'] as &$placeResult) {
					if(isset($placeResult['geometry']['location'])) {
						$plat = $placeResult['geometry']['location']['lat'];
						$plong = $placeResult['geometry']['location']['lng'];
						
						$distance = $this->getDistanceBetweenPoints($lat, $long, $plat, $plong);
						$placeResult['distance'] = $distance;
					}
				}
				
				
				//sort by distance
				usort($placeResults['result'], 'self::compareDistance');
				
				return view("Places/list", ['placeResults' =>$placeResults,
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
	
	private function compareDistance($place1, $place2) {
		$dist1 = 0;
		$dist2 = 0;
		if(isset($place1['distance'])) {
			$dist1 = $place1['distance'];
		}
		if(isset($place2['distance'])) {
			$dist2 = $place2['distance'];
		}
		return ($dist1 > $dist2);
			
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
	
	
	private function getDrivingDistance($lat1,$long1, $lat2, $long2)
	{
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&&units=imperial&destinations=".$lat2.",".$long2."&mode=driving&key=".Places::$GOOGLE_MAPS_API_KEY;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response, true);
		$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
		$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
	
		return array('distance' => $dist, 'time' => $time);
	}
	
	private function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
	    $theta = $lon1 - $lon2;
	    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
	    $miles = acos($miles);
	    $miles = rad2deg($miles);
	    $miles = $miles * 60 * 1.1515;
	    return number_format($miles, 1, '.', '');
	}
}

?>