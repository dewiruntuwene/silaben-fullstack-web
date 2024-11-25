<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Notification</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    // Fungsi untuk mengirim notifikasi ke relawan
    function sendWhatsAppNotificationRelawan(laporanId) {
      const data = { laporan_id: laporanId };
      fetch('https://silaben.site/app/public/home/sendWhatsAppNotificationRelawan', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.status === 'success') {
          alert('Notifikasi telah dikirim kepada Masyarakat!');
        } else {
          alert('Gagal mengirim notifikasi ke Masyarakat' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim notifikasi ke Masyarakat');
      });
    }

    function notifyMasyarakat(laporanId) {
      const data = { laporan_id: laporanId };
      fetch('https://silaben.site/app/public/home/saveDisasterData', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.status === 'success') {
          alert('Notifikasi telah dikirim kepada Masyarakat!');
        } else {
          alert('Gagal mengirim notifikasi ke Masyarakat' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim notifikasi ke Masyarakat');
      });
    }

    function updateStatusLaporan(laporan_id, status_laporan) {
        const data = { laporan_id: laporan_id, status_laporan: status_laporan };
        
        fetch('https://silaben.site/app/public/home/updateLaporan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.status === 'success') {
                alert('Status Laporan berhasil diupdate');
                window.location.reload();
            } else {
                alert('Gagal update status laporan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal update status laporan');
        });
    }
  </script>
