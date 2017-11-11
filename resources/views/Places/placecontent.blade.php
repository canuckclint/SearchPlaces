@php extract($placeDetails, EXTR_PREFIX_ALL, 'p'); 
@endphp

@if(isset($placeDetails['name']))

<div id="plLogo">
<a target="_blank" href="{{$p_url}}" title="{{$p_name}} website">
	@if($p_pType=='yelp')
		<img class="yelpLogo" src="{{asset('img/yelp_logo.png')}}" />
	@else
		<img class="googleLogo" src="{{asset('img/gplaces_logo.png')}}"  />
	@endif
</a>
</div>

	<h3>{{ $p_name }}</h3>

	<div id="photolinks" class="photolinks">
	@if(isset($placeDetails['photos']))
		@for($i=0; $i < min(count($p_photos), 7); $i++)
			@if($p_pType == 'google')
			    <a href="{{App\Libraries\Places::getPhotoURL($p_photos[$i]['photo_reference'], 500, 500)}}" title="{{$p_name}}">
			        <img src="{{App\Libraries\Places::getPhotoURL($p_photos[$i]['photo_reference'], 60, 50)}}" title="{{$p_name}}">
			    </a>
		    @else
		         <a href="{{$p_photos[$i]}}" title="{{$p_name}}">
			        <img src="{{$p_photos[$i]}}"title="{{$p_name}}">
			    </a>
			@endif
		    
	    @endfor
	@endif
	</div>
	<p>
	 {{ $p_formatted_address }} 
	
		@if(isset($p_types[0]))
			&bull; {{ ucwords(str_replace('_', ' ', $p_types[0])) }}
		@endif
		 <br />
		
		@if(isset($p_rating))
				{{ $p_rating }}
					
				@if($p_pType == 'google')
					<input class="input-rating" value="{{ $p_rating }}" />
				@else
					<img src="{{ asset($p_rating_img) }}" title="rating_{{$p_rating}}" />
				@endif
		@endif
	</p>
	<p>
		@if(isset($p_formatted_phone_number))
		
			<span class="glyphicon glyphicon-earphone"></span> {{ $p_formatted_phone_number }}<br />
		@endif	
		<span class="glyphicon glyphicon-map-marker" /> <a target="_blank" href="{{$p_url}}" title="{{$p_name}} website">{{ucwords($p_pType)}}</a>
	
		
		</p>

	<!--  START REVIEWS -->
	
	@if(isset($placeDetails['reviews']))
		<div class="reviews">
			@foreach($p_reviews as $review)
				<label>{{ $review['author_name'] }}</label>
				@if($p_pType=='google')
					<input class="input-rating"  value="{{ $review['rating'] }}" />
				@else
					<img src="{{ asset($review['rating_img']) }}" title="rating_{{$review['rating']}}" />
				@endif
				
<!-- 				<span class="lblRating">{{ $review['rating'] }}</span>  -->
				<span class="lblRel">{{ $review['relative_time_description']}}</span>
				<p>{{$review['text']}}</p>
			@endforeach
		</div>
	
	@endif
	
@endif