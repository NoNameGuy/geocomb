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

	function initMap(location) {
		var coordinates = {"latitude": null, "longitude": null};
		if(location!=null){
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

		var stations = getStations();
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

	function getStations(){
		var district=$("#inputdistrict").val();
		var fuelType=$("#landingFuelType>input[name='fuelType']").val();
		var brand=$("#brand").val();
		console.log("antes do ajax");

		$.ajax({
            url: "api/stations/"+district+"/"+brand+"/"+fuelType,
            type: "GET",
            dataType: "json",
            //delay: 50,
            //data: data,

            success: function(data) {
        		console.log("success: "+data["stations"]);

       // return results;
    		},
           /* success: function (data) {
            	console.table(data["stations"]);
                $.map(data["stations"] , function (key, value) {
                	//console.log(data["districts"][value]);
                	console.log(" key "+key+" value: "+value);
	                //return data["stations"][value];
	        //}
            })},*/
            error: function (textStatus, errorThrown) {
                console.log("Error getting the station data")
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


	$("#inputdistrict").keyup(function(){
		//alert($("#inputdistrict").val());
		delay( function(){
			var brand = $("#inputdistrict").val();
			var geocoder = new google.maps.Geocoder();
			geocodeAddress(geocoder, map);
		},1000);
	});

	$("#landingBack").click(function(){
		$("#inputdistrict").val('');
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
        var address = $('#inputdistrict').val();
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

	window.initMap = initMap;
	window.updateVehiclePage = updateVehiclePage;
});
