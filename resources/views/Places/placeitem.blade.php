@php extract($place, EXTR_PREFIX_ALL, 'p'); 
@endphp
@section('head')
		@if($placeIdx == 0 && isset($place['photos'][0]['photo_reference']))
		<style type="text/css">
				div.jumbotron::after {
					background: <?php echo 'url('. App\Libraries\Places::getPhotoURL($place['photos'][0]['photo_reference'], 500, 300) . ')' ?> no-repeat;
						background-size: cover;
						 opacity: 0.2;
						  top: 0;
						  left: 0;
						  bottom: 0;
						  right: 0;
						  position: absolute;
						  content: "";
						  height: 300px;
						  z-index: -1;
				}
		</style>
	@endif
@append
<!-- todo install blueimp gallery -->
@if(isset($place['name']))
	<h3><a href="place/{{$p_place_id}}">{{$placeIdx+1}}. {{ $p_name }}</a> </h3>
	

	@if(isset($p_types[0]))
		{{ ucwords($p_types[0]) }} &bull;
	@endif

	 {{ $p_vicinity }} <br />
	
	@if(isset($p_rating))
		{{ $p_rating }} 
		<input class="input-rating"  value="{{ $p_rating }}" />
	@endif
	
@endif

