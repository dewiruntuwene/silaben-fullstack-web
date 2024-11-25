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
          document.getElementById("btnNotifyRelawan").textContent = "Sudah Dikirimkan oleh BPBD";
          document.getElementById("btnNotifyRelawan").disabled = true;
        } else if (xhr.readyState == 4) {
          alert("Gagal mengirim notifikasi ke Relawan");
        }
      };

      // Kirim permintaan ke server
      xhr.send();
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

    <section class="section mt-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Daftar Laporan Bencana</h5>
          <div class="table-responsive">
            <!-- Search and Table -->
            <table id="dataLaporanTable" class="table table-striped table-hover align-middle">
              <thead class="text-center">
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
                    <th>Level Bencana</th>
                    <th>Status Notifikasi Lembaga</th>
                    <th>Notifikasi</th>
                    <th>Status Laporan</th>
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
                      <td>
                        <div>
                          <p class="fw-bold mb-1"><?= strtoupper($laporan['level_bencana']); ?></p> 
                        </div>
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
                    <!-- laporannya masih bisa dikirim, jika status laporannya belum selesai, kalau sudah selesai, sudah tidak bisa dikiri -->
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
                  </div>
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
