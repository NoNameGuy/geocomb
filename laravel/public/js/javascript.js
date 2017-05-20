"use strict";
var js = $(document).ready(function(){

	getLocation();

	$("#inputdistrict").autocomplete({
		source: function(request, response){
			$.ajax({
	            url: "api/districts",
	            type: "GET",
	            dataType: "json",
	            //delay: 50,
	            data: request,
	             
	            success: function (data) {
	                response($.map(data["districts"] , function (key, value) {
	                	//console.log(data["districts"][value]);
		                return data["districts"][value];
	            }))}

	         });
		}
	});

	$("input[name='fuelType']").change(function(){
		alert($(this).val());
	});


	function getLocation() {

		if($("#latitude").val()==="" || $("#longitude").val()===""){
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition);
		    } else {
		        alert("Geolocation is not supported by this browser.");
		    }
		}
	}
	function showPosition(position) {
		$("#latitude").val(position.coords.latitude);
		$("#longitude").val(position.coords.longitude);
	    submitForm();
	}
	function submitForm(){
		$("#submit").click();
	}
	

	function initMap(centerCoordinates=null) {
		var coordinates = {"latitude": 39.676944, "longitude": -8.1425};
		//console.log(coordinates);
		if (centerCoordinates!==null) {

			centerCoordinates=""+centerCoordinates;
			var commaIndex = centerCoordinates.indexOf(",");
			coordinates.latitude = parseFloat(centerCoordinates.substr(1, commaIndex-1));
			coordinates.longitude = parseFloat(centerCoordinates.substr(commaIndex+2, commaIndex+6));

		}

		var pt = {lat: coordinates.latitude, lng:  coordinates.longitude};
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 10,
			center: pt
		});

		placeMarker(map);
		
	}

	function placeMarker(map) {
		var myLatLng = {"lat": 39.7495, "lng":-8.8077};
		var marker = new google.maps.Marker({
				position: myLatLng,
        	map: map,
          	title: 'Fuel Station'
        });
	}

	$("#inputdistrict").keyup(function(){
		//alert($("#inputdistrict").val());
		delay( function(){
			var brand = $("#inputdistrict").val();
			var geocoder = new google.maps.Geocoder();
			geocodeAddress(geocoder, map);
		},1000);
	});

	var delay = (function(){
		var timer = 0;
	  	return function(callback, ms){
	    	clearTimeout (timer);
	    	timer = setTimeout(callback, ms);
	  	};
	})();

	function geocodeAddress(geocoder, resultsMap) {
        var address = $('#inputdistrict').val();
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            initMap(results[0].geometry.location);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
	}

	window.initMap = initMap;
});
