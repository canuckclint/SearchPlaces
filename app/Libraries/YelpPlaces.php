<?php

namespace App\Libraries;

use \Stevenmaguire\OAuth2\Client\Provider\Yelp;

class YelpPlaces {
	private $accessToken = 'AdBbC9xSv_CS8nzp6wZw9vLloJ6rSjsCwOEngmY1wcnASTqb0VTHuBBwWFH84RlJoDfYUfeNmP1abXsOpXGeUBMKVhsRv7NwrSwm9vQNg4vYBCGQ6cD2IneNMPoEWnYx';
	private $client = null;
	public function __construct() {
		// Provide the access token to the yelp-php client
		$this->client = new \Stevenmaguire\Yelp\v3\Client ( array (
				'accessToken' => $this->accessToken,
				'apiHost' => 'api.yelp.com' 
		) // Optional, default 'api.yelp.com'
 );
	}
	private function humanTiming($time) {
		$time = time () - $time; // to get the time since that moment
		$time = ($time < 1) ? 1 : $time;
		$tokens = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second' 
		);
		
		foreach ( $tokens as $unit => $text ) {
			if ($time < $unit)
				continue;
			$numberOfUnits = floor ( $time / $unit );
			
			return str_replace ( '1', 'a', $numberOfUnits ) . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
		}
	}
	public function getAccessToken() {
		// Get access token via oauth2-yelp library
		$provider = new Yelp ( [ 
				'clientId' => 'v8JFCTKVGR9MgyBL5uKfKw',
				'clientSecret' => 'GgNmkZZqgC4YcPBTy7XaGBsR6heDYdNkJe14B0DUezrgmlbMGjV95XbfVdTaKNUT' 
		] );
		
		$accessToken = ( string ) $provider->getAccessToken ( 'client_credentials' );
		return $accessToken;
	}
	public function getTest() {
		$parameters = [ 
				'term' => 'riva',
				'location' => '60611',
				// 'latitude' => 41.8781,
				// 'longitude' => -87.6298,
				'radius' => 40000, // 25 miles in m
				                   // 'categories' => ['bars'],
				'locale' => 'en_US',
				'limit' => 10,
				// 'offset' => 2,
				'sort_by' => 'best_match',
				'price' => '1,2,3' 
		]
		// 'open_now' => true,
		// 'attributes' => ['hot_and_new','deals']
		;
		
		$results = $this->client->getBusinessesSearchResults ( $parameters );
		var_dump ( $results );
		exit ();
	}
	public function RatingToImgURL($rating) {
		$ratingStr = ( int ) $rating . (($rating - ( int ) $rating) == 0.5 ? '_half' : '');
		return 'img/yelp-stars/regular/regular_' . $ratingStr . '.png';
	}
	public function getPlaceMatch($place) {
		// todo need postal code etc
		$postal_code = '';
		foreach ( $place ['address_components'] as $address_component ) {
			// Check types is set then get first element (may want to loop through this to be safe,
			// rather than getting the first element all the time)
			if (isset ( $address_component ['types'] ) && $address_component ['types'] [0] == 'postal_code') {
				$postal_code = $address_component ['long_name'];
			}
		}
		
		if (empty ( $postal_code ))
			return false;
		
		$parameters = [ 
				'term' => $place ['name'],
				'location' => $postal_code,
				// 'latitude' => 41.8781,
				// 'longitude' => -87.6298,
				'radius' => 40000, // 25 miles in m
				                   // 'categories' => ['bars'],
				'locale' => 'en_US',
				'limit' => 1,
				// 'offset' => 2,
				'sort_by' => 'best_match',
				//'price' => '1,2,3,4' 
		]
		// 'open_now' => true,
		// 'attributes' => ['hot_and_new','deals']
		;
		
		
		$yelpResults = $this->client->getBusinessesSearchResults ( $parameters );
		
		if(!$yelpResults) 
			return false;
		
		$businesses = $yelpResults->businesses;
		
	    //var_dump($parameters); 		var_dump($businesses); exit;
		
		if (count ( $businesses ) < 1)
			return false;
		
		$businessId = $businesses [0]->id;
		
		$business = $this->client->getBusiness ( $businessId );
		
		$revs = $this->client->getBusinessReviews ( $businessId );
		
		if(isset($revs) && isset ($revs->reviews)) {
			$business->reviews = $this->client->getBusinessReviews ( $businessId )->reviews;
		} else {
			return false;
		}
		
		$business = ( array ) $business;
		
		$namePercent = 0;
		similar_text ( $place ['name'], $business ['name'], $namePercent );
		
		
		if ($namePercent < 35) {
			return false;
		}
		
		$gPlace = $this->toGooglePlace ( $business );
		
		return $gPlace;
	}
	public function getBusinessMatch($place) {
	}
	
	// convert business result to have same fields as google place
	private function toGooglePlace($business) {
		$place = $business;
		$place ['reviews'] = array ();
		$place ['rating_img'] = $this->RatingToImgURL ( $place ['rating'] );
		
		$place ['formatted_address'] = $business ['location']->display_address [0] . ", " . $business ['location']->display_address [1];
		$place ['formatted_phone_number'] = $business ['phone'];
		
		for($i = 0; $i < count ( $business ['reviews'] ); $i ++) {
			$place ['reviews'] [] = array ();
			$place ['reviews'] [$i] ['author_name'] = $business ['reviews'] [$i]->user->name;
			$place ['reviews'] [$i] ['text'] = $business ['reviews'] [$i]->text;
			$place ['reviews'] [$i] ['rating'] = $business ['reviews'] [$i]->rating;
			$place ['reviews'] [$i] ['rating_img'] = $this->RatingToImgURL ( $business ['reviews'] [$i]->rating );
			
			$place ['reviews'] [$i] ['relative_time_description'] = $this->humanTiming ( strtotime ( $business ['reviews'] [$i]->time_created ) );
		}
		
		// reviews -> user.name => author_name
		// time_created copy => relative_time_description
		
		return $place;
	}
}

?>