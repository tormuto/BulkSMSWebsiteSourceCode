	//<script src="https://maps.googleapis.com/maps/api/js?v=3.17"></script>
	
	var map,geocoder;
	
    function initialize()
    {		
		var map_canvas = document.getElementById('map-canvas');
		if(map_canvas=== null)return;
		if(typeof google === 'undefined')
		{
			setTimeout(initialize,2000);
			return;
		}
		
		map_default_lat=map_canvas.getAttribute('default_lat');
		if(map_default_lat===null)map_default_lat=6.65826;

		map_default_long=map_canvas.getAttribute('default_long');
		if(map_default_long===null)map_default_long=3.31369;

		var map_options = {
		  center: new google.maps.LatLng(map_default_lat,map_default_long),
		  zoom: 16,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(map_canvas, map_options);
		geocoder= new google.maps.Geocoder();
		
		map_default_address=map_canvas.getAttribute('default_address');
		if(map_default_address!==null)codeAddress(map_default_address);
	}

	
	$(function(){
		if($('#map-canvas').length)
		{
			$('<script>').attr('type','text/javascript').attr('src','https://maps.googleapis.com/maps/api/js?v=3.17&sensor=false&callback=initialize').appendTo('head');
			////google.maps.event.addDomListener(window, 'load', initialize);
		}
	
	});
  
  function codeAddress(address)
  {
	geocoder.geocode( { 'address': address}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location
		});
		
		lat_long="";
		lat_long_i=0;
		for(var key in results[0].geometry.location) 
		{
			lat_long+=results[0].geometry.location[key];
			lat_long_i++;
			if(lat_long_i==2)break;
			lat_long+=",";
		}
		
		if(document.getElementById('address_lat_long')!==null)document.getElementById('address_lat_long').value=lat_long;

		} else {
		//alert("Geocode was not successful for the following reason: " + status);
		window.console&&console.log("Geocode was not successful for the following reason: " + status);
	  }
	});
  }
  
	var rad = function(x) {
		return x * Math.PI / 180;
	};

	var getDistance = function(p1, p2) {
	  var R = 6378137; // Earth’s mean radius in meter
	  var dLat = rad(p2.lat() - p1.lat());
	  var dLong = rad(p2.lng() - p1.lng());
	  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
		Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
		Math.sin(dLong / 2) * Math.sin(dLong / 2);
	  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	  var d = R * c;
	  return d; // returns the distance in meter
	};
