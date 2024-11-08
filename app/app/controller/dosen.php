<?php
class dosen extends Controller{
	// Constructor
	public function __construct(){

	}

	// Default method
	public function index($name="Semmy", $age="24"){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user-name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			//$arr_data['name'] = $name; 
			//$arr_data['age'] = $age;
			$arr_title['title'] = "Home Page";
			
			# email dan role
			$lecturer_email = $_SESSION['user-email'];
			$lecturer_role = $_SESSION['user-role'];

			// get all active classes in database
			$arr_data['data-total'] = $this->logic("Home_model")->get_summary_data($lecturer_email, $lecturer_role);
			//var_dump($arr_data['data-total']);
			
			// Display page and send data
			$this->display('template/header', $arr_title);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/index", $arr_data);
			$this->display('template/footer');
		}
	}

//	// Default method
//	public function index($name="Juan", $age="24"){
//		session_start();
//		// Cek session pada halaman dashboard
//		if (!isset($_SESSION['user-name'])) {
//			// Jika session tidak ada, redirect ke halaman login
//			header('Location: '.APP_PATH.'/login/');
//			exit();
//		}else{
//			// Associative Arrays (arrays with keys)
//			$arr_data['name'] = $name;
//			$arr_data['age'] = $age;
//			$arr_data['title'] = "Create Class";
//			// Display page and send data
//			$this->display('template/header', $arr_data);
//			$this->display("template/sidebar", $arr_data);
//			$this->display("dosen/index", $arr_data);
//			$this->display('template/footer');
//		}
//	}


	// Default method
	public function active(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user-name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		}else{
			// Associative Arrays (arrays with keys)
			$arr_title['title'] = "Active Class";

			# email dan id_class
			$lecturer_email = $_SESSION['user-email'];

			// get all active classes in database
			$arr_data['data-active-classes'] = $this->logic("Dosen_model")->get_active_classes($lecturer_email);
			//var_dump($arr_data['data-active-classes'][0]);

			//var_dump($arr_data['data-active-classes']);
			
			// Display page and send data
			$this->display('template/header', $arr_title); 
			$this->display("template/sidebar");
			$this->display("dosen/active", $arr_data);
			$this->display('template/footer');
		}
	}
	
	// search class for RPS
	public function rps(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user-name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		}else{
			// Associative Arrays (arrays with keys)
			$arr_title['title'] = "Search Class";

			# email dan id_class
			$lecturer_email = $_SESSION['user-email'];
			
			// total classes
			$arr_data['total-classes'] = $this->logic("Dosen_model")->get_count_data_classes();
			
			// get all active classes in database
			//$arr_data['data-active-classes'] = $this->logic("Dosen_model")->search_active_classes($lecturer_email);
			//var_dump($arr_data['data-active-classes'][0]);

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				//$arr_data['user'] = $this->logic("Admin_model")->getAllDataLecturer($_POST["search-keyword"]);
				$arr_data['data-active-classes'] = $this->logic("Dosen_model")->search_active_classes($lecturer_email, $_POST["search-keyword"]);
				//var_dump($arr_data['data-active-classes'][0]);
			}else{
				//$arr_data['user'] = $this->logic("Admin_model")->getAllDataLecturer("BUKAN-SEARCH");
				$arr_data['data-active-classes'] = $this->logic("Dosen_model")->search_active_classes($lecturer_email, "BUKAN-SEARCH");
				//var_dump($arr_data['data-active-classes'][0]);
			}
			
			// Display page and send data
			$this->display('template/header', $arr_title);
			$this->display("template/sidebar");
			$this->display("dosen/rps", $arr_data);
			$this->display('template/footer');
		}
	}

	// handle dosen/lecturer create new class
	public function create(){
		// Start session
		session_start();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//var_dump($_POST);
			
			// check status create new class (true or false)
			$status = $this->logic("Dosen_model")->create_new_class($_POST);
			
			if ($status) {
				echo "Create new class berhasil.";
			} else {
				echo "Create new class tidak berhasil.";
			}
		}else{
			echo "Error, it's not secure request.";
		}
	}

	// Info selected class
	public function info($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_title['title'] = "Info Class";
				
				# email dan id_class
				$lecturer_email = $_SESSION['user-email'];
				
				// get all active classes in database
				$arr_data['selected-classes'] = $this->logic("Dosen_model")->get_info_classes($id_class);
				//var_dump($arr_data['selected-classes']);

				// jika kosong
				if(empty($arr_data['selected-classes'])){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika "email user login" berbeda dengan class yang di select.
				}else if($lecturer_email != $arr_data['selected-classes'][0]['email_lecturer']){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika tidak kosong
				}else{
					// Display page and send data
					$this->display('template/header', $arr_title);
					$this->display("template/sidebar");
					$this->display("dosen/info", $arr_data);
					$this->display('template/footer');
				}
			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}


	// Update selected class
	public function update($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_title['title'] = "Update Class";
				
				# email dan id_class
				$lecturer_email = $_SESSION['user-email'];
				
				// get all active classes in database
				$arr_data['data-update-class'] = $this->logic("Dosen_model")->get_update_class($id_class);
				//var_dump($arr_data['data-update-class']);

				// jika kosong
				if(empty($arr_data['data-update-class'])){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika "email user login" berbeda dengan class yang di select.
				}else if($lecturer_email != $arr_data['data-update-class'][0]['email_lecturer']){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika tidak kosong
				}else{
					// Display page and send data
					$this->display('template/header', $arr_title);
					$this->display("template/sidebar");
					$this->display("dosen/update", $arr_data);
					$this->display('template/footer');
				}
			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}

	// handle dosen/lecturer update class info
	public function updateclass(){
		// Start session
		session_start();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//var_dump($_POST);
			
			// check status create new class (true or false)
			$status = $this->logic("Dosen_model")->update_class_info($_POST);
			
			if ($status) {
				echo "Updated info class berhasil.";
			} else {
				echo "Create new class tidak berhasil.";
			}
		}else{
			echo "Error, it's not secure request.";
		}
	}

	// Status class (Archive/Complete class)
	public function status($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_title['title'] = "Archive Class";
				
				# email dan id_class
				$lecturer_email = $_SESSION['user-email'];
				
				// get all active classes in database
				$arr_data['selected-classes'] = $this->logic("Dosen_model")->get_info_classes($id_class);
				//var_dump($arr_data['selected-classes']);

				// jika kosong
				if(empty($arr_data['selected-classes'])){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika "email user login" berbeda dengan class yang di select.
				}else if($lecturer_email != $arr_data['selected-classes'][0]['email_lecturer']){
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();

				// jika tidak kosong
				}else{
					// Display page and send data
					$this->display('template/header', $arr_title);
					$this->display("template/sidebar");
					$this->display("dosen/status", $arr_data);
					$this->display('template/footer');
				}
			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}

	// Status class (Archive/Complete class)
	public function statusprocess($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_data['title'] = "archive";
				// get all active classes in database
				$status = $this->logic("Dosen_model")->update_class_status($id_class);
				//var_dump($arr_data['selected-classes']);

				if($status){ // if true
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/archive/');
					exit();
				}else{
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/active/');
					exit();
				}

			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}

	// Get all archive/complete classes
	public function archive(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user-name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		}else{
			// Associative Arrays (arrays with keys)
			$arr_title['title'] = "Archive Class";

			# email dan id_class
			$lecturer_email = $_SESSION['user-email'];

			// get all active classes in database
			$arr_data['data-archive-classes'] = $this->logic("Dosen_model")->get_complete_classes($lecturer_email);
			//var_dump($arr_data['data-active-classes'][0]);

			// Display page and send data
			$this->display('template/header', $arr_title);
			$this->display("template/sidebar");
			$this->display("dosen/archive", $arr_data);
			$this->display('template/footer');
		}
	}

	// Delete class
	public function delete($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_data['title'] = "archive";
				// get all active classes in database
				$status = $this->logic("Dosen_model")->delete_class($id_class);
				//var_dump($arr_data['selected-classes']);

				if($status){ // if true
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/archive/');
					exit();
				}else{
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/archive/');
					exit();
				}

			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}

	// Restore class
	public function restore($id_class="empty"){
		if($id_class !=="empty"){
			session_start();
			// Cek session pada halaman dashboard
			if (!isset($_SESSION['user-name'])) {
				// Jika session tidak ada, redirect ke halaman login
				header('Location: '.APP_PATH.'/login/');
				exit();
			}else{
				// Associative Arrays (arrays with keys)
				$arr_data['title'] = "archive";
				// get all active classes in database
				$status = $this->logic("Dosen_model")->restore_class($id_class);
				//var_dump($arr_data['selected-classes']);

				if($status){ // if true
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/archive/');
					exit();
				}else{
					// Jika id class tidak di init
					header('Location: '.APP_PATH.'/dosen/archive/');
					exit();
				}

			}
		} else {
			// Jika id class tidak di init
			header('Location: '.APP_PATH.'/dosen/active/');
			exit();
			
		}
	}








}
?>