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
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    container.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: 1200 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

/* =====================================================
   LOADER FUNCTIONS
===================================================== */
function showLoader() {
    const loader = document.querySelector('.loader-wrapper');
    if (loader) {
        loader.classList.add('loderhide');
        loader.style.display = 'block';
    }
}

function hideLoader() {
    const loader = document.querySelector('.loader-wrapper');
    if (loader) {
        setTimeout(() => {
            loader.classList.remove('loderhide');
        }, 500);
    }
}

// Hide loader saat halaman selesai dimuat
window.addEventListener('load', function() {
    hideLoader();
});

/* =====================================================
   GLOBAL STATE
===================================================== */
let editSellerId = null;
let deleteSellerId = null;

/* =====================================================
   MODAL HANDLER (GLOBAL FUNCTION)
===================================================== */
function openAddSellerModal() {
    document.getElementById('addForm').reset();
    new bootstrap.Modal(document.getElementById('addModal')).show();
}

function openEditSeller(id) {
    showLoader(); // Tampilkan loader
    
    fetch(SELLER_BASE_URL + '&m=show&id=' + id)
        .then(r => r.json())
        .then(res => {
            hideLoader(); // Sembunyikan loader
            
            if (!res.success) {
                showToast(res.message, 'danger');
                return;
            }

            const d = res.data;
            editSellerId = id;

            document.getElementById('editSellerName').value    = d.name ?? '';
            document.getElementById('editSellerNik').value   = d.nik ?? '';
            document.getElementById('editSellerEmail').value   = d.email ?? '';
            document.getElementById('editSellerPassword').value   = '';
            document.getElementById('editSellerAddress').value = d.address ?? '';
            document.getElementById('editSellerAccount').value = d.account_number ?? '';

            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(() => {
            hideLoader(); // Sembunyikan loader
            showToast('Gagal mengambil data seller', 'danger');
        });
}

function openDeleteSeller(id, name) {
    deleteSellerId = id;
    document.getElementById('deleteMessage').textContent =
        `Are you sure want to delete "${name}" seller?`;

    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

/* =====================================================
   EVENT BINDING
===================================================== */
document.addEventListener('DOMContentLoaded', () => {

    // Hide loader saat DOM ready
    hideLoader();

    /* ADD SELLER */
    const addForm = document.getElementById('addForm');
    addForm?.addEventListener('submit', e => {
        e.preventDefault();

        const data = new FormData(addForm);
        
        showLoader(); // Tampilkan loader

        fetch(SELLER_BASE_URL + '&m=store', {
            method: 'POST',
            body: data
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
            ).hide();

            // Tampilkan loader sebelum reload
            showLoader();
            setTimeout(() => location.reload(), 1200);
        })
        .catch(() => {
            hideLoader(); // Sembunyikan loader
            showToast('Gagal menambah seller', 'danger');
        });
    });

    /* EDIT SELLER */
    const editForm = document.getElementById('editForm');
    editForm?.addEventListener('submit', e => {
        e.preventDefault();

        if (!editSellerId) {
            showToast('Seller tidak valid', 'danger');
            return;
        }

        const data = new FormData(editForm);
        
        showLoader(); // Tampilkan loader

        fetch(SELLER_BASE_URL + '&m=update&id=' + editSellerId, {
            method: 'POST',
            body: data
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
            ).hide();

            // Tampilkan loader sebelum reload
            showLoader();
            setTimeout(() => location.reload(), 1200);
        })
        .catch(() => {
            hideLoader(); // Sembunyikan loader
            showToast('Gagal update seller', 'danger');
        });
    });

    /* DELETE SELLER */
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn?.addEventListener('click', () => {

        if (!deleteSellerId) return;

        showLoader(); // Tampilkan loader

        fetch(SELLER_BASE_URL + '&m=destroy&id=' + deleteSellerId, {
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
            ).hide();

            // Tampilkan loader sebelum reload
            showLoader();
            setTimeout(() => location.reload(), 1200);
        })
        .catch(() => {
            hideLoader(); // Sembunyikan loader
            showToast('Gagal menghapus seller', 'danger');
        });
    });

    /* BUTTON & CARD CLICK */
    document.getElementById('btnAddSeller')
        ?.addEventListener('click', openAddSellerModal);

    document.getElementById('sellerCards')
        ?.addEventListener('click', e => {
            const edit = e.target.closest('.btnEdit');
            const del  = e.target.closest('.btnDelete');

            if (edit) openEditSeller(edit.dataset.id);
            if (del) openDeleteSeller(del.dataset.id, del.dataset.name);
        });
});

document.addEventListener('click', e => {
  const img = e.target.closest('img[data-bs-target="#qrisModal"]');
  if (!img) return;
  document.getElementById('qrisPreview').src = img.dataset.img;
});