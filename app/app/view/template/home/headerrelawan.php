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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/a/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/a/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="<?php echo APP_PATH; ?>/a/assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://unpkg.com/feather-icons"></script>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  
</head>
<body>
<header id="header" class="fixed-top d-flex align-items-center header-transparent">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1>
			<a href="<?php echo APP_PATH; ?>/home/indexrelawan/">
				SILABEN
			</a>
		</h1>
      </div>
      <nav id="navbar" class="navbar">
        <ul>
			
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/indexrelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/indexrelawan/">Home</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/aboutrelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/aboutrelawan/">About</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/maprelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/maprelawan/">Map</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/datarelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/datarelawan/">History Laporan</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/semuabencana') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/semuabencana/">Daftar Kegiatan Relawan</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/historykegiatanrelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/historykegiatanrelawan/">History Kegiatan Relawan</a></li>
          <li><a class="nav-link scrollto <?php if (strpos($_SERVER['REQUEST_URI'], '/home/profilerelawan') !== false) echo 'active'; ?>" href="<?php echo APP_PATH; ?>/home/profilerelawan/">Profile</a></li>
          <!-- <li class="dropdown"><a href="" class="<?php if (strpos($_SERVER['REQUEST_URI'], '/home/datarelawan') !== false or strpos($_SERVER['REQUEST_URI'], '/home/area') !== false) echo 'active'; ?>"><span>Services</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="<?php echo APP_PATH; ?>/home/data/">Status Data Laporan</a></li>
             
              <li><a href="<?php echo APP_PATH; ?>/home/area/">Data Batasan Area</a></li>
              <li><a href="<?php echo APP_PATH; ?>/home/area/">Change Password</a></li>
              <li><a href="<?php echo APP_PATH; ?>/home/profilemasyarakatrelawan/">Profile</a></li>
            </ul>
          </li> -->
       
			<?php
        if (isset($data['user_name'])) {
          $name_user = $data['user_name'];
          echo "<a class='nav-link scrollto active bg-danger text-white rounded px-3 py-2' href='".APP_PATH."/login/logout/'>SIGN OUT</a>";
        } else {
          echo "<a class='nav-link scrollto active bg-danger text-white rounded px-3 py-2' href='".APP_PATH."/login/index/'>SIGN IN</a>";
        }
      ?>
			
		  </li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

    </div>
</header>