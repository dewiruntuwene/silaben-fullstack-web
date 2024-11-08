
<section id="hero" class="d-flex flex-column justify-content-end align-items-center position-relative" style="margin-bottom: 50px;">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <!-- Slide 1 -->
    <div class="carousel-item active" style=" bottom:10%;">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">Data Pembatasan Area <span>Bencana</span></h2>
        <p class="animate__animated animate__fadeInUp">Data pembatasan area bencana dari laporan bencana relawan.</p>
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
							<h5>Hi <?php echo $data['user_name'];?>, explore data batasan area bencana !!!</h5>
						</div>
							<div class="mb-3">
								
								<!-- Table with stripped rows -->
							    <table id="tbllaporanbencana" class="table table-striped table-bordered table-sm border align-middle table-hover">
									<thead>
									  <tr>
										<th>
										  <b>NO</b>
										</th>
										<th></th>
										<th>Foto Bencana</th>
										<th>Jenis Bencana</th>
										<th>Lokasi</th>                  
										<th Waktu kejadian="date" data-format="YYYY/DD/MM">Start Date</th>
										<th>Description</th>
									  </tr>
									</thead>
									<tbody>
									  <tr>
										<th>
										  <b>1</b>
										</th>
										<td></td>
										<td></td>
										<td>Pohon Tumbang</td>
										<td>Airmadidi</td>
										<td>2024/02/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>2</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>likupang</td>
										<td>2024/01/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>3</b>
										</th>
										<td></td>
										<td></td>
										<td>Gempa Bumi</td>
										<td>Bitung</td>
										<td>2024/04/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>2</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>likupang</td>
										<td>2024/01/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>3</b>
										</th>
										<td></td>
										<td></td>
										<td>Gempa Bumi</td>
										<td>Bitung</td>
										<td>2024/04/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>2</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>likupang</td>
										<td>2024/01/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>3</b>
										</th>
										<td></td>
										<td></td>
										<td>Gempa Bumi</td>
										<td>Bitung</td>
										<td>2024/04/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>2</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>likupang</td>
										<td>2024/01/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>3</b>
										</th>
										<td></td>
										<td></td>
										<td>Gempa Bumi</td>
										<td>Bitung</td>
										<td>2024/04/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>2</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>likupang</td>
										<td>2024/01/11</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>3</b>
										</th>
										<td></td>
										<td></td>
										<td>Gempa Bumi</td>
										<td>Bitung</td>
										<td>2024/04/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>4</b>
										</th>
										<td></td>
										<td></td>
										<td>Pohon Tumbang</td>
										<td>Kairagi</td>
										<td>2024/12/10</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>5</b>
										</th>
										<td></td>
										<td></td>
										<td>Banjir</td>
										<td>Airmadidi bawah</td>
										<td>2024/08/01</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>6</b>
										</th>
										<td></td>
										<td></td>
										<td>Kerusuhan</td>
										<td>Airmadidi Atas</td>
										<td>2024/03/06</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>7</b>
										</th>
										<td></td>
										<td></td>
										<td>Pohon Tumbang</td>
										<td>Tumaluntung</td>
										<td>2024/01/10</td>
										<td>silahkan di lihat</td>
									  </tr>
									  <tr>
										<th>
										  <b>8</b>
										</th>
										<td></td>
										<td></td>
										<td>Kerusakan jalan</td>
										<td>Airmadidi</td>
										<td>2024/01/10</td>
										<td>silahkan di lihat</td>
									  </tr>
									</tbody>
							    </table>
							  <!-- End Table with stripped rows -->
								
								
								
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
				pageLength: 50, // Show 20 rows per page
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
  