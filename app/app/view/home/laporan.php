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
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      <b>NO</b>
                    </th>
                    <th></th>
                    <th>Jenis Bencana</th>
                    <th>Lokasi</th>                  
                    <th Waktu kejadian="date" data-format="YYYY/DD/MM">Start Date</th>
                    <th>Description</th>
                  </tr>
                </thead>
                  <?php $count = 1; 
                  foreach($data['datalaporan'] as $laporan): ?>
                    <tr>
                    <th scope="row"><?= $count ?></th>
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
