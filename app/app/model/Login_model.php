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

	// public function getUserById($user_id) {
	// 	// Ambil data user berdasarkan ID
	// 	// Contoh query, sesuaikan dengan query di sistem Anda
	// 	$query = $this->db->query("SELECT * FROM users WHERE id = '$user_id'");

	// 	if ($result->num_rows > 0) {
    //         // Mengembalikan data user dalam bentuk array asosiatif
    //         return $result->fetch_assoc();
    //     } else {
    //         return false; // Jika user tidak ditemukan
    //     }
	// }
	
	public function updatePassword($user_id, $hashed_password) {
		// Update password di database
		return $this->db->query("UPDATE tbl_user SET user_password = '$hashed_password' WHERE user_id = '$user_id'");
	}
	

    // Fungsi untuk mengecek password lama
    public function checkOldPassword($user_id, $old_password) {
        $query = $this->db->query("SELECT user_password FROM tbl_user WHERE user_id = '$user_id';");
        
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $stored_password = $row['user_password'];

            // Verifikasi password lama
            if (password_verify($old_password, $stored_password)) {
                return true; // Password lama cocok
            }
        }
        return false; // Password lama tidak cocok
    }

    // Fungsi untuk memperbarui password baru
    // public function updatePassword($user_id, $new_password_encrypted) {
    //     //$new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT); // Enkripsi password baru

    //     $update_result = $this->db->query("UPDATE tbl_user SET user_password = '$new_password_encrypted' WHERE user_id = '$user_id';");

    //     return $update_result;
    // }
	
	// Fungsi untuk memperbarui data user
	public function update_user_data($data) {
		$user_id = $data['user_id'];
		$user_name = $data['user_name'];
		$gender = $data['gender'];
		$role = $data['role'];
		$whatsapp_number = $data['whatsapp_number'];
		$email = $data['email'];

		try {
			// Query untuk memperbarui data pengguna berdasarkan user_id
			$query = "UPDATE tbl_user SET 
				user_name = '$user_name',
				gender = '$gender',
				role = '$role',
				whatsapp_number = '$whatsapp_number',
				email = '$email'
				WHERE user_id = '$user_id'";
				
			$result = $this->db->query($query);

			// Tutup koneksi database
			$this->db->db_close();

			return $result ? true : false;
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
			return false;
		}
	}

    // Fungsi untuk mendapatkan data user berdasarkan user_id
    public function get_user_by_id($user_id) {
        $result = $this->db->query("SELECT * FROM tbl_user WHERE user_id = '$user_id';");
        $this->db->db_close(); // Tutup koneksi database
        
        if ($result->num_rows > 0) {
            // Mengembalikan data user dalam bentuk array asosiatif
            return $result->fetch_assoc();
        } else {
            return false; // Jika user tidak ditemukan
        }
    }

	public function check_login_regular_admin($data){
        $role = $data['role'];
		$name = $data['name'];
		$password = $data['password'];
		//var_dump($data);
		
		try{
			// case sensitive dengan menambahkan modifier BINARY sebelum kolom name
			$result = $this->db->query("select `user_id`, `user_name`, `user_password`, `gender`, `role`, `whatsapp_number`, `email` from tbl_user where BINARY user_name = '$name' AND role = '$role';");
			$this->db->db_close(); // Close database connection
			
			// var_dump($result);
			 // Cek apakah ada hasil yang ditemukan
			 if ($result && $result->num_rows > 0) {
				// Ambil data pengguna
				$user_data = $result->fetch_assoc();
	
				// Verifikasi password menggunakan password_verify
				if (password_verify($password, $user_data['user_password'])) {
					
					return $user_data;
					// var_dump($user_data);
				} else {
					return [];
				}
			} else {
				// Tidak ada data yang cocok
				return []; 
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
			$result = $this->db->query("INSERT INTO `tbl_relawan`(`relawan_id`, `nik`, `nama_relawan`, `jenis_kelamin`, `bidang_keahlian`, `ketersediaan`, `registered_date`, `tanggal_lahir`, `alamat`, `no_whatsapp`, `email`, `password`) 
										VALUES ('$relawan_id','$nik','$nama_relawan','$jenis_kelamin','$bidang_keahlian','$ketersediaan','$registered_date','$tanggal_lahir','$alamat','$no_whatsapp','$email','$password');");
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