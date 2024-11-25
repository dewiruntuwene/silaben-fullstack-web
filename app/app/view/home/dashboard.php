

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Total Reports -->
          <div class="col-12">
            <div class="card info-card">

              <div class="card-body">
                <h5 class="card-title">Jumlah Total Laporan Bencana</h5>
                <div class="d-flex align-items-center">
                  <div class="ps-3">
                  <h6>
                      <?php 
                      // Directly output the total report count
                      if (isset($data['total_report']) && is_numeric($data['total_report'])) {
                          echo htmlspecialchars($data['total_report']); // Output safely
                      } else {
                          // Default value if 'total_report' is not set or invalid
                      }
                      ?>
                  </h6>

                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Total Reports -->

          <!-- Reports by Status -->
          <div class="col-12">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Status Laporan</h5>

                <div class="d-flex justify-content-between">
                  <div>
                    <p>Verified</p>
                    <h6>
                      <?php 
                        // Output jumlah laporan yang terverifikasi
                        if (isset($data['reports_by_status']['verified'])) {
                            echo htmlspecialchars($data['reports_by_status']['verified']); 
                        } else {
                            echo "0"; // Default value if not set
                        }
                      ?>
                    </h6>
                  </div>
                  <div>
                    <p>Unverified</p>
                    <h6>
                      <?php 
                        // Output jumlah laporan yang terverifikasi
                        if (isset($data['reports_by_status']['unverified'])) {
                            echo htmlspecialchars($data['reports_by_status']['unverified']); 
                        } else {
                            echo "0"; // Default value if not set
                        }
                      ?>
                    </h6>
                  </div>
                </div>

              </div>

            </div>
          </div><!-- End Reports by Status -->

          <!-- Top Categories -->
          <div class="col-12">
              <div class="card">
                  <div class="card-body">
                      <h5 class="card-title">Kategori Laporan Terbanyak</h5>
                      <div id="categoryChart" style="min-height: 400px;" class="echart"></div>
                      <script>
                          document.addEventListener("DOMContentLoaded", () => {
                              console.log("Script loaded and DOMContentLoaded fired");
                              echarts.init(document.querySelector("#categoryChart")).setOption({
                                  tooltip: {
                                      trigger: 'item'
                                  },
                                  legend: {
                                      top: '5%',
                                      left: 'center'
                                  },
                                  series: [{
                                      name: 'Kategori',
                                      type: 'pie',
                                      radius: '50%',
                                      data: [
                                          <?php foreach ($all_categories as $category) { ?>
                                              { value: <?= $category['count'] ?>, name: '<?= $category['jenis_bencana'] ?>' },
                                          <?php } ?>
                                      ],
                                      emphasis: {
                                          itemStyle: {
                                              shadowBlur: 10,
                                              shadowOffsetX: 0,
                                              shadowColor: 'rgba(0, 0, 0, 0.5)'
                                          }
                                      }
                                  }]
                              });
                          });
                      </script>
                  </div>
              </div>
          </div>
          <!-- End Top Categories -->

        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Report Trends -->
        <!-- <div class="card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>

              <li><a class="dropdown-item" href="#">Harian</a></li>
              <li><a class="dropdown-item" href="#">Mingguan</a></li>
              <li><a class="dropdown-item" href="#">Bulanan</a></li>
            </ul>
          </div>

          <div class="card-body pb-0">
            <h5 class="card-title">Tren Laporan Bencana <span>| Mingguan</span></h5>

            <div id="reportTrendsChart" style="min-height: 400px;" class="echart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                echarts.init(document.querySelector("#reportTrendsChart")).setOption({
                  tooltip: {
                    trigger: 'axis'
                  },
                  xAxis: {
                    type: 'category',
                    data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                  },
                  yAxis: {
                    type: 'value'
                  },
                  series: [{
                    data: [120, 200, 150, 80, 70, 110, 130],
                    type: 'line',
                    smooth: true
                  }]
                });
              });
            </script>

          </div>
        </div> -->
        <!-- End Report Trends -->

      </div><!-- End Right side columns -->

    </div>
  </section>

</main><!-- End #main -->
