<section id="hero" class="d-flex flex-column justify-content-end align-items-center position-relative">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="carousel-container">
		<?php
			if (isset($data['user_name'])) {
				$name_user = $data['user_name'];
				echo "<h2 class='animate__animated animate__fadeInDown'>Hi, welcome $name_user !!!</h2>";
			} else {
				echo "<h2 class='animate__animated animate__fadeInDown'>Sigap Lapor <span>Bencana</span></h2>";
			}
		?>
		<p class="animate__animated animate__fadeInUp">Layanan pelaporan, notifikasi peringatan dan pembatasan area virtual rawan bencana yang terjadi disekitar masyarakat dengan menggunakan teknologi kecerdasan buatan (AI) untuk proses pemeriksaan kesesuaian dan verifikasi bukti laporan secara otomatis.</p>
    
      </div>
    </div>
  
  <div class="carousel-container">
    <!-- <h2><?php echo $data['user_id']; ?></h2>
    <h2><?php echo $data['user_name']; ?></h2>
    <h2><?php echo $data['email']; ?></h2>
    <h2><?php echo $data['gender']; ?></h2>
    <h2><?php echo $data['role']; ?></h2> -->
    
    <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>">
    <input type="hidden" id="user_name" value="<?php echo $data['user_name']; ?>">
    <input type="hidden" id="email" value="<?php echo $data['email']; ?>">
    <input type="hidden" id="gender" value="<?php echo $data['gender']; ?>">
    <input type="hidden" id="whatsapp_number" value="<?php echo $data['whatsapp_number']; ?>">
    <input type="hidden" id="role" value="<?php echo $data['role']; ?>">
    <div class="mt-3" style="position: relative; z-index: 100; top: 100px;">
      <button id="emergencyButton" class="btn btn-danger btn-lg" onclick="handleEmergency()">Emergency</button>
    </div>
  </div>
    
    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bx bx-chevron-left" aria-hidden="true"></span>
    </a>

    <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
      <span class="carousel-control-next-icon bx bx-chevron-right" aria-hidden="true"></span>
    </a>
  </div>

  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
    <defs>
      <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
    </defs>
    <g class="wave1">
      <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)"></use>
    </g>
    <g class="wave2">
      <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)"></use>
    </g>
    <g class="wave3">
      <use xlink:href="#wave-path" x="50" y="9" fill="#fff"></use>
    </g>
  </svg>
</section>

