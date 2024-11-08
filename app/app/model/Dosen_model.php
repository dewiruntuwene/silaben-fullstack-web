<?php  
class Dosen_model{
	private $db;

	public function __construct(){
		// create object from database class
		$this->db = new Database;

		// check status
		if($this->db == false){
			//echo "<script>console.log('Connection failed.' );</script>";
		}else{
			//echo "<script>console.log('Connected successfully.' );</script>";
		}
	}

	
	// function to create new clas
	public function create_new_class($data){
		$id_unique = $data['id-unique-class'];
		$email_lecturer = $data['email-lecturer'];
		$code_class = $data['code-class'];
		$name_subject = $data['subject-name'];
		$name_lecturer = $data['lecturer-name'];
		$fakultas = $data['facultas'];
		$prodi = $data['prodi'];
		$sks = $data['sks'];
		$room_number = $data['building-room-number'];
		$room_lat = $data['room-latitude'];
		$room_long = $data['room-longitude'];
		$jadwal_class = $data['jadwal-day-time'];

		// Time zone
		//date_default_timezone_set('Asia/Jakarta');
		date_default_timezone_set('Asia/Makassar');
		
		$sql = "INSERT INTO tbl_classes (`id_class`, `email_lecturer`, `code_class`, `name_subject`, `name_lecturer`, `fakultas`, `prodi`, `sks`, `building_room`, `room_latitude`, `room_longitude`, `jadwal_class_day_time`, `daftar_email_student`, `created_at`, `status_class`) VALUES ('$id_unique', '$email_lecturer', '$code_class', '$name_subject', '$name_lecturer', '$fakultas', '$prodi', '$sks', '$room_number', '$room_lat', '$room_long', '$jadwal_class', '', NOW(), 'active')";
		if($this->db->query($sql) === TRUE) {
		  return true;
		} else {
		  return false;
		}
	}

