
<section id="hero" class="d-flex flex-column justify-content-end align-items-center position-relative" style="margin-bottom: 50px;">
  <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">
    <!-- Slide 1 -->
    <div class="carousel-item active" style=" bottom:10%;">
      <div class="carousel-container">
        <h2 class="animate__animated animate__fadeInDown">Status Laporan <span>Bencana</span></h2>
        <p class="animate__animated animate__fadeInUp">Data laporan bencana yang di submit ke dalam sistem.</p>
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
							<h5>Hi <?php echo $data['user_name'];?>, manage data laporan bencana anda !!!</h5>
						</div>
							<div class="mb-3">
							
							<?php 
								if(empty($data['datalaporan'])){
									echo "<h6>Maaf, anda belum pernah melaporkan kejadian bencana.</h6>";
								} else {
							?>							
								<!-- Table with stripped rows -->
								<table id="tbllaporanbencana" class="table table-striped table-bordered table-sm border align-middle table-hover">
									<thead>
										<tr>
											<th class="text-center ps-0 pe-0" width="1%">No</th>
											<th class="text-center" width="40%">Laporan Bencana</th>
											<th class="text-center" width="40%">AI Reviewed</th>                  
											<th class="text-center" width="5%">Bukti</th>
											<th class="text-center" width="3%">Status</th>
                                                                                        <th class="text-center" width="3%">Status Laporan</th>
											<th class="text-center ps-0 pe-0" width="3%">#</th> <!-- Kolom Baru -->
										</tr>
									</thead>
									<tbody>
										<?php 
										$count = 1;
										foreach($data['datalaporan'] as $laporan): ?>
											<tr>
												<th class="text-center"><?php echo $count;?></th>
												<td>
													<div>
														<p class="fw-bold mb-1"><?php echo strtoupper($laporan['report_title']); ?></p> 
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='layers' style="width: 16px; height: 16px;"></span> 
															<?php echo $laporan['report_description'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='map-pin' style="width: 16px; height: 16px;"></span> 
															<?php echo $laporan['lokasi_bencana'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='calendar' style="width: 16px; height: 16px;"></span> 
															<?php echo $laporan['report_date'].", ".$laporan['report_time'];?>
														</p>
													</div>
												</td>
												<td>
													<div>
														<p class="mb-1" style="font-size: 12px;">
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='home' style="width: 16px; height: 16px;"></span> 
															<?php echo "<b>Jenis Bencana: </b>".$laporan['jenis_bencana'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='clipboard' style="width: 16px; height: 16px;"></span> 
															<?php echo "<b>Klasifikasi Bencana: </b>".$laporan['klasifikasi_bencana'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='tool' style="width: 16px; height: 16px;"></span> 
															<?php echo "<b>Level Kerusakan Infrastruktur: </b>".$laporan['level_kerusakan_infrastruktur'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='check-circle' style="width: 16px; height: 16px;"></span> 
															<?php echo "<b>Kesesuaian Laporan: </b>".$laporan['kesesuaian_laporan'];?>
														</p>
														<p class="text-muted mb-0 small" style="font-size: 11px;">
															<span class='text-primary' data-feather='info' style="width: 16px; height: 16px;"></span> 
															<?php echo $laporan['deskripsi_singkat_ai']." Warga juga sarankan untuk ".$laporan['saran_singkat'];?>
														</p>
													</div>
												</td>
												<td class="text-center">
													<img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti'];?>" class="img-thumbnail preview-image" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti'];?>" style="width: 100px; cursor: pointer;">
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
												<td class="text-center"> <!-- Colomn Action -->
													<div class="dropdown">
														<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $count;?>" data-bs-toggle="dropdown" aria-expanded="false"></button>
														<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $count;?>">
															<li><a class="dropdown-item" href="#">Ubah status laporan</a></li>
															<li><a class="dropdown-item" href="#">Hapus laporan</a></li>
														</ul>
													</div>
												</td>
											</tr>
											<?php 
											$count++;
										endforeach; ?>  
									</tbody>
								</table>
								<!-- End Table with stripped rows -->

								
							<?php 
								}
							?>	
								
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

<!-- Modal preview gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Foto Bukti Bencana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!--
		<img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti'];?>" class="img-fluid">
		-->
		<img id="modalImage" src="" class="img-fluid">
      </div>
    </div>
  </div>
</div>



	
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


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  