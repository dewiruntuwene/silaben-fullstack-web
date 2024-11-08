$(document).ready(function(){
    // header menu menjadi tidak transparent.
    $("#header").removeClass("header-transparent");

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
        position: 'bottomright' // Set position to bottom right
    }).addTo(map);

    // Define custom icon for "I'm Here" marker
    var myIcon = L.icon({
        iconUrl: 'https://silaben.site/app/public/a/assets/img/here.png', // URL to your red icon 
        iconSize: [40, 60], // size of the icon
        iconAnchor: [22, 60], // point of the icon which will correspond to marker's location
        popupAnchor: [-3, -60] // point from which the popup should open relative to the iconAnchor
    });



    // Function to update marker position
    function updateMarker(lat, lng, displayName, updateView = true) {
        if (updateView) {
            map.setView([lat, lng], 12); // Zoom ke lokasi baru
        }

        // Create or update "I'm Here" marker
        if (window.myMarker) {
            myMarker.setLatLng([lat, lng]).bindPopup(displayName).openPopup();
        } else {
            window.myMarker = L.marker([lat, lng], { icon: myIcon }).addTo(map).bindPopup(displayName).openPopup();
        }

		/*
        document.getElementById('infopanelmap').innerHTML = 
            '<table>' +
                '<tr><td><strong>Location</strong></td><td><strong> : </strong>' + displayName + '</td></tr>' +
                '<tr><td><strong>Longitude</strong></td><td><strong> : </strong>' + lng + '</td></tr>' +
                '<tr><td><strong>Latitude</strong></td><td><strong> : </strong>' + lat + '</td></tr>' +
            '</table>';
		*/	
		
    }

    // Fetch data from the provided URL and add markers to the map
    $.getJSON('https://silaben.site/app/public/home/datalaporanweb/', function(data) {
        data.forEach(function(item) {
            var lat = parseFloat(item.latitude);
            var lng = parseFloat(item.longitude); 
            var popupContent = 
            '<div style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; padding: 0px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.0);">' +
                '<div style="display: flex; align-items: center; margin-bottom: 10px;">' +
                    '<span class="material-icons" style="color: #d32f2f; font-size: 24px; margin-right: 8px;">event</span>' +
                    '<span style="font-weight: bold; color: #d32f2f;">' + item.report_date + '</span>' +
                    '<span class="material-icons" style="color: #d32f2f; font-size: 24px; margin-left: auto; margin-right: 8px;">access_time</span> ' +
                    '<span style="font-weight: bold; color: #d32f2f;"> ' + item.report_time + '</span>' +
                '</div>' +
                '<h4 style="margin-top: 0; color: #d32f2f;">' + item.report_title + '</h4>' +
                '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; border-radius: 2px; overflow: hidden;">' +
                    '<tr style=" border: 1px solid #ddd;">' +
                        '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Deskripsi:</td>' +
                        '<td style="padding: 2px; border: 1px solid #ddd;">' + item.report_description + '</td>' +
                    '</tr>' +
                    '<tr style=" border: 1px solid #ddd;">' +
                        '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Lokasi:</td>' +
                        '<td style="padding: 2px; border: 1px solid #ddd;">' + item.lokasi_bencana + '</td>' +
                    '</tr>' +
                    '<tr style=" border: 1px solid #ddd;">' +
                        '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Level Kerusakan:</td>' + 
                        '<td style="padding: 2px; border: 1px solid #ddd;">' + item.level_kerusakan_infrastruktur + '</td>' +
                    '</tr>' +
                    '<tr style=" border: 1px solid #ddd;">' +
                        '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Status:</td>' +
                        '<td style="padding: 2px; border: 1px solid #ddd;">' + item.status + '</td>' +
                    '</tr>' +
                '</table>' +
            '</div>';



            // Add marker for each data point
            var marker = L.marker([lat, lng]).addTo(map).bindPopup(popupContent);

            // Show popup on mouseover
            marker.on('mouseover', function() {
                marker.openPopup();
            });
        });
    });

    // Get current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // Get location name using Nominatim reverse geocoding
            $.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng, function (data) {
                var displayName = data.display_name;
				var popupContent = 
					'<b>YOU ARE HERE !!!</b><br><br>' +
					'<b>Lokasi:</b><br> ' + displayName;
                updateMarker(lat, lng, popupContent);
            });
        }, function () {
            // Handle location error
            alert('Unable to retrieve your location. Showing default location.');
        });
    } else {
        // Browser doesn't support Geolocation
        alert('Geolocation is not supported by your browser. Showing default location.');
    }
});
