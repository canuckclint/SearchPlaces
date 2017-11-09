@php extract($place, EXTR_PREFIX_ALL, 'p'); 
@endphp
<!-- todo install blueimp gallery -->
@if(isset($place['name']))
	<h3><a href="place/{{$p_place_id}}">{{ $p_name }}</a> </h3>


	@if(isset($p_types[0]))
		{{ ucwords($p_types[0]) }} &bull;
	@endif

	 {{ $p_vicinity }} <br />
	
	@if(isset($p_rating))
		{{ $p_rating }} 
		<input class="input-rating"  value="{{ $p_rating }}" />
	@endif
	
@endif

