<main id="main" class="main">

<div class="pagetitle">
  <h1>Data Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Data Pengguna</li>
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
                <th>Nama</th>
                <th>Gender</th>
                <th>Role</th>                  
                <th>Nomor Whatsapp </th>
                <th>Email</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <?php 
              // Check if 'datapengguna' key exists and is an array or object before processing
              if (isset($data['datapengguna']) && (is_array($data['datapengguna']) || is_object($data['datapengguna']))) {
                  $count = 1; 
                  foreach ($data['datapengguna'] as $pengguna): ?>
                      <tr>
                          <th scope="row"><?= $count ?></th>
                          <td>
                                    <div>
                                      <p class="fw-bold mb-1"><?= strtoupper($pengguna['user_name']); ?></p>
                                      <p class="text-muted mb-0 small" style="font-size: 11px;">
                                        <span class='text-primary' data-feather='phone' style="width: 16px; height: 16px;"></span> 
                                        <?= $pengguna['nomor_whatsapp']; ?>
                                      </p>
                                      <p class="text-muted mb-0 small" style="font-size: 11px;">
                                        <span class='text-primary' data-feather='mail' style="width: 16px; height: 16px;"></span> 
                                        <?= $pengguna['email']; ?>
                                      </p>
                                    </div>
                                  </td>
                          <td><?= strtoupper($pengguna['user_name']); ?></td>
                          <td><?= $pengguna['gender']; ?></td>
                          <td><?= $pengguna['role']; ?></td>
                          <td><?= $pengguna['nomor_whatsapp']; ?></td>
                          <td><?= $pengguna['email']; ?></td>
                          <td class="text-center">
                              <?php
                              if ($pengguna['status'] === "active") {
                                  echo "<span class='badge bg-success rounded-pill d-inline-block'>".$pengguna['status']."</span>";
                              } else {
                                  echo "<span class='badge bg-danger rounded-pill d-inline-block'>".$pengguna['status']."</span>";
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

