<?php
  // Mulai sesi PHP
  session_start();

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
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

<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- <script src="<?php echo APP_PATH; ?>/a/assets/js/action-map-geofencing.js"></script> -->


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

                
        //Check user's location and notify when inside the geofence
        // function trackUserLocation() {
        //     // Memantau posisi pengguna
        //     navigator.geolocation.watchPosition(function(position) {
        //         var userLat = position.coords.latitude;
        //         var userLng = position.coords.longitude;
        //         var userId = document.getElementById('user_id').value;

        //         $.cookie("latitude", userLat);
        //         $.cookie("longitude", userLng);
                
        //         //console.log("User ID:", userId);
        //         //console.log("User Latitude:", userLat); // Log latitude
        //         //console.log("User Longitude:", userLng); // Log longitude

        //         // Panggil AJAX untuk membandingkan
        //         $.ajax({
        //             url: 'https://silaben.site/app/public/UserController/checkAndSendNotification',
        //             type: 'POST',
        //             contentType: 'application/json', // Menentukan tipe konten
        //             data: JSON.stringify({ // Mengonversi data ke string JSON
        //                 user_id: userId,
        //                 latitude: userLat,
        //                 longitude: userLng
        //             }),
        //             success: function(response) {
        //                 console.log("Notification process completed:", response);
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error("Failed to process notification:", error);
        //             }
        //         });
        //     }, function(error) {
        //         console.error("Error getting location:", error);
        //     });
        // }

        // function trackUserLocation() {
        //     // Memantau posisi pengguna
        //     navigator.geolocation.watchPosition(function(position) {
        //         var userLat = position.coords.latitude;
        //         var userLng = position.coords.longitude;
        //         var userId = document.getElementById('user_id').value;

        //         $.cookie("latitude", userLat);
        //         $.cookie("longitude", userLng);
                
        //         //console.log("User ID:", userId);
        //         //console.log("User Latitude:", userLat); // Log latitude
        //         //console.log("User Longitude:", userLng); // Log longitude

        //         // Panggil AJAX untuk memperbarui lokasi di database dan memeriksa apakah berada di sekitar bencana
                
        //     }, function(error) {
        //         console.error("Error getting location:", error);
        //     });
        // }

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

        // function trackUserLocation() {
        //     navigator.geolocation.watchPosition(function(position) {
        //         var userLat = position.coords.latitude;
        //         var userLng = position.coords.longitude;
        //         var radius = 13; // in kilometers

        //         $.cookie("latitude", userLat);
        //         $.cookie("longitude", userLng);

        //         // console.log(userLat, userLng)

        //         // Fetch disaster data from Redis at intervals
        //         setInterval(function() {
        //             $.ajax({
        //                 url: 'https://silaben.site/app/public/home/getDisasterDataFromRedis', // PHP endpoint to fetch data from Redis
        //                 type: 'GET',
        //                 success: function(response) {
        //                     const data = JSON.parse(response);
        //                     console.log(data)

        //                     if (data.status === 'active') {
        //                         const disasterLat = data.latitude;
        //                         const disasterLng = data.longitude;
        //                         const message = data.message;

        //                         // Calculate distance
        //                         const distance = calculateDistance(userLat, userLng, disasterLat, disasterLng);
        //                         const distanceInKilometers = Math.floor(distance);
        //                         console.log("distance:", distanceInKilometers)
        //                         // If user is within the radius, send WhatsApp message
        //                         if (distanceInKilometers <= radius) {
        //                             // alert(`Warning! ${message}`);
        //                             sendMessage(message);
        //                         }else {
        //                             console.log("You're not in the radius")
        //                         }
        //                     }
        //                 },
        //                 error: function(error) {
        //                     console.error("Error fetching disaster data:", error);
        //                 }
        //             });
        //         }, 60000); // Check every minute

        //     }, function(error) {
        //         console.error("Error getting location:", error);
        //     });
        // }

        // async function sendMessage(message) {
        //     const target = "<?php echo $_SESSION['whatsapp_number']; ?>";
        //     console.log(target)
        //     const url = 'https://api.fonnte.com/send';
        //     const token = 'TOKEN'; // Ganti 'TOKEN' dengan token Anda yang sebenarnya
        //     const payload = new URLSearchParams({
        //         target: target,
        //         message: message,
        //         countryCode: '62' // Optional
        //     });

        //     try {
        //         const response = await fetch(url, {
        //         method: 'POST',
        //         headers: {
        //             'Authorization': 'GRnm9ah7XakS8sJnXhKQ',
        //             'Content-Type': 'application/x-www-form-urlencoded'
        //         },
        //         body: payload
        //         });

        //         if (!response.ok) {
        //         throw new Error(`HTTP error! Status: ${response.status}`);
        //         }

        //         const result = await response.text(); // Ubah sesuai format yang diharapkan, misal `json()`
        //         console.log(result); // Menampilkan hasil response
        //     } catch (error) {
        //         console.error('Error:', error.message);
        //     }
        // }

        // Function to send WhatsApp notification
        // async function sendWhatsAppNotification(message) {
        //     const target = "<?php echo $_SESSION['whatsapp_number']; ?>";
        //     console.log(target)
        //     const url = 'https://api.fonnte.com/send';

        //     const data = new FormData();
        //     data.append('target', target);
        //     data.append('message', message);
        //     data.append('delay', '2'); // Delay in seconds
        //     data.append('countryCode', '62'); // Optional

        //     const headers = new Headers();
        //     headers.append('Authorization', 'GRnm9ah7XakS8sJnXhKQ'); // Replace with actual token

        //     try {
        //         const response = await fetch(url, {
        //             method: 'POST',
        //             body: data,
        //             headers: headers,
        //         });

        //         if (!response.ok) {
        //             throw new Error('Network response was not ok: ' + response.statusText);
        //         }

        //         const responseData = await response.json();

        //         // Output response for debugging (optional)
        //         console.log(`Message sent to ${target}:`, responseData);
        //     } catch (error) {
        //         console.error('Error sending message:', error);
        //     }
        // }

        // Helper to convert degrees to radians
        function degToRad(deg) {
            return deg * (Math.PI / 180);
        }

        // Function to trigger WhatsApp message
        function sendWhatsAppMessage(message) {
            // Implement your WhatsApp API call here
            console.log("Sending WhatsApp message:", message);
        }


        // // Panggil fungsi untuk mulai memantau lokasi pengguna
        // trackUserLocation();

        //Request notification permission on page load
        if ("Notification" in window) {
            Notification.requestPermission();
        }

    // Fetch data from the provided URL and add markers to the map
    // $.getJSON('https://silaben.site/app/public/home/datalaporanweb/', function(data) {
    //     // console.log(data);
    //     data.forEach(function(item) {
    //         var lat = parseFloat(item.latitude);
    //         var lng = parseFloat(item.longitude); 
    //         var popupContent = 
    //         '<div style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; padding: 0px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.0);">' +
    //             '<div style="display: flex; align-items: center; margin-bottom: 10px;">' +
    //                 '<span class="material-icons" style="color: #d32f2f; font-size: 24px; margin-right: 8px;">event</span>' +
    //                 '<span style="font-weight: bold; color: #d32f2f;">' + item.report_date + '</span>' +
    //                 '<span class="material-icons" style="color: #d32f2f; font-size: 24px; margin-left: auto; margin-right: 8px;">access_time</span> ' +
    //                 '<span style="font-weight: bold; color: #d32f2f;"> ' + item.report_time + '</span>' +
    //             '</div>' +
    //             '<h4 style="margin-top: 0; color: #d32f2f;">' + item.report_title + '</h4>' +
    //             '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; border-radius: 2px; overflow: hidden;">' +
    //                 '<tr style=" border: 1px solid #ddd;">' +
    //                     '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Deskripsi:</td>' +
    //                     '<td style="padding: 2px; border: 1px solid #ddd;">' + item.report_description + '</td>' +
    //                 '</tr>' +
    //                 '<tr style=" border: 1px solid #ddd;">' +
    //                     '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Lokasi:</td>' +
    //                     '<td style="padding: 2px; border: 1px solid #ddd;">' + item.lokasi_bencana + '</td>' +
    //                 '</tr>' +
    //                 '<tr style=" border: 1px solid #ddd;">' +
    //                     '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Level Kerusakan:</td>' + 
    //                     '<td style="padding: 2px; border: 1px solid #ddd;">' + item.level_kerusakan_infrastruktur + '</td>' +
    //                 '</tr>' +
    //                 '<tr style=" border: 1px solid #ddd;">' +
    //                     '<td style="font-weight: bold; padding: 2px; border: 1px solid #ddd;">Status:</td>' +
    //                     '<td style="padding: 2px; border: 1px solid #ddd;">' + item.status + '</td>' +
    //                 '</tr>' +
    //             '</table>' +
    //         '</div>';



    //         // Add marker for each data point
    //         var marker = L.marker([lat, lng]).addTo(map).bindPopup(popupContent);

    //         // Show popup on mouseover
    //         marker.on('mouseover', function() {
    //             marker.openPopup();
    //         });
    //     });
    // });



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
                            <p><strong>${time}:</strong> ${weather[0].weather}, ${weather[0].t}°C, ${weather[0].tcc}% awan, ${weather[0].tp} mm curah hujan</p>`;
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

<!-- <style>
  /* Media queries untuk responsivitas */
  @media (max-width: 768px) {
    .gempa-container {
      left: 10px; /* Mengurangi jarak dari kiri */
      top: 60px; /* Mengurangi jarak dari atas */
      width: 95%; /* Menyesuaikan lebar pada layar kecil */
    }

    header {
      padding: 5px; /* Mengurangi padding header */
    }

    header h1 {
      font-size: 18px; /* Menyesuaikan ukuran font pada header */
    }

    header nav {
      font-size: 14px; /* Menyesuaikan ukuran font pada nav */
    }

    .gempa-info h3 {
      font-size: 14px; /* Ukuran font lebih kecil di mobile */
    }

    .gempa-detail p, .gempa-summary p {
      font-size: 12px; /* Ukuran font lebih kecil untuk detail gempa */
    }
  }

  @media (max-width: 576px) {
    .gempa-container {
      top: 50px; /* Mengurangi jarak dari atas */
      left: 5px; /* Mengurangi jarak dari kiri */
      width: 50%; /* Menyesuaikan lebar pada layar sangat kecil */
    }

    header {
      padding: 3px; /* Mengurangi padding header lebih jauh */
    }

    header h1 {
      font-size: 16px; /* Ukuran font lebih kecil pada mobile */
    }

    header nav {
      font-size: 12px; /* Menyesuaikan ukuran font pada nav untuk mobile */
    }

    .gempa-info h3 {
      font-size: 12px; /* Ukuran font lebih kecil di mobile */
    }

    .gempa-detail p, .gempa-summary p {
      font-size: 10px; /* Ukuran font lebih kecil untuk detail gempa */
    }
  }
</style> -->
