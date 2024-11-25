<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        .profile-header {
            background: linear-gradient(rgba(3, 24, 70, 0.768), rgba(3, 24, 70, 0.768)), 
                        url('https://silaben.site/app/public/a/assets/img/background-bencana.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            color: white;
            padding: 40px 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .profile-header h2 {
            font-size: 28px;
            margin: 10px 0;
            font-weight: bold;
        }

        .profile-header p {
            font-size: 18px;
            margin: 0;
        }

        .detail-container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: bold;
        }

        .img-preview {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }

        .details p {
            font-size: 16px;
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 20px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
        }

        .btn:hover {
            background: #0056b3;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-input {
            margin-bottom: 20px;
        }

        .form-input label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .form-input textarea,
        .form-input input,
        .form-input select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }

        .submit-btn {
            background: red;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <!-- <h1><?php echo $data['user_name']; ?></h1> -->
    <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>">
    <div class="profile-header">
        <!-- <?php 
            if($data['gender'] == 'female'){ 
              $img_profile = "profile-female.jpg";
            }
            
            if($data['gender'] == 'male'){ 
              $img_profile = "profile-male.jpg";
            }
          
        ?>
        <div style="display: flex;">
            <img src="<?php echo APP_PATH; ?>/src/assets/img/<?php echo $img_profile; ?>" alt="User Avatar" width="100" height="100">
            <div style="margin-left: 20px;">
                <h2><?php echo $data['user_name']; ?></h2>
                <p><?php echo $data['role']; ?></p>
            </div>
        </div> -->

        <h2 class="animate__animated animate__fadeInDown"> <span></span></h2>
    </div>
    <div class="detail-container">
        <?php foreach ($data['datalaporan'] as $laporan): ?>
            <input type="hidden" id="laporan_id" value="<?php echo $laporan['laporan_id']; ?>">
            <h1 class="header"><?php echo strtoupper($laporan['report_title']); ?></h1>
            <img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti']; ?>" alt="Bukti Laporan" class="img-preview">
            <p><strong>Deskripsi:</strong> <?php echo $laporan['report_description']; ?></p>
            <p><strong>Jumlah Relawan yang Dibutukan:</strong> <?php echo $laporan['jumlah_relawan_dibutuhkan']; ?></p>
            <p><strong>Jumlah Relawan yang Terdaftar:</strong> <?php echo $laporan['jumlah_relawan_terdaftar']; ?></p>
            <p><strong>Lokasi:</strong> <?php echo $laporan['lokasi_bencana']; ?></p>
            <p><strong>Tanggal:</strong> <?php echo $laporan['report_date'] . ", " . $laporan['report_time']; ?></p>
            <p><strong>Pelapor:</strong> <?php echo $laporan['pelapor_name']; ?></p>
            <p><strong>Instansi Pelapor:</strong> <?php echo $laporan['lapor_instansi']; ?></p>
            <p><strong>Status:</strong> <?php echo $laporan['status']; ?></p>
            <p><strong>Jenis Bencana:</strong> <?php echo $laporan['jenis_bencana']; ?></p>
            <p><strong>Klasifikasi Bencana:</strong> <?php echo $laporan['klasifikasi_bencana']; ?></p>
            <p><strong>Level Kerusakan Infrastruktur:</strong> <?php echo $laporan['level_kerusakan_infrastruktur']; ?></p>
            <p><strong>Level Bencana:</strong> <?php echo $laporan['level_bencana']; ?></p>
            <p><strong>Kesesuaian Laporan:</strong> <?php echo $laporan['kesesuaian_laporan']; ?></p>
            <p><strong>Deskripsi Singkat AI:</strong> <?php echo $laporan['deskripsi_singkat_ai']; ?></p>
            <p><strong>Saran Singkat:</strong> <?php echo $laporan['saran_singkat']; ?></p>
            <p><strong>Potensi Bahaya Lanjutan:</strong> <?php echo $laporan['potensi_bahaya_lanjutan']; ?></p>
            <p><strong>Penilaian Akibat Bencana:</strong> <?php echo $laporan['penilaian_akibat_bencana']; ?></p>
            <p><strong>Kondisi Cuaca:</strong> <?php echo $laporan['kondisi_cuaca']; ?></p>
            <!-- Conditional Button Display -->
            <?php if ($laporan['jumlah_relawan_dibutuhkan'] > 0): ?>
                <a href="#" class="btn" onclick="openModal()">Jadi Relawan</a>
            <?php else: ?>
                <a href="#" class="btn" style="background-color: gray; pointer-events: none;">Relawan Penuh</a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>


    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
        <span class="close-icon" onclick="closeModal()">&times;</span>
            <div class="modal-header">Daftar Aktivitas</div>
            <div class="modal-body">
                <div class="form-input">
                    <label>Kenapa Anda tertarik untuk menjadi relawan pada aktivitas ini?</label>
                    <textarea rows="4" placeholder="Tulis jawaban Anda..." type="alasan" id="alasan"></textarea>
                </div>
                <!-- <div class="form-input">
                    <label>Pilih pekerjaan Anda:</label>
                    <select>
                        <option value="">Pilih pekerjaan...</option>
                        <option value="1">Mahasiswa</option>
                        <option value="2">Pekerja</option>
                        <option value="3">Lainnya</option>
                    </select>
                </div>
                <div class="form-input">
                    <label>Nomor Handphone</label>
                    <input type="text" placeholder="Masukkan nomor Anda...">
                </div> -->
                <span>*Data profile anda akan dikiri</span>
                <div class="modal-footer">
                    <button class="submit-btn" onclick="sendRegistration(event)">Kirim Pendaftaran</button>
                </div>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function sendRegistration(event) {
            event.preventDefault();

            const relawan_id = document.getElementById('user_id').value;
            const laporan_id = document.getElementById('laporan_id').value;
            const alasan = document.getElementById('alasan').value;
            
            const data = {
                relawan_id: relawan_id,
                laporan_id: laporan_id,
                alasan: alasan,
            };

            console.log(data);

            fetch('https://silaben.site/app/public/home/daftarRelawan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(response => response.text())
            .then(result => {
                alert(result); // Tampilkan hasil pesan
                location.reload();
            }).catch(error => {
                console.error('Error:', error);
            });
        }

    </script>
</body>
