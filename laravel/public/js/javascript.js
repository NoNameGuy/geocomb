"use strict";
var js = $(document).ready(function(){

	getLocation();

	var array;
	$("#district").autocomplete({
		source: function(request, response){
			if (array){
				response($.ui.autocomplete.filter(array, request.term));
			}else{
			$.ajax({
	            url: "api/districts",
	            type: "GET",
	            dataType: "json",
	            //delay: 50,
	            data: request.term,

	            success: function (data) {
	                response(array = $.map(data["districts"] , function (key, value) {
		                return data["districts"][value];
	            }))}

	         });
		}
	}
	});

	$("#brand").autocomplete({
		source: function(request, response){
			if (array){
				response($.ui.autocomplete.filter(array, request.term));
			}else{
			$.ajax({
							url: "api/brands",
							type: "GET",
							dataType: "json",
							//delay: 50,
							data: request.term,

							success: function (data) {
									response(array = $.map(data["brands"] , function (key, value) {
										return data["brands"][value];
							}))}

					 });
		}
	}
	});

	$("input[name='fuelType']").change(function(){
		alert($(this).val());
	});




	$("#landingSearch").click(function(){
		localStorage.setItem("district", $("#district").val());
		localStorage.setItem("brand", $("#brand").val());
		var fuel = [];
		$("#landingFuelType input:checked").each(function(){
			fuel.push($(this).val());
		});
		localStorage.setItem("fuelType", fuel);
	});

	function getStations(){
		if(localStorage.getItem("district")===null || localStorage.getItem("brand")===null || localStorage.getItem("fuelType")===null){
			/*localStorage.setItem("district", $("#district").val());
			localStorage.setItem("brand", $("#brand").val());
			localStorage.setItem("fuelType", $("#landingFuelType>input[name='fuelType']").val());*/
			//alert("Please fill the data");
		}else {
			var fuelType=new Array();
			var district=localStorage.getItem("district");
			var brand=localStorage.getItem("brand");
			fuelType.push(localStorage.getItem("fuelType"));
		/*var district=$("#district").val();
		var fuelType=$("#landingFuelType>input[name='fuelType']").val();
		var brand=$("#brand").val();*/
		var stationsData;
		var link = null;
		if(brand===undefined || brand===''){
			link= "api/stations/"+district+"/all/"+fuelType[0];
		}else{
			link= "api/stations/"+district+"/"+brand+"/"+fuelType[0];
		}
		console.log(link);
		$.ajax({
						async: false,
						url: link,
            type: "GET",
            dataType: "json",
						success: function (data) {
							stationsData = data["stations"];
							//console.table(stationsData);
						},

            error: function (textStatus, errorThrown) {
                console.log("Error getting the station data")
            }

         });

			 }
			 //console.table(stationsData);
			 return stationsData;

	}


		function initMap(location=null) {
			var coordinates = {"latitude": null, "longitude": null};
			if(location!==null){
				coordinates = {"latitude": location.latitude, "longitude": location.longitude};
			}else{
				if($("#latitude").val()!='' && $("#longitude").val()!=''){
					coordinates = {"latitude": $("#latitude").val(), "longitude": $("#longitude").val()};
				}else{
				 	coordinates = {"latitude": 39.676944, "longitude": -8.1425};
				}
			}

			var pt = {lat: parseFloat(coordinates.latitude), lng:  parseFloat(coordinates.longitude)};
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 10,
				center: pt
			});

			var markers=new Array();
			markers.push(getStations());
				//console.log(markers);
			//label: labels[labelIndex++ % labels.length],
			placeMarker(map, markers);

		}


		function placeMarker(map, markers) {
			var myLatLng = {"lat": 39.7495, "lng":-8.8077};
			var labels = '12345';
			var labelIndex = 0;
			markers.forEach(function(marker){
				for (var i = 0; i < marker.length; i++) {
					//console.log(parseFloat(marker[i].latitude)+" lng"+ parseFloat(marker[i].longitude));
					new google.maps.Marker({
							position: {"lat": parseFloat(marker[i].latitude), "lng": parseFloat(marker[i].longitude)},
							label: labels[labelIndex++ % labels.length],
			        map: map,
			        title: marker[i].stationName
			      });
				}
			});
		}

	function getLocation() {

		if (($('#latitude').val()==='' || $('#longitude').val()==='') && localStorage.getItem("latitude")!==null && localStorage.getItem("longitude")!==null) {
			$('#latitude').val(localStorage.getItem("latitude"));
			$('#longitude').val(localStorage.getItem("longitude"));

		}else{
			if($("#latitude").val()==="" || $("#longitude").val()===""){
			    if (navigator.geolocation) {
			        navigator.geolocation.getCurrentPosition(showPosition);
			    } else {
			        alert("Geolocation is not supported by this browser.");
			    }
			}
		}
	}

	function showPosition(position) {


			$("#latitude").val(position.coords.latitude);
			$("#longitude").val(position.coords.longitude);
			localStorage.setItem("latitude", position.coords.latitude);
			localStorage.setItem("longitude", position.coords.longitude);
		    submitForm();

	}
	function submitForm(){
		$("#landingHiddenSubmit").click();
	}


	$("#district").keyup(function(){
		//alert($("#district").val());
		delay( function(){
			var brand = $("#district").val();
			var geocoder = new google.maps.Geocoder();
			geocodeAddress(geocoder, map);
		},1000);
	});

	$("#landingBack").click(function(){
		$("#district").val('');
		$("#brand").val('');
		$("#landingFuelType>input[name='fuelType']").prop('checked', false);
		$("#landingSearch").click();
	});

	var delay = (function(){
		var timer = 0;
	  	return function(callback, ms){
	    	clearTimeout (timer);
	    	timer = setTimeout(callback, ms);
	  	};
	})();

	function geocodeAddress(geocoder, resultsMap) {
        var address = $('#district').val();
        var location = { 'latitude':null, 'longitude':null};
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
          	location.latitude = results[0].geometry.location.lat();
          	location.longitude = results[0].geometry.location.lng();
            initMap(location);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
	}

	$('#landingSearch').click(function(){
		/*$.ajax({
            url: "api/stations",
            type: "GET",
            dataType: "json",
            //delay: 50,
            //data: request,

            success: function (data) {
                response($.map(data["districts"] , function (key, value) {
                	//console.log(data["districts"][value]);
	                return data["districts"][value];
            }))}

         });*/
         initMap();
	});

	function updateVehiclePage(brand, model, fuel, consumption, preferred){
		alert("updateVehiclePage");
	}

	//User Page

	function initMapUP() {
		var coordinates = {"latitude": null, "longitude": null};

				coordinates = {"latitude": 39.676944, "longitude": -8.1425};


		var pt = {lat: parseFloat(coordinates.latitude), lng:  parseFloat(coordinates.longitude)};
		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer;

		var mapUP = new google.maps.Map(document.getElementById('mapUP'), {
			zoom: 10,
			center: pt
		});
		directionsDisplay.setMap(mapUP);




		$("#upSearch").click(function(){
				var coordinates = getCoordinates();
				/*console.table(coordinates.origin);
				console.table(coordinates.destination);*/

				calculateAndDisplayRoute(directionsService, directionsDisplay);
		});


	}

	function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        /*var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
          if (checkboxArray.options[i].selected) {
            waypts.push({
              location: checkboxArray[i].value,
              stopover: true
            });
          }
        }*/

				//waypts.push({location: "Leiria",stopover: true}, {location: "Lisboa",stopover: true});

        directionsService.route({
          origin: "Leiria",
          destination: "Lisboa",
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
						console.table(response);
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            /*var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }*/
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }


	function getCoordinates(){
		var origin = $("#upOrigin").val();
		var destination = $("#upDestination").val();
		var key = "AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s";
		var coordinates = {origin: {latitude:null, longitude:null},destination: {latitude:null, longitude:null} };

		$.ajax({
			dataType: "json",
			url: "https://maps.googleapis.com/maps/api/geocode/json?address="+origin+"&key="+key,
			success: function(data){
				coordinates.origin.latitude = data.results[0].geometry.location.lat;
				coordinates.origin.longitude = data.results[0].geometry.location.lng;
			}
		});
		$.ajax({
			dataType: "json",
			url: "https://maps.googleapis.com/maps/api/geocode/json?address="+destination+"&key="+key,
			success: function(data){
				coordinates.destination.latitude = data.results[0].geometry.location.lat;
				coordinates.destination.longitude = data.results[0].geometry.location.lng;
			}
		});
		return coordinates;
	}

	window.initMap = initMap;
	window.initMapUP = initMapUP;
	window.updateVehiclePage = updateVehiclePage;
});
