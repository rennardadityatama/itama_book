      <!-- footer start-->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 p-0 footer-left">
              <p class="mb-0">Copyright © 2026 iTamaBook. All rights reserved.</p>
            </div>
          </div>
        </div>
      </footer>

      <div id="toastContainer"
        class="toast-container position-fixed top-0 end-0 p-3"
        style="z-index:1055"></div>

      <script>
        function showToast(message, type = 'success') {
          const container = document.getElementById('toastContainer');
          if (!container) return;

          // Decide color & icon
          const isSuccess = type === 'success';

          const toast = document.createElement('div');
          toast.className = `
    toast show mb-3 border-0 shadow
    ${isSuccess ? 'bg-success' : 'bg-danger'}
    text-white
  `;

          toast.innerHTML = `
    <div class="toast-body d-flex align-items-center gap-3 px-4 py-3">
      <i class="fa ${isSuccess ? 'fa-check-circle' : 'fa-times-circle'} fs-4"></i>
      <div class="fw-semibold">
        ${message}
      </div>
    </div>
  `;

          container.appendChild(toast);

          // Auto dismiss
          setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
          }, 3000);
        }


        document.querySelectorAll('.add-to-cart-form').forEach(form => {
          form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch('<?= BASE_URL ?>index.php?c=customerCart&m=add', {
                method: 'POST',
                body: new FormData(this)
              })
              .then(res => res.json())
              .then(res => {
                showToast(res.message, res.status);

                // OPTIONAL: update cart badge di navbar
                // updateCartCount();
              })
              .catch(() => {
                showToast('Terjadi kesalahan', 'danger');
              });
          });
        });
      </script>