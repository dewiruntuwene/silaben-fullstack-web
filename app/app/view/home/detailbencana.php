<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .detail-container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background: #0056b3;
        }
        .img-preview {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <h1 class="header"><?php echo strtoupper($laporan['report_title']); ?></h1>
        <img src="<?php echo APP_PATH; ?>/fotobukti/<?php echo $laporan['report_file_name_bukti']; ?>" alt="Bukti Laporan" class="img-preview">
        <p><strong>Deskripsi:</strong> <?php echo $laporan['report_description']; ?></p>
        <p><strong>Lokasi:</strong> <?php echo $laporan['lokasi_bencana']; ?></p>
        <p><strong>Tanggal:</strong> <?php echo $laporan['report_date'] . ", " . $laporan['report_time']; ?></p>
        <p><strong>Jenis:</strong> <?php echo $laporan['jenis_bencana']; ?></p>
        <a href="jadi_relawan.php?id=<?php echo $laporan['report_id']; ?>" class="btn">Jadi Relawan</a>
    </div>
</body>