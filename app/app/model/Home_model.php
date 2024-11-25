<?php  
class Home_model{
	private $db;

	private $redis;
	

	public function __construct(){
		$this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379); // Adjust IP and port if necessary
		// create object from database class
		$this->db = new Database;

		// check status
		if($this->db == false){
			//echo "<script>console.log('Connection failed.' );</script>";
		}else{
			//echo "<script>console.log('Connected successfully.' );</script>";
		}
	}

	// Generate unique id
	private function generate_unique_id() {
        return uniqid();
    }

	public function saveEmergencyReport($data) {
		$laporan_darurat_id = $this->generate_unique_id();
		$pelapor_id = $data['user_id'];
		$user_name = $data['user_name'];
		$email = $data['email'];
		$whatsapp_number = $data['whatsapp_number'];
		$gender = $data['gender'];
		$role = $data['role'];
		$latitude = $data['latitude'];
		$longitude = $data['longitude'];
		$lokasi = $data['lokasi'];
		$create_at = $data['created_at'];

		// Query untuk menyimpan laporan darurat
		$query = "INSERT INTO tbl_laporan_darurat 
				  (laporan_darurat_id, pelapor_id,user_name, gender, role, email, whatsapp_number, latitude, longitude, lokasi, created_at) 
				  VALUES ('$laporan_darurat_id', '$pelapor_id', '$user_name', '$gender', '$role', '$email','$whatsapp_number', '$latitude', '$longitude', '$lokasi', '$create_at')";
	
		// Eksekusi query
		$result = $this->db->query($query);
		
		// Tutup koneksi database
		$this->db->db_close();

		return $result ? true : false;
	}	

	// Save disaster data to Redis
	public function saveDisasterData($message, $level, $latitude, $longitude) {
		$timestamp = time();
		$formattedDate = date("Y-m-d H:i:s", $timestamp);
		$this->redis->hMSet('latest_disaster', [
			'message' => $message,
			'level' => $level,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'timestamp' => $formattedDate,
		]);
		$this->redis->expire('latest_disaster', 3600); // Optional expiration time
	}

	// Method to fetch disaster data from Redis
	public function getDisasterData() {
        return $this->redis->hGetAll('latest_disaster');
    }

	// Fungsi untuk memperbarui lokasi masyarakat di database
    public function updateUserLocation($user_id, $latitude, $longitude) {
        $updated_at = date('Y-m-d H:i:s');
        $sql = "UPDATE tbl_user 
                SET latitude = '$latitude', longitude = '$longitude'
                WHERE user_id = '$user_id'";

        return $this->db->query($sql); // Gunakan method query() dari kelas Database
    }

    // Fungsi untuk mendapatkan bencana yang berdekatan dengan lokasi pengguna
    public function getNearbyDisasters($userLat, $userLng) {
		$geofenceRadius = 8; // 8 km radius (6371 adalah radius bumi dalam km)
	
		// Pastikan nilai latitude dan longitude valid
		if (empty($userLat) || empty($userLng)) {
			return [];
		}
	
		// Haversine formula untuk menghitung jarak berdasarkan latitude dan longitude
		$sql = "SELECT *, 
					   (6371 * acos(cos(radians($userLat)) * cos(radians(latitude)) 
					   * cos(radians(longitude) - radians($userLng)) + sin(radians($userLat)) 
					   * sin(radians(latitude)))) AS distance 
				FROM tbl_laporan
				HAVING distance <= $geofenceRadius";
	
		$result = $this->db->query($sql);
		$nearbyDisasters = [];
	
		// Proses hasil query
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$nearbyDisasters[] = $row;
			}
		}
	
		return $nearbyDisasters;
	}


	// Fungsi untuk mendapatkan pengguna
	public function getUserById($user_id) {
		$sql = "SELECT * FROM tbl_user";
		$result = $this->db->query($sql); // Jalankan query

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row;
		}

		return null; // Return null jika tidak ada hasil
	}

	public function get_all_users() {
		// Query untuk mendapatkan semua data pengguna
		$query = $this->db->query("SELECT user_id, user_name, latitude, longitude, whatsapp_number FROM tbl_user");
	
		if ($query->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
			

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	

	public function updateUserLocationTemp($user_id, $latitude, $longitude) {
		// Query untuk menyimpan atau memperbarui lokasi di tabel sementara
		$query = "
			INSERT INTO tbl_user_location_temp (user_id, latitude, longitude) 
			VALUES ($user_id, $latitude, $longitude)
			ON DUPLICATE KEY UPDATE latitude = $latitude, longitude = $longitude";
		
		// Debugging: Tampilkan query untuk melihat jika ada yang salah
		echo $query; // Menampilkan query yang dijalankan
	
		$this->db->query($query);
	}
	
	

    // Fungsi untuk mendapatkan nomor WhatsApp pengguna
    public function getUserWhatsAppNumber($user_id) {
        $sql = "SELECT whatsapp_number FROM tbl_user WHERE user_id = '$user_id'";
        $result = $this->db->query($sql); // Jalankan query

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['whatsapp_number'];
        }

        return null; // Return null jika tidak ada hasil
    }

	// Fungsi untuk mendapatkan nomor WhatsApp pengguna dalam rentang geografis tertentu
	public function getNearbyUsersWhatsAppNumbers($disasterLat, $disasterLng, $radius = 10) {
		// Formula Haversine untuk menghitung jarak antar dua titik berdasarkan koordinat
		$sql = "
			SELECT 
				whatsapp_number, 
				(
					6371 * ACOS(
						COS(RADIANS($disasterLat)) * 
						COS(RADIANS(latitude)) * 
						COS(RADIANS(longitude) - RADIANS($disasterLng)) + 
						SIN(RADIANS($disasterLat)) * 
						SIN(RADIANS(latitude))
					)
				) AS distance 
			FROM tbl_user 
			HAVING distance <= $radius
		";
	
		$result = $this->db->query($sql); // Jalankan query
	
		$whatsappNumbers = [];
		if ($result->num_rows > 0) {
			// Ambil semua nomor WhatsApp pengguna dalam radius tertentu
			while ($row = $result->fetch_assoc()) {
				$whatsappNumbers[] = $row['whatsapp_number'];
			}
		}
	
		return $whatsappNumbers; // Return array nomor WhatsApp
	}
	



	public function get_total_reports() {
		$result = $this->db->query("
			SELECT COUNT(`pelapor_id`) AS total_report 
			FROM tbl_laporan;
		");
		// var_dump(($result));
		
		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['total_report'];
		} else {
			return 0; // Jika tidak ada hasil, kembalikan 0
		}

		$this->db->db_close();
	}

	public function get_total_reports_lembaga() {
		$nama_instansi = $_SESSION['user_name'];
		$result = $this->db->query("
			SELECT COUNT(`pelapor_id`) AS total_report FROM tbl_laporan WHERE lapor_instansi = '$nama_instansi'
		");
		// var_dump(($result));
		
		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['total_report'];
		} else {
			return 0; // Jika tidak ada hasil, kembalikan 0
		}

		$this->db->db_close();
	}

	// Get status laporan untuk dashboard
	public function get_reports_by_status($user_id) {
		$result = $this->db->query("
			SELECT 
				SUM(status = 'verified') AS verified,
				SUM(status = 'unverified') AS unverified
			FROM tbl_laporan;
		");
		return $result->fetch_assoc();
	}

		// Get status laporan untuk dashboard
		public function get_reports_by_status_lembaga($user_id) {
			$nama_instansi = $_SESSION['user_name'];
			$result = $this->db->query("
				SELECT 
					SUM(status = 'verified') AS verified,
					SUM(status = 'unverified') AS unverified
				FROM tbl_laporan WHERE lapor_instansi = '$nama_instansi';
			");
			return $result->fetch_assoc();
		}

	// Get Laporan yang paling sering untuk dashboard
	public function get_all_categories() {
		//$sql = "SELECT jenis_bencana, COUNT(*) as jumlah FROM tbl_laporan GROUP BY jenis_bencana";
		$result = $this->db->query("
			SELECT jenis_bencana, COUNT(*) as jumlah FROM tbl_laporan GROUP BY jenis_bencana
		");

		
		//Menyimpan data dalam array
		$all_categories = [];
		while ($row = $result->fetch_assoc()) {
			$all_categories[] = [
				'jenis_bencana' => $row['jenis_bencana'],
				'count' => $row['jumlah']
			];
		}

		$this->db->db_close();
	}

	// Get tren laporan untuk dashboard
	public function get_report_trends($user_id, $interval) {
		$result = $this->db->query("
			SELECT COUNT(*) as report_count, DATE_FORMAT(report_date, '%Y-%m-%d') as report_day 
			FROM tbl_laporan 
			GROUP BY report_day 
			ORDER BY report_day;
		");
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	// Ambil semua nomor relawan dari database
	public function get_all_volunteer_numbers() {
		$query = "SELECT no_whatsapp FROM tbl_relawan";
		$result = $this->db->query($query);

		$numbers = [];
		while ($row = $result->fetch_assoc()) {
			$numbers[] = $row['no_whatsapp'];
		}
		return $numbers;
		var_dump($numbers);
	}

	// Ambil semua nomor masyarakat dari database
	public function get_all_public_numbers() {
		$query = "SELECT whatsapp_number FROM tbl_user";
		$result = $this->db->query($query);

		$numbers = [];
		while ($row = $result->fetch_assoc()) {
			$numbers[] = $row['whatsapp_number'];
		}
		return $numbers;
		var_dump($numbers);
	}

	// Ambil data pelaporan terbaru
	// public function get_latest_report() {
	// 	// Query untuk mendapatkan laporan terbaru
	// 	$query = $this->db->query("SELECT * FROM tbl_laporan ORDER BY report_date DESC LIMIT 1");
	
	// 	if ($query->num_rows > 0) {
	// 		// konversi hasil query menjadi array asosiatif
	// 		$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
			

	// 		return $rows;
	// 	} else {
	// 		return []; // Empty array
	// 	}
	// }

	public function get_report($laporan_id) {
		// Query untuk mendapatkan laporan terbaru
		$query = $this->db->query("SELECT * FROM tbl_laporan WHERE laporan_id = '$laporan_id';");
	
		if ($query->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);
			

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	
	

	// Mendapatkan laporan yang belum dinotifikasi
    public function get_send_reports($laporan_id) {
        // Query untuk mendapatkan laporan yang belum dinotifikasi
        $sql = "SELECT * FROM tbl_laporan WHERE laporan_id = '$laporan_id'";

        // Eksekusi query
        $result = $this->db->query($sql);

        // Cek jika query gagal
        // if (!$result) {
        //     echo "Error in query: " . $this->db->error; // Ubah menjadi $this->db->connect_error jika perlu
        //     return array();
        // }

        // Tampilkan hasil query untuk memastikan hasilnya array
        $reports = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;  // Simpan setiap baris hasil query ke dalam array
            }
        }

        return $reports; // Mengembalikan array laporan yang belum dinotifikasi
    }

	public function get_all_users_with_coordinates() {
		// Query untuk mendapatkan semua pengguna dengan nomor telepon, latitude, dan longitude
		$sql = "SELECT user_id, whatsapp_number, latitude, longitude 
				FROM tbl_user";
		
		// Eksekusi query
		$query = $this->db->query($sql);

		return $query->fetch_assoc();
	}
	
	
	// Simpan data notifikasi ke tabel notifikasi
	public function save_notification($data) {
		$this->db->query("
			INSERT INTO notifications (laporan_id, user_id, status, message, created_at, updated_at)
			VALUES (?, ?, ?, ?, ?, ?)
		", array(
			$data['laporan_id'],
			$data['user_id'],
			$data['status'],
			$data['message'],
			$data['created_at'],
			$data['updated_at']
		));
	}

	// Update laporan untuk menandai bahwa notifikasi sudah dikirim
	public function mark_report_as_done($laporan_id, $status_laporan) { 
		// Query untuk memperbarui status_laporan dan level_bencana
		$sql = "UPDATE tbl_laporan SET status_laporan = '$status_laporan', level_bencana = '$status_laporan' WHERE laporan_id = '$laporan_id'";
		
		// Eksekusi query dengan langsung menggunakan variabel
		return $this->db->query($sql);
	}
	

	// Delete laporan
	public function delete_report($laporan_id) {
		// Query untuk memperbarui laporan sebagai sudah dinotifikasi
		$sql = "DELETE FROM tbl_laporan WHERE laporan_id = '$laporan_id'";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}


	// Update laporan untuk menandai bahwa notifikasi sudah dikirim
	public function mark_report_as_notified($laporan_id) {
		// Query untuk memperbarui laporan sebagai sudah dinotifikasi
		$sql = "UPDATE tbl_laporan SET is_notified = 1";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}

	public function mark_report_as_notified_relawan($laporan_id) {
		// Query untuk memperbarui laporan sebagai sudah dinotifikasi
		$sql = "UPDATE tbl_laporan SET is_notified_relawan = 1";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}

	public function mark_report_as_notified_masyarakat($laporan_id) {
		// Query untuk memperbarui laporan sebagai sudah dinotifikasi
		$sql = "UPDATE tbl_laporan SET is_notified_masyarakat = 1";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}
	

	// Get total lapora untuk dashboard
	// Menggunakan MySQLi
	// Home_model.php
	
	// insert data pelaporan
	public function insert_data_pelaporan($data){ 
		$laporan_id = $this->generate_unique_id();
		$deskripsi = $data['deskripsi'];
		$latitude = $data['latitude'];
		$longtitude = $data['longtitude'];
		$image = $data['image'];
		$status = $data['status'];

		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_laporan`(`laporan_id`, `deskripsi`, `latitude`, `longtitude`, `image`, `status`) 
										VALUES ('$laporan_id','$deskripsi','$latitude','$longtitude','user','$image','$status');");
			$this->db->db_close(); // Close database connection
			
			return $result; 
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}

	
	// insert data pelaporan dari web
	public function insert_data_pelaporan_web($id_laporan, $file_name, $data, $data_ai){ 
		session_start();
		$user_id = $_SESSION['user_id'];
		$user_name = $_SESSION['user_name'];
		$email = $_SESSION['email'];
		$user_role = $_SESSION['role'];

		var_dump($user_id);

		// Validate and sanitize user input
		$reportTitle = filter_input(INPUT_POST, 'report-title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$reportDescription = filter_input(INPUT_POST, 'report-description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$longitude = filter_input(INPUT_POST, 'input-long-location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$latitude = filter_input(INPUT_POST, 'input-lat-location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$location = filter_input(INPUT_POST, 'input-lokasi-bencana', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$agency = filter_input(INPUT_POST, 'lapor-instansi', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		// Get the current date and time
		$reportDate = filter_input(INPUT_POST, 'report-date', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Current date
		$reportTime = filter_input(INPUT_POST, 'report-time', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Current time
		$jumlah_relawan_dibutuhkan = filter_input(INPUT_POST, 'jumlah-relawan-dibutuhkan', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		// data AI
		$jenis_bencana = $data_ai['jenis_bencana'];
		$klasifikasi_bencana = $data_ai['klasifikasi_bencana'];
		$level_kerusakan_infrastruktur = $data_ai['level_kerusakan_infrastruktur'];
		$level_bencana = $data_ai['level_bencana'];
		$kesesuaian_laporan = $data_ai['kesesuaian_laporan'];
		$deskripsi_singkat_ai = $data_ai['deskripsi_singkat_ai'];
		$saran_singkat = $data_ai['saran_singkat'];
		$potensi_bahaya_lanjutan = $data_ai['potensi_bahaya_lanjutan'];
		$penilaian_akibat_bencana = $data_ai['penilaian_akibat_bencana'];
		$kondisi_cuaca = $data_ai['kondisi_cuaca'];
		$hubungi_instansi_terkait = $data_ai['hubungi_instansi_terkait'];
		
		// Cek status kesesuai laporan
		if($kesesuaian_laporan === "sesuai"){
			$isVerified = "verified";
		}else{
			$isVerified = "unverified";
		}

		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_laporan`(`laporan_id`, `pelapor_id`, `pelapor_role`, `pelapor_name`, `pelapor_email`, `report_title`, `report_description`, `latitude`, `longitude`, `lokasi_bencana`, `lapor_instansi`, `report_date`, `report_time`, `report_file_name_bukti`, `identity`, `status`, `jenis_bencana`, `klasifikasi_bencana`, `level_kerusakan_infrastruktur`, `level_bencana`, `kesesuaian_laporan`, `deskripsi_singkat_ai`, `saran_singkat`, `potensi_bahaya_lanjutan`, `penilaian_akibat_bencana`, `kondisi_cuaca`, `hubungi_instansi_terkait`, `jumlah_relawan_dibutuhkan`) 
			VALUES ('$id_laporan','$user_id','$user_role', '$user_name', '$email','$reportTitle','$reportDescription','$latitude','$longitude','$location','yes','$reportDate','$reportTime','$file_name','Not Anonymous','$isVerified', '$jenis_bencana', '$klasifikasi_bencana', '$level_kerusakan_infrastruktur', '$level_bencana', '$kesesuaian_laporan', '$deskripsi_singkat_ai', '$saran_singkat', '$potensi_bahaya_lanjutan', '$penilaian_akibat_bencana', '$kondisi_cuaca', '$hubungi_instansi_terkait', '$jumlah_relawan_dibutuhkan');");
			$this->db->db_close(); // Close database connection
			
			return true; 
		} catch (Exception $e) {
			//echo "Maaf terjadi kesalahan: " . $e->getMessage();
			return false;
		}
	}

	// insert data pelaporan dari mobile
	public function insert_data_pelaporan_mobile($id_laporan, $file_name, $data, $data_ai){ 
		// Validate and sanitize user input
		$reportTitle = filter_input(INPUT_POST, 'report-title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$reportDescription = filter_input(INPUT_POST, 'report-description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$longitude = filter_input(INPUT_POST, 'input-long-location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$latitude = filter_input(INPUT_POST, 'input-lat-location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$location = filter_input(INPUT_POST, 'input-lokasi-bencana', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$agency = filter_input(INPUT_POST, 'lapor-instansi', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$reportDate = filter_input(INPUT_POST, 'report-date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$reportTime = filter_input(INPUT_POST, 'report-time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		//$identity = filter_input(INPUT_POST, 'identity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$user_id = filter_input(INPUT_POST, 'user-id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$user_role = filter_input(INPUT_POST, 'user-role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$user_name = filter_input(INPUT_POST, 'user-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$jumlah_relawan_dibutuhkan = filter_input(INPUT_POST, 'jumlah-relawan-dibutuhkan', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// data AI
		$jenis_bencana = $data_ai['jenis_bencana'];
		$klasifikasi_bencana = $data_ai['klasifikasi_bencana'];
		$level_kerusakan_infrastruktur = $data_ai['level_kerusakan_infrastruktur'];
		$level_bencana = $data_ai['level_bencana'];
		$kesesuaian_laporan = $data_ai['kesesuaian_laporan'];
		$deskripsi_singkat_ai = $data_ai['deskripsi_singkat_ai'];
		$saran_singkat = $data_ai['saran_singkat'];
		$potensi_bahaya_lanjutan = $data_ai['potensi_bahaya_lanjutan'];
		$penilaian_akibat_bencana = $data_ai['penilaian_akibat_bencana'];
		$kondisi_cuaca = $data_ai['kondisi_cuaca'];
		$hubungi_instansi_terkait = $data_ai['hubungi_instansi_terkait'];

		// Cek status kesesuai laporan
		if($kesesuaian_laporan === "sesuai"){
			$isVerified = "verified";
		}else{
			$isVerified = "unverified";
		}
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_laporan`(`laporan_id`, `pelapor_id`, `pelapor_role`, `pelapor_name`, `pelapor_email`, `report_title`, `report_description`, `latitude`, `longitude`, `lokasi_bencana`, `lapor_instansi`, `report_date`, `report_time`, `report_file_name_bukti`, `identity`, `status`, `jenis_bencana`, `klasifikasi_bencana`, `level_kerusakan_infrastruktur`, `level_bencana`, `kesesuaian_laporan`, `deskripsi_singkat_ai`, `saran_singkat`, `potensi_bahaya_lanjutan`, `penilaian_akibat_bencana`, `kondisi_cuaca`, `hubungi_instansi_terkait`, `jumlah_relawan_dibutuhkan`) 
			VALUES ('$id_laporan','$user_id','$user_role', '$user_name', '$email','$reportTitle','$reportDescription','$latitude','$longitude','$location','$agency','$reportDate','$reportTime','$file_name','Not Anonymous','$isVerified', '$jenis_bencana', '$klasifikasi_bencana', '$level_kerusakan_infrastruktur', '$level_bencana', '$kesesuaian_laporan', '$deskripsi_singkat_ai', '$saran_singkat', '$potensi_bahaya_lanjutan', '$penilaian_akibat_bencana', '$kondisi_cuaca', '$hubungi_instansi_terkait', '$jumlah_relawan_dibutuhkan');");
			$this->db->db_close(); // Close database connection
			
			return true; 
		} catch (Exception $e) {
			//echo "Maaf terjadi kesalahan: " . $e->getMessage();
			return false;
		}
	}


	// tampilkan semua data pelaporan
	public function get_data_pelaporan(){
		$result = $this->db->query("select * from tbl_laporan;");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	// tampilkan semua data pelaporan
	public function get_data_pelaporan_web($user_id){
		$result = $this->db->query("select * from tbl_laporan where pelapor_id = '$user_id';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	public function get_all_data_pelaporan_web($user_id){
		$result = $this->db->query("select * from tbl_laporan where ;");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	// tampilkan semua data pelaporan admin
	public function get_data_pelaporan_web_admin(){
		$result = $this->db->query("SELECT * 
		FROM tbl_laporan 
		WHERE status != 'unverified' 
		AND status_laporan != 'SELESAI';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	//mengambil data dari tbl_laporan dan tbl_pendaftaran_relawan
	public function getLaporanWithRelawanDetails($relawan_id) {
		$query = "
			SELECT 
				l.report_title,
				l.report_file_name_bukti,
				l.report_description,
				l.lokasi_bencana,
				l.report_date,
				l.report_time,
				l.jenis_bencana,
				l.jumlah_relawan_dibutuhkan,
				l.status,
				r.relawan_id,
				r.alasan,
				r.status_pendaftaran,
				r.tanggal_daftar,
				rl.nama_relawan
			FROM tbl_pendaftaran_relawan r
			JOIN tbl_laporan l ON r.laporan_id = l.laporan_id
			JOIN tbl_relawan rl ON r.relawan_id = rl.relawan_id
			WHERE r.relawan_id = '$relawan_id'
			ORDER BY r.tanggal_daftar DESC;
		";
		
		$result = $this->db->query($query);
		$data = [];
	
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
		}
	
		$this->db->db_close(); // Tutup koneksi database
		return $data;
	}
	

	public function get_data_pelaporan_by_id($laporan_id){
		$result = $this->db->query("SELECT * 
		FROM tbl_laporan 
		WHERE laporan_id = '$laporan_id';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	// pendaftaran relawan
	public function tambah_pendaftaran_relawan($data) {
		$pendaftaran_id = $this->generate_unique_id();
		$relawan_id = $data['relawan_id'];
		$laporan_id = $data['laporan_id'];
		$alasan = $data['alasan'];

		$query = "INSERT INTO tbl_pendaftaran_relawan (pendaftaran_id, laporan_id, relawan_id, status_pendaftaran, alasan) 
				  VALUES ('$pendaftaran_id' ,'$laporan_id', '$relawan_id', 'PENDING', '$alasan');";
	
		$result = $this->db->query($query);
		$this->db->db_close(); // Close database connection
	
		if ($result) {
			return true; // Pendaftaran berhasil
		} else {
			return false; // Pendaftaran gagal
		}
	}

	public function decrease_jumlah_relawan($laporan_id) {
		// Query untuk mengurangi jumlah relawan yang dibutuhkan
		$sql = "UPDATE tbl_laporan SET jumlah_relawan_dibutuhkan = jumlah_relawan_dibutuhkan - 1 WHERE laporan_id = '$laporan_id' AND jumlah_relawan_dibutuhkan > 0";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}

	public function increase_jumlah_terdaftar($laporan_id) {
		// Query untuk meningkatkan jumlah relawan yang dibutuhkan
		$sql = "UPDATE tbl_laporan SET jumlah_relawan_terdaftar = jumlah_relawan_terdaftar + 1 WHERE laporan_id = '$laporan_id'";
		
		// Eksekusi query dengan bind parameter untuk keamanan
		return $this->db->query($sql, array($laporan_id));
	}	

	public function checkIfAlreadyRegistered($relawan_id, $laporan_id) {
		$query = "SELECT * FROM tbl_pendaftaran_relawan WHERE relawan_id = '$relawan_id' AND laporan_id = '$laporan_id'";
	
		$result = $this->db->query($query);
		$this->db->db_close(); // Close database connection
	
		if ($result->num_rows > 0) {
			return true; // User has already registered
		} else {
			return false; // User has not registered yet
		}
	}	

	public function getRelawanByLaporanId($laporan_id) {
		// Menyiapkan query untuk mengambil data relawan berdasarkan laporan_id
		$query = "SELECT * FROM tbl_pendaftaran_relawan WHERE laporan_id = '$laporan_id'";
		
		// Menjalankan query
		$result = $this->db->query($query);
		
		// Menutup koneksi database
		$this->db->db_close();
		
		// Mengembalikan hasil query
		if ($result) {
			return $result->fetch_all(MYSQLI_ASSOC); // Mengambil hasil sebagai array asosiatif
		} else {
			return false; // Jika tidak ada data
		}
	}


	public function getRelawanDetailsByLaporanId($laporan_id) {
		// Query untuk mendapatkan data relawan berdasarkan `relawan_id`
		$query = "
			SELECT 
				pr.pendaftaran_id,
				pr.laporan_id,
				pr.relawan_id,
				pr.alasan,
				pr.status_pendaftaran,
				pr.tanggal_daftar,
				pr.alasan,
				r.nik,
				r.alamat,
				r.tanggal_lahir,
				r.bidang_keahlian,
				r.nama_relawan,
				r.email,
				r.no_whatsapp,
				r.jenis_kelamin
			FROM 
				tbl_pendaftaran_relawan pr
			JOIN 
				tbl_relawan r ON pr.relawan_id = r.relawan_id
			WHERE 
				pr.laporan_id = '$laporan_id'
		";
	
		// Eksekusi query
		$result = $this->db->query($query);
	
		// Array untuk menyimpan data hasil query
		$data = [];
	
		if ($result && $result->num_rows > 0) {
			// Ambil semua data dan masukkan ke array
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
		}
	
		// Tutup koneksi database
		$this->db->db_close();
	
		// Kembalikan data sebagai array
		return !empty($data) ? $data : false;
	}

	public function update_status_daftar($pendaftaran_id, $status_pendaftaran) {
		// Query untuk memperbarui status_laporan dan level_bencana
		$sql = "UPDATE tbl_pendaftaran_relawan SET status_pendaftaran = '$status_pendaftaran' WHERE pendaftaran_id = '$pendaftaran_id'";
		
		// Eksekusi query dengan langsung menggunakan variabel
		return $this->db->query($sql);

	}
	
	
	

	// tampilkan semua data pelaporan lembaga
	public function get_data_pelaporan_web_lembaga($user_name) {
		// Ambil nama_instansi dari session
		$nama_instansi = $_SESSION['user_name'];
		//var_dump($nama_instansi);
		
		// Query untuk mengambil semua laporan
		$query = "SELECT * FROM tbl_laporan";
		$result = $this->db->query($query);
	
		// Tutup koneksi database
		$this->db->db_close();
	
		$rows = [];
		if ($result->num_rows > 0) {
			// Loop melalui hasil query
			while ($row = mysqli_fetch_assoc($result)) {
				// Pisahkan nilai kolom 'hubungi_instansi_terkait' dengan koma
				$instansiList = explode(',', $row['hubungi_instansi_terkait']);

				// Trim setiap instansi untuk menghilangkan spasi
				$instansiList = array_map('trim', $instansiList);

				// var_dump($instansiList);
				
				// Jika nama instansi ditemukan dalam array, tambahkan laporan ke dalam hasil
				if (in_array($nama_instansi, $instansiList)) {
					$rows[] = $row;
				}
			}
		}
	
		return $rows;

		// var_dump($instansi_list);
	}

	public function get_data_pelaporan_for_daftar_relawan($user_name) {
		// Ambil nama_instansi dari session
		$nama_instansi = $_SESSION['user_name'];
		//var_dump($nama_instansi);
		
		// Query untuk mengambil semua laporan
		$query = "SELECT * FROM tbl_laporan WHERE jumlah_relawan_terdaftar > 0";
		$result = $this->db->query($query);
	
		// Tutup koneksi database
		$this->db->db_close();
	
		$rows = [];
		if ($result->num_rows > 0) {
			// Loop melalui hasil query
			while ($row = mysqli_fetch_assoc($result)) {
				// Pisahkan nilai kolom 'hubungi_instansi_terkait' dengan koma
				$instansiList = explode(',', $row['hubungi_instansi_terkait']);

				// Trim setiap instansi untuk menghilangkan spasi
				$instansiList = array_map('trim', $instansiList);

				// var_dump($instansiList);
				
				// Jika nama instansi ditemukan dalam array, tambahkan laporan ke dalam hasil
				if (in_array($nama_instansi, $instansiList)) {
					$rows[] = $row;
				}
			}
		}
	
		return $rows;

		// var_dump($instansi_list);
	}
	
	

	// tampilkan semua data pelaporan
	public function show_data_pelaporan_map(){
		$result = $this->db->query("SELECT * 
		FROM tbl_laporan 
		WHERE status != 'unverified' 
		AND status_laporan = 'SEMENTARA TERJADI';
		");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}

	public function get_data_relawan(){
		$result = $this->db->query(("select * from tbl_relawan"));
		$this->db->db_close();

		if ($result->num_rows > 0) {
			
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

			$rows = array_reverse($rows);

			return $rows;
		} else {
			return [];
		}
	}

	public function get_data_pengguna(){
		$result = $this->db->query(("select * from tbl_user"));
		$this->db->db_close();

		if ($result->num_rows > 0) {
			
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

			$rows = array_reverse($rows);

			return $rows;
		} else {
			return [];
		}
		
	}
	// Mendapatkan semua deskripsi pengguna
public function tampilkan_user_name_pengguna() {
    // Query untuk mengambil semua deskripsi dari tabel tbl_user
    $query = "SELECT user_name FROM tbl_user";

    // Jalankan query
    $result = $this->db->query($query);
    
    // Tutup koneksi database
    $this->db->db_close();

    // Periksa apakah ada hasil
    if ($result->num_rows > 0) {
        // Ambil hasil sebagai array asosiatif
        $user_name = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Tambahkan deskripsi ke dalam array
            $user_name[] = $row['Name'];
        }

        // Kembalikan daftar deskripsi
        return $user_name;
    } else {
        // Jika tidak ada deskripsi, kembalikan array kosong
        return [];
    }
}
// Fungsi untuk mencari pengguna berdasarkan email
// public function get_user_by_email($email) {
// 	$query = $this->db->get_where('tbl_user', array('email' => $email));
// 	return $query->row_array();
// }

// Fungsi untuk menyimpan token reset password
// public function save_reset_token($email, $token) {
// 	$data = array(
// 		'reset_token' => $token
// 	);

// 	$this->db->where('email', $email);
// 	$this->db->update('tbl_user', $data);
// }
// Fungsi untuk menyimpan token reset ke database dan mengirim email
//     public function storeResetToken($email) {
//         $query = $this->db->get_where('tbl_user', array('email' => $email));
        
//         if ($query->num_rows() == 0) {
//             return false;
//         }

//         $token = bin2hex(random_bytes(50)); // Generate token unik
//         $data = array('reset_token' => $token);

//         $this->db->where('email', $email);
//         $this->db->update('tbl_user', $data);

//         $resetLink = base_url("home/resetPasswordForm/$token");
//         $subject = "Reset Password";
//         $message = "Klik link berikut untuk mereset password Anda: " . $resetLink;

//         return mail($email, $subject, $message);
//     }

//     // Fungsi untuk memperbarui password dengan token
//     public function updatePasswordWithToken($token, $new_password) {
//         $query = $this->db->get_where('tbl_user', array('reset_token' => $token));
        
//         if ($query->num_rows() == 0) {
//             return false;
//         }

//         $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
//         $data = array(
//             'user_password' => $hashed_password,
//             'reset_token' => NULL
//         );

//         $this->db->where('reset_token', $token);
//         return $this->db->update('tbl_user', $data);
//     }
// }
// // Fungsi untuk mengambil username pengguna
// public function tampilkan_user_name_pengguna() {
// 	// Query untuk mengambil semua username dari tabel tbl_user
// 	$query = "SELECT user_name FROM tbl_user";
// 	$result = $this->db->query($query);

// 	if ($result->num_rows() > 0) {
// 		// Ambil hasil sebagai array asosiatif
// 		$user_names = [];
// 		foreach ($result->result_array() as $row) {
// 			$user_names[] = $row['user_name'];
// 		}
// 		return $user_names;
// 	} else {
// 		// Jika tidak ada username, kembalikan array kosong
// 		return [];
// 	}
// }
// }
// // Menyimpan token reset password ke database
// public function storeResetToken($email, $token) {
// 	$stmt = $this->db->prepare("UPDATE tbl_user SET reset_token = ? WHERE email = ?");
// 	return $stmt->execute([$token, $email]);
// }

// // Memeriksa apakah token valid
// public function verifyResetToken($token) {
// 	$stmt = $this->db->prepare("SELECT * FROM tbl_user WHERE reset_token = ?");
// 	$stmt->execute([$token]);
// 	return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// // Mengupdate password dan menghapus token setelah reset berhasil
// public function resetPassword($token, $newPassword) {
// 	$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
// 	$stmt = $this->db->prepare("UPDATE tbl_user SET user_password = ?, reset_token = NULL WHERE reset_token = ?");
// 	return $stmt->execute([$hashedPassword, $token]);
// }
	/*
	

	// get total data
	public function get_summary_data($email_dosen, $role) {
		// total active class dari tbl_classes
		$activeClassesResult = $this->db->query("SELECT COUNT(*) as total_active_classes FROM tbl_classes WHERE status_class = 'active';");
		$activeClassesCount = $activeClassesResult->fetch_assoc()['total_active_classes'];

		// total archived class dari tbl_classes
		$archivedClassesResult = $this->db->query("SELECT COUNT(*) as total_archived_classes FROM tbl_classes WHERE status_class = 'complete';");
		$archivedClassesCount = $archivedClassesResult->fetch_assoc()['total_archived_classes'];

		// total mahasiswa dari tbl_students
		$totalStudentsResult = $this->db->query("SELECT COUNT(*) as total_students FROM tbl_students");
		$totalStudentsCount = $totalStudentsResult->fetch_assoc()['total_students'];

		// total lecturers dari tbl_operator dengan role = 'dosen'
		$totalLecturersResult = $this->db->query("SELECT COUNT(*) as total_lecturers FROM tbl_operator WHERE role = 'dosen';");
		$totalLecturersCount = $totalLecturersResult->fetch_assoc()['total_lecturers'];

		// Buat kosong untuk role yang lain.
		$role_query="";
		
		if($role == "dosen"){
			$role_query="AND email_lecturer = '$email_dosen'"; // jika dosen harus ambil sesuai data
		}

		// Student Attendance History
		$studentAttendanceResult = $this->db->query("SELECT COUNT(*) as total_attendance_history FROM tbl_attendance_history WHERE status IN ('P', 'L', 'E') $role_query;");
		$studentAttendanceCount = $studentAttendanceResult->fetch_assoc()['total_attendance_history'];

		// Student Attendance History (Present)
		$studentAttendanceResult = $this->db->query("SELECT COUNT(*) as total_present FROM tbl_attendance_history WHERE status IN ('P') $role_query;");
		$studentPresent = $studentAttendanceResult->fetch_assoc()['total_present'];

		// Student Attendance History (Late)
		$studentAttendanceResult = $this->db->query("SELECT COUNT(*) as total_late FROM tbl_attendance_history WHERE status IN ('L') $role_query;");
		$studentLate = $studentAttendanceResult->fetch_assoc()['total_late'];

		// Student Attendance History (Izin)
		$studentAttendanceResult = $this->db->query("SELECT COUNT(*) as total_izin FROM tbl_attendance_history WHERE status IN ('E') $role_query;");
		$studentIzin = $studentAttendanceResult->fetch_assoc()['total_izin'];

		$this->db->db_close(); // Tutup koneksi database

		// Mengembalikan data dalam bentuk array asosiatif
		return [
			'total_active_classes' => $activeClassesCount,
			'total_archived_classes' => $archivedClassesCount,
			'total_students' => $totalStudentsCount,
			'total_lecturers' => $totalLecturersCount,
			'total_history_attendance' => $studentAttendanceCount,
			'total_present' => $studentPresent,
			'total_late' => $studentLate,
			'total_izin' => $studentIzin,
		];
	}


	// check operator 
	public function check_operator($data)
	{
		$email = $data['email'];
		
		// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
		$result = $this->db->query("select * from tbl_operator where  email = '$email';");
		$this->db->db_close(); // Close database connection

		if ($result->num_rows > 0) {
			// convert to associative array
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		} else {
			return []; // kosong return false
		}
	}
	

	// tampilkan semua class dengan status active
	public function get_active_classes(){
		$result = $this->db->query("select * from tbl_classes where status_class = 'active';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}



	// tampilkan semua class dengan status archive/complete
	public function get_complete_classes(){
		$result = $this->db->query("select * from tbl_classes where status_class = 'complete';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}



	// tampilkan profile
	
	public function get_info_operator($data)
	{
		$email = $data['email'];
		$password = $data['password'];
		$role = $_POST['select-role'];
		// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
		$result = $this->db->query("select * from tbl_operator where role = '$role' AND email = '$email' AND BINARY password = '$password';");
		$this->db->db_close(); // Close database connection

		if ($result->num_rows > 0) {
			// convert to associative array
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		} else {
			return []; // kosong return false
		}
	}

	// check password status
	public function check_password($data){
		// variables
		$email = $data['email'];
		$password = $data['old_password'];

		// enkripsi password
		$pass_encripted = hash('md5', $password);
		
		// check password (case sensitive dengan menambahkan modifier BINARY sebelum kolom name)
		$result = $this->db->query("select password from tbl_operator where  email = '$email' AND BINARY password = '$pass_encripted';");
		$this->db->db_close(); // Close database connection

		if ($result->num_rows > 0) {
			// convert to associative array
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $rows;
		} else {
			return []; // kosong return false
		}
	}

	// update password
	public function update_password($data){
		// Time zone
		//date_default_timezone_set('Asia/Jakarta');
		date_default_timezone_set('Asia/Makassar');
		
		// variables
		$email = $data['email'];
		$password = $data['new_password'];

		// enkripsi password
		$pass_encripted = hash('md5', $password);

		// Sql query to update status
		$sql = "UPDATE tbl_operator SET `password`='$pass_encripted', `updated_at`=NOW() WHERE email = '$email';";

		if ($this->db->query($sql) === TRUE) {
			return true;
		} else {
			return false;
		}
	}
	
	
	*/
	
}
?>