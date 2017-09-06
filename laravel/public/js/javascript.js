"use strict";
var js = $(document).ready(function(){

	var waypts = new Array();
	getLocation();
	var routePoints = [];
	var mapUP;

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
				zoom: 7,
				center: pt
			});

			var markers=new Array(getStations());
			console.log("markers");
			console.table(markers);
			markers.push(markers);

			var myLatLng = {"lat": 39.7495, "lng":-8.8077};
			var labels = '12345';
			var labelIndex = 0;

			markers.forEach(function(marker){
				for (var i = 0; i < marker.length; i++) {
					console.log(parseFloat(marker[i].latitude)+" lng"+ parseFloat(marker[i].longitude));
					new google.maps.Marker({
							position: {"lat": parseFloat(marker[i].latitude), "lng": parseFloat(marker[i].longitude)},
							label: labels[labelIndex++ % labels.length],
							map: map,
							title: marker[i].stationName
						});


					}

			});

		}


		function initMapDetails() {

				var map = new google.maps.Map(document.getElementById('mapDetails'), {
					zoom: 7,
					center: {lat: 39.7495, lng: -8.8077}
				});
				var currentUrl = window.location.href;
				var id = currentUrl.match(/\d+/g);
				var coordinates = {lat: null, lng:null};
				$.ajax({
					async:false,
			    url: '/api/station/'+id[0],//'http://geocomb.app/receiveCoords',
			    type: 'GET',

			    success: function (response) {
			        //console.log("data sent "+ response["station"].latitude);
							coordinates.lat = response["station"].latitude;
			        coordinates.lng = response["station"].longitude;

			    },
			    error: function(error){
			    	console.log("could not send data, error: ");
			    	console.table(error);
			    }
			});

				new google.maps.Marker({
						position: {"lat": parseFloat(coordinates.lat), "lng": parseFloat(coordinates.lng)},
						map: map,
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

		 mapUP = new google.maps.Map(document.getElementById('mapUP'), {
			zoom: 7,
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
				//var myLatLng = {"lat": 39.7495, "lng":-8.8077};
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

				$.ajax({
					async: false,
			    url: '/userpage',//'http://geocomb.app/receiveCoords',
			    type: 'POST',
			    data: {"searching": true},
			    //contentType: "application/json; charset=utf-8",

			    success: function (response) {
			        console.log("data sent "+ response);
			    },
			    error: function(error){
			    	console.log("could not send data, error: ");
			    	console.table(error);
			    }
			});


	});
}

	$('#upNewSearch').click(function(){
		$.ajax({
			async: false,
			url: '/userpage',
			type: 'POST',
			data: {"searching": false},
			//contentType: "application/json; charset=utf-8",

			success: function (response) {
					console.log("data sent "+ response);
			},
			error: function(error){
				console.log("could not send data, error: ");
				console.table(error);
			}
		});
	});

	$('#sendRouteEmail').click(function(){
		//console.table(routePoints);
		var start = routePoints["legs"][0]["start_address"];
		var station = routePoints["legs"][0]["end_address"];
		var end = routePoints["legs"][1]["end_address"];
		var link;
		link = "https://www.google.com/maps/dir/"+start+"/"+station+"/"+end;
		link = link.replace(/ /g , "+");
		$('#routeLink').val(link);
		$('#formEmailLink').submit();
	});

	$('#btnFeelingLucky').click(function(){
		var coordinates = {origin: {latitude:null, longitude:null},destination: {latitude:null, longitude:null} };
		var points = [];
		var key = "AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s";
		coordinates = getCoordinates();
		var i, j, k, l;
		var multiplier=1;


		console.log("coordinates");
		console.table(coordinates);

		var autonomyKm = $('#upAutonomyKm').val();
		var checkForStationPoints = [];
		var point = {"latitude":null, "longitude":null};
		var pointsArray = [];
		var vehicleId = $('select[name=upSelectVehicle]').val();
		var distance = $('#upAutonomyKm').val();
		console.log("distance: "+ distance);

		console.log("latitude origin: "+coordinates.origin.latitude+" longitude origin: "+coordinates.origin.longitude);

		var directionsService = new google.maps.DirectionsService();
	  var directionsDisplay = new google.maps.DirectionsRenderer({
	    map: mapUP,
	    preserveViewport: true
	  });
	  directionsService.route({
	    origin: new google.maps.LatLng(coordinates.origin.latitude, coordinates.origin.longitude),
	    destination: new google.maps.LatLng(coordinates.destination.latitude, coordinates.destination.longitude),
	    /*waypoints: [{
	      stopover: true,
	      //location: new google.maps.LatLng(51.263439, 1.03489)
	    }],*/
	    travelMode: google.maps.TravelMode.DRIVING
	  }, function(response, status) {
	    if (status === google.maps.DirectionsStatus.OK) {
		      // directionsDisplay.setDirections(response);
		      var polyline = new google.maps.Polyline({
		        path: [],
		        strokeColor: '#0000FF',
		        strokeWeight: 3
		      });
		      var bounds = new google.maps.LatLngBounds();


		      var legs = response.routes[0].legs;
		      for (i = 0; i < legs.length; i++) {
		        var steps = legs[i].steps;
		        for (j = 0; j < steps.length; j++) {
		          var nextSegment = steps[j].path;
	            for (k = 0; k < nextSegment.length; k++) {
		          polyline.getPath().push(nextSegment[k]);
		          bounds.extend(nextSegment[k]);
		        }
		      }
		    }


			var totalPoints = polyline.getPath().getArray().length;
	    	var previousPoint = {"latitude":null, "longitude": null};
	    	var previousPointString = polyline.getPath().getArray()[0].toString();
	    	previousPoint.latitude = previousPointString.substring( 1, previousPointString.indexOf(','));
	    	previousPoint.longitude = previousPointString.substring( previousPointString.indexOf(',')+1,previousPointString.length-1);
	    	point.latitude = previousPoint.latitude;
	    	point.longitude = previousPoint.longitude;
	    	pointsArray.push(previousPoint);

	    	for (l = 1; l < totalPoints; l++) {
	    		var currentPointString = polyline.getPath().getArray()[l].toString();
	    		var currentPoint= {"latitude":null, "longitude":null};
	    		currentPoint.latitude = currentPointString.substring( 1, currentPointString.indexOf(','));
	    		currentPoint.longitude = currentPointString.substring( currentPointString.indexOf(',')+1, currentPointString.length-1);
	    		var currentDistance = calculateDistance(previousPoint.latitude, previousPoint.longitude, currentPoint.latitude, currentPoint.longitude);
	    		if(currentDistance>4*multiplier && currentDistance<(4*multiplier+1)){
	    			console.log("GUARDAR ESTE PONTO");
	    			console.log("lat: "+currentPoint.latitude+" lng: "+currentPoint.longitude);
	    			pointsArray.push(currentPoint);
	    			multiplier++;
	    		}

	    	}

	    	pointsArray.push(currentPoint);
	    	console.table(pointsArray);

				//console.log("COORDS ORIGIN"+ coordinates.origin.latitude +" "+ coordinates.origin.longitude);
	    	$.ajax({
					async: false,
			    url: '/receiveCoords',//'http://geocomb.app/receiveCoords',
			    type: 'POST',
			    data: {"points": pointsArray, "vehicleId": vehicleId, "distance": distance, "latitudeOrigin": coordinates.origin.latitude, "longitudeOrigin": coordinates.origin.longitude},//{ "_token" : $('meta[name=_token]').attr('content'), name: "John", location: "Boston" },//JSON.stringify(pointsArray),//{_token: CSRF_TOKEN},
			    //contentType: "application/json; charset=utf-8",

			    success: function (response) {
			        console.log("data sent "+ response);
			        $("body").html(response);
			    },
			    error: function(error){
			    	console.log("could not send data, error: ");
			    	console.table(error);
			    }
			});


			var link= "/receivedCoords";
			var stationData;
			//console.log(link);
			$.ajax({
						async: false,
						url: link,
						crossDomain: true,
						type: "GET",
						dataType: "json",
						success: function (data) {
							stationData = data['station'];
							console.table(stationData);
						},
						error: function (error) {
							console.log("Error getting the station data");
							console.table(error);
						}
			});
			console.table(stationData);

	      	//polyline.setMap(mapUP);

					//directionsDisplay.setMap(mapUP);
					waypts.push({
						location: new google.maps.LatLng(stationData["latitude"], stationData["longitude"]),
						stopover: true
					});
					calculateAndDisplayRoute(directionsService, directionsDisplay);
					waypts.pop();


	    } else {
	      window.alert('Directions request failed due to ' + status);
	    }
	  });

	});


	function calculateDistance(latitudeOrigin, longitudeOrigin, latitudeDestination, longitudeDestination)
    {
        /*var earthRadius = 6371;//km

        var latitudeDifference = latitudeOrigin-latitudeDestination;
        var longitudeDifference = longitudeOrigin-longitudeDestination;

        var a = Math.pow(Math.sin(latitudeDifference/2),2) + Math.cos(latitudeOrigin) * Math.cos(latitudeDestination) * Math.pow(Math.sin(longitudeDifference/2), 2);
        var c = 2 * a * Math.pow(Math.tan(Math.sqrt(a)*Math.sqrt(1-a)) ,2);
        return earthRadius * c;*/

        var lat1 = latitudeOrigin* Math.PI / 180;
		var lat2 = latitudeDestination* Math.PI / 180;
		var lon1 = longitudeOrigin* Math.PI / 180;
		var lon2 = longitudeDestination* Math.PI / 180;

		var dist = (6371 * Math.acos( Math.cos( lat1 ) * Math.cos( lat2 ) * Math.cos( lon2 - lon1 ) + Math.sin( lat1 ) * Math.sin(lat2) ) );
		//dist = number_format($dist, 2, '.', '');
		return dist;
    }

	function getCoordinatePoints(result) {
            var currentRouteArray = result.routes[0];  //Returns a complex object containing the results of the current route
            var currentRoute = currentRouteArray.overview_path; //Returns a simplified version of all the coordinates on the path


            obj_newPolyline = new google.maps.Polyline({ map: map }); //a polyline just to verify my code is fetching the coordinates
            var path = obj_newPolyline.getPath();
            for (var x = 0; x < currentRoute.length; x++) {
                var pos = new google.maps.LatLng(currentRoute[x].kb, currentRoute[x].lb)
                latArray[x] = currentRoute[x].kb; //Returns the latitude
                lngArray[x] = currentRoute[x].lb; //Returns the longitude
                path.push(pos);
            }
      }


	function calculateAndDisplayRoute(directionsService, directionsDisplay) {

		directionsService.route({
		  origin: $("#upOrigin").val(),
		  destination: $("#upDestination").val(),
		  waypoints: waypts,
			avoidTolls: $('#upPaidRoads').is(':checked'),
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
			async: false,
			dataType: "json",
			url: "https://maps.googleapis.com/maps/api/geocode/json?address="+origin+"&key="+key,
			success: function(data){
				coordinates.origin.latitude = data.results[0].geometry.location.lat;
				coordinates.origin.longitude = data.results[0].geometry.location.lng;
			}
		});
		$.ajax({
			async: false,
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
		var coordinates = {latitude:null, longitude:null};
		coordinates = getCoordinates();
		var stationsData =null;


		var pointsArray = [];
		var i, j, k, l;
		var multiplier = 1;
		var point = {"latitude":null, "longitude":null};



		var directionsService = new google.maps.DirectionsService();
		var directionsDisplay = new google.maps.DirectionsRenderer({
			map: mapUP,
			preserveViewport: true
		});
		directionsService.route({
			origin: new google.maps.LatLng(coordinates.origin.latitude, coordinates.origin.longitude),
			destination: new google.maps.LatLng(coordinates.destination.latitude, coordinates.destination.longitude),
			/*waypoints: [{
				stopover: true,
				//location: new google.maps.LatLng(51.263439, 1.03489)
			}],*/
			avoidTolls: !$('#upPaidRoads').is(':checked'),
			travelMode: google.maps.TravelMode.DRIVING
		}, function(response, status) {
			if (status === google.maps.DirectionsStatus.OK) {
					// directionsDisplay.setDirections(response);
					var polyline = new google.maps.Polyline({
						path: [],
						strokeColor: '#0000FF',
						strokeWeight: 3,
					});
					var bounds = new google.maps.LatLngBounds();


					var legs = response.routes[0].legs;
					for (i = 0; i < legs.length; i++) {
						var steps = legs[i].steps;
						for (j = 0; j < steps.length; j++) {
							var nextSegment = steps[j].path;
							for (k = 0; k < nextSegment.length; k++) {
							polyline.getPath().push(nextSegment[k]);
							bounds.extend(nextSegment[k]);
						}
					}
				}


			var totalPoints = polyline.getPath().getArray().length;
				var previousPoint = {"latitude":null, "longitude": null};
				var previousPointString = polyline.getPath().getArray()[0].toString();
				previousPoint.latitude = previousPointString.substring( 1, previousPointString.indexOf(','));
				previousPoint.longitude = previousPointString.substring( previousPointString.indexOf(',')+1,previousPointString.length-1);
				point.latitude = previousPoint.latitude;
				point.longitude = previousPoint.longitude;
				pointsArray.push(previousPoint);

				for (l = 1; l < totalPoints; l++) {
					var currentPointString = polyline.getPath().getArray()[l].toString();
					var currentPoint= {"latitude":null, "longitude":null};
					currentPoint.latitude = currentPointString.substring( 1, currentPointString.indexOf(','));
					currentPoint.longitude = currentPointString.substring( currentPointString.indexOf(',')+1, currentPointString.length-1);
					var currentDistance = calculateDistance(previousPoint.latitude, previousPoint.longitude, currentPoint.latitude, currentPoint.longitude);
					if(currentDistance>4*multiplier && currentDistance<(4*multiplier+1)){
						console.log("GUARDAR ESTE PONTO");
						console.log("lat: "+currentPoint.latitude+" lng: "+currentPoint.longitude);
						pointsArray.push(currentPoint);
						multiplier++;
					}

				}

				pointsArray.push(currentPoint);



//console.table(pointsArray);
		$.ajax({
				async: false,
				url: "/api/receiveCoordinates",
				type: 'POST',
				dataType: "json",
				data: {"pathPoints": pointsArray,
					"latitudeOrigin": coordinates.origin.latitude,
					"longitudeOrigin": coordinates.origin.longitude,
					"latitudeDestination": coordinates.destination.latitude,
					"longitudeDestination": coordinates.destination.longitude
					},//{ "_token" : $('meta[name=_token]').attr('content'), name: "John", location: "Boston" },//JSON.stringify(pointsArray),//{_token: CSRF_TOKEN},

				success: function (response) {
						console.table("data sent "+ response["stations"]);
				},
				error: function(error){
					console.log("could not send data, error: ");
					console.table(error);
				}
		});
}});


	$.ajax({
					async: false,
					url: 'api/stationsup',
					type: "GET",
					dataType: "json",
					success: function (data) {
						stationsData = data["stations"];
						console.log("stationsData");
						console.table(stationsData);
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
	window.initMapDetails = initMapDetails;
	window.updateVehiclePage = updateVehiclePage;
});
