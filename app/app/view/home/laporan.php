<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Notification</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    // Fungsi untuk mengirim notifikasi ke relawan
    function sendWhatsAppNotificationRelawan() {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "https://silaben.site/app/public/home/sendWhatsAppNotificationRelawan", true);
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

    function notifyMasyarakat() {
        // Menggunakan Fetch API untuk memanggil fungsi sendWhatsAppNotificationMasyarakat
        fetch('https://silaben.site/app/public/home/saveDisasterData', {
            method: 'POST', // Gunakan metode POST jika diperlukan
            headers: {
                'Content-Type': 'application/json',
                // Tambahkan header lain jika diperlukan, seperti Authorization
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Mengubah response ke format JSON
        })
        .then(data => {
            // Tindakan jika berhasil
            console.log('Success:', data);
            alert('Notifikasi telah dikirim kepada masyarakat!');
        })
        .catch((error) => {
            // Tindakan jika gagal
            console.error('Error:', error);
            alert('Gagal mengirim notifikasi.');
        });
    }

    function notifyRelawan() {
        // Menggunakan Fetch API untuk memanggil fungsi sendWhatsAppNotificationMasyarakat
        fetch('https://silaben.site/app/public/home/sendWhatsAppNotificationRelawan', {
            method: 'POST', // Gunakan metode POST jika diperlukan
            headers: {
                'Content-Type': 'application/json',
                // Tambahkan header lain jika diperlukan, seperti Authorization
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Mengubah response ke format JSON
        })
        .then(data => {
            // Tindakan jika berhasil
            console.log('Success:', data);
            alert('Notifikasi telah dikirim kepada masyarakat!');
        })
        .catch((error) => {
            // Tindakan jika gagal
            console.error('Error:', error);
            alert('Gagal mengirim notifikasi.');
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
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      <b>NO</b>
                    </th>
                    <th>Report Details</th>
                    <th>Jenis Bencana</th>
                    <th>Foto Bencana</th> 
                    <th>Lokasi Bencana</th>               
                    <th>Start Date</th>
                    <th>Description</th>
                    <th>User Info</th>
                    <th>Status</th> 
                    <th>Status Notifikasi Lembaga</th>
                    <th>Notifikasi</th>
                    <!-- New Column for User Info -->
                  </tr>
                </thead>
                <tbody>
                  <?php $count = 1; 
                  foreach($data['datalaporan'] as $laporan): ?>
                    <tr>
                      <th scope="row"><?= $count ?></th>
                      <td>
                        <div>
                          <p class="fw-bold mb-1"><?= strtoupper($laporan['report_title']); ?></p> 
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='layers' style="width: 16px; height: 16px;"></span> 
                            <?= $laporan['report_description'];?>
                          </p>
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='calendar' style="width: 16px; height: 16px;"></span> 
                            <?= $laporan['report_date'].", ".$laporan['report_time'];?>
                          </p>
                        </div>
                      </td>
                      <td>
                        <div>
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='home' style="width: 16px; height: 16px;"></span> 
                            <strong>Jenis Bencana: </strong><?= $laporan['jenis_bencana'];?>
                          </p>
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='clipboard' style="width: 16px; height: 16px;"></span> 
                            <strong>Klasifikasi Bencana: </strong><?= $laporan['klasifikasi_bencana'];?>
                          </p>
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='tool' style="width: 16px; height: 16px;"></span> 
                            <strong>Level Kerusakan Infrastruktur: </strong><?= $laporan['level_kerusakan_infrastruktur'];?>
                          </p>
                          <p class="text-muted mb-0 small">
                            <span class='text-primary' data-feather='check-circle' style="width: 16px; height: 16px;"></span> 
                            <strong>Kesesuaian Laporan: </strong><?= $laporan['kesesuaian_laporan'];?>
                          </p>
                        </div>
                      </td>
                      <td class="text-center">
                        <img src="<?= APP_PATH; ?>/fotobukti/<?= $laporan['report_file_name_bukti'];?>" class="img-thumbnail preview-image" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="<?= APP_PATH; ?>/fotobukti/<?= $laporan['report_file_name_bukti'];?>" style="width: 100px; cursor: pointer;">
                      </td>
                      <td>
                        <div class="text-muted mb-0 small">
                          <span class='text-primary' data-feather='map-pin' style="width: 16px; height: 16px;"></span> 
                          <?= $laporan['lokasi_bencana'];?>
                        </div>
                      </td>
                      <td>
                        <p class="text-muted mb-0 small">
                          <span class='text-primary' data-feather='calendar' style="width: 16px; height: 16px;"></span> 
                          <?= $laporan['report_date'].", ".$laporan['report_time'];?>
                        </p>
                      </td>
                      <td>
                        <p class="text-muted mb-0 small">
                          <span class='text-primary' data-feather='info' style="width: 16px; height: 16px;"></span> 
                          <?= $laporan['deskripsi_singkat_ai']." Warga juga sarankan untuk ".$laporan['saran_singkat'];?>
                        </p>
                      </td>
                      <td>
                        <p class="text-muted mb-0 small">
                          <span class='text-primary' data-feather='user' style="width: 16px; height: 16px;"></span> 
                          <strong style="font-size: small;">Nama Pengguna: </strong><?= $laporan['pelapor_name'];?> <!-- Display User Name -->
                        </p>
                        <p class="text-muted mb-0 small">
                          <span class='text-primary' data-feather='mail' style="width: 16px; height: 16px;"></span> 
                          <strong>Email: </strong><?= $laporan['pelapor_email'];?> <!-- Display User Email -->
                        </p>
                      </td>
                      <td class="text-center">
                        <?php 
                          if($laporan['status'] === "verified"){
                            echo "<span class='badge bg-success rounded-pill d-inline-block'>".$laporan['status']."</span>";
                          }elseif($laporan['status'] === "unverified"){
                            echo "<span class='badge bg-warning rounded-pill d-inline-block'>".$laporan['status']."</span>";
                          }else{
                            echo "<span class='badge bg-danger rounded-pill d-inline-block'>".$laporan['status']."</span>";
                          }
                        ?>
                      </td>
                      <td class="text-primary mb-0 small">
                        <?php 
                        if ($laporan['is_notified'] == '0') {
                            echo '<strong style="color: red; font-size: small">Belum Diberitahukan</strong>'; // Teks untuk status belum diberitahukan
                        } else {
                            echo '<strong style="color: green; font-size: small">Sudah Diberitahukan</strong>'; // Teks untuk status sudah diberitahukan
                        }
                        ?>
                    </td>
                    <td> 
                      <div>
                        <button class="btn btn-danger" style="width: 80%; margin-bottom: 5%; margin-top: 5%" onclick="sendWhatsAppNotificationRelawan()">Notified Relawan</button>
                        <button class="btn btn-danger" style="width: 80%" onclick="notifyMasyarakat()">Notified Masyakat</button>
                      </div>
                    </td>
                    </tr>
                    <?php 
                      $count++;
                    endforeach; ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
