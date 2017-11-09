@extends('layouts.main')


@php extract($placeDetails['result'], EXTR_PREFIX_ALL, 'p'); 
@endphp



@section('head')

	<link href="{{asset('..\vendor\kartik-v\bootstrap-star-rating\css\star-rating.css')}}" media="all" rel="stylesheet" type="text/css" />
	<style type="text/css">
		div.rating-xs {
	    	font-size: 1em;
		}
		div.rating-container {
			display: inline;
		}
	</style>
@stop


@section('head')
	<link href="{{asset('css/place.css')}}" rel="stylesheet">
	<link href="{{asset('css/blueimp-gallery/blueimp-gallery.css')}}" rel="stylesheet">
@append

@section('content')


@if(isset($placeDetails['result']['name']))
	<h3>{{ $p_name }}</h3>


	 {{ $p_vicinity }} 
	@if(isset($p_types[0]))
		&bull; {{ ucwords(str_replace('_', ' ', $p_types[0])) }}s 
	@endif
	 <br />
	
	@if(isset($p_rating))
		{{ $p_rating }} 
		<input class="input-rating" value="{{ $p_rating }}" />
	@endif
	
	@if(($placeDetails['result']['reviews']))
		<div class="reviews">
			@foreach($p_reviews as $review)
				<label>{{ $review['author_name'] }}</label>
				
				<input class="input-rating"  value="{{ $review['rating'] }}" /><br />
<!-- 				<span class="lblRating">{{ $review['rating'] }}</span>  -->
				<span class="lblRel">{{ $review['relative_time_description']}}</span>
				<p>{{$review['text']}}</p>
				
				
			@endforeach
		</div>
	
	@endif
	
@endif

<div id="photolinks">
	@foreach($p_photos as $photo)
	    <a href="{{App\Libraries\Places::getPhotoURL($photo['photo_reference'], 500, 500)}}" title="{{$p_name}}">
	        <img src="{{App\Libraries\Places::getPhotoURL($photo['photo_reference'], 50, 50)}}" title="{{$p_name}}">
	    </a>
    @endforeach
</div>



@stop

{{-- var_dump($placeDetails['result']) --}}
@section('footer')
		<script src="{{asset('..\vendor\kartik-v\bootstrap-star-rating\js\star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/star-rating.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/blueimp-gallery/blueimp-gallery.min.js')}}" type="text/javascript"></script>
		<script>
			document.getElementById('photolinks').onclick = function (event) {
			    event = event || window.event;
			    var target = event.target || event.srcElement,
			        link = target.src ? target.parentNode : target,
			        options = {index: link, event: event},
			        links = this.getElementsByTagName('a');
			    blueimp.Gallery(links, options);
			};
			</script>
@stop
