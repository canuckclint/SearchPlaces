<?php

namespace App\Http\Controllers;

use App\Libraries\Places;


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
		
		$res = Places::getResults($geoCoords->getLatitude(), $geoCoords->getLongitude());
		
		
		
		
		
		var_dump($res);
		
		
	}
	
}

?>