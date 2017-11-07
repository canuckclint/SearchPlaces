@php extract($place, EXTR_PREFIX_ALL, 'p'); 
@endphp

@section('head')

	<link href="..\vendor\kartik-v\bootstrap-star-rating\css\star-rating.css" media="all" rel="stylesheet" type="text/css" />
	<style type="text/css">
		div.rating-xs {
	    	font-size: 1em;
		}
		div.rating-container {
			display: inline;
		}
	</style>
@stop

@if(isset($place['name']))

	<h3>{{ $p_name }} </h3>


	@if(isset($p_types[0]))
		{{ ucwords($p_types[0]) }} &bull;
	@endif

	 {{ $p_vicinity }} <br />
	
	@if(isset($p_rating))
		{{ $p_rating }} 
		<input class="input-rating"  value="{{ $p_rating }}" />
	@endif
	
@endif
