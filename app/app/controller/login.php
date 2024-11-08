<?php
class login extends Controller{
	// Constructor
	public function __construct(){

	}

	// Display UI login form
	public function index(){
		$arr_data['title'] = "Login";
		
		// Display page and send data
		$this->display("login/index", $arr_data);
	} 
	
	public function register(){
		$arr_data['title'] = "Register";
		
		// Display page and send data
		$this->display("login/register", $arr_data);
	} 

	 // Fungsi untuk meng-handle perubahan password
	//  public function updatePassword() {
	// 	// Menerima data yang dikirim melalui fetch request
	// 	$data = json_decode(file_get_contents('php://input'), true);
	
	// 	if (!empty($data)) {
	// 		$user_id = $data['user_id'];
	// 		$old_password = $data['old_password'];
	// 		$new_password = $data['new_password'];
	// 		$confirm_password = $data['confirm_password'];
	
	// 		// Pastikan password baru dan konfirmasi password cocok
	// 		if ($new_password !== $confirm_password) {
	// 			echo json_encode(['success' => false, 'message' => 'Password baru dan konfirmasi tidak cocok']);
	// 			return;
	// 		}
	
	// 		// Enkripsi password lama dan baru
	// 		//$old_password_encrypted = hash('md5', $old_password);
	// 		// Enkripsi password baru dengan bcrypt
	// 		$new_password_encrypted = password_hash($new_password, PASSWORD_BCRYPT);
	// 		// Cek apakah password lama cocok dengan yang di database
	// 		$checkOldPassword = $this->logic("Login_model")->checkOldPassword($user_id, $old_password);
	// 		if (!$checkOldPassword) {
	// 			echo json_encode(['success' => false, 'message' => 'Password lama tidak cocok']);
	// 			return;
	// 		}
	
	// 		// Perbarui password di database
	// 		$updated = $this->logic("Login_model")->updatePassword($user_id, $new_password_encrypted);
	// 		if ($updated) {
	// 			echo json_encode(['success' => true, 'message' => 'Password berhasil diperbarui']);
	// 		} else {
	// 			echo json_encode(['success' => false, 'message' => 'Gagal memperbarui password']);
	// 		}
	// 	} else {
	// 		echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
	// 	}
	// }

	public function changePassword() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Mengambil data JSON dari request body
			$data = json_decode(file_get_contents("php://input"), true);
			
			// var_dump($data);

			$old_password = $data['old_password'];
			$new_password = $data['new_password'];
			$confirm_password = $data['confirm_password'];
			$user_id = $data['user_id'];
	
			// Validasi jika password baru dan konfirmasi sama
			if ($new_password !== $confirm_password) {
				echo "Password baru dan konfirmasi password tidak sama!";
				return;
			}
	
			// Ambil password yang ada di database (hashed)
			$user = $this->logic("Login_model")->get_user_by_id($user_id);
			// var_dump($user);
			if (!$user) {
				echo "Pengguna tidak ditemukan!";
				return;
			}
			
			// Verifikasi password lama
			if (!password_verify($old_password, $user['user_password'])) {
				echo "Password lama salah!";
				return;
			}
	
			// Hash password baru
			$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
			