	// tampilkan semua class dengan status active
	public function get_active_classes($lecturer_email){
		// date today
		date_default_timezone_set('Asia/Makassar');
		$gettoday = date('d M Y'); // format "04 May 2023"
		
		//$result = $this->db->query("select * from tbl_classes where status_class = 'active';");
		//$result = $this->db->query("select * from tbl_classes where status_class = 'active' AND email_lecturer = '$lecturer_email';");
		
		//$result = $this->db->query("SELECT 
		//								c.*, 
		//								COUNT(a.id_attendance) AS total_meetings
		//							FROM 
		//								tbl_classes c
		//							LEFT JOIN 
		//								tbl_attendance_list a ON c.id_class = a.id_class
		//							WHERE 
		//								c.status_class = 'active' 
		//								AND c.email_lecturer = '$lecturer_email' 
		//								AND STR_TO_DATE(a.`date_attendance`, '%d %M %Y') <= STR_TO_DATE('$gettoday', '%d %M %Y')
		//							GROUP BY 
		//								c.id_class;
		//							");
		
		$result = $this->db->query("SELECT 
										c.*, 
										COUNT(a.id_attendance) AS total_meetings
									FROM 
										tbl_classes c
									LEFT JOIN 
										tbl_attendance_list a ON c.id_class = a.id_class AND a.shared_public = 'yes'
									WHERE 
										c.status_class = 'active' 
										AND c.email_lecturer = '$lecturer_email' 
									GROUP BY 
										c.id_class;
									");
		
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			//$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	
	// tampilkan semua class dengan status active untuk WF page
	public function get_active_classes_for_wf($lecturer_email){
		// date today 
		date_default_timezone_set('Asia/Makassar');
		$gettoday = date('d M Y'); // format "04 May 2023"
		
		$result = $this->db->query("SELECT 
										c.*, 
										COUNT(a.id_attendance) AS total_meetings
									FROM 
										tbl_classes c
									LEFT JOIN 
										tbl_attendance_list a ON c.id_class = a.id_class AND a.shared_public = 'yes'
									WHERE 
										c.status_class = 'active' 
										AND c.email_lecturer = '$lecturer_email'
									GROUP BY 
										c.id_class;
									");
		
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			//$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	
	// Untuk Kesesuaian RPS
	public function search_active_classes($lecturer_email, $key_search){ 
		// date today
		date_default_timezone_set('Asia/Makassar');
		$gettoday = date('d M Y'); // format "04 May 2023"
		
		
		// check apakah $key_search adalah string yang tidak kosong dan tidak hanya berisi white space
		if (!empty(trim($key_search))) {
			// Kode query SQL 
			if($key_search == "BUKAN-SEARCH"){
				//$result = $this->db->query("select * from tbl_classes where status_class = 'active' AND `fakultas` LIKE '%komputer%' LIMIT 20;"); 
				$result = $this->db->query("SELECT 
												c.*, 
												COUNT(a.id_attendance) AS total_meetings
											FROM 
												tbl_classes c
											LEFT JOIN 
												tbl_attendance_list a ON c.id_class = a.id_class
											WHERE 
												c.status_class = 'active' 
												AND c.fakultas LIKE '%komputer%' 
											GROUP BY 
												c.id_class
											LIMIT 50;"); 
			}else{
				//$result = $this->db->query("select * from tbl_classes where status_class = 'active' AND email_lecturer LIKE '%$key_search%' OR `name_lecturer` LIKE '%$key_search%' OR `name_subject` LIKE '%$key_search%' OR `fakultas` LIKE '%$key_search%' OR `code_class` LIKE '%$key_search%' LIMIT 20;");
				$result = $this->db->query("SELECT 
												c.*, 
												COUNT(a.id_attendance) AS total_meetings
											FROM 
												tbl_classes c
											LEFT JOIN 
												tbl_attendance_list a ON c.id_class = a.id_class
											WHERE 
												c.status_class = 'active' 
												AND c.email_lecturer LIKE '%$key_search%' 
												OR c.`name_lecturer` LIKE '%$key_search%' 
												OR c.`name_subject` LIKE '%$key_search%' 
												OR c.`fakultas` LIKE '%$key_search%' 
												OR c.`code_class` LIKE '%$key_search%' 
											GROUP BY 
												c.id_class
											LIMIT 50;");
			}
			
			$this->db->db_close(); // Menutup koneksi database

			if ($result->num_rows > 0) {
				// Mengonversi ke array asosiatif
				$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
				// balik urutan baris
				//$rows = array_reverse($rows);
				return $rows;
			} else {
				return []; // Array kosong
			}
		} else {
			// $key_search kosong atau hanya berisi whitespace
			return [];
		}
	}
	
	// Count All active classes
	public function get_count_data_classes() { 
		// Sql query 
		$check_sql = "SELECT COUNT(*) as total FROM tbl_classes where status_class = 'active';";
		
		// run query
		$result = $this->db->query($check_sql);
		$row = $result->fetch_assoc();
		
		return $row['total'];
	}
	

	// tampilkan semua class dengan status archive/complete
	public function get_complete_classes($lecturer_email){
		//$result = $this->db->query("select * from tbl_classes where status_class = 'complete';");
		$result = $this->db->query("select * from tbl_classes where status_class = 'complete' AND email_lecturer = '$lecturer_email';");
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


	// tampilkan class info
	public function get_info_classes($id){
		$result = $this->db->query("select * from tbl_classes where id_class = '$id';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
			// balik urutan baris
			//$rows = array_reverse($rows);

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	
	// tampilkan class untuk update
	public function get_update_class($id){
		$result = $this->db->query("select * from tbl_classes where id_class = '$id' AND status_class = 'active';");
		$this->db->db_close(); // Close database connection
		
		if ($result->num_rows > 0) {
			// konversi hasil query menjadi array asosiatif
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $rows;
		} else {
			return []; // Empty array
		}
	}
	
	// function to update class info
	public function update_class_info($data){
		$id_unique = $data['id-unique-class'];
		$email_lecturer = $data['email-lecturer'];
		$code_class = $data['code-class'];
		$name_subject = $data['subject-name'];
		$name_lecturer = $data['lecturer-name'];
		$fakultas = $data['facultas'];
		$prodi = $data['prodi'];
		$sks = $data['sks'];
		$room_number = $data['building-room-number'];
		$room_lat = $data['room-latitude'];
		$room_long = $data['room-longitude'];
		$jadwal_class = $data['jadwal-day-time'];

		$sql = "UPDATE tbl_classes SET `code_class`='$code_class', `name_subject`='$name_subject', `name_lecturer`='$name_lecturer', `fakultas`='$fakultas', `prodi`='$prodi', `sks`='$sks', `building_room`='$room_number', `room_latitude`='$room_lat', `room_longitude`='$room_long', `jadwal_class_day_time`='$jadwal_class'  WHERE id_class = '$id_unique' AND status_class = 'active';";
		
		if($this->db->query($sql) === TRUE) {
		  return true;
		} else {
		  return false;
		}
	}

	// archive class or update class status
	public function update_class_status($id){
		// Sql query to update status
		$sql = "UPDATE tbl_classes SET `status_class`='complete' WHERE id_class = '$id';";
		
		if($this->db->query($sql) === TRUE) {
		  return true;
		} else {
		  return false;
		}
	}

	// handle delete class
	public function delete_class($id){
		$sql = "DELETE from tbl_classes WHERE id_class = '$id';";
		if($this->db->query($sql) === TRUE) {
		  return true;
		} else {
		  return false;
		}
	}

	// handle restore class
	public function restore_class($id){
		// Sql query to update status
		$sql = "UPDATE tbl_classes SET `status_class`='active' WHERE id_class = '$id';";

		if($this->db->query($sql) === TRUE) {
		  return true;
		} else {
		  return false;
		}
	}

}
?>