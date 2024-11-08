<?php
	// set time zone
	//date_default_timezone_set('Asia/Jakarta');
	date_default_timezone_set('Asia/Makassar');

	// format date and time
	$date = date('l, j F Y');
	$time = date('h:i A');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 mt-3">
	<div class="container-fluid">
		
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-2 mb-3 border-bottom">
			<h1 class="h2">Archive/Complete Classes</h1>
			<div class="btn-toolbar mb-2 mb-md-0">
			  <div class="btn-group me-0">
				<button type="button" class="btn btn-sm btn-outline-secondary"><?php echo $date; ?></button>
				<button type="button" class="btn btn-sm btn-outline-secondary"><?php echo $time; ?></button>
			  </div>
			</div>
		</div>
		
        

        <?php 
            if(empty($data['data-archive-classes'])){
                echo "<h6>Belum terdapat archive/complete class yang diassign.</h6>";
            } else {
        ?>

        <div class="table-responsive" style="width: 100%; min-height: 300px; height: 100vh; height: 100%;">
            <table class="table table-striped table-bordered align-middle mb-0 bg-white table-hover">
            <thead class="bg-light table-primary">
                <tr>
                <th>Course</th>
                <th class="text-center">Schedule</th>
                <th class="text-center">Room</th>
                <th class="text-center">Status</th>
                <th class="text-center" width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($data['data-archive-classes'] as $course): ?>
					<?php
						// input pola semester id:
						// 2022_2023_1 = Semester Ganjil 2022/2023
						// 2022_2023_2 = Semester Genap 2022/2023
						// 2022_2023_3 = Semester padat 2022/2023
						$split_data = explode("_", $course['semester_id']);

						// Memastikan ada tiga bagian setelah membagi string
						if (count($split_data) == 3) {
							$year1 = $split_data[0]; // Tahun
							$year2 = $split_data[1]; // Tahun
							$semester = $split_data[2]; // Semester

							// Menentukan jenis semester berdasarkan angka semester
							if ($semester == 1) {
								$semester_name = "Semester Ganjil";
							} elseif ($semester == 2) {
								$semester_name = "Semester Genap";
							} elseif ($semester == 3) {
								$semester_name = "Semester Padat";
							} else {
								$semester_name = "Semester tidak valid";
							}

							// Semester Padat 2022/2023
							$academic_year = $semester_name . " ". $year1 . "/" . $year2; // Format tahun ajaran

							//echo "Tahun Ajaran: " . $academic_year . "<br>";
							//echo "Semester: " . $semester_name . "<br>";
						}else{
							// Semester Padat 2022/2023
							$academic_year = "Format semester tidak valid";
						}
					?>
					<?php
						$date_str = $course['created_at'];
						$date = DateTime::createFromFormat("Y-m-d H:i:s", $date_str);
						$new_date_str = $date->format("d F Y h:i A");
					?>
					
                    <tr id="<?php echo $course['id_class']; ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="material-icons text-danger me-3">event_note</span>
                                <div>
                                    <div class="fw-bold mb-1" id='matakuliah'><?php echo "[".$course['code_class']."] ".$course['name_subject']; ?></div>
                                    <p class="text-muted mb-0 small"><span class='text-primary' data-feather='layers'></span> <?php echo $academic_year; ?></p>
									<!--<p class="text-muted mb-0 small"><span class='text-primary' data-feather='flag'></span> <?php echo $course['sks']; ?> with <b><?php echo $course['max_absent']; ?></b> absences max limit.</p> -->
									<p class="text-muted mb-0 small"><span class='text-primary' data-feather='calendar'></span> <?php echo $new_date_str; ?></p>
                                    <p class="text-muted mb-0 small"><span class='text-primary' data-feather='user-check'></span> <?php echo $course['name_lecturer']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                                $daytime = explode(", ", $course['jadwal_class_day_time']);
                            ?>
                            <p class="fw-normal mb-1"><?php echo $daytime[0]; ?></p>
                            <p class="text-muted mb-0 small"><?php echo $daytime[1]; ?></p>
                        </td>
                        <td class="text-center">
                            <p class="text-muted mb-0 small"><?php echo $course['building_room']; ?></p>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger rounded-pill d-inline-block"><?php echo $course['status_class']; ?></span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Options</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item disabled">Delete Class</a></li>
                                    <li><a class="dropdown-item disabled">Restore Class</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo APP_PATH;?>/students/summarize/<?php echo $course['id_class']; ?>">Attendance Summary</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
            </table>
        </div>
        
        <?php 
            }
        ?>

    </div>
</main>

<!-- bootstrap delete class confirmasi -->
<div class="modal fade" id="modal-delete-dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header text-center d-block">
				<h4 class="modal-title">Delete Message Dialog</h4>
			</div>
			<div class="modal-body">
            <input type="text" class="course-name form-control mb-2" style="background-color: lightgrey;" readonly>
				<p>Do you want to delete this class in database permanently?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<a href="<?php echo APP_PATH; ?>/dosen/" id="delete-class" class="btn btn-danger">Delete Course</a>
			</div>
		</div>
	</div>
</div>

<!-- bootstrap restore class confirmasi -->
<div class="modal fade" id="modal-restore-dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header text-center d-block">
				<h4 class="modal-title">Restore Message Dialog</h4>
			</div>
			<div class="modal-body">
                    <input type="text" class="course-name form-control mb-2" style="background-color: lightgrey;" readonly>
					<p>Do you want to restore this class as an active class?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<a href="<?php echo APP_PATH; ?>/dosen/" id="restore-class" class="btn btn-danger">Restore Course</a>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo APP_PATH; ?>/js/handle-actions.js"></script>