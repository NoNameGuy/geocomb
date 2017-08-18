"use strict";
var js = $(document).ready(function(){

	var waypts = [];
	getLocation();
	var routePoints = [];

	$("#district").val(localStorage.getItem("district"));

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

var array2;
	$("#brand").autocomplete({
		source: function(request, response){
			if (array2){
				response($.ui.autocomplete.filter(array2, request.term));
			}else{
			$.ajax({
							url: "api/brands",
							type: "GET",
							dataType: "json",
							//delay: 50,
							data: request.term,

							success: function (data) {
									response(array2 = $.map(data["brands"] , function (key, value) {
										return data["brands"][value];
							}))}

					 });
		}
	}
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

			var link = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+","+position.coords.longitude;
			$.ajax({
							async: false,
							url: link,
				type: "GET",
				dataType: "json",
							success: function (data) {
								localStorage.setItem("district", data["results"]["0"]["address_components"]["1"]["long_name"]);
							},

				error: function (textStatus, errorThrown) {
					console.log("Error getting the station data")
				}

			 });


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



		var markers=new Array();

		$("#upSearch").click(function(){
			var autonomykm = $("#upAutonomyKm").val();
			var autonomyl = $("#upAutonomyL").val();
			var consumption = $("#upConsumption").val();
			//original logic
			//(autonomykm==='' && autonomyl==='')||(autonomykm!=='' && autonomyl!=='' || consumption!=='') || (autonomyl!=='' && autonomykm!=='' && consumption==='') || (autonomyl!=='' && autonomyl==='' && consumption!=='')
			if( (autonomykm==='' && autonomyl==='') || (autonomyl!=='' && consumption==='') || (autonomykm!=='' && autonomyl!=='') ){
				alert("Preencha apenas a autonomia em km ou a autonomia em litros e o consumo");
			}else{
				var coordinates = getCoordinates();
				/*console.table(coordinates.origin);
				console.table(coordinates.destination);*/

				if($("#upAutonomyKm").val()!==''){
					localStorage.setItem("autonomy", $("#upAutonomyKm").val());
				} else {
					var autonomy = $("#upAutonomyL").val()*100/$("#upConsumption").val();
					localStorage.setItem("autonomy", autonomy);
				}

				calculateAndDisplayRoute(directionsService, directionsDisplay);
				markers.push(getStationsUP());
				//console.table(markers[0][1]);
				var myLatLng = {"lat": 39.7495, "lng":-8.8077};
				var labels = '12345';
				var labelIndex = 0;
				/*for(var j=0;j<markers[0].length;j++){

					var marker = new google.maps.Marker({
							position: {"lat": parseFloat(markers[0][j].latitude), "lng": parseFloat(markers[0][j].longitude)},
							label: labels[labelIndex++ % labels.length],
							map: mapUP,
							title: markers[0][j].stationName
						});

					/*marker.addListener('dblclick', function() {
						alert("double click"+ marker.position);
						waypts.push({
							location:marker.position,
							stopover: true
						});
					});*/

				//	calculateAndDisplayRoute(directionsService, directionsDisplay);
				//}
				markers[0].forEach(function(marker){
					//console.table(marker);
					var currentMarker = new google.maps.Marker({
							position: {"lat": parseFloat(marker.latitude), "lng": parseFloat(marker.longitude)},
							label: labels[labelIndex++ % labels.length],
							map: mapUP,
							title: marker.stationName
						});

						currentMarker.addListener('dblclick', function() {
							//alert("double click"+ currentMarker.position);
							waypts.push({
								location:currentMarker.position,
								stopover: true
							});
							calculateAndDisplayRoute(directionsService, directionsDisplay);
						});

				});

		}
	});
}

	$('#sendRouteEmail').click(function(){
		//console.table(routePoints);
		var start = routePoints["legs"][0]["start_address"];
		var station = routePoints["legs"][0]["end_address"];
		var end = routePoints["legs"][1]["end_address"];
		var link = "https://www.google.com/maps/dir/"+start+"/"+station+"/"+end;
		$('#routeLink').val("asdf");
		console.log(link);
	});


	function calculateAndDisplayRoute(directionsService, directionsDisplay) {

		directionsService.route({
		  origin: $("#upOrigin").val(),
		  destination: $("#upDestination").val(),
		  waypoints: waypts,
		  optimizeWaypoints: true,
		  travelMode: 'DRIVING'
		}, function(response, status) {
		  if (status === 'OK') {
			directionsDisplay.setDirections(response);
			var route = response.routes[0];
			routePoints = route;
						//console.table(route);
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

	function getStationsUP(){
		var origin = $("#upOrigin").val();
		var destination = $("#upDestination").val();
		var autonomy = localStorage.getItem("autonomy");
		var link= "api/stationsup/"+origin+"/"+destination+"/"+autonomy;
		var stationsData =null;
	//console.log(link);
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

		 //console.table(stationsData);
		 return stationsData;

	}

	window.initMap = initMap;
	window.initMapUP = initMapUP;
	window.updateVehiclePage = updateVehiclePage;
});