			// Update password di database
			if ($this->logic("Login_model")->updatePassword($user_id, $hashed_password)) {
				echo json_encode(["success" => true, "message" => "Password berhasil diubah!"]);
			} else {
				echo json_encode(["success" => false, "message" => "Terjadi kesalahan saat mengubah password."]);
			}
		}
	}
	
	

	// Fungsi untuk memperbarui data pengguna
    public function updateUser() {
        // Menerima data yang dikirim melalui fetch request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $updateData = [
                'user_id' => $data['user_id'], // Ambil user_id dari data
                'user_name' => $data['user_name'],
                'gender' => $data['gender'],
                'role' => $data['role'],
                'whatsapp_number' => $data['whatsapp_number'],
                'email' => $data['email']
            ];

            // Debugging: Tampilkan data yang akan diupdate
            var_dump($updateData);

            // Panggil fungsi untuk memperbarui data di model
            $updated = $this->logic("Login_model")->update_user_data($updateData);

            if ($updated) {
                // Perbarui session dengan data baru
                $_SESSION['user']['user_id'] = $updateData['user_id'];
                $_SESSION['user']['user_name'] = $updateData['user_name'];
                $_SESSION['user']['gender'] = $updateData['gender'];
                $_SESSION['user']['role'] = $updateData['role'];
                $_SESSION['user']['whatsapp_number'] = $updateData['whatsapp_number'];
                $_SESSION['user']['email'] = $updateData['email'];

                echo json_encode(['success' => true]);

            } else {
                echo "REGISTER SUCCESS";
            }
        } else {
            echo "REGISTER FAILED";
        }
    }

	
	// handle login user
	public function process(){
		try{
			// Start session
			session_start();
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				//var_dump($_POST);
				
				// validate user input
				$role = $_POST['role'];
				$name = $_POST['name'];
				$password = $_POST['password'];
				
				
				if (empty($_POST['role']) || empty($_POST['name']) || empty($_POST['password'])) {
					// Jika ada input yang kosong
					error_log("kosong");
					header('Location: ' . APP_PATH . '/login/index/');
					exit();
				}
				
				// cek login admin
				if($role == "admin"){
					// check status login in database
					$arr_data['status-login'] = $this->logic("Login_model")->check_login_regular_admin($_POST);
					//var_dump($arr_data['status-login']);
					if (empty($arr_data['status-login'])) {
						error_log("Login failed: Incorrect credentials or user not found.");
						var_dump($arr_data['status-login']);
					}

					if (!empty($arr_data['status-login'])) {
						// set session variables
						$_SESSION['user_id'] = $arr_data['status-login'][0]["user_id"];
						$_SESSION['user_name'] = $arr_data['status-login'][0]["user_name"];
						$_SESSION['role'] = $arr_data['status-login'][0]["role"];
						$_SESSION['gender'] = $arr_data['status-login'][0]["gender"];
						$_SESSION['whatsapp_number'] = $arr_data['status-login'][0]["whatsapp_number"];
						$_SESSION['email'] = $arr_data['status-login'][0]["email"];
					
						// redirect to secure page
						header('Location: '.APP_PATH.'/home/dashboard/'); // Redirect ke page yang sama/lain.
						exit();
					} else {
						// redirect to secure page
						header('Location: '.APP_PATH.'/login/index/'); 
						exit();
					}
				}
				
				// cek login user regular dan admin
				if($role == "user"){
					// check status login in database
					$arr_data['status-login'] = $this->logic("Login_model")->check_login_regular_admin($_POST);
					var_dump($arr_data['status-login']);
					
					// if (empty($arr_data['status-login'])) {
					// 	error_log("Login failed: Incorrect credentials or user not found.");
					// 	// var_dump($arr_data['status-login']);
					// }

					if (isset($arr_data['status-login']) && !empty($arr_data['status-login'])) {
						// set session variables
						$_SESSION['user_id'] = $arr_data['status-login']["user_id"];
						$_SESSION['user_name'] = $arr_data['status-login']["user_name"];
						$_SESSION['role'] = $arr_data['status-login']["role"];
						$_SESSION['gender'] = $arr_data['status-login']["gender"];
						$_SESSION['whatsapp_number'] = $arr_data['status-login']["whatsapp_number"];
						$_SESSION['email'] = $arr_data['status-login']["email"];
						var_dump($_SESSION);
						// redirect to secure page
						header('Location: '.APP_PATH.'/home/index/'); // Redirect ke page yang sama/lain.
						exit();
					} else {
						// redirect to secure page
						header('Location: '.APP_PATH.'/login/index/'); 
						exit();
					}
				}
				
				
				// cek user relawan
				if($role == "relawan"){
					// check status login in database
					$arr_data['status-relawan'] = $this->logic("Login_model")->check_login_relawan($_POST);
					var_dump($arr_data['status-relawan']);
					
					if (!empty($arr_data['status-relawan'])) {
						// set session variables
						$_SESSION['user_id'] = $arr_data['status-relawan'][0]["relawan_id"];
						$_SESSION['user_name'] = $arr_data['status-relawan'][0]["nama_relawan"];
						$_SESSION['role'] = 'relawan';
						$_SESSION['gender'] = $arr_data['status-relawan'][0]["jenis_kelamin"];
						$_SESSION['whatsapp_number'] = $arr_data['status-relawan'][0]["no_whatsapp"];
						$_SESSION['email'] = $arr_data['status-relawan'][0]["email"];
						$_SESSION['nik'] = $arr_data['status-relawan'][0]["nik"];
						$_SESSION['bidang_keahlian'] = $arr_data['status-relawan'][0]["bidang_keahlian"];
						$_SESSION['tanggal_lahir'] = $arr_data['status-relawan'][0]["tanggal_lahir"];
						
						// redirect to secure page
						header('Location: '.APP_PATH.'/home/index/'); // Redirect ke page yang sama/lain.
						exit();
					} else {
						// redirect to secure page
						header('Location: '.APP_PATH.'/login/index/'); 
						exit();
					}
				}

				// cek login user regular dan admin
				if($role == "lembaga"){
					// check status login in database
					$arr_data['status-lembaga'] = $this->logic("Login_model")->check_login_lembaga($_POST);
					//var_dump($arr_data['status-login']);
					
					if (!empty($arr_data['status-lembaga'])) {
						// set session variables
						$_SESSION['user_id'] = $arr_data['status-lembaga'][0]["lembaga_id"];
						$_SESSION['user_name'] = $arr_data['status-lembaga'][0]["nama_lembaga"];
						$_SESSION['role'] = 'lembaga';
						$_SESSION['gender'] = 'male';
						$_SESSION['jenis_lembaga'] = $arr_data['status-lembaga'][0]["jenis_lembaga"];
						$_SESSION['alamat'] = $arr_data['status-lembaga'][0]["alamat"];
						$_SESSION['email'] = $arr_data['status-lembaga'][0]["email"];
					
						// redirect to secure page
						header('Location: '.APP_PATH.'/home/dashboardlembaga/'); // Redirect ke page yang sama/lain.
						exit();
					} else {
						// redirect to secure page
						header('Location: '.APP_PATH.'/login/index/'); 
						exit();
					}
				} 
				
			}
		} catch (Exception $e) {
			// redirect to secure page
			header('Location: '.APP_PATH.'/login/index/'); 
			exit();
		}
	}
	
	public function loginmobile(){
		try {
			// Start session
			//session_start();
			
			$response = []; // Initialize an empty response array
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				//var_dump($_POST);
				
				// validate user input
				$role = $_POST['role'];
				$name = $_POST['name'];
				$password = $_POST['password'];
				
				// Check login based on role
				if ($role == "user" || $role == "admin") {
					// Check login status in database for regular users and admins
					$response['status-login'] = $this->logic("Login_model")->check_login_regular_admin($_POST);
				
				}
				
				if ($role == "relawan") {
					// Check login status in database for relawan
					$response['status-login'] = $this->logic("Login_model")->check_login_relawan($_POST);
				}
			}
			
			// Output JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
			
		} catch (Exception $e) {
			// Handle exceptions
			echo json_encode(['error' => 'An error occurred.']);
		}
	}

	// handle regist anggota reguler
	public function regist_reguler_process() {
		try {
			// Start session
			session_start();
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Pengecekan apakah input kosong
				if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['gender']) || empty($_POST['whatsapp_number'])) {
					// Jika ada input yang kosong, arahkan ke halaman register yang sama
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}

				$email = $_POST['email'];
				$password = $_POST['password'];
	
				// Validasi Email
				if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
					!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
					echo "Email tidak valid!";
					return;
				}
	
				// Validasi Password
				if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
					echo "Password harus minimal 8 karakter, mengandung huruf dan angka!";
					return;
				}

				 // Enkripsi password menggunakan password_hash()
				 $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);

				 // Gantikan password asli dengan yang telah dienkripsi
				 $_POST['password'] = $password_hashed;
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_reguler($_POST);
				
				if ($status) {
					// redirect to secure page
					header('Location: ' . APP_PATH . '/login/index/');
					exit();
				} else {
					// redirect to register page with error message
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}
	
	// handle regist mobile anggota reguler
	public function regist_mobile_reguler() {
		try {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Pengecekan apakah input kosong
				if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['gender']) || empty($_POST['whatsapp_number'])) {
					
					echo "REGISTER FAILED";
				}
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_reguler($_POST);
				
				if ($status) {
					echo "REGISTER SUCCESS";
					
				} else {
					echo "REGISTER FAILED";
				}
			}
		} catch (Exception $e) {
			echo "REGISTER FAILED";
		}
	}

	// handle regist relawan
	public function regist_relawan_process() {
		try {
			// Start session
			session_start();
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Pengecekan apakah input kosong
				if (empty($_POST['nik']) || empty($_POST['full_name']) || empty($_POST['genderrelawan']) || empty($_POST['dob']) || empty($_POST['emailrelawan']) || empty($_POST['current_address']) || empty($_POST['whatsapp_number_relawan']) || empty($_POST['job']) || empty($_POST['passwordrelawan'])) {
					// Jika ada input yang kosong, arahkan ke halaman register yang sama
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_relawan($_POST);
				// var_dump($status);

				if ($status) {
					// redirect to secure page
					header('Location: ' . APP_PATH . '/login/index/');
					exit();
				} else {
					// redirect to register page with error message
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}
	
	// handle regist mobile relawan
	public function regist_mobile_relawan() {
		try {
			// Start session
			//session_start();
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Pengecekan apakah input kosong
				if (empty($_POST['nik']) || empty($_POST['full_name']) || empty($_POST['genderrelawan']) || empty($_POST['dob']) || empty($_POST['emailrelawan']) || empty($_POST['current_address']) || empty($_POST['whatsapp_number_relawan']) || empty($_POST['job']) || empty($_POST['passwordrelawan'])) {
					echo "REGISTER FAILED";
				}
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_relawan($_POST);
				
				if ($status) {
					echo "REGISTER SUCCESS";
				} else {
					echo "REGISTER FAILED";
				}
			}
		} catch (Exception $e) {
			echo "REGISTER FAILED";
		}
	}

	// handle regist lembaga
	public function regist_lembaga_process() {
		try {
			// Start session
			session_start();
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Pengecekan apakah input kosong
				if (empty($_POST['nama_lembaga']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['jenis_lembaga']) || empty($_POST['alamat']) || empty($_POST['nomor_telp'])) {
					// Jika ada input yang kosong, arahkan ke halaman register yang sama
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}
				var_dump($_POST);
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_lembaga($_POST);
				// var_dump($status);

				// if ($status) {
				// 	echo "REGISTER SUCCESS";
				// } else {
				// 	echo "REGISTER FAILED";
				// }

				if ($status) {
					// redirect to secure page
					header('Location: ' . APP_PATH . '/login/index/');
					exit();
				} else {
					// redirect to register page with error message
					header('Location: ' . APP_PATH . '/login/register');
					exit();
				}
			}
		} catch (Exception $e) {
			echo "Maaf terjadi kesalahan: " . $e->getMessage();
		}
	}

	// handle login user
	public function logout(){
		// hapus session saat user logout
		session_start();
		session_destroy();
		
		// redirect ke login form
		header('Location: '.APP_PATH.'/login/');
		exit();
	}

}
?>