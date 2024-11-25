<main id="main" class="main">

<div class="pagetitle">
  <h1>Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">

      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
        <?php 
            if($data['gender'] == 'female'){ 
                $img_profile = "profile-female.jpg";
            }
            
            if($data['gender'] == 'male'){ 
                $img_profile = "profile-male.jpg";
            }
        
        ?>
          <img src="<?php echo APP_PATH; ?>/src/assets/img/<?php echo $img_profile; ?>" alt="Profile" class="rounded-circle">
          <h2><?php echo $data['user_name']; ?></h2>
          <h3><?php echo $data['email']; ?></h3>
          <div class="social-links mt-2">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>

    </div>

    <div class="col-xl-8">

      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">

            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
            </li>

            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
            </li>

            <!-- <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
            </li> -->

            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
            </li>

          </ul>
          <div class="tab-content pt-2">

            <div class="tab-pane fade show active profile-overview" id="profile-overview">

              <!-- <h5 class="card-title">Profile Details</h5> -->

              <div class="row">
                <div class="col-lg-3 col-md-4 label ">Nama Lembaga</div>
                <div class="col-lg-9 col-md-8"><?php echo $data['user_name']; ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Jenis Lembaga</div>
                <div class="col-lg-9 col-md-8"><?php echo $data['jenis_lembaga']; ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Alamat</div>
                <div class="col-lg-9 col-md-8"><?php echo $data['alamat']; ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Nomor WhatsApp</div>
                <div class="col-lg-9 col-md-8"><?php echo $data['nomor_telp']; ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8"><?php echo $data['email']; ?></div>
              </div>

              <!-- <div class="row">
                <div class="col-lg-3 col-md-4 label">Phone</div>
                <div class="col-lg-9 col-md-8">(436) 486-3538 x29071</div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8">k.anderson@example.com</div>
              </div> -->

            </div>

            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

              <!-- Profile Edit Form -->
              <form onsubmit="saveUpdatedData(event)">
              <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>"> <!-- Hidden input untuk user_id -->
                <!-- <div class="row mb-3">
                  <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                  <div class="col-md-8 col-lg-9">
                    <img src="assets/img/profile-img.jpg" alt="Profile">
                    <div class="pt-2">
                      <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                      <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                    </div>
                  </div>
                </div> -->

                <div class="row mb-3">
                  <label for="nama_lembaga" class="col-md-4 col-lg-3 col-form-label">Nama Lembaga</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="nama_lembaga" type="text" class="form-control" id="nama_lembaga" value="<?php echo $data['user_name']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="jenis_lembaga" class="col-md-4 col-lg-3 col-form-label">Jenis Lembaga</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="jenis_lembaga" type="text" class="form-control" id="jenis_lembaga" value="<?php echo $data['jenis_lembaga']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="alamat" class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="alamat" type="text" class="form-control" id="alamat" value="<?php echo $data['alamat']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="nomor_telp" class="col-md-4 col-lg-3 col-form-label">Nomor WhatsApp</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="nomor_telp" type="text" class="form-control" id="nomor_telp" value="<?php echo $data['nomor_telp']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="email" type="text" class="form-control" id="email" value="<?php echo $data['email']; ?>">
                  </div>
                </div>

                <!-- <div class="row mb-3">
                  <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="phone" type="text" class="form-control" id="Phone" value="(436) 486-3538 x29071">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="email" type="email" class="form-control" id="Email" value="k.anderson@example.com">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="twitter" type="text" class="form-control" id="Twitter" value="https://twitter.com/#">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="facebook" type="text" class="form-control" id="Facebook" value="https://facebook.com/#">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="instagram" type="text" class="form-control" id="Instagram" value="https://instagram.com/#">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="linkedin" type="text" class="form-control" id="Linkedin" value="https://linkedin.com/#">
                  </div>
                </div> -->

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form><!-- End Profile Edit Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-settings">

              <!-- Settings Form -->
              <!-- <form>

                <div class="row mb-3">
                  <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                  <div class="col-md-8 col-lg-9">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="changesMade" checked>
                      <label class="form-check-label" for="changesMade">
                        Changes made to your account
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="newProducts" checked>
                      <label class="form-check-label" for="newProducts">
                        Information on new products and services
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="proOffers">
                      <label class="form-check-label" for="proOffers">
                        Marketing and promo offers
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                      <label class="form-check-label" for="securityNotify">
                        Security alerts
                      </label>
                    </div>
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form> -->
              <!-- End settings Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
              <!-- Change Password Form -->

              <form id="change-password-form" onsubmit="changePasswordLembaga(event)">
              <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>">
                <div class="row mb-3">
                  <label for="old-password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="password" type="password" class="form-control" id="old-password">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="new-password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="newpassword" type="password" class="form-control" id="new-password">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="confirm-password" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="renewpassword" type="password" class="form-control" id="confirm-password">
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
              </form><!-- End Change Password Form -->

            </div>

          </div><!-- End Bordered Tabs -->

        </div>
      </div>

    </div>
  </div>