<script>
function handleEmergency() {
  if (confirm("Apakah Anda yakin ingin mengirim laporan darurat?")) {
    navigator.geolocation.getCurrentPosition(function(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        
        // Ambil informasi relawan dari elemen HTML
        const userId = document.getElementById('user_id').value;
        const user_name = document.getElementById('user_name').value;
        const gender = document.getElementById('gender').value;
        const whatsapp_number = document.getElementById('whatsapp_number').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;
        
        // Panggil Nominatim Reverse Geocoding API untuk mendapatkan nama lokasi
        $.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + latitude + '&lon=' + longitude, function(geocodeData) {
            if (geocodeData && geocodeData.address) {
                var locationName = geocodeData.display_name;
                
                // Kirim data darurat ke server
                $.ajax({
                    url: 'https://silaben.site/app/public/home/emergencyReport/',
                    type: 'POST',
                    // headers: {
                    //     'Content-Type': 'application/json'
                    // },
                    data: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                        lokasi: locationName, // Tambahkan nama lokasi
                        user_id: userId,       // Tambahkan relawanId
                        user_name: user_name,                 // Tambahkan nama relawan
                        gender: gender,             // Tambahkan gender relawan
                        whatsapp_number: whatsapp_number,         // Tambahkan nomor WhatsApp
                        email: email,
                        role: role                // Tambahkan email
                    }),
                    success: function(response) {
                      console.log("Raw response:", response);
                        try {
                            const res = typeof response === "string" ? JSON.parse(response) : response;
                            if (res.status === 'success') {
                                alert(res.message);
                            } else {
                                alert("Error: " + res.message);
                            }
                        } catch (e) {
                            console.error("Response parsing error:", e);
                            alert("Kesalahan format response dari server.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error sending emergency report:", xhr, status, error);
                        alert("Gagal mengirim laporan darurat.");
                    }
                });
            } else {
                alert("Gagal mendapatkan nama lokasi.");
            }
        }).fail(function() {
            alert("Gagal terhubung ke layanan geocode Nominatim.");
        });
    }, function(error) {
        console.error("Error getting location:", error);
        alert("Gagal mendeteksi lokasi Anda.");
    });
}

}
</script>

  <!-- ======= Form Input Section ======= -->
  <section id="report-form" class="report-form-section position-absolute" style="z-index: 50; top: 500px; left: 50%; transform: translateX(-50%);">
	<div class="container my-4">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="report-form">
					<div class="form-title text-center">
						<h5>Anda Lapor, Kami Tertolong !!!</h5>
					</div>
					<form id="reportForm" method="POST" action="<?php echo APP_PATH; ?>/home/submitlaporan" enctype="multipart/form-data">
						<div class="mb-3">
							<input type="text" class="form-control" name="report-title" placeholder="Judul Laporan Bencana" required>
						</div>
						<div class="mb-3">
							<input type="text" class="form-control" name="report-description" placeholder="Deskripsi Singkat Spesifik Lokasi dan Kejadian Bencana" required>
						</div>
						<div class="mb-3">
							<label for="category" class="form-label"><strong>Cari Lokasi Kejadian Bencana !!!</strong></label>
							<div class="input-group">
								<input type="text" name="input-long-location" id="input-long-location" class="form-control" placeholder="Longitude" style="background-color: lightgray;" readonly>
								<input type="text" name="input-lat-location" id="input-lat-location" class="form-control" placeholder="Latitude" style="background-color: lightgray;" readonly> 
								<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalopenstreetmap"><span class="material-icons">pin_drop</span></button>
							</div>
							<input type="text" name="input-lokasi-bencana" id="input-lokasi-bencana" class="form-control mt-2" placeholder="Lokasi kejadian bencana." style="background-color: lightgray;" readonly>
						</div>
							<!-- <div class="mb-3">
								<label for="related-agency" class="form-label"><strong>Lapor ke Instansi Terkait:</strong></label>
								<select class="form-select" name="lapor-instansi">
									<option value="BPBD" selected>BPBD</option>
									<option value="Dinas Lingkungan Hidup">Dinas Lingkungan Hidup</option>
								</select>
							</div> -->
							<div class="mb-3">
                  <label for="report-date" class="form-label"><strong>Tanggal Kejadian Bencana:</strong></label>
                  <input type="date" class="form-control" name="report-date" id="report-date" required>
              </div>
              <div class="mb-3">
                  <label for="report-time" class="form-label"><strong>Waktu Kejadian Bencana:</strong></label>
                  <input type="time" class="form-control" name="report-time" id="report-time" required>
              </div>
              <div class="mb-3">
                <label for="report-date" class="form-label"><strong>Jumlah Relawan yang Dibutuhkan:</strong></label>
                <input S
                  type="number" 
                  onclick="incrementValue()"
                  class="form-control" 
                  name="jumlah-relawan-dibutuhkan" 
                  id="jumlah-relawan-dibutuhkan" 
                  placeholder="Jumlah Relawan yang Dibutuhkan" 
                  value="0" 
                  min="0" 
                  required>
              </div>

							<div class="mb-3">
								<label for="report-file" class="form-label"><strong>Bukti Foto Gambar Bencana:</strong></label>
								<input type="file" class="form-control" name="report-file" accept=".jpg,.jpeg,.png,.gif" required>
							</div>
							<div class="mt-4 d-flex align-items-center justify-content-between p-3" style="background-color: #ADD8E6; border-radius: 10px;">
								<!--
								<div class="d-flex align-items-center">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="identity" id="anonymous" value="Anonymous" >
										<label class="form-check-label" for="anonymous">Anonim</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="identity" id="not-anonymous" value="Not Anonymous" checked>
										<label class="form-check-label" for="not-anonymous">Tidak Anonim</label>
									</div>
								</div>
								-->
								<div class="d-flex align-items-center">
									
								</div>
								<div>
									<button type="submit" class="btn btn-danger">LAPOR KEJADIAN BENCANA</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

<main id="main">
    <section id="features" class="features text-center ">
      <div class="container text-center col-lg-12">
		<div class="row justify-content-center">
		  <div class="col-md-2 process-step">
			<span class="material-icons icon-large">how_to_reg</span>
			<h5>[1] Pelapor Mendaftar</h5>
			<p>Relawan melaporkan kejadian bencana yang terjadi di sekitar dengan deskripsi yang jelas dan lengkap.</p>
		  </div>
		  <div class="col-md-2 process-step">
			<span class="material-icons icon-large">new_releases</span>
			<h5>[2] Proses Verifikasi</h5>
			<p>Laporan kejadian bencana akan diperiksa dan diverifikasi sebelum ditangani oleh instansi terkait.</p>
		  </div>
		  <div class="col-md-2 process-step">
			<span class="material-icons icon-large">account_tree</span>
			<h5>[3] Tindak Lanjut</h5>
			<p>Lembaga/dinas/instansi terkait akan menindaklanjuti dan memberikan tanggapan setiap laporan.</p>
		  </div>
		  <div class="col-md-2 process-step">
			<span class="material-icons icon-large">published_with_changes</span>
			<h5>[4] Kemajuan Laporan</h5>
			<p>Status perkembangan laporan akan diperbarui secara berkala untuk memberikan informasi terbaru.</p>
		  </div>
		  <div class="col-md-2 process-step">
			<span class="material-icons icon-large">fact_check</span>
			<h5>[5] Selesai</h5>
			<p>Proses penanganan bencana selesai dan data laporan bisa digunakan untuk referensi dan evaluasi di masa mendatang.</p>
		  </div>
		</div>

      </div>
    </section>
