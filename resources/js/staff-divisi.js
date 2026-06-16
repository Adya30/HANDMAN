document.addEventListener('DOMContentLoaded', () => {
    const dataEl = document.getElementById('staff-divisi-data');
    if (!dataEl) return;

    const allGroups = JSON.parse(dataEl.dataset.grups || '[]');
    const urlStaffDivisi = dataEl.dataset.urlStaffDivisi;
    const urlGrupKerja = dataEl.dataset.urlGrupKerja;
    const tabSession = dataEl.dataset.tabSession;
    const assetStorage = dataEl.dataset.assetStorage;

    function switchTab(tabId) {
        document.getElementById('tab-content-staff-list').classList.add('hidden');
        document.getElementById('tab-content-grup-kerja').classList.add('hidden');

        const btnStaff = document.getElementById('tab-btn-staff-list');
        const btnGrup = document.getElementById('tab-btn-grup-kerja');

        btnStaff.className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all";
        btnGrup.className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all";

        if (tabId === 'staff-list') {
            document.getElementById('tab-content-staff-list').classList.remove('hidden');
            btnStaff.className = "border-[#3B28CC] text-[#3B28CC] whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 cursor-pointer transition-all";
        } else {
            document.getElementById('tab-content-grup-kerja').classList.remove('hidden');
            btnGrup.className = "border-[#3B28CC] text-[#3B28CC] whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 cursor-pointer transition-all";
        }
    }

    window.switchTab = switchTab;

    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || tabSession;
    if (tab === 'grup-kerja') {
        switchTab('grup-kerja');
    } else {
        switchTab('staff-list');
    }

    document.querySelectorAll('.staff-row').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = urlStaffDivisi + "/" + this.dataset.id;
        });
    });

    document.querySelectorAll('.grup-row').forEach(row => {
        row.addEventListener('click', function() {
            showGroupDetail(this.dataset.id);
        });
    });

    function openGrupModal() {
        document.getElementById('input-nama-grup').value = '';
        document.getElementById('input-deskripsi').value = '';

        const modal = document.getElementById('modal-grup');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('input-nama-grup').focus();
    }

    window.openGrupModal = openGrupModal;

    function showGroupDetail(id) {
        const grup = allGroups.find(g => String(g.id) === String(id));
        if (!grup) return;

        document.getElementById('detail-grup-nama').textContent = 'Detail Grup - ' + grup.nama_grup;
        document.getElementById('detail-grup-deskripsi').textContent = grup.deskripsi || 'Tidak ada deskripsi.';

        const dateStr = new Date(grup.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const creatorName = grup.creator ? grup.creator.nama_lengkap : '-';
        document.getElementById('detail-grup-meta').textContent = `Dibuat oleh ${creatorName} pada ${dateStr}`;
        document.getElementById('detail-grup-anggota-count').textContent = grup.anggota.length;

        const listEl = document.getElementById('detail-grup-anggota-list');
        listEl.innerHTML = '';
        grup.anggota.forEach(m => {
            const foto = m.foto_profil ? `${assetStorage}/${m.foto_profil}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama_lengkap)}&background=3B28CC&color=fff&size=64`;
            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-2.5 bg-gray-50 rounded-xl border border-indigo-50 shrink-0';
            item.innerHTML = `
                <img src="${foto}" class="w-8 h-8 rounded-full object-cover border border-indigo-50 shrink-0">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 truncate">${m.nama_lengkap}</p>
                    <p class="text-xs text-gray-400 truncate">${m.email}</p>
                </div>
            `;
            listEl.appendChild(item);
        });

        const formDelete = document.getElementById('form-delete-grup');
        formDelete.action = urlGrupKerja + "/" + grup.id;

        const modal = document.getElementById('modal-detail-grup');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.showGroupDetail = showGroupDetail;

    function closeGrupModal() {
        document.getElementById('modal-grup').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.closeGrupModal = closeGrupModal;

    function closeDetailGrupModal() {
        document.getElementById('modal-detail-grup').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.closeDetailGrupModal = closeDetailGrupModal;

    document.getElementById('form-grup')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('btn-submit-grup');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Memproses...';
    });

    document.getElementById('filter-status')?.addEventListener('change', () => document.getElementById('filter-form').submit());

    initRealTimeValidation('form-grup');
});
