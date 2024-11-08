<?php  
class Login_model{
	private $db;

	public function __construct(){
		try{
			// create object from database class
			$this->db = new Database;

			// check status
			if($this->db == false){
				//echo "<script>console.log('Connection failed.' );</script>";
			}else{
				//echo "<script>console.log('Connected successfully.' );</script>";
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}

	public function check_login_regular_admin($data){
        $role = $data['role'];
		$name = $data['name'];
		$password = $data['password'];
		
		// enkripsi password
		$pass_encripted = hash('md5', $password);
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("select `user_id`, `user_name`, `user_password`, `gender`, `role`, `whatsapp_number`, `email` from tbl_user where user_name = '$name' AND BINARY user_password = '$password' AND role = '$role';");
			$this->db->db_close(); // Close database connection
			
			if ($result->num_rows > 0) {
				// convert to associative array
				$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
				return $rows;
			} else {
				return []; // kosong return false
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}
	
	public function check_login_relawan($data){
        $role = $data['role'];
		$name = $data['name'];
		$password = $data['password'];
		
		// enkripsi password
		$pass_encripted = hash('md5', $password);
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("select `relawan_id`, `nik`, `nama_relawan`, `jenis_kelamin`, `bidang_keahlian`, `ketersediaan`, `registered_date`, `tanggal_lahir`, `alamat`, `no_whatsapp`, `email` from tbl_relawan where nama_relawan = '$name' AND BINARY password = '$password';");
			$this->db->db_close(); // Close database connection
			
			if ($result->num_rows > 0) {
				// convert to associative array
				$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
				return $rows;
			} else {
				return []; // kosong return false
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
		
	}

	public function check_login_lembaga($data){
        $role = $data['role'];
		$name = $data['name'];
		$password = $data['password'];
		
		// enkripsi password
		$pass_encripted = hash('md5', $password);
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("select `lembaga_id`, `nama_lembaga`, `password`, `email`, `jenis_lembaga`, `alamat`, `nomor_telp` from tbl_lembaga where nama_lembaga = '$name' AND BINARY password = '$password';");
			$this->db->db_close(); // Close database connection
			
			if ($result->num_rows > 0) {
				// convert to associative array
				$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
				return $rows;
			} else {
				return []; // kosong return false
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
		
	}
	
	// Generate unique id
	private function generate_unique_id() {
        return uniqid();
    }

 
	// insert data register reguler
	public function insert_data_reguler($data){ 
		$user_id = $this->generate_unique_id();
		$name = $data['name'];
		$password = $data['password'];
		$email = $data['email'];
		$gender = $data['gender'];
		$whatsapp_number = $data['whatsapp_number'];

		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_user`(`user_id`, `user_name`, `user_password`, `gender`, `role`, `whatsapp_number`, `email`) 
										VALUES ('$user_id','$name','$password','$gender','user','$whatsapp_number','$email');");
			$this->db->db_close(); // Close database connection
			
			return $result; 
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}
	
	// insert data register reguler
	public function insert_data_relawan($data){ 
		// Set time zone
		date_default_timezone_set('Asia/Pontianak');
		
		// data
		$relawan_id = $this->generate_unique_id();
		$nik = $_POST['nik'];
		$nama_relawan = $_POST['full_name'];
		$jenis_kelamin = $_POST['genderrelawan'];
		$bidang_keahlian = $_POST['job'];
		$ketersediaan = 'yes';
		$registered_date = date('Y-m-d H:i:s');
		$tanggal_lahir = $_POST['dob'];
		$alamat = $_POST['current_address'];
		$no_whatsapp = $_POST['whatsapp_number_relawan'];
		$email = $_POST['emailrelawan'];
		$password = $_POST['passwordrelawan'];
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_relawan`(`relawan_id`, `nik`, `nama_relawan`, `jenis_kelamin`, `bidang_keahlian`, `ketersediaan`, `registered_date`, `tanggal_lahir`, `alamat`, `no_whatsapp`, `email`, `password`) VALUES ('$relawan_id','$nik','$nama_relawan','$jenis_kelamin','$bidang_keahlian','$ketersediaan','$registered_date','$tanggal_lahir','$alamat','$no_whatsapp','$email','$password');");
			$this->db->db_close(); // Close database connection
			
			return $result; 
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}

	// insert data register reguler
	public function insert_data_lembaga($data){ 
		$lembaga_id = $this->generate_unique_id();
		$nama_lembaga = $data['nama_lembaga'];
		$password = $data['password'];
		$email = $data['email'];
		$jenis_lembaga = $data['jenis_lembaga'];
		$alamat = $data['alamat'];
		$nomor_telp = $data['nomor_telp'];

		// $lembaga_id = $this->generate_unique_id();
		// $nama_lembaga = 'Damkar';
		// $password = '12345';
		// $email = 'damkar@gmail.com';
		// $jenis_lembaga = 'pemadam kebakaran';
		// $alamat = 'jl. arnold mononutu';
		// $nomor_telp = '0812343535';

		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("INSERT INTO `tbl_lembaga`(`lembaga_id`, `nama_lembaga`, `password`, `email`, `jenis_lembaga`, `alamat`, `nomor_telp`) 
										VALUES ('$lembaga_id','$nama_lembaga','$password','$email','$jenis_lembaga','$alamat','$nomor_telp');");
			$this->db->db_close(); // Close database connection
			
			return $result; 
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}

}
?>