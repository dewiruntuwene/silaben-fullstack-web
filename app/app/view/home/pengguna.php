<main id="main" class="main">

<div class="pagetitle">
  <h1>Data Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Data Pengguna</li>
    </ol>
  </nav>
  <div class="mb-3">
   <input type="text" id="searchInput" class="form-control" placeholder="Cari pengguna...">
</div>
</div><!-- End Page Title -->

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <!-- Table -->
              <div class="table-responsive">
                <table id="userTable" class="table table-striped table-hover align-middle">
                <thead class="text-center">
                    <tr>
                      <th>NO</th>
                      <th>Nama</th>
                      <th>Gender</th>
                      <th>Role</th>
                      <th>Nomor Whatsapp</th>
                      <th>Email</th>
                      <th>Status</th>
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
			  <td><?= $pengguna['user_name']; ?></td>
                          <td><?= $pengguna['gender']; ?></td>
                          <td><?= $pengguna['role']; ?></td>
                          <td><?= $pengguna['whatsapp_number']; ?></td>
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


<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#userTable tbody tr');

    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)')?.innerText.toLowerCase() || '';
        const gender = row.querySelector('td:nth-child(3)')?.innerText.toLowerCase() || '';
        const role = row.querySelector('td:nth-child(4)')?.innerText.toLowerCase() || '';
        const whatsapp = row.querySelector('td:nth-child(5)')?.innerText.toLowerCase() || '';
        const email = row.querySelector('td:nth-child(6)')?.innerText.toLowerCase() || '';

        if (name.includes(searchValue) || gender.includes(searchValue) || role.includes(searchValue) || whatsapp.includes(searchValue) || email.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>


