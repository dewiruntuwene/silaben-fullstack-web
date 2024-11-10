<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SILABEN</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="<?php echo APP_PATH; ?>/login/assets/img/silabenlogo.png" rel="icon">
  <link href="<?php echo APP_PATH; ?>/login/assets/img/silabenlogo.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/login/assets/css/style.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
  
  <style>
	  .modal-backdrop {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1040;
		background-color: rgba(0, 0, 0, 0.6); /* Opacity backdrop */
	  }
	  
  </style>

<script>
    function validateForm(event) {
      event.preventDefault(); // Mencegah pengiriman form default

      // Ambil nilai email dan password dari input
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      // Regex untuk memvalidasi email
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      // Regex untuk memvalidasi password (minimal 8 karakter, satu huruf, satu angka)
      const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;

      // Cek validasi email
      if (!emailPattern.test(email)) {
        alert("Email tidak valid! Silakan masukkan email yang benar.");
        return false;
      }

      // Cek validasi password
      if (!passwordPattern.test(password)) {
        alert("Password tidak valid! Password harus minimal 8 karakter dan mengandung huruf serta angka.");
        return false;
      }

      // Jika semua validasi lolos, kirim formulir
      event.target.submit();
    }
</script>


</head>

<body style="background-image: url('<?php echo APP_PATH; ?>/login/assets/img/backroundlogin.jpg'); background-size: cover;">
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="<?php echo APP_PATH; ?>/login/assets/img/silabenlogo.png" alt="">
                  <span class="d-none d-lg-block">SILABEN</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create an account</p>
                  </div>

                  <!-- Nav Tabs -->
                  <ul class="nav nav-tabs justify-content-center" id="registerTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active fw-bold" id="masyarakat-tab" data-bs-toggle="tab" data-bs-target="#masyarakat" type="button" role="tab" aria-controls="masyarakat" aria-selected="true">
                        <i data-feather="user"></i> Daftar Anggota Reguler
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link fw-bold" id="relawan-tab" data-bs-toggle="tab" data-bs-target="#relawan" type="button" role="tab" aria-controls="relawan" aria-selected="false">
                        <i data-feather="users"></i> Daftar Relawan
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link fw-bold" id="lembaga-tab" data-bs-toggle="tab" data-bs-target="#lembaga" type="button" role="tab" aria-controls="lembaga" aria-selected="false">
                        <i data-feather="users"></i> Daftar Lembaga
                      </button>
                    </li>
                  </ul>

                  <!-- Tab Panes -->
                  <div class="tab-content pt-3" id="registerTabContent">
                    <!-- Masyarakat Tab -->
                    <div class="tab-pane fade show active" id="masyarakat" role="tabpanel" aria-labelledby="masyarakat-tab">
                      <form class="row g-3 needs-validation" novalidate method="POST" action="<?php echo APP_PATH; ?>/login/regist_reguler_process" onsubmit="return validateForm(event)">
                        <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="user"></i></span>
                          <input type="text" name="name" class="form-control" id="name" placeholder="Enter User Name" required>
                          <div class="invalid-feedback">Please, enter your name!</div>
                        </div>

                        <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="mail"></i></span>
                          <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email Address" required>
                          <div class="invalid-feedback">Please enter a valid Email address!</div>
                        </div>

                        <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="lock"></i></span>
                          <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                          <div class="invalid-feedback">Please enter your password!</div>
                        </div>

                        <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="user"></i></span>
                          <select name="gender" class="form-select" id="gender" placeholder="Choose Gender..." required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                          </select>
                          <div class="invalid-feedback">Please select your gender!</div>
                        </div>

                        <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="phone"></i></span>
                          <input type="tel" name="whatsapp_number" class="form-control" id="whatsapp_number" placeholder="Enter Whatsapp Number" required>
                          <div class="invalid-feedback">Please enter your WhatsApp number!</div>
                        </div>

                        <div class="col-12 d-flex justify-content-between">
                      <a href="<?php echo APP_PATH; ?>/" class="btn btn-danger w-100 me-1">
                      <i data-feather="home"></i> Home</a>
                      <button type="button" class="btn btn-danger w-100 me-1" data-bs-toggle="modal" data-bs-target="#helpModal"><i data-feather="help-circle"></i> Help</button>
                      <button class="btn btn-primary w-100" type="submit"><i data-feather="user-plus"></i> Daftar Reguler</button>
                    </div>  

                        <div class="col-12">
                          <p class="small mb-0">Already have an account? <a href="<?php echo APP_PATH; ?>/login/index">Log in</a></p>
                        </div>
                      </form>
                    </div>

                    <!-- Relawan Tab -->
                    <div class="tab-pane fade" id="relawan" role="tabpanel" aria-labelledby="relawan-tab">
                      <form class="row g-3 needs-validation" novalidate method="POST" action="<?php echo APP_PATH; ?>/login/regist_relawan_process" onsubmit="return validateForm(event)">
                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input type="text" name="nik" class="form-control" id="nik" placeholder="Enter NIK (No. KTP)" required>
                        <div class="invalid-feedback">Please, enter your NIK!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="user"></i></span>
                        <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Enter Full Name" required>
                        <div class="invalid-feedback">Please, enter your full name!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="user"></i></span>
                        <select name="genderrelawan" class="form-select" id="genderrelawan" placeholder="Choose Gender..." required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        </select>
                        <div class="invalid-feedback">Please select your gender!</div>
                      </div>

                      <div class="col-12">
                        <div class="input-group">
                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                          <span class="input-group-text">Birth Date:</span>
                        <input type="date" name="dob" class="form-control" id="dob" placeholder="Enter Date of Birth" required>
                        <div class="invalid-feedback">Please, enter your date of birth!</div>
                        </div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="mail"></i></span>
                        <input type="email" name="emailrelawan" class="form-control" id="emailrelawan" placeholder="Enter Email Address" required>
                        <div class="invalid-feedback">Please enter a valid Email address!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        <input type="text" name="current_address" class="form-control" id="current_address" placeholder="Enter Current Address" required>
                        <div class="invalid-feedback">Please, enter your current address!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="phone"></i></span>
                        <input type="tel" name="whatsapp_number_relawan" class="form-control" id="whatsapp_number_relawan" placeholder="Enter Whatsapp Number" required>
                        <div class="invalid-feedback">Please enter your WhatsApp number!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="briefcase"></i></span>
                        <input type="text" name="job" class="form-control" id="job" placeholder="Enter Job (expertise areas)" required>
                        <div class="invalid-feedback">Please enter your job/expertise!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="lock"></i></span>
                        <input type="password" name="passwordrelawan" class="form-control" id="passwordrelawan" placeholder="Enter Password" required>
                        <div class="invalid-feedback">Please enter your password!</div>
                      </div>

                      <div class="col-12 d-flex justify-content-between">
                        <a href="<?php echo APP_PATH; ?>/" class="btn btn-danger w-100 me-1">
                        <i data-feather="home"></i> Home</a>
                        <button type="button" class="btn btn-danger w-100 me-1" data-bs-toggle="modal" data-bs-target="#helpModal"><i data-feather="help-circle"></i> Help</button>
                        <button class="btn btn-primary w-100" type="submit"><i data-feather="user-plus"></i> Daftar Relawan</button>
                      </div>

                      <div class="col-12">
                        <p class="small mb-0">Already have an account? <a href="<?php echo APP_PATH; ?>/login/index">Log in</a></p>
                      </div>
                      </form>
                    </div>


                    <!-- Lembaga Tab -->
                    <div class="tab-pane fade" id="lembaga" role="tabpanel" aria-labelledby="lembaga-tab">
                      <form class="row g-3 needs-validation" novalidate method="POST" action="<?php echo APP_PATH; ?>/login/regist_lembaga_process" onsubmit="return validateForm(event)">
                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input type="text" name="nama_lembaga" class="form-control" id="nama_lembaga" placeholder="Enter Nama Lembaga" required>
                        <div class="invalid-feedback">Please, enter your name of institution!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="lock"></i></span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                        <div class="invalid-feedback">Please enter your password!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="mail"></i></span>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
                        <div class="invalid-feedback">Please enter a valid Email!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        <input type="text" name="jenis_lembaga" class="form-control" id="jenis_lembaga" placeholder="Enter Jenis Lembaga" required>
                        <div class="invalid-feedback">Please, enter your types of institutions!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="briefcase"></i></span>
                        <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Enter alamat" required>
                        <div class="invalid-feedback">Please enter your addresses!</div>
                      </div>

                      <div class="col-12 input-group">
                        <span class="input-group-text"><i data-feather="phone"></i></span>
                        <input type="text" name="nomor_telp" class="form-control" id="nomor_telp" placeholder="Enter Nomor Telepon" required>
                        <div class="invalid-feedback">Please enter your phone number!</div>
                      </div>

                      <div class="col-12 d-flex justify-content-between">
                        <a href="<?php echo APP_PATH; ?>/" class="btn btn-danger w-100 me-1">
                        <i data-feather="home"></i> Home</a>
                        <button type="button" class="btn btn-danger w-100 me-1" data-bs-toggle="modal" data-bs-target="#helpModal"><i data-feather="help-circle"></i> Help</button>
                        <button class="btn btn-primary w-100" type="submit"><i data-feather="user-plus"></i> Daftar Lembaga</button>
                      </div>

                      <div class="col-12">
                        <p class="small mb-0">Already have an account? <a href="<?php echo APP_PATH; ?>/login/index">Log in</a></p>
                      </div>
                      </form>
                    </div>
                  </div><!-- End Tab Content --> 
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
	
	<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true" data-backdrop="static">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="helpModalLabel">Help</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<p>Jika anda memerlukan bantuan dalam proses pendaftaran akun website SILABEN ini, silakan hubungi tim dukungan kami atau lihat beberapa pertanyaan umum di situs web kami. Terima Kasih :)</p>
			<ul>
			  <li><strong>Contact Email:</strong> support@silaben.com</li>
			  <li><strong>Phone:</strong> +62 811 8432 446</li>
			  <li><strong>FAQs:</strong> <a href="https://silaben.com/faqs" target="_blank">silaben.com/faqs</a></li>
			</ul>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>

	
  </main><!-- End #main -->


	

  <script src="<?php echo APP_PATH; ?>/login/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <script>
    feather.replace()
  </script>
</body>

</html>
