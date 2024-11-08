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
					header('Location: ' . APP_PATH . '/login/index/');
					exit();
				}
				
				// cek login admin
				if($role == "admin"){
					// check status login in database
					$arr_data['status-login'] = $this->logic("Login_model")->check_login_regular_admin($_POST);
					//var_dump($arr_data['status-login']);
					
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
					//var_dump($arr_data['status-login']);
					
					if (!empty($arr_data['status-login'])) {
						// set session variables
						$_SESSION['user_id'] = $arr_data['status-login'][0]["user_id"];
						$_SESSION['user_name'] = $arr_data['status-login'][0]["user_name"];
						$_SESSION['role'] = $arr_data['status-login'][0]["role"];
						$_SESSION['gender'] = $arr_data['status-login'][0]["gender"];
						$_SESSION['whatsapp_number'] = $arr_data['status-login'][0]["whatsapp_number"];
						$_SESSION['email'] = $arr_data['status-login'][0]["email"];
						
						
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
					//var_dump($arr_data['status-relawan']);
					
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
						header('Location: '.APP_PATH.'/home/dashboard/'); // Redirect ke page yang sama/lain.
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
				
				// check status login in database
				$status = $this->logic("Login_model")->insert_data_lembaga($_POST);
				
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