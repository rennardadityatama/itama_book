/**
 * =====================================================
 * CUSTOMER PAGE SCRIPT (ANTI RE-INIT)
 * =====================================================
 */
(() => {
    // 🔒 GUARD: prevent re-execution
    if (window.__customerJSInitialized) {
        return;
    }
    window.__customerJSInitialized = true;

    console.count('customer.js executed');

    /* =====================================================
       STATE (PRIVATE)
    ===================================================== */
    let editCustomerId = null;
    let deleteCustomerId = null;

    /* =====================================================
       LOADER FUNCTIONS
    ===================================================== */
    function showLoader() {
        const loader = document.getElementById('globalSpinner') || document.querySelector('.loader-wrapper');
        if (loader) {
            loader.classList.remove('d-none');
            loader.classList.add('loderhide');
            loader.style.display = 'block';
        }
    }

    function hideLoader() {
        const loader = document.getElementById('globalSpinner') || document.querySelector('.loader-wrapper');
        if (loader) {
            setTimeout(() => {
                loader.classList.remove('loderhide');
                loader.classList.add('d-none');
                loader.style.display = '';
            }, 500);
        }
    }

    // Hide loader saat halaman selesai dimuat
    window.addEventListener('load', function() {
        hideLoader();
    });

    /* =====================================================
       TOAST
    ===================================================== */
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${type} border-0 mb-2`;
        toastEl.innerHTML = `
            <div class="d-flex ${type === 'success' ? 'bg-success' : 'bg-danger'} text-white rounded">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        `;
        container.appendChild(toastEl);

        const toast = new bootstrap.Toast(toastEl, { delay: 1200 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    /* =====================================================
       MODAL HANDLERS
    ===================================================== */
    function openAddCustomerModal() {
        const form = document.getElementById('addForm');
        if (!form) return;

        form.reset();
        new bootstrap.Modal(document.getElementById('addModal')).show();
    }

    function openEditCustomer(id) {
        showLoader(); // Tampilkan loader
        
        fetch(`${CUSTOMER_BASE_URL}&m=show&id=${id}`)
            .then(r => r.json())
            .then(res => {
                hideLoader(); // Sembunyikan loader
                
                if (!res.success) {
                    showToast(res.message, 'danger');
                    return;
                }

                const d = res.data;
                editCustomerId = id;

                document.getElementById('editCustomerName').value = d.name ?? '';
                document.getElementById('editCustomerNik').value = d.nik ?? '';
                document.getElementById('editCustomerEmail').value = d.email ?? '';
                document.getElementById('editCustomerPassword').value = '';
                document.getElementById('editCustomerAddress').value = d.address ?? '';
                document.getElementById('editCustomerPhone').value = d.phone ?? '';

                new bootstrap.Modal(document.getElementById('editModal')).show();
            })
            .catch(() => {
                hideLoader(); // Sembunyikan loader
                showToast('Failed to retrieve customer data', 'danger');
            });
    }

    function openDeleteCustomer(id, name) {
        deleteCustomerId = id;
        const msg = document.getElementById('deleteMessage');
        if (msg) {
            msg.textContent = `Are you sure want to delete "${name}" customer?`;
        }

        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    /* =====================================================
       EVENT BINDING (SAFE)
    ===================================================== */
    function bindEvents() {

        // Hide loader saat DOM ready
        hideLoader();

        /* ADD */
        const addForm = document.getElementById('addForm');
        addForm?.addEventListener('submit', e => {
            e.preventDefault();

            showLoader(); // Tampilkan loader

            fetch(`${CUSTOMER_BASE_URL}&m=store`, {
                method: 'POST',
                body: new FormData(addForm)
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader(); // Sembunyikan loader
                    
                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('addModal')
                    )?.hide();

                    // Tampilkan loader sebelum reload
                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader(); // Sembunyikan loader
                    showToast('Error add customer', 'danger');
                });
        });

        /* EDIT */
        const editForm = document.getElementById('editForm');
        editForm?.addEventListener('submit', e => {
            e.preventDefault();

            if (!editCustomerId) {
                showToast('Invalid customer', 'danger');
                return;
            }

            showLoader(); // Tampilkan loader

            fetch(`${CUSTOMER_BASE_URL}&m=update&id=${editCustomerId}`, {
                method: 'POST',
                body: new FormData(editForm)
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader(); // Sembunyikan loader
                    
                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('editModal')
                    )?.hide();

                    // Tampilkan loader sebelum reload
                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader(); // Sembunyikan loader
                    showToast('Error updating customer', 'danger');
                });
        });

        /* DELETE */
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn?.addEventListener('click', () => {
            if (!deleteCustomerId) return;

            showLoader(); // Tampilkan loader

            fetch(`${CUSTOMER_BASE_URL}&m=destroy&id=${deleteCustomerId}`, {
                method: 'POST'
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader(); // Sembunyikan loader
                    
                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('deleteModal')
                    )?.hide();

                    // Tampilkan loader sebelum reload
                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader(); // Sembunyikan loader
                    showToast('Error deleting customer', 'danger');
                });
        });

        /* BUTTONS & CARDS (DELEGATION) */
        document.getElementById('btnAddCustomer')
            ?.addEventListener('click', openAddCustomerModal);

        document.getElementById('customerCards')
            ?.addEventListener('click', e => {
                const editBtn = e.target.closest('.btnEdit');
                const delBtn = e.target.closest('.btnDelete');

                if (editBtn) {
                    openEditCustomer(editBtn.dataset.id);
                }
                if (delBtn) {
                    openDeleteCustomer(
                        delBtn.dataset.id,
                        delBtn.dataset.name
                    );
                }
            });
    }

    /* =====================================================
       INIT
    ===================================================== */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindEvents);
    } else {
        bindEvents();
    }
})();