</main>
<div class="modal fade" id="modalopenstreetmap" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <div class="input-group">
          <input type="text" id="search-input" class="form-control" placeholder="Search for a location in North Sulawesi" />
          <button class="btn btn-primary" id="search-button">Search</button>
        </div>
      </div>
      <div class="modal-body p-0" style="display: flex; flex-direction: column; height: 70vh;">
        <div id="map" style="height: 100%; width: 100%;"></div>
        
        <div id="info">Click on the map to get the coordinates</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="btn-select-location" class="btn btn-primary">Choose Selected Location</button>
      </div>
    </div>
  </div>
</div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <script src="<?php echo APP_PATH; ?>/a/assets/js/action-map.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    function incrementValue() {
      const input = document.getElementById('jumlah-relawan-dibutuhkan');
      input.value = parseInt(input.value) + 1; // Tambah nilai sebesar 1
    } // Update setiap 1 detik
   // Get the current local date and time
   const currentDate = new Date().toLocaleDateString('en-CA'); // ISO format YYYY-MM-DD
    document.getElementById('report-date').value = currentDate;

    const currentTime = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }); // HH:MM format
    document.getElementById('report-time').value = currentTime;
</script>

  <script>
    
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

	    function trackUserLocation() {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                var radius = 13; // in kilometers

                // $.cookie("latitude", userLat);
                // $.cookie("longitude", userLng);

                // console.log(userLat, userLng)

                // Fetch disaster data from Redis at intervals
                setInterval(function() {
                    $.ajax({
                        url: 'https://silaben.site/app/public/home/getDisasterDataFromRedis', // PHP endpoint to fetch data from Redis
                        type: 'GET',
                        success: function(response) {
                            const data = JSON.parse(response);
                            console.log(data)

                            if (data.status === 'active') {
                                const disasterLat = data.latitude;
                                const disasterLng = data.longitude;
                                const message = data.message;

                                // Calculate distance
                                const distance = calculateDistance(userLat, userLng, disasterLat, disasterLng);
                                const distanceInKilometers = Math.floor(distance);
                                console.log("distance:", distanceInKilometers)
                                // If user is within the radius, send WhatsApp message
                                if (distanceInKilometers <= radius) {
                                    // alert(`Warning! ${message}`);
                                    sendMessage(message);
                                    showNotification(message);
                                }else {
                                    console.log("You're not in the radius")
                                }
                            }
                        },
                        error: function(error) {
                            console.error("Error fetching disaster data:", error);
                        }
                  });
                }, 60000); // Check every minute

            }, function(error) {
                console.error("Error getting location:", error);
            });
      }

        async function sendMessage(message) {
            const target = "<?php echo $data['whatsapp_number']; ?>";
            console.log(target)
            const url = 'https://api.fonnte.com/send';
            const token = 'TOKEN'; // Ganti 'TOKEN' dengan token Anda yang sebenarnya
            const payload = new URLSearchParams({
                target: target,
                message: message,
                countryCode: '62' // Optional
            });

            try {
                const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Authorization': 'GRnm9ah7XakS8sJnXhKQ',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: payload
                });

                if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const result = await response.text(); // Ubah sesuai format yang diharapkan, misal `json()`
                console.log(result); // Menampilkan hasil response
            } catch (error) {
                console.error('Error:', error.message);
            }
        }
        function handleEmergency() {
    const userId = document.getElementById("user_id").value;

    if (!userId) {
        // Redirect to the sign-in page if user is not logged in
        window.location.href = "/app/public/login/index/"; // Ubah URL sesuai dengan rute halaman sign-in di aplikasi Anda
    } else {
        // Lakukan tindakan darurat lainnya di sini jika pengguna sudah login
        alert("Anda sudah login. Melakukan tindakan darurat...");
        // Anda bisa menambahkan logika lainnya di sini
    }
}

		trackUserLocation();
  </script>