</head>
<body>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Pelaporan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Data Pelaporan</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
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
                  <th>Status Laporan</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 1;
                foreach ($data['datalaporan'] as $laporan): ?>
                  <tr>
                    <th scope="row"><?= $count ?></th>
                    <td>
                      <div>
                        <p class="fw-bold mb-1"><?= strtoupper($laporan['report_title']); ?></p>
                        <p class="text-muted mb-0 small"><?= $laporan['report_description'];?></p>
                        <p class="text-muted mb-0 small"><?= $laporan['lokasi_bencana'];?></p>
                        <p class="text-muted mb-0 small"><?= $laporan['report_date'] . ", " . $laporan['report_time'];?></p>
                      </div>
                    </td>
                    <td>
                      <p class="text-muted mb-0 small"><?= $laporan['jenis_bencana'];?></p>
                      <p class="text-muted mb-0 small"><?= $laporan['klasifikasi_bencana'];?></p>
                      <p class="text-muted mb-0 small"><?= $laporan['level_kerusakan_infrastruktur'];?></p>
                      <p class="text-muted mb-0 small"><?= $laporan['kesesuaian_laporan'];?></p>
                    </td>
                    <td class="text-center">
                      <img src="<?= APP_PATH; ?>/fotobukti/<?= $laporan['report_file_name_bukti'];?>" class="img-thumbnail preview-image" style="width: 100px;">
                    </td>
                    <td class="text-center">
                      <?php 
                        $statusBadgeClass = $laporan['status'] === "verified" ? "bg-success" : ($laporan['status'] === "unverified" ? "bg-warning" : "bg-danger");
                        echo "<span class='badge $statusBadgeClass'>".$laporan['status']."</span>";
                      ?>
                    </td>
                    <td class="text-muted mb-0 small">
                      <?= $laporan['is_notified_masyarakat'] == 0 ? '<strong style="color: red;">Belum Diberitahukan</strong>' : '<strong style="color: green;">Sudah Diberitahukan</strong>'; ?>
                    </td>
                    <td class="text-muted mb-0 small">
                      <?= $laporan['is_notified_relawan'] == 0 ? '<strong style="color: red;">Belum Diberitahukan</strong>' : '<strong style="color: green;">Sudah Diberitahukan</strong>'; ?>
                    </td>
                    <td>
                      <div>
                        <?php if ($laporan['status_laporan'] !== "SELESAI"): ?>
                          <button class="btn btn-danger" style="width: 100%; margin-bottom: 5%; margin-top: 5%" onclick="sendWhatsAppNotificationRelawan('<?= $laporan['laporan_id']; ?>')">Notified Relawan</button>
                        <?php else: ?>
                          <button class="btn btn-secondary" style="width: 100%; margin-bottom: 5%; margin-top: 5%" disabled>Notified Relawan</button>
                        <?php endif; ?>

                        <?php if ($laporan['status_laporan'] !== "SELESAI"): ?>
                          <button class="btn btn-danger" style="width: 100%" onclick="notifyMasyarakat('<?= $laporan['laporan_id']; ?>')">Notified Masyarakat</button>
                        <?php else: ?>
                          <button class="btn btn-secondary" style="width: 100%" disabled>Notified Masyarakat</button>
                        <?php endif; ?>
                        </td>
												<td class="text-center">
                          <?php if ($laporan['status_laporan'] !== "SELESAI"): ?>
                            <select class="form-control" id="status-laporan" onchange="updateStatusLaporan('<?= $laporan['laporan_id']; ?>', this.value)">
                                <option value="SELESAI">Bencana Selesai</option>
                                <?php if ($laporan['jenis_bencana'] == 'Banjir'): ?>
                                    <option value="SIAGA 4">SIAGA 4</option>
                                    <option value="SIAGA 3">SIAGA 3</option>
                                    <option value="SIAGA 2">SIAGA 2</option>
                                    <option value="SIAGA 1">SIAGA 1</option>
                                <?php elseif ($laporan['jenis_bencana'] == 'Gunung Api'): ?>
                                    <option value="LEVEL 1">LEVEL 1 (Aktif Normal)</option>
                                    <option value="LEVEL 2">LEVEL 2 (Waspada)</option>
                                    <option value="LEVEL 3">LEVEL 3 (Siaga)</option>
                                    <option value="LEVEL 4">LEVEL 4 (Awas)</option>
                                <?php endif; ?>
                            </select>
                            <?php elseif ($laporan['status_laporan'] === "SELESAI"): ?>
                                <span class='badge text-success d-inline-block'>Laporan Selesai.</span>
                            <?php endif; ?>

													<!-- <?php 
														if($laporan['status_laporan'] === "SEMENTARA TERJADI"){
												
															echo "<form action='mark_complete.php' method='POST' style='display:inline;'>
															<input type='hidden' name='report_id' value='".$laporan['id']."'> 
															<button type='submit' class='btn btn-success btn-sm'>Bencana Selesai</button>
															</form>";
														}elseif($laporan['status'] === "SELESAI"){
															//echo "<span class='badge bg-warning rounded-pill d-inline-block'>".$laporan['status_laporan']."</span>";
															echo "<span class='text-success d-inline-block'>Laporan Selesai.</span>";
														}else{
															echo "<span class='badge bg-warning rounded-pill d-inline-block'>".$laporan['status_laporan']."</span>";
														}
													?> -->
													
												</td>
                      </div>
                    </td>
                  </tr>
                  <?php $count++; endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  </script>
	<script>
        // Initialize DataTable
		$(document).ready(function () {
			$('#tbllaporanbencana').DataTable({
				responsive: true,
				searching: true, // Enable search feature
				lengthChange: true, // Enable show entries feature
				pageLength: 100, // Show 100 rows per page
				columnDefs: [
					{ targets: [0, -1], orderable: false } // Disable sorting on the first column
					//{ targets: [0, 1, -1], orderable: false } // Disable sorting on the first, second, and last columns
					
				]
			});
			
			// Remove sorting icons from the first column header
			$('#tbllaporanbencana thead th:first-child').removeClass('sorting sorting_asc sorting_desc');
    
			// Add margin to dataTables_filter to create space between search field and table
			$('.dataTables_filter').css('margin-bottom', '8px');
		});

    </script>
	
	<script>
		$(document).ready(function() {
		  $('.preview-image').on('click', function() {
			var imageSrc = $(this).data('src');
			$('#modalImage').attr('src', imageSrc);
		  });
		});
		
</script>
</main>
</body>
</html>
