function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toastEl = document.createElement('div');
    // type: success = hijau, danger = merah
    toastEl.className = `toast align-items-center text-bg-${type} border-0 mb-2`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    // Set innerHTML dengan bg-success/bg-danger
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

/* ADD CATEGORY */
function addCategory() {
    const input = document.getElementById('categoryName');
    const name = input.value.trim();
    if (!name) { showToast('Nama kategori wajib diisi', 'danger'); input.focus(); return; }

    fetch(CATEGORY_BASE_URL + '&m=store', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res.success) {
            input.value = '';
            showToast(res.message, 'success'); // hijau
            const tbody = document.getElementById('categoryTableBody');
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
        } else showToast(res.message, 'danger'); // merah
    }).catch(err => { console.error(err); showToast('Terjadi kesalahan', 'danger'); });
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
    if (!name) { showToast('Nama kategori wajib diisi', 'danger'); return; }

    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));

    fetch(CATEGORY_BASE_URL + '&m=update&id=' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res.success) {
            showToast(res.message, 'success'); // hijau
            const tbody = document.getElementById('categoryTableBody');
            const rowIndex = this.dataset.rowElement;
            if (rowIndex !== undefined) {
                tbody.rows[rowIndex - 1].querySelector('.cat-name').textContent = name;
            }
            modal.hide();
        } else showToast(res.message, 'danger'); // merah
    }).catch(err => { console.error(err); showToast('Terjadi kesalahan saat update', 'danger'); });
});

function updateRowNumbers(){
    const tbody = document.getElementById('categoryTableBody');
    Array.from(tbody.rows).forEach((row,index)=>{
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

document.getElementById('confirmDeleteBtn').addEventListener('click',()=>{
    if(!deleteId || !deleteElement) return;
    fetch(CATEGORY_BASE_URL + '&m=destroy&id='+deleteId,{method:'POST'})
    .then(r=>r.json()).then(res=>{
        if(res.success){
            showToast(res.message,'success'); // hijau
            deleteElement.closest('tr').remove();
            updateRowNumbers(); // update nomor urut setelah hapus
        }else showToast(res.message,'danger'); // merah
        deleteId=null; deleteElement=null;
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    }).catch(err=>{console.error(err); showToast('Terjadi kesalahan saat hapus','danger');});
});

/* Pasang listener tombol tambah */
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('addCategoryBtn');
    if (btn) btn.addEventListener('click', addCategory);
});
