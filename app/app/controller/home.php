<?php
class home extends Controller{

	// Constructor
	public function __construct(){

	}

	// Default method
	public function index(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/home");
			$this->display('template/home/footer');
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			$arr_data['user_name'] = $_SESSION['user_name'];
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/home", $_SESSION);
			$this->display('template/home/footer');
		}
	}

	function fetchGroupData() {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.fonnte.com/fetch-group',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: GRnm9ah7XakS8sJnXhKQ' 
			),
		));
	
		$response = curl_exec($curl);
	
		if (curl_errno($curl)) {
			// Handle error if needed
			echo 'Curl error: ' . curl_error($curl);
		}
	
		curl_close($curl);
		
		echo  $response;
	}

	function getWhatsAppGroupData() {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.fonnte.com/get-whatsapp-group',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: GRnm9ah7XakS8sJnXhKQ' 
			),
		));
	
		$response = curl_exec($curl);
	
		if (curl_errno($curl)) {
			// Handle error if needed
			echo 'Curl error: ' . curl_error($curl);
		}
	
		curl_close($curl);
		
		echo  $response;
	}

	public function saveDisasterData() {
        // Fetch and format the message
		$latestReport = $this->logic("Home_model")->get_latest_report();
       // Ekstrak jenis bencana dan buat pesan
		$disasterType = $latestReport[0]['jenis_bencana'];
		//var_dump($disasterType);
		$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport[0]['lokasi_bencana'];

        // Get the other fields
        $level = $latestReport[0]['level'];
        $latitude = $latestReport[0]['latitude'];
        $longitude = $latestReport[0]['longitude'];

        // Save data to Redis
        $this->logic("Home_model")->saveDisasterData($message, $level, $latitude, $longitude);

        echo json_encode(['status' => 'success', 'message' => 'Disaster data saved successfully.']);
    }

    public function getDisasterDataFromRedis() {
        $disasterData = $this->logic("Home_model")->getDisasterData();

        if (!empty($disasterData)) {
            echo json_encode([
                'status' => 'active',
                'message' => $disasterData['message'],
                'level' => $disasterData['level'],
                'latitude' => $disasterData['latitude'],
                'longitude' => $disasterData['longitude'],
				'timestamp' => $disasterData['timestamp']
            ]);
        } else {
            echo json_encode(['status' => 'no_disaster']);
        }
    }

	// Fungsi untuk memperbarui lokasi masyarakat dan menyimpan data dalam sesi
	public function checkAndSendNotification() {
		// Mulai sesi untuk menyimpan data sementara
		session_start();

		// Ambil data JSON dari POST request
		$data = json_decode(file_get_contents('php://input'), true);

		// Pastikan data diterima dengan benar
		$user_id = $data['user_id'] ?? null;
		$latitude = $data['latitude'] ?? null;
		$longitude = $data['longitude'] ?? null;

		var_dump("User  ID: $user_id, Latitude: $latitude, Longitude: $longitude");
		

		if (!$user_id || !$latitude || !$longitude) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
			return;
		}

		

		echo json_encode(['status' => 'success', 'message' => 'Location updated and stored in session']);
	}


// // Fungsi untuk mengirim notifikasi WhatsApp menggunakan API Fonnte
// public function sendGeofenceWhatsAppNotification() {
//     // Mulai sesi untuk mengakses data
//     session_start();

//     // Ambil data dari sesi
//     $user_id = $_SESSION['user_id'] ?? null;
//     $latitude = $_SESSION['latitude'] ?? null;
//     $longitude = $_SESSION['longitude'] ?? null;

//     if (!$user_id || !$latitude || !$longitude) {
//         echo json_encode(['status' => 'error', 'message' => 'No location data found in session']);
//         return;
//     }

//     // Cek apakah pengguna berada dekat dengan lokasi bencana
//     $disasters = $this->logic("Home_model")->getNearbyDisasters($latitude, $longitude);

//     if (!empty($disasters)) {
//         // Ekstrak jenis bencana dan buat pesan
//         $disasterType = $disasters['jenis_bencana'];
//         $message = "Ada laporan bencana: $disasterType di lokasi " . $disasters['lokasi_bencana'];

//         // Dapatkan nomor WhatsApp pengguna
//         $whatsappNumber = $this->logic("Home_model")->getUserWhatsAppNumber($user_id);

//         // Kirim pesan ke setiap nomor secara terpisah
//         foreach ($whatsappNumber as $target) {
//             $curl = curl_init();

//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => 'https://api.fonnte.com/send',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => array(
//                     'target' => $target,
//                     'message' => $message,
//                     'delay' => '2',
//                     'countryCode' => '62', // optional
//                 ),
//                 CURLOPT_HTTPHEADER => array(
//                     'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya
//                 ),
//             ));

//             // Kirim permintaan dan tutup curl
//             $response = curl_exec($curl);
//             curl_close($curl);

//             // Debugging: Tampilkan respon dari API
//             echo "Message sent to $target: $response\n";

