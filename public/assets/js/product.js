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
   GLOBAL STATE
===================================================== */
let editProductId = null;
let deleteProductId = null;

/* =====================================================
   MODAL HANDLER (GLOBAL FUNCTION)
===================================================== */
function openAddProductModal() {
    document.getElementById('addForm').reset();
    new bootstrap.Modal(document.getElementById('addModal')).show();
}

function openEditProduct(id) {
    fetch(PRODUCT_BASE_URL + '&m=show&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.status) {
                showToast(res.message, 'danger');
                return;
            }

            const d = res.data;
            editProductId = id;

            document.getElementById('editProductId').value = id;
            document.getElementById('editProductName').value = d.name ?? '';
            document.getElementById('editProductPrice').value = d.price ?? '';
            document.getElementById('editProductCategoryId').value = d.category_id ?? '';
            document.getElementById('editProductCost').value = d.cost_price ?? '';
            document.getElementById('editProductMargin').value = d.margin ?? '';
            document.getElementById('editProductStock').value = d.stock ?? '';
            document.getElementById('editProductDescription').value = d.description ?? '';
            document.getElementById('editProductImage').value = ''; // leave blank for new upload

            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(() => showToast('Failed to fetch product data', 'danger'));
}

function openDeleteProduct(id, name) {
    deleteProductId = id;
    document.getElementById('deleteMessage').textContent =
        `Are you sure you want to delete "${name}" product?`;

    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

/* =====================================================
   EVENT BINDING
===================================================== */
document.addEventListener('DOMContentLoaded', () => {

    /* ADD PRODUCT */
    const addForm = document.getElementById('addForm');
    addForm?.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(addForm);

        fetch(PRODUCT_BASE_URL + '&m=store', {
            method: 'POST',
            body: data
        })
            .then(r => r.json())
            .then(res => {
                if (!res.status) {
                    showToast(res.message, 'danger');
                    return;
                }

                showToast(res.message, 'success');
                bootstrap.Modal.getInstance(
                    document.getElementById('addModal')
                ).hide();

                setTimeout(() => location.reload(), 1200);
            })
            .catch(() => showToast('Failed to add product', 'danger'));
    });

    /* EDIT PRODUCT */
    const editForm = document.getElementById('editForm');
    editForm?.addEventListener('submit', e => {
        e.preventDefault();

        if (!editProductId) {
            showToast('Invalid product', 'danger');
            return;
        }

        const data = new FormData(editForm);

        fetch(PRODUCT_BASE_URL + '&m=update&id=' + editProductId, {
            method: 'POST',
            body: data
        })
            .then(r => r.json())
            .then(res => {
                if (!res.status) {
                    showToast(res.message, 'danger');
                    return;
                }

                showToast(res.message, 'success');
                bootstrap.Modal.getInstance(
                    document.getElementById('editModal')
                ).hide();

                setTimeout(() => location.reload(), 1200);
            })
            .catch(() => showToast('Failed to update product', 'danger'));
    });

    /* DELETE PRODUCT */
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn?.addEventListener('click', () => {

        if (!deleteProductId) return;

        fetch(PRODUCT_BASE_URL + '&m=destroy&id=' + deleteProductId, {
            method: 'POST'
        })
            .then(r => r.json())
            .then(res => {
                if (!res.status) {
                    showToast(res.message, 'danger');
                    return;
                }

                showToast(res.message, 'success');
                bootstrap.Modal.getInstance(
                    document.getElementById('deleteModal')
                ).hide();

                setTimeout(() => location.reload(), 1200);
            })
            .catch(() => showToast('Failed to delete product', 'danger'));
    });

    /* BUTTON CLICK */
    document.getElementById('btnAddProduct')
        ?.addEventListener('click', openAddProductModal);

    document.getElementById('productTable')
        ?.addEventListener('click', e => {
            const edit = e.target.closest('.btnEdit');
            const del = e.target.closest('.btnDelete');

            if (edit) openEditProduct(edit.dataset.id);
            if (del) openDeleteProduct(del.dataset.id, del.dataset.name);
        });
});
