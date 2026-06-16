document.addEventListener('DOMContentLoaded', () => {
    let gambarObjectUrl = null;
    let dokumenObjectUrl = null;
    const MAX_GAMBAR_SIZE = 10 * 1024 * 1024;
    const MAX_DOKUMEN_SIZE = 20 * 1024 * 1024;
    let gambarValid = true;
    let dokumenValid = true;

    const btnSubmit = document.getElementById('btn_submit');
    const formTugas = document.getElementById('form_tugas');
    const gambarFile = document.getElementById('gambar_file');
    const hapusGambar = document.getElementById('hapus_gambar');
    const namaFile = document.getElementById('nama_file');
    const hapusDokumen = document.getElementById('hapus_dokumen');
    const kategoritugasSelect = document.getElementById('kategoritugas');

    if (!formTugas) return;

    function validasiForm() {
        const errorContainer = document.getElementById('pesan_error_kapasitas');
        const errorText = document.getElementById('teks_error_kapasitas');

        if (!gambarValid) {
            errorText.textContent = "Ukuran file Gambar melebihi batas maksimum 10MB! File tidak dapat diupload.";
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
            btnSubmit.disabled = true;
        } else if (!dokumenValid) {
            errorText.textContent = "Ukuran file Dokumen melebihi batas maksimum 20MB! File tidak dapat diupload.";
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
            btnSubmit.disabled = true;
        } else {
            errorContainer.classList.remove('flex');
            errorContainer.classList.add('hidden');
            btnSubmit.disabled = false;
        }
    }

    if (gambarFile) {
        gambarFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (gambarObjectUrl) {
                URL.revokeObjectURL(gambarObjectUrl);
                gambarObjectUrl = null;
            }

            if (file) {
                gambarValid = file.size <= MAX_GAMBAR_SIZE;
                gambarObjectUrl = URL.createObjectURL(file);

                const linkElement = document.getElementById('link_preview_gambar');
                linkElement.setAttribute('href', gambarObjectUrl);
                linkElement.removeAttribute('download');
                linkElement.setAttribute('target', '_blank');
                linkElement.textContent = file.name + ' (Klik untuk preview)';

                const previewContainer = document.getElementById('container_preview_gambar');
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex', 'flex-col');
            } else {
                gambarValid = true;
            }
            validasiForm();
        });
    }

    if (hapusGambar) {
        hapusGambar.addEventListener('click', function(e) {
            e.preventDefault();
            gambarFile.value = '';
            const previewContainer = document.getElementById('container_preview_gambar');
            previewContainer.classList.remove('flex', 'flex-col');
            previewContainer.classList.add('hidden');
            if (gambarObjectUrl) {
                URL.revokeObjectURL(gambarObjectUrl);
                gambarObjectUrl = null;
            }
            gambarValid = true;
            validasiForm();
        });
    }

    if (namaFile) {
        namaFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (dokumenObjectUrl) {
                URL.revokeObjectURL(dokumenObjectUrl);
                dokumenObjectUrl = null;
            }

            if (file) {
                dokumenValid = file.size <= MAX_DOKUMEN_SIZE;
                dokumenObjectUrl = URL.createObjectURL(file);

                const linkElement = document.getElementById('link_preview_dokumen');
                linkElement.setAttribute('href', dokumenObjectUrl);
                linkElement.textContent = file.name + ' (Klik untuk preview)';

                const previewContainer = document.getElementById('container_preview_dokumen');
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex', 'flex-col');
            } else {
                dokumenValid = true;
            }
            validasiForm();
        });
    }

    if (hapusDokumen) {
        hapusDokumen.addEventListener('click', function(e) {
            e.preventDefault();
            namaFile.value = '';
            const previewContainer = document.getElementById('container_preview_dokumen');
            previewContainer.classList.remove('flex', 'flex-col');
            previewContainer.classList.add('hidden');
            if (dokumenObjectUrl) {
                URL.revokeObjectURL(dokumenObjectUrl);
                dokumenObjectUrl = null;
            }
            dokumenValid = true;
            validasiForm();
        });
    }

    const staffContainer = document.getElementById('assignee_staff_container');
    const grupContainer = document.getElementById('assignee_grup_container');
    const staffSelect = document.getElementById('user_id');
    const grupSelect = document.getElementById('grup_kerja_id');

    function toggleAssigneeFields() {
        if (kategoritugasSelect.value === 'Individu') {
            staffContainer.classList.remove('hidden');
            grupContainer.classList.add('hidden');
            staffSelect.disabled = false;
            staffSelect.required = true;
            grupSelect.disabled = true;
            grupSelect.required = false;
            grupSelect.value = '';
        } else if (kategoritugasSelect.value === 'Kelompok') {
            staffContainer.classList.add('hidden');
            grupContainer.classList.remove('hidden');
            staffSelect.disabled = true;
            staffSelect.required = false;
            staffSelect.value = '';
            grupSelect.disabled = false;
            grupSelect.required = true;
        } else {
            staffContainer.classList.add('hidden');
            grupContainer.classList.add('hidden');
            staffSelect.disabled = true;
            staffSelect.required = false;
            grupSelect.disabled = true;
            grupSelect.required = false;
        }
    }

    if (kategoritugasSelect) {
        kategoritugasSelect.addEventListener('change', toggleAssigneeFields);
        toggleAssigneeFields();
    }

    initRealTimeValidation('form_tugas');
});
