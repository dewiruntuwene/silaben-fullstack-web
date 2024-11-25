<main id="main" class="main">

<div class="pagetitle">
  <h1>Data Relawan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Data Relawan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title text-center">Daftar Relawan</h5>
          <!-- Table with stripped rows -->
          <!-- Custom Search Bar -->
          <div class="mb-3">
              <input type="text" id="searchInput" class="form-control" placeholder="Cari relawan...">
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle datatable">
                <thead class="table-primary text-center">
                  <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Relawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Ketersediaan</th>
                    <th>Bidang Keahlian</th>
                    <th>Registered Date</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Nomor Whatsapp</th>
                    <th>Email</th>
                  </tr>
                </thead>
                <tbody>

            <?php 
              // Check if 'datarelawan' key exists and is an array or object before processing
              if (isset($data['datarelawan']) && (is_array($data['datarelawan']) || is_object($data['relawan']))) {
                  $count = 1; 
                  foreach ($data['datarelawan'] as $relawan): ?>
                      <tr>
                          <th scope="row"><?= $count ?></th>
                          <td>
                                    <div>
                                      <p class="fw-bold mb-1"><?= strtoupper($relawan['user_name']); ?></p>
                                      <p class="text-muted mb-0 small" style="font-size: 11px;">
                                        <span class='text-primary' data-feather='phone' style="width: 16px; height: 16px;"></span> 
                                        <?= $relawan['nomor_whatsapp']; ?>
                                      </p>
                                      <p class="text-muted mb-0 small" style="font-size: 11px;">
                                        <span class='text-primary' data-feather='mail' style="width: 16px; height: 16px;"></span> 
                                        <?= $relawan['email']; ?>
                                      </p>
                                    </div>
                                  </td>
                          <td><?= strtoupper($relawan['nama_relawan']); ?></td>
                          <td><?= $relawan['jenis_kelamin']; ?></td>
                          <td><?= $relawan['ketersediaan']; ?></td>
                          <td><?= $relawan['bidang_keahlian']; ?></td>
                          <td><?= $relawan['registered_date']; ?></td>
                          <td><?= $relawan['tanggal_lahir']; ?></td>
                          <td><?= $relawan['alamat']; ?></td>
                          <td><?= $relawan['no_whatsapp']; ?></td>
                          <td><?= $relawan['email']; ?></td>
                          <td class="text-center">
                              <?php
                              if ($relawan['status'] === "active") {
                                  echo "<span class='badge bg-success rounded-pill d-inline-block'>".$relawan['status']."</span>";
                              } else {
                                  echo "<span class='badge bg-danger rounded-pill d-inline-block'>".$relawan['status']."</span>";
                              }
                              ?>
                          </td>
                      </tr>
                      <?php 
                      $count++;
                  endforeach; 
              } else {
                  echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
              }
              ?>   
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

</main><!-- End #main -->
