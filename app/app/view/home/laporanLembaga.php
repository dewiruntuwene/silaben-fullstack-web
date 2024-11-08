<?php
// Mulai sesi PHP
session_start();

// Ambil user_id dari session jika ada
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
include 'file:///C:/Users/Dewi/AppData/Local/Temp/scp33171/var/www/html/app/app/view/home/map.php';
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Notification</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    // Fungsi untuk mengirim notifikasi ke relawan
    function sendWhatsAppNotificationRelawan(laporanId) {
      fetch('https://silaben.site/app/public/home/sendWhatsAppNotificationRelawan', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ laporan_id: laporanId }) // Kirim data laporan untuk diproses di server
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Notifikasi berhasil dikirim ke Relawan');
          // Update UI setelah notifikasi dikirim
          document.querySelector(#relawan-button-${laporanId}).disabled = true;
          document.querySelector(#relawan-button-${laporanId}).classList.replace('btn-danger', 'btn-secondary');
        } else {
          alert('Gagal mengirim notifikasi ke Relawan');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim notifikasi ke Relawan');
      });
    }

    function notifyMasyarakat(laporanId) {
      fetch('https://silaben.site/app/public/home/saveDisasterData', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ laporan_id: laporanId }) // Kirim data laporan untuk diproses di server
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Notifikasi telah dikirim kepada Masyarakat!');
          // Update UI setelah notifikasi dikirim
          document.querySelector(#masyarakat-button-${laporanId}).disabled = true;
          document.querySelector(#masyarakat-button-${laporanId}).classList.replace('btn-danger', 'btn-secondary');
        } else {
          alert('Gagal mengirim notifikasi ke Masyarakat');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim notifikasi ke Masyarakat');
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
                  <th>Status Notifikasi Masyarakat</th>
                  <th>Status Notifikasi Relawan</th>
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
                    <td class="text-muted mb-0 small">
                        <?php 
                        if ($laporan['is_notified_masyarakat'] == 0) {
                            echo '<strong style="color: red;">Belum Diberitahukan</strong>';
                        } else {
                            echo '<strong style="color: green;">Sudah Diberitahukan</strong>';
                        }
                        ?>
                    </td>
                    <td class="text-muted mb-0 small">
                        <?php 
                        if ($laporan['is_notified_relawan'] == 0) {
                            echo '<strong style="color: red;">Belum Diberitahukan</strong>';
                        } else {
                            echo '<strong style="color: green;">Sudah Diberitahukan</strong>';
                        }
                        ?>
                    </td>
                    <td> 
                      <div>
                        <?php if ($laporan['is_notified_relawan'] == 0): ?>
                            <button class="btn btn-danger" id="relawan-button-<?= $laporan['id']; ?>" style="width: 150%; margin-bottom: 5%; margin-top: 5%" onclick="sendWhatsAppNotificationRelawan(<?= $laporan['id']; ?>)">Notified Relawan</button>
                        <?php else: ?>
                            <button class="btn btn-secondary" id="relawan-button-<?= $laporan['id']; ?>" style="width: 150%; margin-bottom: 5%; margin-top: 5%" disabled>Notified Relawan</button>
                        <?php endif; ?>

                        <?php if ($laporan['is_notified_masyarakat'] == 0): ?>
                            <button class="btn btn-danger" id="masyarakat-button-<?= $laporan['id']; ?>" style="width: 150%" onclick="notifyMasyarakat(<?= $laporan['id']; ?>)">Notified Masyarakat</button>
                        <?php else: ?>
                            <button class="btn btn-secondary" id="masyarakat-button-<?= $laporan['id']; ?>" style="width: 150%" disabled>Notified Masyarakat</button>
                        <?php endif; ?>
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