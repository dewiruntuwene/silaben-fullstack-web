
<section id="hero" class="d-flex flex-column justify-content-end align-items-center position-relative" style="margin-bottom: 200px;">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">Sigap Lapor <span>Bencana</span></h2>
        <p class="animate__animated animate__fadeInUp">Layanan pelaporan, notifikasi peringatan dan pembatasan area virtual rawan bencana yang terjadi disekitar masyarakat dengan menggunakan teknologi kecerdasan buatan (AI) untuk proses pemeriksaan kesesuaian dan verifikasi bukti laporan secara otomatis.</p>
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
  <!-- ======= Form Input Section ======= -->
  <section id="report-form" class="report-form-section position-absolute" style="top: 80%;">
		<div class="container my-4">
			<div class="row justify-content-center">
				<div class="col-lg-7">
					<div class="report-form">
						<div class="form-title text-center" style="background-color: red; ">
							<h5>Maaf, bukti laporan bukan bencana!!!</h5>
						</div>
						<form id="reportForm" method="POST" action="<?php echo APP_PATH; ?>/home/submitlaporan"  enctype="multipart/form-data">
							<div class="mb-3">
								<label for="category" class="form-label"><strong>Hi <?php echo $data['user_name'];?> maaf, laporan bencana anda telah dibatalkan oleh sistem karena setelah dilakukan pengecekan oleh AI, bukti yang anda lampirkan tidak sesuai dan bukan termasuk dalam kategori bencana. Mohon lakukan pengecekan kembali isi laporan anda sebelum disubmit ke dalam sistem pelaporan bencana ini. Setiap tindakan yang melanggar akan ditindalanjuti demi keamanan. Perlu dingat bahwa data pelapor akan disimpan. Thank you !!!</strong></label> 
							</div>
							<div class="mt-4 d-flex align-items-center justify-content-between p-0" style="border-radius: 10px;">
								<div class="d-flex align-items-center">
									<a href="<?php echo APP_PATH; ?>/" class="btn btn-lg btn-primary me-1">Buat laporan baru</a>
									<a href="<?php echo APP_PATH; ?>/home/map/" class="btn btn-lg btn-primary">Tampilkan Map</a>
								</div>
								<div>
									<a href="<?php echo APP_PATH; ?>/home/data/" class="btn btn-lg btn-success">Tampilkan Daftar Laporan</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <script src="<?php echo APP_PATH; ?>/a/assets/js/action-map.js"></script>
  