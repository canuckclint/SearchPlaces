@php extract($place, EXTR_PREFIX_ALL, 'p'); 
@endphp

@section('head')

	<link href="..\vendor\kartik-v\bootstrap-star-rating\css\star-rating.css" media="all" rel="stylesheet" type="text/css" />
@stop

@if(isset($place['name']))
	<h1>{{ $p_name }} </h1>


	Address: {{ $p_vicinity }} <br />
	
	@if(isset($p_rating))
		Rating: {{ $p_rating }}
		<input id="input-id" name="input-name" type="number" class="rating" min=1 max=5 step=1 value={{$p_rating}} data-size="xs"  
		data-displayonly="true">
	@endif
	
	{{-- var_dump($place) --}}
@endif

@section('footer')
C:\xampp\htdocs\SearchPlaces
	<script src="..\vendor\kartik-v\bootstrap-star-rating\js\star-rating.js" type="text/javascript"></script>
		<script>
$(document).on('ready', function(){
	$('#input-id').rating({displayOnly: true, step: 0.5});
	});
</script>
@stop
