$(document).on('ready', function(){

	//bind event
	var searchLocation = $("input[name='location']").val();
	
	$("input[name='location']").geocomplete({location: searchLocation})
			  .bind("geocode:result", gComplete);
	
	function gComplete (event, result){
		var placeId = result.place_id;
		//console.log(placeId);
		$("input[name='locationId']").val(placeId);
		//var $locId= $("input[name='location']");
		//$locId.val('abc' + $locId.val());
	 }
	
	// Trigger geocoding request.
	$("button").click(function(){
		if(! $("input[name='locationId']").val()) {
			$("input[name='location']").trigger("geocode");
		}
	});
});
