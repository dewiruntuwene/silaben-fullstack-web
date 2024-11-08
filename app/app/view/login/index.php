<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SILABEN</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="<?php echo APP_PATH; ?>/src/assets/img/silabenlogo.png" rel="icon">
  <link href="<?php echo APP_PATH; ?>/src/assets/img/silabenlogo.png" rel="apple-touch-icon">
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
</head>
<body style="background-image: url('<?php echo APP_PATH; ?>/login/assets/img/backroundlogin.jpg'); background-size: cover;">
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?php echo APP_PATH; ?>/" class="logo d-flex align-items-center w-auto">
                  <img src="<?php echo APP_PATH; ?>/login/assets/img/silabenlogo.png" alt="">
                  <span class="d-none d-lg-block">SILABEN</span>
                </a> 
              </div>

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-4">
                    <h5 class="card-title text-center pb-0 fs-4"><strong><u>Si</u></strong>gap <strong><u>La</u></strong>por <strong><u>Ben</u></strong>cana</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate method="POST" action="<?php echo APP_PATH; ?>/login/process">

                    <div class="col-12 input-group">
                          <span class="input-group-text"><i data-feather="clipboard"></i></span>
                          <select name="role" class="form-select" id="role" required>
                            <option value="user">Anggota Reguler</option>
                            <option value="relawan">Relawan</option>
                            <option value="admin">Admin</option>
                            <option value="lembaga">Lembaga</option>
                          </select>
                    </div>

                        
					<div class="col-12">
					  <div class="input-group has-validation">
						<span class="input-group-text"><i data-feather="users"></i></span>
						<input type="text" name="name" class="form-control" id="name" placeholder="Enter User Name" required>
						<div class="invalid-feedback">Please enter your username.</div>
					  </div>
					</div>

                    <div class="col-12">
                      <div class="input-group has-validation">
						<span class="input-group-text"><i data-feather="lock"></i></span>
						<input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
						<div class="invalid-feedback">Please enter your password!</div>
					   </div>
                    </div>

                    <div class="col-12 d-flex justify-content-between">
					  <a href="<?php echo APP_PATH; ?>/" class="btn btn-primary w-100 me-1"><i data-feather="home" class="mt-2 mb-1"></i><br>Back to Home</a>
					  <button class="btn btn-primary w-100" type="submit"><i data-feather="user" class="mt-2 mb-1"></i><br>Sign In Account</button>
					</div>


                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="<?php echo APP_PATH; ?>/login/register">Create an account</a></p>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/apexcharts/apexcharts.min.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/chart.js/chart.umd.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/echarts/echarts.min.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/quill/quill.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/simple-datatables/simple-datatables.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/tinymce/tinymce.min.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/vendor/php-email-form/validate.js"></script>
	<script src="<?php echo APP_PATH; ?>/login/assets/js/main.js"></script>
	<script>
		feather.replace()
	</script>
</body>
</html> 