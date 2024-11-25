<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pendaftaran Relawan</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- <script>
    function detailDaftarRelawan(relawan_id) {
        // Redirect ke halaman detail relawan berdasarkan relawan_id
        const detailUrl = `<?= APP_PATH ?>/home/detailrelawan/${relawan_id}`;
        window.location.href = detailUrl; // Mengarahkan ke halaman detail relawan
    }
  </script> -->
</head>
<body>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Pendaftaran Relawan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item">Data Pendaftaran Relawan</li>
        <li class="breadcrumb-item active">Data Pendaftaran Relawan</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body" style="overflow-x: auto;">>
            <table class="table datatable">
              <thead>
                <tr>
                  <th><b>NO</b></th>
                  <th>Nama</th>
                  <th>NIK</th>
                  <th>Jenis Kelamin</th>
                  <th>Bidang Keahlian</th>
                  <th>Tanggal Registrasi</th>
                  <th>Tanggal Lahir</th>
                  <th>Alamat</th>
                  <th>Nomor Whatsapp</th>
                  <th>Email</th>
                  <th>Alasan</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 1;
                foreach ($data['relawan_list'] as $relawan): ?>
                  <tr>
                    <th scope="row"><?= $count ?></th>
                    <td>
                      <div>
                        <p class="fw-bold mb-1"><?= strtoupper($relawan['nama_relawan']); ?></p>
                      </div>
                    </td>
                    <td>
                      <p class="text-muted mb-0 small"><?= $relawan['nik']; ?></p>
                    </td>
                    <td class="text-center">
                      <p class="text-muted mb-0 small"><?= $relawan['jenis_kelamin']; ?></p>
                    </td>
                    <td class="text-muted mb-0 small"><?= $relawan['bidang_keahlian']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['tanggal_daftar']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['tanggal_lahir']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['alamat']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['no_whatsapp']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['email']; ?></td>
                    <td class="text-muted mb-0 small"><?= $relawan['alasan']; ?></td>
                    <td>
                      <?php if ($relawan['status_pendaftaran'] === "PENDING"): ?>
                        <button class="btn btn-success" onclick="updateStatusDaftar('<?= $relawan['pendaftaran_id']; ?>', 'APPROVED')">
                          Terima
                        </button>
                        <button class="btn btn-danger" onclick="updateStatusDaftar('<?= $relawan['pendaftaran_id']; ?>', 'REJECTED')">
                          Tolak
                        </button>
                      <?php elseif ($relawan['status_pendaftaran'] === "APPROVED"): ?>
                          <span class='badge bg-success rounded-pill d-inline-block'>diterima</span>
                      <?php elseif ($relawan['status_pendaftaran'] === "REJECTED"): ?>
                        <span class='badge bg-danger rounded-pill d-inline-block'>ditolak</span>
                      <?php endif; ?>
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

  <script>
    $(document).ready(function () {
      // Initialize DataTable
      $('#tbllaporanbencana').DataTable({
        responsive: true,
        searching: true,
        lengthChange: true,
        pageLength: 100,
        columnDefs: [
          { targets: [0, -1], orderable: false }
        ]
      });

      // Remove sorting icons from the first column header
      $('#tbllaporanbencana thead th:first-child').removeClass('sorting sorting_asc sorting_desc');

      // Add margin to dataTables_filter to create space between search field and table
      $('.dataTables_filter').css('margin-bottom', '8px');
    });

    function updateStatusDaftar(pendaftaran_id, status_pendaftaran) {
    const confirmationMessage = `Apakah Anda yakin ingin ${status_pendaftaran === 'APPROVED' ? 'menerima' : 'menolak'} pendaftaran ini?`;
    if (confirm(confirmationMessage)) {
        const data = {
            pendaftaran_id: pendaftaran_id,
            status_pendaftaran: status_pendaftaran,
        };

        fetch('https://silaben.site/app/public/home/updateStatusDaftar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Tampilkan hasil pesan
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

  </script>
</main>
</body>
</html>
