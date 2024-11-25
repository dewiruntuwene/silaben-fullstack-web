<?php

// Ambil user_id dari session jika ada
// $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
  // Mengambil data gempa dari BMKG
  $data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml") or die("Gagal mengakses!");

  // Menyimpan data yang dibutuhkan
  $tanggal = $data->gempa->Tanggal;
  $jam = $data->gempa->Jam;
  $magnitudo = $data->gempa->Magnitude;
  $kedalaman = $data->gempa->Kedalaman;
  $lintang = $data->gempa->Lintang;
  $bujur = $data->gempa->Bujur;
  $lokasi = $data->gempa->Wilayah;
  $dirasakan = $data->gempa->Dirasakan;
  $shakemap = $data->gempa->Shakemap;
?>

<script src=
"https://code.jquery.com/jquery-3.5.1.min.js"
integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
         crossorigin= "anonymous"></script>
      <script src=
"https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>

<div class="p-0" style=" height: 100vh;">
  <div id="map" style="height: 100%; width: 100%;"></div>

  <!-- Container untuk informasi gempa -->
  <div class="gempa-container" style="position: absolute; width: 300px; left: 20px; display: flex; flex-direction: column; top: 80px; background-color: rgba(255, 255, 255, 0.5); padding: 10px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); z-index: 1000;">
		<h1 style="font-size: 20px;">
            <strong>
                Data Gempa Terkini
                <!-- <?php
                    // echo $_COOKIE['latitude'].", ".$_COOKIE['longitude'];

                ?> -->
            </strong></h1>
		<div class="gempa-image">
		    <img src="https://data.bmkg.go.id/DataMKG/TEWS/<?php echo $shakemap; ?>" alt="Peta Shakemap Gempa" style="width: 50%; height: auto;">
		</div>
		<div class="gempa-info">
		<h3 style="font-size: 12px;"><?php echo $tanggal; ?>, <?php echo $jam; ?> WIB</h3>
		<div class="gempa-detail" style="font-size: 12px;">
			<p><i class="bi bi-heart-pulse"></i> <strong>Magnitudo:</strong> <?php echo $magnitudo; ?></p>
			<p><i class="bi bi-arrow-down"></i> <strong>Kedalaman:</strong> <?php echo $kedalaman; ?> km</p>
			<p><i class="bi bi-geo-alt"></i> <strong>Lokasi:</strong> <?php echo $lintang; ?> - <?php echo $bujur; ?></p>
		</div>
		<div class="gempa-summary" style="font-size: 12px;">
			<p><i class="bi bi-record-circle"></i> Pusat gempa berada di <?php echo $lokasi; ?></p>
			<p><i class="bi bi-wifi"></i> Dirasakan: <?php echo $dirasakan; ?></p>
		</div>
		</div>
	</div>

</div>

<!-- <input type="hidden" id="user_id" value="<?php echo $user_id; ?>"> -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- <script src="<?php echo APP_PATH; ?>/a/assets/js/action-map-geofencing.js"></script> -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
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

         // Function to show notification
    function showNotification(title, message) {
        if (Notification.permission === "granted") {
            new Notification(title, { body: message });
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification(title, { body: message });
                }
            });
        }
    }

    // Check if user is within geofence (simple circular geofence)
    function isInsideGeofence(lat1, lng1, lat2, lng2, radius) {
        var distance = map.distance([lat1, lng1], [lat2, lng2]); // Calculate distance between points in meters
        return distance <= radius;
    }

    // Fetch data from the provided URL and add markers to the map
    $.getJSON('https://silaben.site/app/public/home/datalaporanweb/', function(data) {
        //console.log(data);
        data.forEach(function(item) {
            var lat = parseFloat(item.latitude);
            var lng = parseFloat(item.longitude);
            var popupContent = '<div style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; padding: 0px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.0);">' +
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
            var marker = L.marker([lat, lng]).addTo(map).bindPopup(popupContent);
            
            // Show popup on mouseover
            marker.on('mouseover', function() {
                marker.openPopup();
            });

        });
    });

        // Set up geofence around each disaster location (radius in meters)
        const geofenceRadius = 40000; // 5 km radius around disaster location

                
       
        // Function to calculate distance between two geographic coordinates
        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Radius of the Earth in kilometers
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLng = (lng2 - lng1) * (Math.PI / 180);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Distance in kilometers
        }



    $.getJSON('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm2=71.06', function(data) {
        if (Array.isArray(data.data)) {
            data.data.forEach(function(item) {
                let lokasi = item.lokasi;
                let cuacaList = item.cuaca;
                console.log(data)
                // Ambil waktu sekarang
                let date = new Date();
                // console.log(date)
                // let datetime = date.getFullYear()+''+
                //               (date.getMonth()+1).toString().padStart(2,'0')+''+
                //               (date.getDate()).toString().padStart(2, '0')+''+
                //               date.getHours()+'00';
                // console.log(datetime)

                // let weather = area.querySelectorAll('parameter[id::"weather');
                // console.log(weather);

                // Filter cuaca yang paling dekat dengan waktu saat ini
                let currentWeather = cuacaList.reduce(function(closest, weather) {
                    let weatherTime = new Date(weather.local_datetime
                  );
                    return Math.abs(weatherTime - date) < Math.abs(new Date(closest.local_datetime
                    ) - date) ? weather : closest;
                });
                //console.log(currentWeather)

                // Lanjutkan jika cuaca ditemukan
                if (currentWeather) {
                    // Siapkan forecast sebagai container
                    let forecast = `<h4>Prakiraan Cuaca Hari Ini</h4>`;
                    
                    
                    //Iterasi melalui daftar cuaca dan tampilkan data cuaca yang paling relevan
                    cuacaList.forEach(function(weather) {
                        let weatherTime = new Date(weather[0].local_datetime);
                        let time = weatherTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                        //console.log(weather)

                        forecast += `
                            <p>${weatherTime}</p>
                            <p><strong>${time}:</strong> ${weather[0].weather_desc}, ${weather[0].t}°C, ${weather[0].tcc}% awan, ${weather[0].tp} mm curah hujan</p>`;
                    });

                    

                     // Gambar cuaca dari API
                    let weatherIcon = currentWeather[0].image || 'default-icon.svg'; // Ganti dengan ikon default jika URL kosong
                    // console.log(currentWeather)

                    // Tampilkan cuaca dalam popup
                    var popupContent = `

                        <div style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; padding: 10px; flex">
                            <p>Kecamatan ${lokasi.kecamatan}</p>
                            ${forecast}
                        </div>`;
                        // Tambahkan marker dengan ikon cuaca
                    if (lokasi && lokasi.lat && lokasi.lon) {
                        let marker = L.marker([lokasi.lat, lokasi.lon], {
                            icon: L.icon({
                                iconUrl: weatherIcon,
                                iconSize: [50, 50],  // Ukuran ikon
                                iconAnchor: [25, 50], // Titik jangkar (untuk memusatkan gambar)
                                popupAnchor: [0, -50] // Jarak dari ikon ke popup
                            })
                        }).addTo(map);

                        marker.bindPopup(popupContent);
                    } else {
                        console.error('Latitude atau Longitude tidak valid:', lokasi);
                    }
                } else {
                    console.error('Cuaca untuk waktu saat ini tidak ditemukan');
                }
            });
        } else {
            console.error('Data tidak dalam format array:', data.data);
        }
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

    </script>

