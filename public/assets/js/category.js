function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-bg-${type} border-0 mb-2`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
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
    const toast = new bootstrap.Toast(toastEl, { delay: 1000 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

// ==================== LOADER FUNCTIONS ====================
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
// ==================== END LOADER ====================

/* ADD CATEGORY */
function addCategory() {
    const input = document.getElementById('categoryName');
    const name = input.value.trim();
    if (!name) { 
        showToast('Nama kategori wajib diisi', 'danger'); 
        input.focus(); 
        return; 
    }

    showLoader(); // Tampilkan loader

    fetch(CATEGORY_BASE_URL + '&m=store', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        hideLoader(); // Sembunyikan loader
        if (res.success) {
            input.value = '';
            showToast(res.message, 'success');
            const tbody = document.getElementById('categoryTableBody');
            
            // Hapus row "Belum ada data" jika ada
            const emptyRow = tbody.querySelector('tr td[colspan="3"]');
            if (emptyRow) {
                emptyRow.closest('tr').remove();
            }
            
            const rowNumber = tbody.rows.length + 1;
            const newRow = document.createElement('tr');
            newRow.dataset.id = res.id;
            newRow.innerHTML = `
                <td>${rowNumber}</td>
                <td class="cat-name">${name}</td>
                <td>
                    <ul class="action">
                        <li class="edit"><a href="javascript:void(0)" onclick="openEditModal(${res.id},'${name}',this)"><i class="icon-pencil-alt"></i></a></li>
                        <li class="delete"><a href="javascript:void(0)" onclick="openDeleteModal(${res.id},'${name}',this)"><i class="icon-trash"></i></a></li>
                    </ul>
                </td>
            `;
            tbody.appendChild(newRow);
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        } else {
            showToast(res.message, 'danger');
        }
    }).catch(err => { 
        hideLoader(); // Sembunyikan loader
        console.error(err); 
        showToast('Terjadi kesalahan', 'danger'); 
    });
}

/* EDIT CATEGORY */
function openEditModal(id, name, element) {
    document.getElementById('editCategoryId').value = id;
    document.getElementById('editCategoryName').value = name;
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
    document.getElementById('editForm').dataset.rowElement = element.closest('tr').rowIndex;
}

document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const id = document.getElementById('editCategoryId').value;
    const name = document.getElementById('editCategoryName').value.trim();
    if (!name) { 
        showToast('Nama kategori wajib diisi', 'danger'); 
        return; 
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    
    showLoader(); // Tampilkan loader

    fetch(CATEGORY_BASE_URL + '&m=update&id=' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        hideLoader(); // Sembunyikan loader
        if (res.success) {
            showToast(res.message, 'success');
            const tbody = document.getElementById('categoryTableBody');
            const rowIndex = this.dataset.rowElement;
            if (rowIndex !== undefined) {
                const row = tbody.rows[rowIndex - 1];
                if (row) {
                    row.querySelector('.cat-name').textContent = name;
                    // Update onclick attribute
                    const editBtn = row.querySelector('.edit a');
                    const deleteBtn = row.querySelector('.delete a');
                    if (editBtn) {
                        editBtn.setAttribute('onclick', `openEditModal(${id},'${name}',this)`);
                    }
                    if (deleteBtn) {
                        deleteBtn.setAttribute('onclick', `openDeleteModal(${id},'${name}',this)`);
                    }
                }
            }
            modal.hide();
        } else {
            showToast(res.message, 'danger');
        }
    }).catch(err => { 
        hideLoader(); // Sembunyikan loader
        console.error(err); 
        showToast('Terjadi kesalahan saat update', 'danger'); 
    });
});

function updateRowNumbers() {
    const tbody = document.getElementById('categoryTableBody');
    Array.from(tbody.rows).forEach((row, index) => {
        row.cells[0].textContent = index + 1;
    });
}

/* DELETE CATEGORY */
let deleteId = null, deleteElement = null;
function openDeleteModal(id, name, element) {
    deleteId = id;
    deleteElement = element;
    document.getElementById('deleteMessage').textContent = `Are you sure want to delete "${name}" category?`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (!deleteId || !deleteElement) return;
    
    showLoader(); // Tampilkan loader
    
    fetch(CATEGORY_BASE_URL + '&m=destroy&id=' + deleteId, { method: 'POST' })
        .then(r => r.json()).then(res => {
            hideLoader(); // Sembunyikan loader
            if (res.success) {
                showToast(res.message, 'success');
                deleteElement.closest('tr').remove();
                updateRowNumbers();
                
                // Tambahkan row kosong jika tidak ada data
                const tbody = document.getElementById('categoryTableBody');
                if (tbody.rows.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada data kategori
                            </td>
                        </tr>
                    `;
                }
            } else {
                showToast(res.message, 'danger');
            }
            deleteId = null; 
            deleteElement = null;
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        }).catch(err => { 
            hideLoader(); // Sembunyikan loader
            console.error(err); 
            showToast('Terjadi kesalahan saat hapus', 'danger'); 
        });
});

/* Pasang listener tombol tambah */
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('addCategoryBtn');
    if (btn) btn.addEventListener('click', addCategory);
    
    // Hide loader saat halaman category ready
    hideLoader();
});