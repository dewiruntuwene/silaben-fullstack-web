<style>
	body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .profile-header {
            background: linear-gradient(rgba(3, 24, 70, 0.768), rgba(3, 24, 70, 0.768)), 
                        url('https://silaben.site/app/public/a/assets/img/background-bencana.jpg');
            background-size: cover; /* Ensures the image covers the entire header */
            background-position: center center; /* Positions the background image in the center */
            background-repeat: no-repeat; /* Ensures the background doesn't repeat */
            color: white;
            padding: 20px;
            position: relative; /* The profile-header itself is positioned relative to allow absolute positioning inside it */
            display: flex;
            align-items: center;
        }


        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
            width: 120px;
            height: 120px;
            margin-top: 0px;
            position: relative;
        }

        .profile-header h2 {
            /* align-items: center; */
            font-size: 24px;
            width: 120px;
            height: 120px;
            text-align: center;
        }

        .profile-header p {
            margin: 0;
            font-size: 24px;
        }

    .card {
        border: none; /* Hilangkan border default */
        border-radius: 12px; /* Rounded corner */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow modern */
        overflow: hidden; /* Supaya gambar tidak keluar dari card */
        background-color: #fff;
    }

    .card img {
        height: 150px; /* Ukuran seragam untuk gambar */
        object-fit: cover; /* Sesuaikan gambar dalam area */
        width: 100%;
        border-bottom: 1px solid #ddd; /* Pemisah gambar dengan konten */
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .card p {
        font-size: 0.875rem; /* Ukuran teks lebih kecil */
        margin-bottom: 0.5rem;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
        border-radius: 5px;
    }

    .dropdown .dropdown-menu {
        font-size: 0.875rem;
    }

    .btn {
        border-radius: 20px;
        font-size: 0.875rem;
        padding: 0.4rem 1rem;
    }

    .btn-primary {
        background-color: #070478;
        border: none;
    }

    .btn-primary:hover {
        background-color: #05035a;
    }

	.container-card {
		padding: 30px;
		margin-top: 30px;
	}
</style>

<body>
	<div class="profile-header">
        <!-- <?php 
            if($data['gender'] == 'female'){ 
              $img_profile = "profile-female.jpg";
            }
            
            if($data['gender'] == 'male'){ 
              $img_profile = "profile-male.jpg";
            }
          
        ?>
        <div style="display: flex;">
            <img src="<?php echo APP_PATH; ?>/src/assets/img/<?php echo $img_profile; ?>" alt="User Avatar" width="100" height="100">
            <div style="margin-left: 20px;">
                <h2><?php echo $data['user_name']; ?></h2>
                <p><?php echo $data['role']; ?></p>
            </div>
        </div> -->

        <h2 class="animate__animated animate__fadeInDown"> <span></span></h2>
       
    </div>

	<div class="container-card row">
		<?php foreach ($data['datalaporan'] as $laporan): ?>
			<div class="col-md-4 col-lg-3 mb-4">
				<div class="card">
					<img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti']; ?>" alt="Bukti Laporan" class="img-fluid">
					<div class="card-body">
                        <h5 class="card-title"><strong><?php echo strtoupper($laporan['report_title']); ?></strong></h5>
                        <p><i class="fas fa-info-circle" style="color: #ff5722;"></i> <?php echo $laporan['report_description']; ?></p>
                        <p><i class="fas fa-map-marker-alt" style="color: #ff5722;"></i> <?php echo $laporan['lokasi_bencana']; ?></p>
                        <p><i class="fas fa-calendar-alt" style="color: #ff5722;"></i> <?php echo $laporan['report_date'] . ", " . $laporan['report_time']; ?></p>
                        <p><i class="fas fa-exclamation-triangle" style="color: #ff5722;"></i> <?php echo $laporan['jenis_bencana']; ?></p>
                        <p><i class="fas fa-user-friends" style="color: #ff5722;"></i> Dibutuhkan <?php echo $laporan['jumlah_relawan_dibutuhkan']; ?> Relawan</p>
                    </div>
					<div class="card-footer">
						<span class="badge 
							<?php 
							if ($laporan['status'] === 'verified') {
								echo 'bg-success';
							} elseif ($laporan['status'] === 'unverified') {
								echo 'bg-warning';
							} else {
								echo 'bg-danger';
							} ?>">
							<?php echo ucfirst($laporan['status']); ?>
						</span>
						<div class="dropdown">
							<button class="btn btn-sm btn-secondary" type="button" onclick="detailBencana('<?= $laporan['laporan_id']; ?>')">
								Detail
							</button>
							
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</body>


<script>
    function detailBencana(laporanId) {
        // Ganti URL berikut dengan URL yang sesuai untuk halaman detail
        const detailUrl = `https://silaben.site/app/public/home/detailbencana/${laporanId}`;
        
        // Redirect ke halaman detail bencana
        window.location.href = detailUrl;
    }
</script>

<!-- <script>
    function detailBencana(laporanId) {
      const data = { laporan_id: laporanId };
      console.log(data);
      fetch('https://silaben.site/app/public/home/detailbencana', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
            // Redirect ke halaman detail bencana
            window.location.href = data.redirect;
        } else {
            alert('Gagal memuat detail bencana: ' + (data.message || ''));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat detail bencana.');
    });

    }
</script> -->