</section>

</main><!-- End #main -->

<script>
    document.querySelector(".btn-orange").addEventListener("click", function () {
        // Enable input fields for editing
        const inputs = document.querySelectorAll("#edit-profile input");
        inputs.forEach(input => {
            input.disabled = false;
        });

       // Change the button text to 'Simpan'
        this.innerText = 'Simpan';
        this.setAttribute('id', 'save-button'); // Add an ID for the save button

        // Add event listener to save the updated data when 'Simpan' button is clicked
        this.removeEventListener("click", arguments.callee); // Remove the original event listener
        this.addEventListener("click", saveUpdatedData);
    });

    // Function to send the updated data to the backend and disable input fields after saving
    function saveUpdatedData(event) {
      event.preventDefault();

        const lembagaId =  document.getElementById('user_id').value;
        const namaLembaga = document.getElementById('nama_lembaga').value;
        const jenisLembaga = document.getElementById('jenis_lembaga').value;
        const alamat = document.getElementById('alamat').value;
        const nomorTelp = document.getElementById('nomor_telp').value;
        const email = document.getElementById('email').value;

        const data = {
            lembaga_id: lembagaId,
            nama_lembaga: namaLembaga,
            jenis_lembaga: jenisLembaga,
            alamat: alamat,
            nomor_telp: nomorTelp,
            email: email
        };

        fetch('https://silaben.site/app/public/login/updateLembaga', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => response.text())
          .then(result => {
            //alert(result);
            alert('Data berhasil diperbarui');

            window.location.reload();

                // Nonaktifkan kembali input setelah berhasil diperbarui
                // const inputs = document.querySelectorAll("#profile-edit input");
                // inputs.forEach(input => input.disabled = true);
                // // Reset tombol ke "Perbarui Data"
                // const updateButton = document.getElementById("update-button");
                // updateButton.innerText = "Perbarui Data";
                
                // updateButton.addEventListener("click", function () {
                //     inputs.forEach(input => input.disabled = false);
                //     updateButton.innerText = "Simpan";
                //     updateButton.addEventListener("click", saveUpdatedData);
                // });

                 // Perbarui tampilan dengan data terbaru dari respons
                const updatedSessionData = result.data;
                document.getElementById("display_nama_lembaga").innerText = result.nama_lembaga;
                document.getElementById("display_jenis_lembaga").innerText = result.jenis_lembaga;
                document.getElementById("display_alamat").innerText = result.alamat;
                document.getElementById("display_nomor_telp").innerText = result.nomor_telp;
                document.getElementById("display_email").innerText = result.email;

          }).catch(error => {
              console.error('Error:', error);
          });
    }
    function changePasswordLembaga(event) {
        event.preventDefault();

        const oldPassword = document.getElementById('old-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const lembagaId = document.getElementById('user_id').value;

        const data = {
            lembaga_id: lembagaId,
            old_password: oldPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
        };

        fetch('https://silaben.site/app/public/login/changePasswordLembaga', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => response.text())
          .then(result => {
              alert(result); // Tampilkan hasil pesan
          }).catch(error => {
              console.error('Error:', error);
          });
    }

</script>



