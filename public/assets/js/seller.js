/**
 * =====================================================
 * SELLER PAGE SCRIPT (ANTI RE-INIT)
 * =====================================================
 */
(() => {
    // 🔒 GUARD: prevent re-execution
    if (window.__sellerJSInitialized) {
        return;
    }
    window.__sellerJSInitialized = true;

    console.count('seller.js executed');

    /* =====================================================
       STATE (PRIVATE)
    ===================================================== */
    let editSellerId = null;
    let deleteSellerId = null;

    /* =====================================================
       LOADER FUNCTIONS
    ===================================================== */
    function showLoader() {
        const loader =
            document.getElementById('globalSpinner') ||
            document.querySelector('.loader-wrapper');

        if (loader) {
            loader.classList.remove('d-none');
            loader.classList.add('loderhide');
            loader.style.display = 'block';
        }
    }

    function hideLoader() {
        const loader =
            document.getElementById('globalSpinner') ||
            document.querySelector('.loader-wrapper');

        if (loader) {
            setTimeout(() => {
                loader.classList.remove('loderhide');
                loader.classList.add('d-none');
                loader.style.display = '';
            }, 500);
        }
    }

    // Hide loader saat halaman selesai dimuat
    window.addEventListener('load', hideLoader);

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
    function openAddSellerModal() {
        const form = document.getElementById('addForm');
        if (!form) return;

        form.reset();
        new bootstrap.Modal(document.getElementById('addModal')).show();
    }

    function openEditSeller(id) {
        showLoader();

        fetch(`${SELLER_BASE_URL}&m=show&id=${id}`)
            .then(r => r.json())
            .then(res => {
                hideLoader();

                if (!res.success) {
                    showToast(res.message, 'danger');
                    return;
                }

                const d = res.data;
                editSellerId = id;

                document.getElementById('editSellerName').value = d.name ?? '';
                document.getElementById('editSellerNik').value = d.nik ?? '';
                document.getElementById('editSellerEmail').value = d.email ?? '';
                document.getElementById('editSellerPassword').value = '';
                document.getElementById('editSellerAddress').value = d.address ?? '';
                document.getElementById('editSellerAccount').value = d.account_number ?? '';

                new bootstrap.Modal(document.getElementById('editModal')).show();
            })
            .catch(() => {
                hideLoader();
                showToast('Failed to retrieve seller data', 'danger');
            });
    }

    function openDeleteSeller(id, name) {
        deleteSellerId = id;
        const msg = document.getElementById('deleteMessage');
        if (msg) {
            msg.textContent = `Are you sure want to delete "${name}" seller?`;
        }

        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    /* =====================================================
       EVENT BINDING (SAFE)
    ===================================================== */
    function bindEvents() {
        hideLoader();

        /* ADD */
        const addForm = document.getElementById('addForm');
        addForm?.addEventListener('submit', e => {
            e.preventDefault();

            showLoader();

            fetch(`${SELLER_BASE_URL}&m=store`, {
                method: 'POST',
                body: new FormData(addForm)
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader();

                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('addModal')
                    )?.hide();

                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader();
                    showToast('Error add seller', 'danger');
                });
        });

        /* EDIT */
        const editForm = document.getElementById('editForm');
        editForm?.addEventListener('submit', e => {
            e.preventDefault();

            if (!editSellerId) {
                showToast('Invalid seller', 'danger');
                return;
            }

            showLoader();

            fetch(`${SELLER_BASE_URL}&m=update&id=${editSellerId}`, {
                method: 'POST',
                body: new FormData(editForm)
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader();

                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('editModal')
                    )?.hide();

                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader();
                    showToast('Error updating seller', 'danger');
                });
        });

        /* DELETE */
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn?.addEventListener('click', () => {
            if (!deleteSellerId) return;

            showLoader();

            fetch(`${SELLER_BASE_URL}&m=destroy&id=${deleteSellerId}`, {
                method: 'POST'
            })
                .then(r => r.json())
                .then(res => {
                    hideLoader();

                    if (!res.success) {
                        showToast(res.message, 'danger');
                        return;
                    }

                    showToast(res.message, 'success');
                    bootstrap.Modal.getInstance(
                        document.getElementById('deleteModal')
                    )?.hide();

                    showLoader();
                    setTimeout(() => location.reload(), 1200);
                })
                .catch(() => {
                    hideLoader();
                    showToast('Error deleting seller', 'danger');
                });
        });

        /* BUTTONS & CARDS (DELEGATION) */
        document.getElementById('btnAddSeller')
            ?.addEventListener('click', openAddSellerModal);

        document.getElementById('sellerCards')
            ?.addEventListener('click', e => {
                const editBtn = e.target.closest('.btnEdit');
                const delBtn = e.target.closest('.btnDelete');

                if (editBtn) {
                    openEditSeller(editBtn.dataset.id);
                }
                if (delBtn) {
                    openDeleteSeller(
                        delBtn.dataset.id,
                        delBtn.dataset.name
                    );
                }
            });

        document.addEventListener('click', e => {
            const img = e.target.closest('img[data-bs-target="#qrisModal"]');
            if (!img) return;

            const preview = document.getElementById('qrisPreview');
            if (preview) {
                preview.src = img.dataset.img;
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
