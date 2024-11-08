<?php
// Mulai sesi PHP
session_start();

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Notification</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
  
    // Fungsi untuk mengirim notifikasi ke relawan
    function sendWhatsAppNotificationRelawan() {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "home/sendWhatsAppNotificationRelawan", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          alert("Notifikasi berhasil dikirim ke Relawan");
        } else if (xhr.readyState == 4) {
          alert("Gagal mengirim notifikasi ke Relawan");
        }
      };

      // Kirim permintaan ke server
      xhr.send();
    }

  // Fungsi untuk mengambil lokasi masyarakat, memperbarui di database, dan memeriksa jika dekat dengan bencana
  function sendWhatsAppNotificationMasyarakat() {
      var userId = document.getElementById('user_id').value;
      console.log("user id:",userId)
      if (!userId) {
        console.error("User ID is not defined");
        return;
      }

      // Ambil lokasi pengguna menggunakan Geolocation API
      navigator.geolocation.getCurrentPosition(function(position) {
          var userLat = position.coords.latitude;
          var userLng = position.coords.longitude;
          var userId = user_id; // User ID diambil dari konteks aplikasi

          console.log("User  Latitude:", userLat); // Log latitude
          console.log("User  Longitude:", userLng); // Log longitude


          // Panggil AJAX untuk memperbarui lokasi di database dan memeriksa apakah berada di sekitar bencana
          $.ajax({
              url: 'https://silaben.site/app/public/UserController/checkAndSendNotification',
              type: 'POST',
              contentType: 'application/json', // Menentukan tipe konten
              data: JSON.stringify({ // Mengonversi data ke string JSON
                  user_id: userId,
                  latitude: userLat,
                  longitude: userLng
              }),
              success: function(response) {
                  console.log("Notification process completed:", response);
              },
              error: function(xhr, status, error) {
                  console.error("Failed to process notification:", error);
              }
          });
      }, function(error) {
          console.error("Error getting user location:", error);
      });
  }
  </script>
</head>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Data Pelaporan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Data Pelaporan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
          <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th><b>NO</b></th>
                  <th>Laporan</th>
                  <th>Hasil AI</th>                  
                  <th>Foto</th>
                  <th>Status</th>
                  <th>Notifikasi</th>
                </tr>
              </thead>
                <?php $count = 1; 
                foreach($data['datalaporan'] as $laporan): ?>
                  <tr>
                    <th scope="row"><?= $count ?></th>
                    <td>
                      <div>
                        <p class="fw-bold mb-1"><?php echo strtoupper($laporan['report_title']); ?></p> 
                        <p class="text-muted mb-0 small"><?php echo $laporan['report_description'];?></p>
                        <p class="text-muted mb-0 small"><?php echo $laporan['lokasi_bencana'];?></p>
                        <p class="text-muted mb-0 small"><?php echo $laporan['report_date'].", ".$laporan['report_time'];?></p>
                      </div>
                    </td>
                    <td>
                      <p class="text-muted mb-0 small"><?php echo $laporan['jenis_bencana'];?></p>
                      <p class="text-muted mb-0 small"><?php echo $laporan['klasifikasi_bencana'];?></p>
                      <p class="text-muted mb-0 small"><?php echo $laporan['level_kerusakan_infrastruktur'];?></p>
                      <p class="text-muted mb-0 small"><?php echo $laporan['kesesuaian_laporan'];?></p>
                    </td>
                    <td class="text-center">
                      <img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti'];?>" class="img-thumbnail preview-image" style="width: 100px;">
                    </td>
                    <td class="text-center">
                      <?php 
                        if($laporan['status'] === "verified"){
                          echo "<span class='badge bg-success'>".$laporan['status']."</span>";
                        } elseif($laporan['status'] === "unverified"){
                          echo "<span class='badge bg-warning'>".$laporan['status']."</span>";
                        } else {
                          echo "<span class='badge bg-danger'>".$laporan['status']."</span>";
                        }
                      ?>
                    </td>
                    <td>
                      <div>
                        <button class="btn btn-danger" onclick="sendWhatsAppNotificationRelawan()">Notified Relawan</button>
                        <button class="btn btn-danger" onclick="sendWhatsAppNotificationMasyarakat()">Notified Masyarakat</button>
                      </div>
                    </td>
                  </tr>
                  <?php $count++; endforeach; ?>
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>

</main>
