<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .profile-header {
            background: linear-gradient(rgba(3, 24, 70, 0.768), rgba(3, 24, 70, 0.768)), 
                        url('https://silaben.site/app/public/a/assets/img/background-bencana.jpg');
            background-size: cover; /* Ensures the image covers the entire header */
            background-position: center center; /* Positions the background image in the center */
            background-repeat: no-repeat; /* Ensures the background doesn't repeat */
            color: white;
            padding: 20px;
            position: relative; /* The profile-header itself is positioned relative to allow absolute positioning inside it */
            display: flex;
            align-items: center;
        }


        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
            width: 120px;
            height: 120px;
            margin-top: 0px;
            position: relative;
        }

        .profile-header h2 {
            /* align-items: center; */
            font-size: 24px;
            width: 120px;
            height: 120px;
            text-align: center;
        }

        .profile-header p {
            margin: 0;
            font-size: 24px;
        }

        .tabs {
            margin-top: 20px;
            margin-bottom: 20px;
 }

        .tabs .nav-link {
            color: #070478;
            font-weight: bold;
        }

        .tabs .nav-link.active {
            color: #070478;
            border-color: #070478;
        }

        .form-section {
            padding: 20px;
        }

        .btn-orange {
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-orange:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <!-- Profile Header -->
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

    <!-- Tab Navigation -->
    <div class="container tabs">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#edit-profile">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#change-password">Ubah Password</a>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="container">
        <div class="tab-content">
            <!-- Edit Profile Tab -->
            <div class="tab-pane fade show active form-section" id="edit-profile">
                <form>
                <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>"> <!-- Hidden input untuk user_id -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" value="<?php echo $data['user_name']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" value="" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="gender" value="<?php echo $data['gender']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" value="<?php echo $data['whatsapp_number']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $data['email']; ?>" disabled>
                    </div>
                    <button type="button" class="btn btn-orange">Perbarui data</button>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane fade form-section" id="change-password">
                <form id="change-password-form" onsubmit="changePassword(event)">
                <input type="hidden" id="user_id" value="<?php echo $data['user_id']; ?>">
                    <div class="mb-3">
                        <label for="old-password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="old-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Ulang Password Baru</label>
                        <input type="password" class="form-control" id="confirm-password" required>
                    </div>
                    <button type="submit" class="btn btn-orange" id="update-password-button">Perbarui Password</button>
                </form>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

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
    function saveUpdatedData() {
        const userId =  document.getElementById('user_id').value;
        const name = document.getElementById('name').value;
        const role = document.getElementById('role').value;
        const gender = document.getElementById('gender').value;
        const whatsapp = document.getElementById('whatsapp').value;
        const email = document.getElementById('email').value;

        const data = {
            user_id: userId,
            user_name: name,
            role: role,
            gender: gender,
            whatsapp_number: whatsapp,
            email: email
        };

        fetch('https://silaben.site/app/public/login/updateUser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => response.json())
          .then(result => {
              if (result.success) {
                  alert('Data berhasil diperbarui');
                  // Disable input fields again after saving
                  const inputs = document.querySelectorAll("#edit-profile input");
                  inputs.forEach(input => {
                      input.disabled = true;
                  });
                  // Change button back to 'Perbarui Data'
                  const saveButton = document.getElementById('save-button');
                  saveButton.innerText = 'Perbarui Data';
                  saveButton.removeEventListener("click", saveUpdatedData); // Remove the save function
                  saveButton.addEventListener("click", function () {
                      // Re-enable input fields for editing
                      inputs.forEach(input => {
                          input.disabled = false;
                      });
                      this.innerText = 'Simpan';
                      this.removeEventListener("click", arguments.callee); // Remove this listener
                      this.addEventListener("click", saveUpdatedData); // Re-attach save listener
                  });
              } else {
                  alert('Gagal memperbarui data');
              }
          }).catch(error => {
              console.error('Error:', error);
          });
    }
    function changePassword(event) {
    event.preventDefault();

    const oldPassword = document.getElementById('old-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const userId = document.getElementById('user_id').value;

    const data = {
        user_id: userId,
        old_password: oldPassword,
        new_password: newPassword,
        confirm_password: confirmPassword
    };

    fetch('https://silaben.site/app/public/login/changePassword', {
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

<!-- <script>
    document.getElementById('update-password-button').addEventListener('click', function () {
        const oldPassword = document.getElementById('old-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const userId = document.getElementById('user_id').value;

        const data = {
            user_id: userId,
            old_password: oldPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
        };

        fetch('https://silaben.site/app/public/login/updatePassword', {
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
    });
</script> -->

