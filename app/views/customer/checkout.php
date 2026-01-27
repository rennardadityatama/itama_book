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
          <div class="col-lg-6 col-sm-12">
            <div class="checkout-details">
              <div class="order-box">
                <div class="title-box">
                  <div class="checkbox-title">
                    <h4 class="mb-0">Product</h4><span>Total</span>
                  </div>
                </div>

                <!-- PRODUCT LIST -->
                <ul class="qty">
                  <?php foreach ($cartItems as $item): ?>
                    <li>
                      <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?>
                      <span>Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <!-- SUBTOTAL -->
                <ul class="sub-total">
                  <li>Subtotal <span class="count">Rp <?= number_format($totalOrder, 0, ',', '.') ?></span></li>
                </ul>

                <!-- TOTAL -->
                <ul class="sub-total total">
                  <li>Total <span class="count">Rp <?= number_format($totalOrder, 0, ',', '.') ?></span></li>
                </ul>

                <!-- PAYMENT METHOD FORM -->
                <form action="<?= BASE_URL ?>index.php?c=customerOrder&m=placeOrder" method="POST" id="checkoutForm" enctype="multipart/form-data">
                  <input type="hidden" name="seller_id" value="<?= $sellerId ?>">

                  <div class="animate-chk">
                    <div class="row">
                      <div class="col">
                        <h6 class="mb-3">Payment Method</h6>

                        <!-- ✅ BANK TRANSFER ONLY -->
                        <label class="d-block mb-2" for="payment-transfer">
                          <input class="radio_animated payment-method-radio"
                            id="payment-transfer"
                            type="radio"
                            name="payment_method"
                            value="transfer"
                            required>
                          Bank Transfer
                        </label>

                        <!-- ✅ QRIS ONLY -->
                        <label class="d-block mb-2" for="payment-qris">
                          <input class="radio_animated payment-method-radio"
                            id="payment-qris"
                            type="radio"
                            name="payment_method"
                            value="qris"
                            required>
                          QRIS
                        </label>
                      </div>
                    </div>
                  </div>

                  <div id="payment-info-transfer" class="payment-info mt-3" style="display: none;">
                    <div class="alert alert-info">
                      <h6 class="alert-heading">Bank Transfer Information</h6>
                      <hr>
                      <p class="mb-1"><strong>Bank Account Number:</strong></p>
                      <h5 class="mb-0"><?= htmlspecialchars($sellerPaymentInfo['account_number'] ?? 'Not Available') ?></h5>
                      <p class="mb-0 mt-2 text-muted"><small>Please transfer to the account number above and upload the payment proof</small></p>
                    </div>
                  </div>

                  <div id="payment-info-qris" class="payment-info mt-3" style="display: none;">
                    <div class="alert alert-info">
                      <h6 class="alert-heading">QRIS Payment</h6>
                      <hr>
                      <?php if (!empty($sellerPaymentInfo['qris_photo'])): ?>
                        <div class="text-center">
                          <img src="<?= BASE_URL ?>/uploads/qris/<?= $sellerPaymentInfo['qris_photo'] ?>"
                            alt="QRIS Code"
                            class="img-fluid"
                            style="max-width: 300px; border: 2px solid #ddd; padding: 10px; border-radius: 8px;">
                          <p class="mt-2 text-muted"><small>Scan the QR code above to pay, then upload the payment proof</small></p>
                        </div>
                      <?php else: ?>
                        <p class="text-danger mb-0">QRIS not available for this seller</p>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div id="payment-proof-section" class="mt-3" style="display: none;">
                    <div class="mb-3">
                      <label for="payment_proof" class="form-label">
                        Upload Payment Proof <span class="text-danger">*</span>
                      </label>
                      <input type="file"
                        class="form-control"
                        id="payment_proof"
                        name="payment_proof"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        required>
                      <small class="text-muted">Max file size: 2MB. Allowed: JPG, PNG, GIF</small>
                    </div>

                    <!-- Preview Image -->
                    <div id="image-preview" style="display: none;">
                      <p class="mb-1"><strong>Preview:</strong></p>
                      <img id="preview-img"
                        src=""
                        alt="Preview"
                        class="img-fluid"
                        style="max-width: 300px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                    </div>
                  </div>

                  <!-- PLACE ORDER BUTTON -->
                  <div class="order-place mt-3">
                    <button type="button" class="btn btn-primary" id="placeOrderBtn">Place Order</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Order Summary (Right Side) -->
          <div class="col-lg-6 col-sm-12">
            <div class="checkout-details">
              <div class="order-box">
                <div class="title-box">
                  <div>
                    <h4 class="mb-3">Order Summary</h4>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($cartItems as $item): ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <?php if (!empty($item['image'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>"
                                  class="img-fluid img-40 me-2"
                                  alt="<?= htmlspecialchars($item['name']) ?>">
                              <?php endif; ?>
                              <span><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                          </td>
                          <td class="text-center"><?= $item['qty'] ?></td>
                          <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                          <td class="text-end">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr class="border-top">
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end">
                          <strong class="text-primary">Rp <?= number_format($totalOrder, 0, ',', '.') ?></strong>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <!-- BACK TO CART BUTTON -->
                <div class="mt-3">
                  <a href="<?= BASE_URL ?>index.php?c=customerCart&m=index" class="btn btn-secondary w-100">
                    Back to Cart
                  </a>
                </div>
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
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const checkoutForm = document.getElementById('checkoutForm');
    const confirmOrderModal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');
    const modalPayment = document.getElementById('modalPayment');
    const modalProof = document.getElementById('modalProof');

    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const paymentInfoTransfer = document.getElementById('payment-info-transfer');
    const paymentInfoQris = document.getElementById('payment-info-qris');
    const paymentProofSection = document.getElementById('payment-proof-section');
    const paymentProofInput = document.getElementById('payment_proof');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // ✅ HANDLE PAYMENT METHOD CHANGE (HANYA 2: Transfer & QRIS)
    paymentMethodRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        // Hide all payment info first
        paymentInfoTransfer.style.display = 'none';
        paymentInfoQris.style.display = 'none';

        // Reset file input
        paymentProofInput.value = '';
        imagePreview.style.display = 'none';

        // Show payment proof section (WAJIB untuk semua payment method)
        paymentProofSection.style.display = 'block';

        // Show relevant payment info based on selection
        if (this.value === 'transfer') {
          paymentInfoTransfer.style.display = 'block';
        } else if (this.value === 'qris') {
          paymentInfoQris.style.display = 'block';
        }
      });
    });

    // ✅ HANDLE FILE PREVIEW
    paymentProofInput.addEventListener('change', function(e) {
      const file = e.target.files[0];

      if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
          alert('Only JPG, PNG, and GIF files are allowed');
          this.value = '';
          imagePreview.style.display = 'none';
          return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
          alert('File size must be less than 2MB');
          this.value = '';
          imagePreview.style.display = 'none';
          return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = 'none';
      }
    });

    // ✅ WHEN "PLACE ORDER" BUTTON IS CLICKED
    placeOrderBtn.addEventListener('click', function(e) {
      e.preventDefault();

      // Check if payment method is selected
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

      if (!paymentMethod) {
        alert('Please select a payment method');
        return false;
      }

      // Check if payment proof is uploaded (WAJIB untuk semua payment)
      if (!paymentProofInput.files || paymentProofInput.files.length === 0) {
        alert('Please upload payment proof');
        return false;
      }

      // Update modal with payment method
      let paymentText = '';
      if (paymentMethod.value === 'transfer') {
        paymentText = 'Bank Transfer';
      } else if (paymentMethod.value === 'qris') {
        paymentText = 'QRIS';
      }

      modalPayment.textContent = paymentText;

      // Update payment proof info in modal
      modalProof.textContent = paymentProofInput.files[0].name;

      // Show confirmation modal
      confirmOrderModal.show();
    });

    // ✅ WHEN "CONFIRM ORDER" BUTTON IN MODAL IS CLICKED
    confirmOrderBtn.addEventListener('click', function() {
      // Close modal
      confirmOrderModal.hide();

      // Submit form
      checkoutForm.submit();
    });
  });
</script>