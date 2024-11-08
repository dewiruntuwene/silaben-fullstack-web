$(document).ready(function(){
	// global variable
	var txt_long = "";
	var txt_lat = "";
	var txt_location = "";
	
	// Initialize map centered on Indonesia with attribution control disabled
	var map = L.map('map', {
		center: [1.515620618141213, 124.99900817871095], // Pusat Manado
		zoom: 7,
		attributionControl: false, // Disable attribution control
		zoomControl: false // Disable default zoom control
	});

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

	// Add zoom control manually with custom position
	L.control.zoom({
		position: 'topright' // Set position to bottom right
	}).addTo(map);
	
	var marker = L.marker([1.515620618141213, 124.99900817871095], { // Manado, Minahasa Utara sebagai default
	draggable: true
	}).addTo(map);

	var circle = L.circle([1.515620618141213, 124.99900817871095], {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.5,
		radius: 300 // Radius dalam meter
	}).addTo(map);

	function updateMarker(lat, lng, updateView = true) {
		marker.setLatLng([lat, lng]);
		if (updateView) {
		  map.setView([lat, lng], 12); // Zoom ke lokasi baru
		}

		// Get location name using Nominatim reverse geocoding
		$.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng, function (data) {
		  var displayName = data.display_name;
		  marker.bindPopup(displayName).openPopup();
		  
		  document.getElementById('info').innerHTML = 
				'<table>' +
					'<tr><td><strong>Location</strong></td><td><strong> : </strong>' + displayName + '</td></tr>' +
					'<tr><td><strong>Longitude</strong></td><td><strong> : </strong>' + lng + '</td></tr>' +
					'<tr><td><strong>Latitude</strong></td><td><strong> : </strong>' + lat + '</td></tr>' +
				'</table>';
		  circle.setLatLng([lat, lng]); // Perbarui posisi lingkaran

			// assign value to global variable
			txt_long = lng;
			txt_lat = lat;
			txt_location = displayName;
			
	
		});
	}

	// Handle marker drag end event
	marker.on('dragend', function (e) {
		var latlng = marker.getLatLng();
		updateMarker(latlng.lat, latlng.lng, false);
	});

	// Handle map click event
	map.on('click', function (e) {
		var latlng = e.latlng;
		updateMarker(latlng.lat, latlng.lng);
	});

	// Get current location
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function (position) {
		  var lat = position.coords.latitude;
		  var lng = position.coords.longitude;
		  
		  if (!lat && !lng) {
			updateMarker(lat, lng);
		  }else{
			updateMarker(1.4898503184672367, 124.84245420933763); // Pusat Manado
		  }
		  
		  
		  
		}, function () {
		  // Handle location error
		  alert('Unable to retrieve your location. Showing default location.');
		});
	} else {
		// Browser doesn't support Geolocation
		alert('Geolocation is not supported by your browser. Showing default location.');
	}

	// Handle search
	document.getElementById('search-button').addEventListener('click', function () {
		var query = document.getElementById('search-input').value;
		if (query) {
		  $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + query + ', North Sulawesi', function (data) {
			if (data.length > 0) {
			  var lat = parseFloat(data[0].lat);
			  var lng = parseFloat(data[0].lon);
			  updateMarker(lat, lng);
			} else {
			  alert('Location not found in North Sulawesi.');
			}
		  });
		}
	});

	// Handle Enter key in search input
	document.getElementById('search-input').addEventListener('keypress', function (e) {
		if (e.key === 'Enter') {
		  document.getElementById('search-button').click();
		}
	});

	// Ensure map is resized correctly when modal is shown
	$('#modalopenstreetmap').on('shown.bs.modal', function () {
		map.invalidateSize();
	});
	
    // Ketika tombol diklik
    $('#btn-select-location').click(function(){
        // Set location
        $('#input-long-location').val(txt_long);
        $('#input-lat-location').val(txt_lat);
        $('#input-lokasi-bencana').val(txt_location);
		
		// Tutup modal 
        $('#modalopenstreetmap').modal('hide');
    });
	
	// Ketika input longitude diklik, lakukan aksi yang sama pada tombol
	$('#input-long-location').click(function() {
		$('#open-map-button').click();
	});

	// Ketika input latitude diklik, lakukan aksi yang sama pada tombol
	$('#input-lat-location').click(function() {
		$('#open-map-button').click();
	});
});


$(document).ready(function() {
	$('#reportForm').on('submit', function(e) {
		var isValid = true;

		//// Periksa apakah judul laporan diisi
		//if ($('input[name="report-title"]').val().trim() === '') {
		//	isValid = false;
		//	alert('Judul Laporan Bencana wajib diisi.');
		//}
		//
		//// Periksa apakah deskripsi laporan diisi
		//if ($('input[name="report-description"]').val().trim() === '') {
		//	isValid = false;
		//	alert('Deskripsi Singkat Spesifik Lokasi dan Kejadian Bencana wajib diisi.');
		//}

		//// Periksa apakah longitude diisi
		//if ($('#input-long-location').val().trim() === '') {
		//	isValid = false;
		//	alert('Longitude wajib diisi.');
		//}

		// Periksa apakah latitude diisi
		if ($('#input-lokasi-bencana').val().trim() === '') {
			isValid = false;
			alert('Lokasi kejadian bencana wajib diisi.');
		}

		// Jika tidak valid, cegah submit form
		if (!isValid) {
			e.preventDefault();
		}
	});
});