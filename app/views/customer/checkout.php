<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- Loader starts-->
<div class="loader-wrapper">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"> </div>
  <div class="dot"></div>
</div>
<!-- Loader ends-->
<!-- Page Body Start-->
<div class="page-body checkout">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-sm-6">
          <h3>Checkout</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
            <li class="breadcrumb-item">Ecommerce</li>
            <li class="breadcrumb-item active">Checkout</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-header pb-0">
        <h4>Checkout Details</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 col-sm-12">
            <?php foreach ($sellers as $sellerId => $seller): ?>
              <div class="card border mb-4">
                <div class="card-header bg-success">
                  <h5 class="mb-0"><i data-feather="shopping-bag"></i> Seller: <?= htmlspecialchars($seller['seller_name']) ?></h5>
                </div>
                <div class="card-body">
                  <form action="<?= BASE_URL ?>index.php?c=customerOrder&m=placeOrderAll" method="POST" enctype="multipart/form-data" class="checkout-seller-form" id="checkoutForm">
                    <input type="hidden" name="seller_id" value="<?= $sellerId ?>">

                    <div class="table-responsive mb-3">
                      <table class="table table-borderless">
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($seller['items'] as $item): ?>
                            <tr>
                              <td>
                                <div class="d-flex align-items-center">
                                  <?php if (!empty($item['image'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>" class="img-fluid img-30 me-2" alt="">
                                  <?php endif; ?>
                                  <span><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                              </td>
                              <td class="text-center"><?= $item['qty'] ?></td>
                              <td class="text-end">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                          <tr class="border-top">
                            <td colspan="2" class="text-end"><strong>Total untuk Seller ini:</strong></td>
                            <td class="text-end"><strong class="text-primary">Rp <?= number_format($seller['total'], 0, ',', '.') ?></strong></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>

                    <hr>

                    <div class="row">
                      <div class="col-md-6">
                        <h6 class="mb-3">Payment Method</h6>
                        <div class="animate-chk">
                          <label class="d-block mb-2">
                            <input class="radio_animated payment-radio" type="radio" name="payment_method" value="transfer" required data-seller="<?= $sellerId ?>"> Bank Transfer
                          </label>
                          <label class="d-block mb-2">
                            <input class="radio_animated payment-radio" type="radio" name="payment_method" value="qris" required data-seller="<?= $sellerId ?>"> QRIS
                          </label>
                        </div>

                        <div id="info-transfer-<?= $sellerId ?>" class="alert alert-info mt-2 p-2" style="display:none;">
                          <small>Bank Account: <strong><?= htmlspecialchars($seller['account_number']) ?></strong></small>
                        </div>
                        <div id="info-qris-<?= $sellerId ?>" class="alert alert-info mt-2 p-2" style="display:none;">
                          <small>Scan QRIS Seller ini di Riwayat setelah order atau hubungi seller.</small>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <h6 class="mb-3">Upload Proof <span class="text-danger">*</span></h6>
                        <input type="file" name="payment_proof" class="form-control form-control-sm mb-2 proof-input" required accept="image/*" data-seller="<?= $sellerId ?>">
                        <div id="preview-container-<?= $sellerId ?>" style="display:none;">
                          <img id="preview-img-<?= $sellerId ?>" src="#" class="img-fluid rounded" style="max-height: 100px;">
                        </div>
                      </div>
                    </div>

                    <div class="text-end mt-3">
                      <button type="submit" class="btn btn-primary btn-sm">Place Order for <?= htmlspecialchars($seller['seller_name']) ?></button>
                    </div>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="col-lg-4 col-sm-12">
            <div class="card border">
              <div class="card-header">
                <h5>Grand Total</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                    Total Belanja
                    <span>Rp <?= number_format($totalOrder, 0, ',', '.') ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                    <div><strong>Total Keseluruhan</strong></div>
                    <span><strong>Rp <?= number_format($totalOrder, 0, ',', '.') ?></strong></span>
                  </li>
                </ul>
                <a href="<?= BASE_URL ?>index.php?c=customerCart&m=index" class="btn btn-outline-secondary w-100">Kembali ke Keranjang</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

<!-- Confirm Order Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="confirmMessage">Are you sure you want to place this order?</p>
        <div class="mt-3">
          <table class="table table-sm table-borderless">
            <tr>
              <td><strong>Total Amount:</strong></td>
              <td class="text-end" id="modalTotal">Rp <?= number_format($totalOrder, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td><strong>Payment Method:</strong></td>
              <td class="text-end" id="modalPayment">-</td>
            </tr>
            <tr>
              <td><strong>Payment Proof:</strong></td>
              <td class="text-end" id="modalProof">-</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary me-2 d-inline-flex align-items-center" id="confirmOrderBtn">
          <i data-feather="check-circle"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const confirmOrderModal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');
    const modalPayment = document.getElementById('modalPayment');
    const modalProof = document.getElementById('modalProof');
    const modalTotal = document.getElementById('modalTotal');

    let currentActiveForm = null; // Untuk menyimpan form mana yang sedang dikonfirmasi

    // 1. HANDLE PERUBAHAN METODE PEMBAYARAN (Setiap Card)
    document.querySelectorAll('.payment-radio').forEach(radio => {
      radio.addEventListener('change', function() {
        const sellerId = this.dataset.seller;
        const card = this.closest('.card-body'); // Ambil container card seller ini

        // Sembunyikan semua info di card ini
        card.querySelector(`#info-transfer-${sellerId}`).style.display = 'none';
        card.querySelector(`#info-qris-${sellerId}`).style.display = 'none';

        // Tampilkan yang sesuai
        if (this.value === 'transfer') {
          card.querySelector(`#info-transfer-${sellerId}`).style.display = 'block';
        } else if (this.value === 'qris') {
          card.querySelector(`#info-qris-${sellerId}`).style.display = 'block';
        }
      });
    });

    // 2. HANDLE PREVIEW GAMBAR & VALIDASI (Setiap Card)
    document.querySelectorAll('.proof-input').forEach(input => {
      input.addEventListener('change', function() {
        const sellerId = this.dataset.seller;
        const previewContainer = document.getElementById(`preview-container-${sellerId}`);
        const previewImg = document.getElementById(`preview-img-${sellerId}`);
        const file = this.files[0];

        if (file) {
          // Validasi Tipe
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
          if (!allowedTypes.includes(file.type)) {
            alert('Hanya file gambar (JPG, PNG, GIF) yang diizinkan');
            this.value = '';
            previewContainer.style.display = 'none';
            return;
          }
          // Validasi Ukuran (2MB)
          if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            this.value = '';
            previewContainer.style.display = 'none';
            return;
          }

          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });
    });

    // 3. HANDLE TOMBOL "PLACE ORDER" (Klik per Seller)
    document.querySelectorAll('.btn-place-order').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const form = this.closest('form');
        const sellerName = this.dataset.sellerName;
        const totalAmount = this.dataset.total;

        // Validasi: Metode pembayaran dipilih?
        const paymentMethod = form.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
          alert(`Silakan pilih metode pembayaran untuk seller ${sellerName}`);
          return;
        }

        // Validasi: Bukti transfer diisi?
        const proofInput = form.querySelector('input[name="payment_proof"]');
        if (!proofInput.files || proofInput.files.length === 0) {
          alert(`Silakan upload bukti pembayaran untuk seller ${sellerName}`);
          return;
        }

        // Isi data ke Modal Konfirmasi
        currentActiveForm = form; // Simpan form ini ke variable global
        modalPayment.textContent = paymentMethod.value.toUpperCase();
        modalProof.textContent = proofInput.files[0].name;
        modalTotal.textContent = totalAmount;

        confirmOrderModal.show();
      });
    });

    // 4. KONFIRMASI FINAL DI MODAL
    confirmOrderBtn.addEventListener('click', function() {
      if (currentActiveForm) {
        confirmOrderModal.hide();
        currentActiveForm.submit(); // Submit form spesifik yang tadi diklik
      }
    });
  });
</script>