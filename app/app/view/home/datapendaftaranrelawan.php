<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Notification</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>

    function detailDaftarRelawan(laporanId) {
        // Ganti URL berikut dengan URL yang sesuai untuk halaman detail
        const detailUrl = `https://silaben.site/app/public/home/detailpendaftaranrelawan/${laporanId}`;
        
         // Redirect ke halaman detail bencana
         window.location.href = detailUrl;
    }
  </script>
</head>
<body>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Pendaftaran Relawan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Data Pendaftaran Relawan</li>
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
                  <th>Jumlah Relawan yang Dibutukan</th>
                  <th>Jumlah Relawan saat ini</th>
                  <th>Daftar Relawan</th>
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
                        <strong style="color: green;"><?= $laporan['jumlah_relawan_dibutuhkan'];?></strong>
                    </td>
                    <td class="text-muted mb-0 small">
                        <strong style="color: green;"><?= $laporan['jumlah_relawan_terdaftar'];?></strong>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-secondary" type="button" onclick="detailDaftarRelawan('<?= $laporan['laporan_id']; ?>')">
                          Detail
                        </button>
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