//             // Update status notifikasi setelah berhasil dikirim
//             $this->logic("Home_model")->mark_report_as_notified_masyarakat($user_id);
//         }
//     } else {
//         echo json_encode(['status' => 'no_disaster_nearby', 'message' => 'No nearby disasters']);
//     }
// }


	//Fungsi untuk menghitung jarak antara dua koordinat
	public function calculateDistance($lat1, $lon1, $lat2, $lon2) {
		$earthRadius = 6371; // Jarak rata-rata bumi dalam kilometer

		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);

		$a = sin($dLat / 2) * sin($dLat / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($dLon / 2) * sin($dLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $earthRadius * $c; // Mengembalikan jarak dalam kilometer
	}

	public function sendWhatsAppNotificationMasyarakat() {
		// Dapatkan data pelaporan terbaru
		$latestReport = $this->logic("Home_model")->get_latest_report();
	
		//$latestReport = $arr_data['latestReport'];
		// Pastikan laporan terbaru tidak null
		if (!$latestReport) {
			echo json_encode(['status' => 'no_latest_report', 'message' => 'No latest report found']);
			return; // Hentikan eksekusi jika tidak ada laporan
		}
	
		// Ekstrak jenis bencana dan buat pesan
		$disasterType = $latestReport[0]['jenis_bencana'];
		//var_dump($disasterType);
		$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport[0]['lokasi_bencana'];
	
		// Ambil latitude dan longitude dari laporan terbaru
		$reportLatitude = $latestReport['latitude'];
		$reportLongitude = $latestReport['longitude'];
	
		// Ambil data cookie latitude & longtitude
		
	
		// Kirim pesan ke pengguna yang berada dalam radius tertentu
		$radius = 13; // Jarak dalam kilometer
	
		 // Array untuk menyimpan nomor WhatsApp pengguna dalam radius
		 $targetsInRadius = [];

		
		// Pastikan pengguna memiliki latitude dan longitude
		if (isset($user['latitude'], $user['longitude'])) {
			$userLatitude = $user['latitude'];
			$userLongitude = $user['longitude'];

			// Hitung jarak antara pengguna dan lokasi bencana
			$distanceInMeters = $this->calculateDistance($reportLatitude, $reportLongitude, $userLatitude, $userLongitude);
			
			/// Konversi jarak ke kilometer dan bulatkan ke bawah
			$distanceInKilometers = floor($distanceInMeters / 1000);

			var_dump($distanceInKilometers);
			// Jika jarak kurang dari radius yang ditentukan, simpan user_id dan nomor WhatsApp
			if ($distanceInKilometers == $radius) {
				$targetsInRadius[] = [
					'user_id' => $user['user_id'],
					'whatsapp_number' => $user['whatsapp_number']
				]; // Simpan user_id dan nomor WhatsApp
			}
		}

		  
		// Jika ada pengguna dalam radius, kirim pesan ke semua
		if (!empty($targetsInRadius)) {
			foreach ($targetsInRadius as $target) {
				$whatsappNumber = $target['whatsapp_number'];
				// Inisialisasi CURL
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://api.fonnte.com/send',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => array(
						'target' => $whatsappNumber, // Gunakan nomor WhatsApp yang valid
						'message' => $message,
						'delay' => '2',
						'countryCode' => '62', // optional
					),
					CURLOPT_HTTPHEADER => array(
						'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya 
					),
				));

				// Eksekusi CURL
				$response = curl_exec($curl);
				curl_close($curl);

				// Output response untuk debugging (optional)
				echo "Message sent to $target: $response\n";
			}
		} else {
			echo "No users in the radius.\n"; // Pesan jika tidak ada pengguna dalam radius
		}
	
		// Update laporan bahwa notifikasi telah dikirim
		$this->logic("Home_model")->mark_report_as_notified_masyarakat($latestReport['laporan_id']);
	}
	
	// public function sendWhatsAppNotificationMasyarakat() {
	// 	// Ambil latitude dan longitude dari cookie
	// 	$userLatitude = isset($_COOKIE['latitude']) ? $_COOKIE['latitude'] : null;
	// 	$userLongitude = isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : null;
	// 	var_dump($userLatitude);

	// 	// Ambil user_id dan nomor WhatsApp dari session
	// 	$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
	// 	$userWhatsApp = isset($_SESSION['whatsapp_number']) ? $_SESSION['whatsapp_number'] : null;
		
	// 	// Pastikan data pengguna tersedia
	// 	if (!$userLatitude || !$userLongitude || !$userId || !$userWhatsApp) {
	// 		echo json_encode(['status' => 'error', 'message' => 'Missing user data']);
	// 		return;
	// 	}
		
	// 	// Dapatkan data laporan bencana terbaru
	// 	$latestReport = $this->logic("Home_model")->get_latest_report();
		
	// 	if (!$latestReport) {
	// 		echo json_encode(['status' => 'no_latest_report', 'message' => 'No latest report found']);
	// 		return;
	// 	}
	
	// 	// Lokasi bencana
	// 	$reportLatitude = $latestReport[0]['latitude'];
	// 	$reportLongitude = $latestReport[0]['longitude'];
	// 	$disasterType = $latestReport[0]['jenis_bencana'];
		
	// 	// Radius dalam kilometer
	// 	$radius = 10; // Contoh: radius 10 kilometer
	
	// 	// Hitung jarak antara pengguna dan lokasi bencana
	// 	$distanceInMeters = $this->calculateDistance($reportLatitude, $reportLongitude, $userLatitude, $userLongitude);
	// 	$distanceInKilometers = $distanceInMeters / 1000;
	
	// 	// Jika jarak dalam radius yang ditentukan, simpan user_id dan nomor WhatsApp
	// 	if ($distanceInKilometers <= $radius) {
	// 		// Array pengguna dalam radius bencana
	// 		$targetsInRadius[] = [
	// 			'user_id' => $userId,
	// 			'whatsapp_number' => $userWhatsApp
	// 		];
	
	// 		// Kirim pesan WhatsApp ke pengguna dalam radius
	// 		foreach ($targetsInRadius as $target) {
	// 			$this->sendWhatsAppMessage($target['whatsapp_number'], $disasterType, $latestReport[0]['lokasi_bencana']);
	// 		}
			
	// 		echo json_encode(['status' => 'success', 'message' => 'WhatsApp message sent to users in radius']);
	// 	} else {
	// 		echo json_encode(['status' => 'no_users_in_radius', 'message' => 'No users found in the specified radius']);
	// 	}
	// }
	
	// private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
	// 	$earthRadius = 6371000; // Radius bumi dalam meter
	
	// 	// Konversi derajat ke radian
	// 	$lat1Rad = deg2rad($lat1);
	// 	$lon1Rad = deg2rad($lon1);
	// 	$lat2Rad = deg2rad($lat2);
	// 	$lon2Rad = deg2rad($lon2);
	
	// 	$dLat = $lat2Rad - $lat1Rad;
	// 	$dLon = $lon2Rad - $lon1Rad;
	
	// 	$a = sin($dLat / 2) * sin($dLat / 2) + 
	// 		 cos($lat1Rad) * cos($lat2Rad) * 
	// 		 sin($dLon / 2) * sin($dLon / 2);
	
	// 	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	
	// 	return $earthRadius * $c; // Jarak dalam meter
	// }
	
	private function sendWhatsAppMessage($whatsappNumber, $disasterType, $disasterLocation) {
		// Kirim pesan WhatsApp menggunakan API WhatsApp (contoh menggunakan cURL)
		$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $disasterLocation;
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.fonnte.com/send',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'target' => $whatsappNumber, // Gunakan nomor WhatsApp yang valid
				'message' => $message,
				'delay' => '2',
				'countryCode' => '62', // optional
			),
			CURLOPT_HTTPHEADER => array(
				'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya 
			),
		));
	
		$response = curl_exec($curl);
		curl_close($curl);
		
		return $response;
	}
	
	
	
	




	public function getDataGempa() {
		$data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml") or die("Gagal mengakses!");
	
		$arr_data['tanggal'] = (string) $data->gempa->Tanggal;
		$arr_data['jam'] = (string) $data->gempa->Jam;
		$arr_data['date_time'] = (string) $data->gempa->DateTime;
		$arr_data['magnitudo'] = (string) $data->gempa->Magnitude;
		$arr_data['kedalaman'] = (string) $data->gempa->Kedalaman;
		$arr_data['koordinat'] = (string) $data->gempa->point->coordinates;
		$arr_data['lintang'] = (string) $data->gempa->Lintang;
		$arr_data['bujur'] = (string) $data->gempa->Bujur;
		$arr_data['lokasi'] = (string) $data->gempa->Wilayah;
		$arr_data['potensi'] = (string) $data->gempa->Potensi;
		$arr_data['dirasakan'] = (string) $data->gempa->Dirasakan;
		$arr_data['shakemap'] = (string) $data->gempa->Shakemap;
	
		// var_dump($arr_data);
		// Panggil view untuk menampilkan data
		$this->display("home/map", $arr_data);
	}

	public function getDataGempaMobile() {
		// Mengambil data XML dari URL BMKG
		$data = simplexml_load_file("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml") or die("Gagal mengakses!");
	
		// Memproses data gempa menjadi array
		$arr_data = [
			'tanggal' => (string) $data->gempa->Tanggal,
			'jam' => (string) $data->gempa->Jam,
			'date_time' => (string) $data->gempa->DateTime,
			'magnitudo' => (string) $data->gempa->Magnitude,
			'kedalaman' => (string) $data->gempa->Kedalaman,
			'koordinat' => (string) $data->gempa->point->coordinates,
			'lintang' => (string) $data->gempa->Lintang,
			'bujur' => (string) $data->gempa->Bujur,
			'lokasi' => (string) $data->gempa->Wilayah,
			'potensi' => (string) $data->gempa->Potensi,
			'dirasakan' => (string) $data->gempa->Dirasakan,
			'shakemap' => (string) $data->gempa->Shakemap
		];
	
		// Mengirimkan data dalam format JSON ke frontend
		header('Content-Type: application/json');
		echo json_encode($arr_data);
	}
	
	
	

	// public function sendWhatsAppNotifLembaga() {
	// 	// Dapatkan data pelaporan terbaru
	// 	$arr_data['latestReport'] = $this->logic("Home_model")->get_latest_report();
	
	// 	// Gunakan data yang diambil
	// 	$latestReport = $arr_data['latestReport'];

	// 	// // Jika laporan terbaru ada dan belum dinotifikasi, kirim notifikasi
    //     // if (!empty($latestReport) && $latestReport['is_notified'] == 0) {
    //     //     $this->sendWhatsAppNotification($latestReport);
    //     // }
	
	// 	// Ekstrak jenis bencana dan buat pesan
	// 	$disasterType = $latestReport['jenis_bencana'];
	// 	$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport['lokasi_bencana'];
	
	// 	// Dapatkan semua nomor relawan dari database
	// 	$allTargetsArray = array("120363260248262080@g.us", "120363262265062569@g.us");
		
	// 	// Kirim pesan ke setiap nomor secara terpisah
	// 	foreach ($allTargetsArray as $target) {
	// 		$curl = curl_init();
	
	// 		curl_setopt_array($curl, array(
	// 			CURLOPT_URL => 'https://api.fonnte.com/send',
	// 			CURLOPT_RETURNTRANSFER => true,
	// 			CURLOPT_ENCODING => '',
	// 			CURLOPT_MAXREDIRS => 10,
	// 			CURLOPT_TIMEOUT => 0,
	// 			CURLOPT_FOLLOWLOCATION => true,
	// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 			CURLOPT_CUSTOMREQUEST => 'POST',
	// 			CURLOPT_POSTFIELDS => array(
	// 				'target' => $target,
	// 				'message' => $message,
	// 				'delay' => '2',
	// 				'countryCode' => '62', // optional
	// 			),
	// 			CURLOPT_HTTPHEADER => array(
	// 				'Authorization: mg2WVMDC13xj9Pj1mUDB' // Ganti TOKEN dengan token yang sebenarnya
	// 			),
	// 		));
	
	// 		$response = curl_exec($curl);
	// 		curl_close($curl);
	
	// 		// Output response for debugging (optional)
	// 		echo "Message sent to $target: $response\n";

	// 		//Simpan data notifikasi ke database
    //         $notificationData = array(
    //             'laporan_id' => $latestReport['laporan_id'],
    //             'user_id' => $target,
    //             'status' => 'sent',
    //             'message' => $message,
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         );
    //         // $this->Home_model->save_notification($notificationData);
	// 		$this->logic("Home_model")->save_notification($notificationData);
	// 	}

	// 	// Update laporan bahwa notifikasi telah dikirim
    //     // $this->Home_model->mark_report_as_notified($report['id']);
	// }

	public function checkNewReportAndSendNotif() {
		// Cek laporan yang belum dinotifikasi
		$newReports = $this->logic("Home_model")->get_unnotified_reports();
	
		// Periksa apakah $newReports adalah array dan memiliki data
		if (is_array($newReports) && !empty($newReports)) {
			foreach ($newReports as $latestReport) {
				// Pastikan $latestReport adalah array sebelum mengakses elemen-elemennya
				if (is_array($latestReport)) {
					// Ekstrak jenis bencana dan buat pesan
					$disasterType = $latestReport['jenis_bencana'] ?? 'Tidak diketahui'; // Gunakan null coalescing untuk default value
					$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport['lokasi_bencana'];
	
					// Daftar grup berdasarkan instansi
					$institutionGroups = array(
						'BPBD' => '120363296878026235@g.us',
						'Dinas Lingkungan Hidup' => '120363314495930112@g.us',
						'Satlantas' => '120363311940237940@g.us',
						'PUBR' => '120363343925640897@g.us',
						'Dinas Pemadam Kebakaran' => '120363313724168474@g.us',
						'POLRI' => '120363294894303917@g.us',
					);
	
					// Periksa instansi dari laporan
					$instansiList = explode(',', $latestReport['hubungi_instansi_terkait']);
					
					// Trim setiap instansi untuk menghilangkan spasi
					$instansiList = array_map('trim', $instansiList);

					var_dump($instansiList);

					// Kirim pesan hanya ke instansi yang sesuai
					// Kirim pesan ke setiap instansi yang ada di laporan
					foreach ($instansiList as $instansi) {
						if (isset($institutionGroups[$instansi])) {
							$target = $institutionGroups[$instansi];
	
							// Inisiasi CURL untuk kirim pesan
							$curl = curl_init();
							curl_setopt_array($curl, array(
								CURLOPT_URL => 'https://api.fonnte.com/send',
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_ENCODING => '',
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 0,
								CURLOPT_FOLLOWLOCATION => true,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => 'POST',
								CURLOPT_POSTFIELDS => array(
									'target' => $target,
									'message' => $message,
									'delay' => '2',
									'countryCode' => '62',
								),
								CURLOPT_HTTPHEADER => array(
									'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya
								),
							));
	
							$response = curl_exec($curl);
							curl_close($curl);
	
							// Output response untuk debugging (opsional)
							echo "Message sent to $target: $response\n";
						}
					}

					 // Tandai laporan sudah dinotifikasi
					 $this->logic("Home_model")->mark_report_as_notified($latestReport['laporan_id']);
				} else {
					// Jika $latestReport bukan array, tampilkan pesan error untuk debugging
					echo "Error: Expected array but got a different type.\n";
				}
			}
		} else {
			// Jika $newReports kosong atau bukan array, tampilkan pesan error
			echo "No new reports found or data format is incorrect.\n";
		}
	}
	
	// public function runPolling() {
    //     while (true) {
    //         $this->checkNewReportAndSendNotif(); // Memeriksa laporan baru
    //         sleep(5); // Delay 5 detik sebelum melakukan polling lagi
    //     }
    // }



	public function sendWhatsAppNotificationRelawan() {
		// Dapatkan data pelaporan terbaru
		$arr_data['latestReport'] = $this->logic("Home_model")->get_latest_report();
	
		// Gunakan data yang diambil
		$latestReport = $arr_data['latestReport'];

		// // Jika laporan terbaru ada dan belum dinotifikasi, kirim notifikasi
        // if (!empty($latestReport) && $latestReport['is_notified'] == 0) {
        //     $this->sendWhatsAppNotification($latestReport);
        // }
	
		// Ekstrak jenis bencana dan buat pesan
		$disasterType = $latestReport[0]['jenis_bencana'];
		$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport[0]['lokasi_bencana'];
	
		// Dapatkan semua nomor relawan dari database
		$allTargetsArray = $this->logic("Home_model")->get_all_volunteer_numbers();
		
		// Kirim pesan ke setiap nomor secara terpisah
		foreach ($allTargetsArray as $target) {
			$curl = curl_init();
	
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.fonnte.com/send',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array(
					'target' => $target,
					'message' => $message,
					'delay' => '2',
					'countryCode' => '62', // optional
				),
				CURLOPT_HTTPHEADER => array(
					'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya
				),
			));
	
			$response = curl_exec($curl);
			curl_close($curl);
	
			// Output response for debugging (optional)
			echo "Message sent to $target: $response\n";

			//Simpan data notifikasi ke database
            $notificationData = array(
                'laporan_id' => $latestReport['laporan_id'],
                'user_id' => $target,
                'status' => 'sent',
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            // $this->Home_model->save_notification($notificationData);
			$this->logic("Home_model")->save_notification($notificationData);
		}

		// Update laporan bahwa notifikasi telah dikirim
        // $this->Home_model->mark_report_as_notified($report['id']);
		$this->logic("Home_model")->mark_report_as_notified($latestReport['']);
	}

	// public function getLatestReport() {
	// 	$arr_data['latestReport'] = $this->logic("Home_model")->get_latest_report();
	// 	$latestReport = $arr_data['latestReport'];
	// 	var_dump($latestReport);

	// 	$this->display("home/laporanlembaga", $arr_data);
	// }

	// public function getAllPublicNumbers() {
	// 	$arr_data['latestReport'] = $this->logic("Home_model")->get_all_public_numbers();

	// 	$this->display("home/laporanlembaga", $arr_data);
	// }

	// public function sendWhatsAppNotificationMasyarakat() {
	// 	// Dapatkan data pelaporan terbaru
	// 	$arr_data['latestReport'] = $this->logic("Home_model")->get_latest_report();
	
	// 	// Gunakan data yang diambil
	// 	$latestReport = $arr_data['latestReport'];

	// 	// // Jika laporan terbaru ada dan belum dinotifikasi, kirim notifikasi
    //     // if (!empty($latestReport) && $latestReport['is_notified'] == 0) {
    //     //     $this->sendWhatsAppNotification($latestReport);
    //     // }
	
	// 	// Ekstrak jenis bencana dan buat pesan
	// 	$disasterType = $latestReport['jenis_bencana'];
	// 	$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport['lokasi_bencana'];
	
	// 	// Dapatkan semua nomor relawan dari database
	// 	$allTargetsArray = $this->logic("Home_model")->get_all_public_numbers();
		
	// 	// Kirim pesan ke setiap nomor secara terpisah
	// 	foreach ($allTargetsArray as $target) {
	// 		$curl = curl_init();
	
	// 		curl_setopt_array($curl, array(
	// 			CURLOPT_URL => 'https://api.fonnte.com/send',
	// 			CURLOPT_RETURNTRANSFER => true,
	// 			CURLOPT_ENCODING => '',
	// 			CURLOPT_MAXREDIRS => 10,
	// 			CURLOPT_TIMEOUT => 0,
	// 			CURLOPT_FOLLOWLOCATION => true,
	// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 			CURLOPT_CUSTOMREQUEST => 'POST',
	// 			CURLOPT_POSTFIELDS => array(
	// 				'target' => $target,
	// 				'message' => $message,
	// 				'delay' => '2',
	// 				'countryCode' => '62', // optional
	// 			),
	// 			CURLOPT_HTTPHEADER => array(
	// 				'Authorization: GRnm9ah7XakS8sJnXhKQ' // Ganti TOKEN dengan token yang sebenarnya
	// 			),
	// 		));
	
	// 		$response = curl_exec($curl);
	// 		curl_close($curl);
	
	// 		// Output response for debugging (optional)
	// 		echo "Message sent to $target: $response\n";
	// 	}

	// 	// Update laporan bahwa notifikasi telah dikirim
    //     // $this->Home_model->mark_report_as_notified($report['id']);
	// 	$this->logic("Home_model")->mark_report_as_notified($latestReport['laporan_id']);
	// }	

	// // Fungsi untuk menghitung jarak
	function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371) {
		// Konversi derajat ke radian
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);
	
		// Hitung perbedaan
		$lonDelta = $lonTo - $lonFrom;
		$latDelta = $latTo - $latFrom;
	
		// Rumus Haversine
		$a = sin($latDelta / 2) * sin($latDelta / 2) +
			 cos($latFrom) * cos($latTo) *
			 sin($lonDelta / 2) * sin($lonDelta / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	
		return $earthRadius * $c; // Jarak dalam kilometer
	}

	public function get_user_coordinates($user_id) {
		// Dapatkan data user dari database
		$user = $this->logic("Home_model")->get_user_by_id($user_id);
	
		// Cek apakah user sudah memiliki latitude dan longitude
		if (!empty($user['latitude']) && !empty($user['longitude'])) {
			// Jika koordinat sudah tersedia, kembalikan data
			return array('latitude' => $user['latitude'], 'longitude' => $user['longitude']);
		}
	
		// Jika koordinat belum tersedia dan ada alamat, lakukan geocoding
		if (!empty($user['address'])) {
			$address = urlencode($user['address']);
			$url = "https://nominatim.openstreetmap.org/search?q=$address&format=json&addressdetails=1&limit=1";
	
			// Menggunakan cURL untuk mendapatkan data dari API
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; My PHP App)');
	
			// Eksekusi dan ambil responsnya
			$response = curl_exec($ch);
			curl_close($ch);
	
			$responseData = json_decode($response, true);
	
			if (!empty($responseData)) {
				$coordinates = $responseData[0];
				$latitude = $coordinates['lat'];
				$longitude = $coordinates['lon'];
	
				// Simpan latitude dan longitude di database
				$this->logic("Home_model")->update_user_coordinates($user_id, $latitude, $longitude);
	
				return array('latitude' => $latitude, 'longitude' => $longitude);
			}
		}
	
		// Jika tidak ada data yang bisa digunakan
		return array('latitude' => null, 'longitude' => null);
	}
	
	

	public function sendWhatsAppNotificationMasyarakatGeofencing() {
		// Dapatkan data pelaporan terbaru
		$arr_data['latestReport'] = $this->logic("Home_model")->get_latest_report();
		
		// Gunakan data yang diambil
		$latestReport = $arr_data['latestReport'];
	
		// Ekstrak jenis bencana dan buat pesan
		$disasterType = $latestReport['jenis_bencana'];
		$message = "Ada laporan bencana: " . $disasterType . " di lokasi " . $latestReport['lokasi_bencana'];
	
		// Dapatkan semua user dengan nomor telepon, latitude, dan longitude
		$allUsers = $this->logic("Home_model")->get_all_users_with_coordinates();
		var_dump($allUsers);
	
		// var_dump($allUsers);
		// Jika user tidak memiliki koordinat, gunakan layanan untuk mendapatkannya (misalnya, menggunakan API Geolocation)
		foreach ($allUsers as $user) {
			if (empty($user['latitude']) || empty($user['longitude'])) {
				// Jika user tidak memiliki koordinat, mintalah melalui browser
				echo "<script>getUserCoordinates({$user['user_id']});</script>";
				continue;  // Skip the user until coordinates are available
			}
	
			// Periksa apakah user dekat dengan lokasi bencana
			if ($this->haversineGreatCircleDistance($user['latitude'], $user['longitude'], $latestReport['latitude'], $latestReport['longitude'])) {
				// Inisiasi CURL untuk kirim pesan
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://api.fonnte.com/send',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => array(
						'target' => $user['phone_number'],
						'message' => $message,
						'delay' => '2',
						'countryCode' => '62', // optional
					),
					CURLOPT_HTTPHEADER => array(
						'Authorization: mg2WVMDC13xj9Pj1mUDB' // Ganti TOKEN dengan token yang sebenarnya
					),
				));
		
				$response = curl_exec($curl);
				curl_close($curl);
		
				// Output response for debugging (optional)
				echo "Message sent to " . $user['phone_number'] . ": $response\n";
	
				// Simpan data notifikasi ke database
				$notificationData = array(
					'laporan_id' => $latestReport['laporan_id'],
					'user_id' => $user['user_id'],
					'status' => 'sent',
					'message' => $message,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);
				$this->logic("Home_model")->save_notification($notificationData);
			}
		}
	
		// Update laporan bahwa notifikasi telah dikirim
		$this->logic("Home_model")->mark_report_as_notified($latestReport['laporan_id']);
	}
	
	
	
	
	// Lapor sukses
	public function success(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home"; 
			
			// Display page and send data
			$this->display('template/home/header', $_SESSION);
			$this->display("home/success", $_SESSION);
			$this->display('template/home/footer', $_SESSION);
		}
	}
	
	// Lapor pending
	public function pending(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home"; 
			
			// Display page and send data
			$this->display('template/home/header', $_SESSION);
			$this->display("home/pending", $_SESSION);
			$this->display('template/home/footer', $_SESSION);
		}
	}
	
	// Lapor error
	public function error(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home"; 
			
			// Display page and send data
			$this->display('template/home/header', $_SESSION);
			$this->display("home/error", $_SESSION);
			$this->display('template/home/footer', $_SESSION);
		}
	}
	
	// Laporan bukan bencana
	public function bukanbencana(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home"; 
			
			// Display page and send data
			$this->display('template/home/header', $_SESSION);
			$this->display("home/bukanbencana", $_SESSION);
			$this->display('template/home/footer', $_SESSION);
		}
	}
	
	// data laporan bencana
	public function data(){
		session_start();
		//print_r($_SESSION);
		
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Data Pelaporan"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			
			try {
				// get data
				$arr_data['datalaporan'] = $this->logic("Home_model")->get_data_pelaporan_web($_SESSION['user_id']);
				// var_dump($arr_data['datalaporan']);
				
				// Display
				$this->display('template/home/header', $arr_data);
				$this->display("home/data", $arr_data);
				$this->display('template/home/footer');
				
			} catch (Exception $e) {
				// Handle exceptions
				// Redirect
				header('Location: '.APP_PATH.'/');
				exit();
			}
		}
	}

	// data laporan bencana
	// public function datalaporanmobile()
	// {
	// 	// Pastikan respons JSON
	// 	// header('Content-Type: application/json');
	
	// 	// // Autentikasi menggunakan token (misalnya, Bearer Token dari Header)
	// 	// $headers = apache_request_headers();
	// 	// if (!isset($headers['Authorization'])) {
	// 	// 	http_response_code(401);
	// 	// 	echo json_encode(['error' => 'Unauthorized']);
	// 	// 	exit();
	// 	// }
	
	// 	// // Ambil token dari header dan validasi
	// 	// $authHeader = $headers['Authorization'];
	// 	// $token = str_replace('Bearer ', '', $authHeader);
	
	// 	// // Fungsi validasi token atau periksa dari database (contoh sederhana)
	// 	// $user = $this->validateToken($token);
	// 	// if (!$user) {
	// 	// 	http_response_code(401);
	// 	// 	echo json_encode(['error' => 'Invalid token']);
	// 	// 	exit();
	// 	// }
	// 	$homeModel = new Home_model();

	// 	// Fetch report data using get_data_pelaporan_web
	// 	$reportData = $homeModel->get_data_pelaporan_web($_SESSION['user_id']);

	
	// 	$data = [
	// 		"title" => "Data Pelaporan",
	// 		"user_id" => $_SESSION['user_id'],
	// 		"user_name" => $_SESSION['user_name'],
	// 		"role" => $_SESSION['role'],
	// 		"whatsapp_number" => $_SESSION['whatsapp_number'],
	// 		"email" => $_SESSION['email'],
	// 		"datalaporan" => $reportData // Replace with your data fetching logic
	// 	];
	
	// 	// Return data in JSON format
	// 	echo json_encode([
	// 		"success" => true,
	// 		"data" => $data
	// 	]);
	// 	// try {
	// 	// 	// Dapatkan data pelaporan berdasarkan user_id
	// 	// 	$arr_data['datalaporan'] = $this->logic("Home_model")->get_data_pelaporan_web($_SESSION['user_id']);
	
	// 	// 	// Kembalikan respons JSON
	// 	// 	echo json_encode($arr_data);
	
	// 	// } catch (Exception $e) {
	// 	// 	// Tangani kesalahan
	// 	// 	http_response_code(500);
	// 	// 	echo json_encode(['error' => 'GET DATA FAILED']);
	// 	// }
	// } 

	public function datalaporanmobile () {
		 // Ambil user_id dari parameter query string
		 $user_id = $_GET['user_id'] ?? null;

		 // Cek apakah user_id valid
		 if (!$user_id) {
			 http_response_code(400); // Bad Request
			 echo json_encode(['error' => 'User ID is required']);
			 return;
		 }
	 
		try {
		
			// Fetch report data using get_data_pelaporan_web
			$reportData = $this->logic("Home_model")->get_data_pelaporan_web($user_id);
		
			// Cek apakah data ditemukan
			if (empty($dataPelaporan)) {
				http_response_code(404); // Not Found
				echo json_encode(['message' => 'No reports found for this user']);
				return;
			}
	
			// Mengembalikan data sebagai JSON
			header('Content-Type: application/json');
			echo json_encode($dataPelaporan);
		} catch (Exception $e) {
			echo json_encode([
				"success" => false,
				"message" => "An error occurred: " . $e->getMessage()
			]);
			exit();
		}
	}

	
	
	// Fungsi untuk validasi token (contoh sederhana)
	private function validateToken($token)
	{
		// Implementasikan logika validasi token sesuai kebutuhan (misalnya, cocokkan dengan database)
		// Misalnya:
		return $this->logic("Auth_model")->get_user_by_token($token);
	}
	
	
	
	// data laporan bencana
	public function area(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/index/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home"; 
			// get data
			$arr_data['datalaporan'] = $this->logic("Home_model")->get_data_pelaporan_web($_SESSION['user_id']);
			//print_r($datalaporan);
			
			
			// Display page and send data
			$this->display('template/home/header', $_SESSION);
			$this->display("home/area", $_SESSION);
			$this->display('template/home/footer', $_SESSION);
		}
	}

	
	// about
	public function about(){
		session_start();
		// Cek session
		if (!isset($_SESSION['user_name'])) {
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "About";
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/about", $arr_data);
			$this->display('template/home/footer');
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "About"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/about", $arr_data);
			$this->display('template/home/footer'); 
		}
		
	}
	
	// method
	public function map(){
		session_start();
		// Cek session
		if (!isset($_SESSION['user_name'])) {
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Map";
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/map", $arr_data);
			$this->display('template/home/footer');
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Map"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/map", $arr_data);
			$this->display('template/home/footer'); 
		}
	}

		// method
	// public function mapAdmin(){
	// 	session_start();
	// 	// Cek session
	// 	if (!isset($_SESSION['user_name'])) {
	// 		// Associative Arrays (arrays with keys)
	// 		$arr_data['title'] = "Map";
			
	// 		// Display page and send data
	// 		$this->display('template/header', $_SESSION);
	// 		$this->display("template/sidebar", $arr_data);
	// 		$this->display("home/map", $arr_data);
	// 		// $this->display("home/dashboardlembaga", $arr_data);
	// 		$this->display("home/index", $arr_data);
	// 		$this->display('template/footer');
		
	// 	}else{
	// 		// Associative Arrays (arrays with keys)
	// 		$arr_data['title'] = "Map"; 
	// 		$arr_data['user_id'] = $_SESSION['user_id'];
	// 		$arr_data['user_name'] = $_SESSION['user_name'];
	// 		$arr_data['role'] = $_SESSION['role'];
	// 		$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
	// 		$arr_data['email'] = $_SESSION['email'];

	// 		// Display page and send data
	// 		$this->display('template/header', $arr_data);
	// 		$this->display("template/sidebar", $arr_data);
	// 		$this->display("home/mapadmin", $arr_data);
	// 		$this->display('template/home/footer');
	// 		$this->display("home/index", $arr_data);
	// 		$this->display('template/footer'); 
	// 	}
	// }
	
	public function mapAdmin() {
		session_start();
		if (!isset($_SESSION['user_name'])) {
			header('Location: '.APP_PATH.'/login/');
			exit();
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/map", $arr_data);
			// $this->display("home/dashboardlembaga", $arr_data);
			$this->display("home/index", $arr_data);
			$this->display('template/footer');
		} else {
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Map"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			$arr_data['gender'] = $_SESSION['gender'];
			
			try {
				// Display page and send data
				$this->display('template/header', $arr_data);
				$this->display("template/sidebar", $arr_data);
				$this->display("home/mapadmin", $arr_data);
				$this->display('template/home/footer');
				$this->display("home/index", $arr_data);
				$this->display('template/footer');
			} catch (Exception $e) {
				header('Location: '.APP_PATH.'/');
				exit();
			}
		}
	
		// Correct the key name in error_log
		error_log(print_r($arr_data['total_report'], true));
	}

	public function mapLembaga() {
		session_start();
		if (!isset($_SESSION['user_name'])) {
			header('Location: '.APP_PATH.'/login/');
			exit();
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/map", $arr_data);
			// $this->display("home/dashboardlembaga", $arr_data);
			$this->display("home/index", $arr_data);
			$this->display('template/footer');
		} else {
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Map"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			$arr_data['gender'] = $_SESSION['gender'];
			
			try {
				// Display page and send data
				$this->display('template/header', $arr_data);
				$this->display("template/sidebarlembaga", $arr_data);
				$this->display("home/maplembaga", $arr_data);
				$this->display('template/home/footer');
				$this->display("home/index", $arr_data);
				$this->display('template/footer');
			} catch (Exception $e) {
				header('Location: '.APP_PATH.'/');
				exit();
			}
		}
	
		// Correct the key name in error_log
		error_log(print_r($arr_data['total_report'], true));
	}

	public function datalaporanweb(){
		try {
			// get data
			$datalaporan = $this->logic("Home_model")->show_data_pelaporan_map();
			
			// Output JSON response
			header('Content-Type: application/json');
			echo json_encode($datalaporan);
			
		} catch (Exception $e) {
			// Handle exceptions
			echo "GET DATA FAILED";
		}		
	}

	public function dashboard() {
		session_start();
		if (!isset($_SESSION['user_name'])) {
			header('Location: '.APP_PATH.'/login/');
			exit();
		} else {
			$arr_data['title'] = "Data Pelaporan"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			
			try {
				// Fetch statistics
				$arr_data['total_report'] = $this->logic("Home_model")->get_total_reports($arr_data['user_id']);
				
				// var_dump($arr_data['total_report']);

				$arr_data['reports_by_status'] = $this->logic("Home_model")->get_reports_by_status($arr_data['user_id']);
				// $arr_data['most_frequent_category'] = $this->logic("Home_model")->get_most_frequent_category($arr_data['user_id'])['jenis_bencana'];
				$arr_data['all_categories'] = $this->logic("Home_model")->get_all_categories($arr_data['user_id']);
				// var_dump($arr_data['all_categories']);

				// Cek apakah $arr_data['all_categories'] kosong
				if (empty($arr_data['all_categories'])) {
					$arr_data['all_categories'] = []; // Jika tidak ada data, set sebagai array kosong
				}

				$arr_data['report_trends_daily'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 1);
				$arr_data['report_trends_weekly'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 7);
				$arr_data['report_trends_monthly'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 30);


				//var_dump($arr_data['report_trends_weekly']);
				// var_dump($arr_data['all_categories']);
				// Display page
				$this->display('template/header', $_SESSION);
				$this->display("template/sidebar", $arr_data);
				$this->display("home/dashboard", $arr_data);
				// $this->display("home/dashboardlembaga", $arr_data);
				$this->display("home/index", $arr_data);
				$this->display('template/footer');
				
			} catch (Exception $e) {
				header('Location: '.APP_PATH.'/');
				exit();
			}
		}
	
		// Correct the key name in error_log
		error_log(print_r($arr_data['total_report'], true));
	}

	public function dashboardlembaga() {
		session_start();
		if (!isset($_SESSION['user_name'])) {
			header('Location: '.APP_PATH.'/login/');
			exit();
		} else {
			$arr_data['title'] = "Data Pelaporan"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			
			try {
				// Fetch statistics
				$arr_data['total_report'] = $this->logic("Home_model")->get_total_reports_lembaga($arr_data['user_id']);
				
				// var_dump($arr_data['total_report']);

				$arr_data['reports_by_status'] = $this->logic("Home_model")->get_reports_by_status_lembaga($arr_data['user_id']);
				// $arr_data['most_frequent_category'] = $this->logic("Home_model")->get_most_frequent_category($arr_data['user_id'])['jenis_bencana'];
				$arr_data['all_categories'] = $this->logic("Home_model")->get_all_categories($arr_data['user_id']);
				// var_dump($arr_data['all_categories']);

				// Cek apakah $arr_data['all_categories'] kosong
				if (empty($arr_data['all_categories'])) {
					$arr_data['all_categories'] = []; // Jika tidak ada data, set sebagai array kosong
				}

				$arr_data['report_trends_daily'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 1);
				$arr_data['report_trends_weekly'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 7);
				$arr_data['report_trends_monthly'] = $this->logic("Home_model")->get_report_trends($arr_data['user_id'], 30);


				//var_dump($arr_data['report_trends_weekly']);
				// var_dump($arr_data['all_categories']);
				// Display page
				$this->display('template/header', $_SESSION);
				$this->display("template/sidebarlembaga", $arr_data);
				// $this->display("home/dashboard", $arr_data);
				$this->display("home/dashboardlembaga", $arr_data);
				$this->display("home/index", $arr_data);
				$this->display('template/footer');
				
			} catch (Exception $e) {
				header('Location: '.APP_PATH.'/');
				exit();
			}
		}
	
		// Correct the key name in error_log
		error_log(print_r($arr_data['total_report'], true));
	}

	
		
	// Data laporan
	public function laporan(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Data Pelaporan"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			$arr_data['whatsapp_number'] = $_SESSION['whatsapp_number'];
			$arr_data['email'] = $_SESSION['email'];
			
			try {
				// get data
				$arr_data['datalaporan'] = $this->logic("Home_model")->get_data_pelaporan_web_admin($_SESSION['user_id']);
				//print_r($datalaporan);
				
				// Display page and send data
				$this->display('template/header', $_SESSION);
				$this->display("template/sidebar", $arr_data);
				$this->display("home/laporan", $arr_data);
				$this->display('template/footer');
				
			} catch (Exception $e) {
				// Handle exceptions
				// Redirect
				header('Location: '.APP_PATH.'/');
				exit();
			}
			
		}
	}

	// Data laporan
	public function laporanlembaga(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Data Pelaporan"; 
			$arr_data['user_id'] = $_SESSION['user_id'];
			$arr_data['user_name'] = $_SESSION['user_name'];
			$arr_data['role'] = $_SESSION['role'];
			// $arr_data['no_telp'] = $_SESSION['no_telp'];
			$arr_data['email'] = $_SESSION['email'];
			
			try {
				// get data
				$arr_data['datalaporan'] = $this->logic("Home_model")->get_data_pelaporan_web_lembaga($_SESSION['user_id']);
				//var_dump($arr_data['datalaporan']);
				
				// Display page and send data
				$this->display('template/header', $_SESSION);
				$this->display("template/sidebarlembaga", $arr_data);
				$this->display("home/laporanlembaga", $arr_data);
				$this->display('template/footer');
				
			} catch (Exception $e) {
				// Handle exceptions
				// Redirect
				header('Location: '.APP_PATH.'/');
				exit();
			}
			
		}
	}
		
		
	// Data pengguna
	public function pengguna(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
		try {
		// get data
			$arr_data['datapengguna'] = $this->logic("Home_model")->get_data_pengguna($_SESSION['user_id']);
			//print_r($datapengguna);
				
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/pengguna", $arr_data);
			$this->display('template/footer');
				
			} catch (Exception $e) {
				// Handle exceptions
				// Redirect
				header('Location: '.APP_PATH.'/');
				exit();
			}
			
		}
	}
	

	// Data relawan
	public function relawan(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
		try {
		// get data
			$arr_data['datarelawan'] = $this->logic("Home_model")->get_data_relawan($_SESSION['relawan_id']);
			//print_r($datarelawan);
				
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/relawan", $arr_data);
			$this->display('template/footer');
				
			} catch (Exception $e) {
				// Handle exceptions
				// Redirect
				header('Location: '.APP_PATH.'/');
				exit();
			}
			
		}
	}
	// Data notifikasi
	public function notifikasi(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/notifikasi", $_SESSION);
			$this->display('template/footer');
		}
	}	
	
	// Profile
	public function profile(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/profile", $_SESSION);
			$this->display('template/footer');
		}
	}	

	public function profilelembaga(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebarlembaga", $arr_data);
			$this->display("home/profilelembaga", $_SESSION);
			$this->display('template/footer');
		}
	}	

	public function profileMasyarakatRelawan(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/home/header', $arr_data);
			$this->display("home/profilemasyarakatrelawan", $_SESSION);
		}
	}	
	
	
	// Contact
	public function contact(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/contact", $_SESSION);
			$this->display('template/footer');
		}
	}	
	
	
	// Register
	public function register(){
		session_start();
		// Cek session pada halaman dashboard
		if (!isset($_SESSION['user_name'])) {
			// Jika session tidak ada, redirect ke halaman login
			header('Location: '.APP_PATH.'/login/');
			exit();
		
		}else{
			// Associative Arrays (arrays with keys)
			$arr_data['title'] = "Home";
			
			// Display page and send data
			$this->display('template/header', $_SESSION);
			$this->display("template/sidebar", $arr_data);
			$this->display("home/register", $_SESSION);
			$this->display('template/footer');
		}
	}	
		

	// OpenAI GPT-4o Vision
	public function run_ai($pelapor_desk, $path_foto_bukti){
		//public function run_ai(){
			// Kunci API
			//$pelapor_desk = "ini adalah kebakaran hutan tropis, yang terjadi di satu daerah.";
			$apiKey = getenv('API_KEY');
	
			// Endpoint API OpenAI
			$url = "https://api.openai.com/v1/chat/completions";
	
			// Data request
			$data = [
				'model' => 'gpt-4o',
				'messages' => [
					[
						'role' => 'user',
						'content' => [
							['type' => 'text', 'text' => 'Periksa kesesuaian dari gambar, kalau gambarnya bukan gambar bencana, berikan error, dapatkan informasi yaitu jenis_bencana, klasifikasi_bencana (alam, non-alam, sosial, bukan bencana), level_kerusakan_infrastruktur (ringan, sedang, berat, total), level_bencana (ringan, sedang, besar, luar biasa), kesesuaian_laporan (seuai, tidak sesuai), deskripsi_singkat_ai,  saran_singkat, potensi_bahaya_lanjutan (yes, no), penilaian_akibat_bencana (persentasi), kondisi_cuaca dan hubungi_instansi_terkait (singkatan instansi dan text separator koma). Output dalam format json saja. Deskripsi Pelapor yaitu '.$pelapor_desk],
							[
								'type' => 'image_url',
								'image_url' => [
									'url' => ''.$path_foto_bukti,
								],
							],
						],
					],
				],
				'max_tokens' => 300,
			];
	
			// Inisialisasi cURL
			$ch = curl_init($url);
	
			// Atur opsi cURL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $apiKey,
			]);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	
			// Eksekusi permintaan
			$response = curl_exec($ch);
	
			// Periksa kesalahan
			if (curl_errno($ch)) {
				//echo 'Error:' . curl_error($ch);
				return [];
				
			} else {
				// Tampilkan hasil
				$result = json_decode($response, true);
				
				// Extract content
				$content = $result['choices'][0]['message']['content'];
				//echo $content;
	
				// Remove the enclosing triple backticks and "json" label
				$content = trim($content, '```json ');
	
				// Decode JSON to associative array
				$contentArray = json_decode($content, true);
	
				// Check for JSON errors
				if (json_last_error() === JSON_ERROR_NONE) {
					// Display the associative array
					//print_r($contentArray['hubungi_instansi_terkait']);
					
					return $contentArray;
	
				} else {
					//echo "JSON Decode Error: " . json_last_error_msg();
					return [];
				}
				
			}
	
			// close cURL
			curl_close($ch);
		}
	


	// submit laporan bencana
	public function submitlaporan() {
		session_start();
		print_r($_SESSION);
		
		// Cek session
		if (isset($_SESSION['user_name'])) {
			try {
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					// Debugging output
					print_r($_FILES);
					print_r($_POST);
					
					$reportDescription = filter_input(INPUT_POST, 'report-description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					
					// Handle file upload
					if (isset($_FILES['report-file']) && $_FILES['report-file']['error'] == UPLOAD_ERR_OK) {
						$uploadDir = '/var/www/html/app/public/fotobukti/'; // Directory to save the uploaded file
						$fileExtension = pathinfo($_FILES['report-file']['name'], PATHINFO_EXTENSION);
						$id_laporan = uniqid();
						$uploadFile = $uploadDir . $id_laporan . '.' . $fileExtension;
						$file_name = $id_laporan . '.' . $fileExtension;
				
						// Move the uploaded file to the server's directory
						if (move_uploaded_file($_FILES['report-file']['tmp_name'], $uploadFile)) {
							$reportFile = $uploadFile;
							//echo 'Laporan berhasil dikirim. File name: ' . $reportFile;
							
							// run AI
							$data_ai = $this->run_ai($reportDescription, "https://silaben.site/app/public/fotobukti/$file_name");
							$kesesuaian_laporan = $data_ai['kesesuaian_laporan'];
							$klasifikasi_bencana = $data_ai['klasifikasi_bencana'];
							
							//echo $kesesuaian_laporan;
							
							// insert data ke database
							$statuslaporan = $this->logic("Home_model")->insert_data_pelaporan_web($id_laporan, $file_name, $_POST, $data_ai);
						
							if($klasifikasi_bencana === "bukan bencana"){
								// redirect ke halaman
								header('Location: ' . APP_PATH . '/home/bukanbencana/');
								exit();
							}elseif($kesesuaian_laporan === "sesuai"){
								// Call the function to send notifications
								$this->checkNewReportAndSendNotif();
								// redirect ke halaman
								header('Location: ' . APP_PATH . '/home/success/');
								exit();
							}else{
								// redirect ke halaman
								header('Location: ' . APP_PATH . '/home/pending/');
								exit();
							}
							
							
						} else {
							// redirect ke halaman
							header('Location: ' . APP_PATH . '/home/error/');
							exit();
						}
					} else {
						// redirect ke halaman
						header('Location: ' . APP_PATH . '/home/error/');
						exit();
					}
				}else{
					// redirect ke halaman
					header('Location: ' . APP_PATH . '/home/error/');
					exit();
				}
			} catch (Exception $e) {
				// redirect ke halaman
				header('Location: ' . APP_PATH . '/home/error/');
				exit();
			}
		}else{
			// redirect ke halaman
			header('Location: ' . APP_PATH . '/login/index/');
			exit();
		}
	}

	// submit laporan bencana dari mobile
	public function submitlaporanmobile() {
		try {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// input data
				$reportDescription = filter_input(INPUT_POST, 'report-description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				
				// Handle file upload
				if (isset($_FILES['report-file']) && $_FILES['report-file']['error'] == UPLOAD_ERR_OK) {
					$uploadDir = '/var/www/html/app/public/fotobukti/'; // Directory to save the uploaded file
					$fileExtension = pathinfo($_FILES['report-file']['name'], PATHINFO_EXTENSION);
					$id_laporan = uniqid();
					$uploadFile = $uploadDir . $id_laporan . '.' . $fileExtension;
					$file_name = $id_laporan . '.' . $fileExtension;

					// Move the uploaded file to the server's directory
					if (move_uploaded_file($_FILES['report-file']['tmp_name'], $uploadFile)) {
						// run AI
						$data_ai = $this->run_ai($reportDescription, "https://silaben.site/app/public/fotobukti/$file_name");
						
						// insert data ke database
						$statuslaporan = $this->logic("Home_model")->insert_data_pelaporan_web($id_laporan, $file_name, $_POST, $data_ai);
						//print_r($data_ai);
						
						if($statuslaporan){
							echo "LAPOR SUCCESS";
						}else{
							echo "LAPOR FAILED";
						}
					} else {
						echo "LAPOR FAILED";
					}
				} else {
					echo "LAPOR FAILED";
				}
			}
		} catch (Exception $e) {
			echo "LAPOR FAILED";
		}
	}

	// resize image
	function resizeImage($sourceFile, $destinationFile, $width, $height) {
		// Get image dimensions
		list($srcWidth, $srcHeight) = getimagesize($sourceFile);

		// Create a new true color image with specified dimensions
		$dstImage = imagecreatetruecolor($width, $height);

		// Determine the file extension
		$extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));

		// Create image resource from file based on file type
		switch ($extension) {
			case 'jpeg':
			case 'jpg':
				$srcImage = imagecreatefromjpeg($sourceFile);
				break;
			case 'png':
				$srcImage = imagecreatefrompng($sourceFile);
				break;
			case 'gif':
				$srcImage = imagecreatefromgif($sourceFile);
				break;
			default:
				throw new Exception('Unsupported image type.');
		}

		// Resize and copy the image to the destination resource
		imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

		// Save the resized image to the destination file
		switch ($extension) {
			case 'jpeg':
			case 'jpg':
				imagejpeg($dstImage, $destinationFile);
				break;
			case 'png':
				imagepng($dstImage, $destinationFile);
				break;
			case 'gif':
				imagegif($dstImage, $destinationFile);
				break;
		}

		// Free up memory
		imagedestroy($srcImage);
		imagedestroy($dstImage);
	}


	// public function datalaporanmobile(){
	// 	try {
	// 		// get data
	// 		$datalaporan = $this->logic("Home_model")->get_data_pelaporan();
			
	// 		// Output JSON response
	// 		header('Content-Type: application/json');
	// 		echo json_encode($datalaporan);
			
	// 	} catch (Exception $e) {
	// 		// Handle exceptions
	// 		echo "GET DATA FAILED";
	// 	}
	// }

	public function getLatestReport(){
		try {
			// get data
			$datalaporanterbaru = $this->logic("Home_model")->get_latest_report();
			// Output JSON response
			header('Content-Type: application/json');
			echo json_encode($datalaporanterbaru);
			
		} catch (Exception $e) {
			// Handle exceptions
			echo "GET DATA FAILED";
		}
	}

	public function getAllPublicNumbers(){
		try {
			// get data
			$datapublicnumbers = $this->logic("Home_model")->get_all_public_numbers();
			// Output JSON response
			header('Content-Type: application/json');
			echo json_encode($datapublicnumbers);
			
		} catch (Exception $e) {
			// Handle exceptions
			echo "GET DATA FAILED";
		}
	}

	public function markReportNotifiedMasyarakat(){
		try {
			// get data
			$markasnotified = $this->logic("Home_model")->mark_report_as_notified_masyarakat();
			// Output JSON response
			header('Content-Type: application/json');
			echo json_encode($markasnotified);
			
		} catch (Exception $e) {
			// Handle exceptions
			echo "GET DATA FAILED";
		}
	}
	
	
}
?>