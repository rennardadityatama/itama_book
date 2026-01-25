<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- Loader starts-->
<div class="loader-wrapper d-none" id="globalSpinner">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"> </div>
  <div class="dot"></div>
</div>
<!-- Loader ends-->
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>User Profile</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Account Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="edit-profile">
      <div class="row">
        <div class="col-xl-4 col-lg-5">
          <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h4 class="card-title mb-0">My Profile</h4>
              <button type="button" class="btn btn-sm btn-dark" id="btn-edit-profile">
                <i data-feather="edit-2"></i>
              </button>
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <div class="row mb-2">
                <div class="profile-title">
                  <div class="d-lg-flex d-block align-items-center">
                    <div class="ratio ratio-1x1 mx-auto mb-2" style="width:90px">
                      <img
                        class="img-fluid rounded-circle object-fit-cover"
                        id="avatarPreview">
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h4 class="mb-1 f-20 txt-primary">
                        <?= htmlspecialchars($user['name']) ?>
                      </h4>
                      <p class="f-12 mb-0">
                        <?= htmlspecialchars($user['role_name']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <h6 class="form-label">NIK</h6>
                <div class="form-control bg-light txt-dark">
                  <?= htmlspecialchars($user['nik']) ?>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label f-w-500">Email</label>
                <div class="form-control bg-light txt-dark">
                  <?= htmlspecialchars($user['email']) ?>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label f-w-500">Address</label>
                <div class="form-control bg-light txt-dark">
                  <?= htmlspecialchars($user['address']) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 col-lg-7">
          <!-- Alert container fixed di pojok kanan atas -->
          <div id="alertContainer" style="position: fixed; top: 20px; right: 20px; width: 350px; z-index: 1055;"></div>
          <form class="card" method="POST" id="editProfileForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= Csrf::token() ?>">
            <div class="card-header pb-0">
              <h4 class="card-title mb-0">Edit Profile</h4>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label f-w-500">Username</label>
                  <input class="form-control" type="text" placeholder="Username" name="name" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label f-w-500">Email Address</label>
                  <input class="form-control" type="email" placeholder="Email" name="email" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label f-w-500">Password</label>
                  <div class="input-group">
                    <input class="form-control" type="password" name="password" id="password" disabled>
                    <span class="input-group-text toggle-password" data-target="password">
                      <i data-feather="eye"></i>
                    </span>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label f-w-500">Confirm Password</label>
                  <div class="input-group">
                    <input class="form-control" type="password" name="confirm_password" id="confirm_password" disabled>
                    <span class="input-group-text toggle-password" data-target="confirm_password">
                      <i data-feather="eye"></i>
                    </span>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label f-w-500">NIK</label>
                  <input class="form-control" type="text" placeholder="NIK" name="nik" disabled>
                </div>
                <div class="col-md-6">
                  <label class="form-label f-w-500">Address</label>
                  <input class="form-control" type="text" placeholder="City" name="address" disabled>
                </div>
                <div class="col-md-12">
                  <label class="form-label f-w-500">Profile Picture</label>
                  <input class="form-control" type="file" name="avatar" id="avatarInput" disabled>
                </div>
              </div>
            </div>
            <div class="card-footer text-end">
              <button class="btn btn-primary" type="submit">
                Update Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

<script>
  const editBtn = document.getElementById('btn-edit-profile');
  const form = document.getElementById('editProfileForm');
  const spinner = document.getElementById('globalSpinner');
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  const inputs = form.querySelectorAll('input:not([type=hidden])');

  function showSpinner() {
    spinner.classList.remove('d-none');
  }

  function hideSpinner() {
    spinner.classList.add('d-none');
  }

  function showAlert(message, status = true) {
    const container = document.getElementById('alertContainer');
    if (!container) return alert(message); // fallback

    // Hapus alert lama
    container.innerHTML = '';

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${status ? 'alert-success' : 'alert-danger'} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.style.width = '100%';
    alertDiv.style.fontSize = '14px';
    alertDiv.innerHTML = `
      <div style="word-wrap: break-word;">${message}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    container.appendChild(alertDiv);

    // Auto dismiss setelah 3 detik
    setTimeout(() => {
      bootstrap.Alert.getOrCreateInstance(alertDiv).close();
    }, 3000);
  }

  inputs.forEach(el => el.disabled = true);

  editBtn.addEventListener('click', () => {
    inputs.forEach(el => el.disabled = false);
    editBtn.innerHTML = '<i data-feather="lock"></i>';
    feather.replace();
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const formData = new FormData(form);
    showSpinner();

    try {
      const res = await fetch('<?= BASE_URL ?>index.php?c=admin&m=updateProfile', {
        method: 'POST',
        body: formData
      });
      const json = await res.json();
      hideSpinner();
      showAlert(json.message, json.status);
      if (json.status) setTimeout(() => location.reload(), 2000);
    } catch (err) {
      hideSpinner();
      showAlert('There is an error', false);
      console.error(err);
    }
  });

  document.querySelectorAll('.toggle-password').forEach(el => {
    el.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-feather', 'eye-off');
      } else {
        input.type = 'password';
        icon.setAttribute('data-feather', 'eye');
      }

      editBtn.innerHTML = '<i data-feather="lock"></i>';
      feather.replace();
    });
  });

  avatarInput.addEventListener('change', function() {
    const file = this.files[0];

    if (!file) return;

    if (!file.type.startsWith('image/')) {
      alert('Please select a valid image file');
      this.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
      avatarPreview.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
</script>