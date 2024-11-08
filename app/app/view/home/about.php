
<section id="hero" class="d-flex flex-column justify-content-end align-items-center position-relative" style="margin-bottom: 50px;">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <!-- Slide 1 -->
    <div class="carousel-item active" style=" bottom:10%;">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">Tentang <span>SILABEN !!!</span></h2>
        <p class="animate__animated animate__fadeInUp">Sistem siaga lapor bencana menggunakan teknologi kecerdasan buatan.</p>
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
  <section id="report-form" class="report-form-section position-absolute" style="top: 50%; bottom:100%; z-index: 100;">
		<div class="container my-4">
			<div class="row justify-content-center">
				<div class="col-lg-12">
					<div class="report-form">
						<div class="form-title text-center" style="background-color: #070478;">
							<h5>Hi, selamat datang di sistem pelaporan bencana !!!</h5>
						</div>
							<div class="mb-3">
								
								<label for="category" class="form-label"><strong>SILABEN </strong>adalah aplikasi inovatif yang memanfaatkan kecerdasan buatan (AI) untuk memungkinkan masyarakat melaporkan berbagai jenis bencana. Bencana alam seperti gempa bumi, tsunami, gunung meletus, banjir, angin topan, tanah longsor, pohon tumbang, dan kebakaran hutan serta lahan dapat dilaporkan melalui aplikasi ini. Selain itu, Silaben juga mencakup pelaporan bencana non-alam seperti kecelakaan transportasi, banjir, wabah penyakit, polusi, pencemaran lingkungan, kerusakan jalan, dan kemacetan, serta bencana sosial seperti konflik sosial, pencurian, dan kekerasan. Setiap laporan yang diterima akan segera diinformasikan kepada masyarakat, relawan, dan instansi terkait, termasuk TNI (Tentara Nasional Indonesia), POLRI (Kepolisian Negara Republik Indonesia), Satlantas (Satuan Lalu Lintas), Dinas Perhubungan, Dinas Kesehatan, Dinas Lingkungan Hidup, Dinas Sosial, Dinas Pemadam Kebakaran dan Penyelamatan, PDAM (Perusahaan Daerah Air Minum), PAM (Perusahaan Air Minum), PLN (Perusahaan Listrik Negara), rumah sakit, dan aparat desa melalui notifikasi. Dengan bantuan AI, proses pengecekan laporan menjadi lebih cepat dan akurat, memastikan respons yang efektif dan tepat waktu. 
								
								<br><br><strong>Apikasi ini berfokus pada beberapa masalah utama:</strong><br>
								
								<br><strong>1. Efficiency and Accessibility of Government Services (Efisiensi dan Aksesibilitas Layanan Pemerintah): </strong>Dengan menghadirkan satu platform terpadu, Silaben mempermudah masyarakat untuk melaporkan berbagai isu dan memperoleh layanan dari berbagai instansi pemerintah. Silaben dirancang untuk meningkatkan efisiensi dan aksesibilitas layanan pemerintahan, memastikan bahwa informasi penting cepat sampai ke pihak yang berwenang.

								<br><br><strong>2. Security and Emergency Response (Keamanan dan Tanggap Darurat): </strong>Silaben memungkinkan pelaporan masalah secara real-time, termasuk bencana alam, non-alam, dan sosial. Hal ini memungkinkan respons dan penanganan yang lebih cepat dan efektif, membantu meminimalkan kerugian dan meningkatkan keselamatan masyarakat.

								<br><br><strong>3. Community Participation and Engagement (Partisipasi dan Keterlibatan Masyarakat): </strong>Silaben memfasilitasi partisipasi aktif masyarakat dalam melaporkan masalah/isu yang terjadi di sekitar dan meningkatkan keterlibatan masyarakat dalam proses pemerintahan dan pembangunan, mendorong kolaborasi antara masyarakat dan pemerintah.

								<br><br><strong>4. Warning and Area Restrictions (Peringatan dan Pembatasan Area): </strong>Silaben menyediakan notifikasi bencana berdasarkan laporan yang diterima dari masyarakat, membantu warga untuk tetap waspada dan mengambil tindakan pencegahan yang tepat. Selain itu, fitur geofencing memungkinkan pembatasan wilayah tertentu, memberikan peringatan yang lebih akurat dan efektif, serta memastikan bahwa masyarakat di area terdampak mendapatkan informasi yang relevan dan tepat waktu.
								
								</label> 
								
							</div> 
							<div class="mt-4 d-flex align-items-center justify-content-between p-0" style="border-radius: 10px;">
								<div class="d-flex align-items-center">
								</div>
								<div>
									<a href="<?php echo APP_PATH; ?>/home/index/" class="btn btn-lg btn-primary me-1">Buat Laporan Baru</a>
									<a href="<?php echo APP_PATH; ?>/home/map/" class="btn btn-lg btn-primary">Tampilkan Map</a>
								</div>
							</div>
						
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

	
	<script>
        // Initialize DataTable
        $(document).ready(function () {
            $('#tbllaporanbencana').DataTable({
				responsive: true,
				searching: true, // Hide search feature
				lengthChange: true, // Hide show entries feature
				pageLength: 100, // Show 20 rows per page
				//"bInfo": false, // Hide "Showing 1 to N of N entries" info 
				//"bPaginate": false, // Disable pagination
				//"pagingType": "numbers", // Show page numbers only
				//"info": false, // Hide information summary
				//"bFilter": false, // Disable search box
				//"ordering": false, // Disable initial sorting
				//"bAutoWidth": false, // Disable auto width calculation
				
			}); 
			
			// Add margin to dataTables_filter to create space between search field and table
			$('.dataTables_filter').css('margin-bottom', '8px');
	
        });

    </script>
	
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  