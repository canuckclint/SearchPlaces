$(document).on('ready', function(){
	//bind event
	var searchLocation = $("input[name='location']").val();
	
	var geoCodeInput = $("input[name='location']").geocomplete({location: searchLocation});
	
	geoCodeInput.bind("geocode:result", gComplete);
	
	geoCodeInput.bind("geocode:error", gError);
	
	var searchClicked = false;
	
	function gError(event) {
		$("input[name='locationId']").val('');
		searchComplete = true;
		
		if(searchClicked)
		{
			$('form#search').submit();
			searchClicked = false;
		}
	}
	
	function gComplete (event, result){
		var placeId = result.place_id;
		$("input[name='locationId']").val(placeId);

		
		if(searchClicked)
		{
			$('form#search').submit();
			searchClicked = false;
		}
		
	 }
	
	// Trigger geocoding request.
	$("button").click(function(){
		searchClicked = true;
		$("input[name='location']").trigger("geocode");	

		return false;
	});
});
