document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // ============================
    // TAB FUNCTIONALITY
    // ============================
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.dataset.tab;

            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.classList.add('hidden');
            });

            button.classList.add('active', 'border-blue-500', 'text-blue-600');
            button.classList.remove('border-transparent', 'text-gray-500');

            const targetTab = document.getElementById(tabName + '-tab');
            if (targetTab) {
                targetTab.classList.add('active');
                targetTab.classList.remove('hidden');
            }
        });
    });

    
    // ============================
    // CERTIFICATE WIZARD & CRUD
    // ============================
    const addCertificateBtn = document.getElementById('add-certificate-btn');
    const certificateModal = document.getElementById('certificate-modal');

    if (certificateModal) {
        const certificateForm = document.getElementById('certificate-form');
        const page1 = document.getElementById('certificate-page-1');
        const page2 = document.getElementById('certificate-page-2');
        const nextBtn = document.getElementById('next-to-page-2');
        const backBtn = document.getElementById('back-to-page-1');

        const categorySelect = document.getElementById('category-select');
        const unitSelect = document.getElementById('unit-select');
        const addSelectedUnitBtn = document.getElementById('add-selected-unit');
        const unitsListContainer = document.getElementById('cert-competency-units-list');
        const closeCertModalBtn = document.getElementById('close-certificate-modal');
        const submitBtn = document.getElementById('submit-certificate');

        let unitIndex = 0;
        let selectedUnits = new Set();

        // ** AWAL PERBAIKAN **
        // Fungsi untuk mengambil unit kompetensi berdasarkan kategori
        async function fetchUnitsByCategory(category) {
    if (!category) {
        unitSelect.innerHTML = '<option value="">-- Pilih kategori terlebih dahulu --</option>';
        unitSelect.disabled = true;
        addSelectedUnitBtn.disabled = true;
        submitBtn.disabled = true;
        return;
    }

    unitSelect.innerHTML = '<option value="">Memuat...</option>';
    unitSelect.disabled = true;
    addSelectedUnitBtn.disabled = true;

    try {
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        console.log('Fetching units for category:', category); 

        const response = await fetch('/admin/competency-units/by-category', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ category: category })
        });

        console.log('Response status:', response.status); 

        const result = await response.json();
        console.log('Response data:', result); 

        if (response.ok && result.success) {
            unitSelect.innerHTML = '<option value="">-- Pilih Unit Kompetensi --</option>';
            
            if (result.units && result.units.length > 0) {
                result.units.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.dataset.code = unit.unit_code;
                    option.dataset.title = unit.title;
                    option.textContent = `${unit.unit_code} - ${unit.title}`;
                    unitSelect.appendChild(option);
                });
                unitSelect.disabled = false;
                console.log('Loaded', result.units.length, 'units'); 
            } else {
                unitSelect.innerHTML = '<option value="">-- Tidak ada unit untuk kategori ini --</option>';
                console.log('No units found for category:', category); 
            }
        } else {
            throw new Error(result.message || 'Failed to load units');
        }
    } catch (error) {
        console.error('Error fetching competency units:', error);
        unitSelect.innerHTML = '<option value="">Error memuat data</option>';
        
        alert('Gagal memuat unit kompetensi: ' + error.message);
    }
}
        if (categorySelect) {
            categorySelect.addEventListener('change', () => {
                const selectedCategory = categorySelect.value;
                fetchUnitsByCategory(selectedCategory);
            });
        }

        if (unitSelect) {
            unitSelect.addEventListener('change', () => {
                if (unitSelect.value) {
                    addSelectedUnitBtn.disabled = false;
                } else {
                    addSelectedUnitBtn.disabled = true;
                }
            });
        }
        
        function updateSubmitButtonState() {
             if (selectedUnits.size > 0) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
    

        if (addCertificateBtn) {
            addCertificateBtn.addEventListener('click', () => {
                if (certificateForm) certificateForm.reset();
                selectedUnits.clear();
                unitsListContainer.innerHTML = '<p class="text-gray-500 text-center text-sm" id="empty-units-message">Belum ada unit yang dipilih.</p>';
                unitIndex = 0;
                
                unitSelect.innerHTML = '<option value="">-- Pilih kategori terlebih dahulu --</option>';
                unitSelect.disabled = true;
                addSelectedUnitBtn.disabled = true;
                submitBtn.disabled = true;


                page1.classList.remove('hidden');
                page2.classList.add('hidden');
                certificateModal.classList.remove('hidden');

                const today = new Date().toISOString().split('T')[0];
                document.getElementById('certificate-issue-date').value = today;
            });
        }

        if (closeCertModalBtn) {
            closeCertModalBtn.addEventListener('click', () => {
                certificateModal.classList.add('hidden');
            });
        }

        if (addSelectedUnitBtn) {
            addSelectedUnitBtn.addEventListener('click', () => {
                const selectedOption = unitSelect.options[unitSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    alert('Pilih unit kompetensi terlebih dahulu.');
                    return;
                }

                const unitId = selectedOption.value;
                const unitCode = selectedOption.dataset.code;
                const unitTitle = selectedOption.dataset.title;

                if (selectedUnits.has(unitId)) {
                    alert('Unit kompetensi ini sudah dipilih.');
                    return;
                }
                selectedUnits.add(unitId);

                const emptyMessage = document.getElementById('empty-units-message');
                if (emptyMessage) {
                    emptyMessage.remove();
                }

                const newUnitRow = document.createElement('div');
                newUnitRow.className = 'flex items-center gap-3 p-3 bg-white border rounded-md shadow-sm';
                newUnitRow.dataset.unitId = unitId;
                newUnitRow.innerHTML = `
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${unitCode}
                        </span>
                    </div>
                    <div class="flex-grow text-sm text-gray-700">${unitTitle}</div>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-unit-btn p-1 rounded hover:bg-red-50">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                    </button>
                    <input type="hidden" name="competency_unit_ids[]" value="${unitId}">
                `;

                unitsListContainer.appendChild(newUnitRow);
                lucide.createIcons();
                unitIndex++;
                updateSubmitButtonState();

                unitSelect.selectedIndex = 0;
                addSelectedUnitBtn.disabled = true;
            });
        }

        unitsListContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-unit-btn');
            if (removeBtn) {
                const unitRow = removeBtn.closest('[data-unit-id]');
                const unitId = unitRow.dataset.unitId;
                selectedUnits.delete(unitId);
                unitRow.remove();

                if (unitsListContainer.children.length === 0) {
                    unitsListContainer.innerHTML = '<p class="text-gray-500 text-center text-sm" id="empty-units-message">Belum ada unit yang dipilih.</p>';
                    unitIndex = 0;
                }
                updateSubmitButtonState();
            }
        });

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const programSelect = document.getElementById('certificate-program');
                const issueDate = document.getElementById('certificate-issue-date');
                const status = document.getElementById('certificate-status');

                if (!programSelect.value || !issueDate.value || !status.value) {
                    alert('Harap lengkapi semua field pada Langkah 1.');
                    return;
                }
                page1.classList.add('hidden');
                page2.classList.remove('hidden');
            });
        }

        if (backBtn) {
            backBtn.addEventListener('click', () => {
                page2.classList.add('hidden');
                page1.classList.remove('hidden');
            });
        }

        if (certificateForm) {
            certificateForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (selectedUnits.size === 0) {
                    alert('Harap pilih minimal satu unit kompetensi.');
                    return;
                }

                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin inline mr-2"></i>Memproses...';
                lucide.createIcons();

                const formData = new FormData(certificateForm);

                try {
                    const response = await fetch('/admin/certificates/bulk', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (response.ok && result.success) {
                        alert(result.message || 'Sertifikat berhasil diterbitkan!');
                        certificateModal.classList.add('hidden');
                        window.location.reload();
                    } else {
                        let errorMessage = result.message || 'Gagal menerbitkan sertifikat';
                        if (result.errors) {
                            errorMessage += ':\n' + Object.values(result.errors).flat().join('\n');
                        }
                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error('Error submitting certificate form:', error);
                    alert('Terjadi kesalahan saat memproses sertifikat: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        }

        certificateModal.addEventListener('click', (e) => {
            if (e.target === certificateModal) {
                certificateModal.classList.add('hidden');
            }
        });
    }

    
    // ============================
    // MODAL FUNCTIONALITY
    // ============================
    const modals = ['user-modal', 'program-modal', 'schedule-modal', 'enrollment-modal', 'gallery-modal'];

    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeButtons = modal.querySelectorAll('.close-modal, #close-' + modalId + ', #close-' + modalId + '-footer');

        const closeModal = () => {
            modal.classList.add('hidden');
            const formType = modalId.replace('-modal', '');
            resetForm(formType);
        };

        closeButtons.forEach(btn => {
            btn.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    });

    async function handleResponse(response) {
        const contentType = response.headers.get('Content-Type');
        if (!response.ok) {
            const errorText = await response.text();
            try {
                const errorJson = JSON.parse(errorText);
                return errorJson;
            } catch (e) {
                throw new Error(errorText || `HTTP error! status: ${response.status}`);
            }
        }
        if (contentType && contentType.includes('application/json')) {
            return await response.json();
        }
        return {
            success: true,
            message: 'Jadwal Berhasil Dihapus',
        };
    }

    // ============================
    // USER CRUD OPERATIONS
    // ============================
    const addUserBtn = document.getElementById('add-user-btn');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', () => {
            document.getElementById('user-modal-title').textContent = 'Tambah Pengguna';
            resetForm('user');
            document.getElementById('user-modal').classList.remove('hidden');
        });
    }

    const userForm = document.getElementById('user-form');
    if (userForm) {
        userForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const userId = document.getElementById('user-id').value;
            const isEdit = userId !== '';

            const nikInput = document.getElementById('user-nik');
            const cleanNik = nikInput.value.replace(/[^0-9]/g, '');

            if (cleanNik && cleanNik.length !== 16) {
                alert('NIK harus 16 digit angka atau kosongkan jika tidak ada.');
                nikInput.focus();
                return;
            }

            const data = {
                name: document.getElementById('user-name').value.trim(),
                email: document.getElementById('user-email').value.trim(),
                nik: cleanNik || null,
                role: document.getElementById('user-role').value
            };

            const password = document.getElementById('user-password').value;
            if (password) {
                data.password = password;
            }

            if (isEdit) {
                data._method = 'PUT';
            }

            try {
                const url = isEdit ? `/admin/users/${userId}` : '/admin/users';
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await handleResponse(response);

                if (response.ok && result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    let errorMessage = 'Terjadi kesalahan:\n';
                    if (result.errors) {
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += `- ${error}\n`;
                            });
                        });
                    } else {
                        errorMessage = result.message || 'Gagal menyimpan data pengguna.';
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const userId = btn.dataset.id;
            try {
                const response = await fetch(`/admin/users/${userId}`);
                const result = await handleResponse(response);

                if (result.success) {
                    resetForm('user');
                    document.getElementById('user-modal-title').textContent = 'Edit Pengguna';
                    document.getElementById('user-id').value = result.data.id;
                    document.getElementById('user-name').value = result.data.name;
                    document.getElementById('user-email').value = result.data.email;
                    document.getElementById('user-nik').value = result.data.nik || '';
                    document.getElementById('user-role').value = result.data.role;
                    document.getElementById('user-password').value = '';
                    document.getElementById('user-modal').classList.remove('hidden');
                } else {
                    alert(result.message || 'Gagal mengambil data pengguna.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    });

    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                const userId = btn.dataset.id;
                try {
                    const response = await fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await handleResponse(response);
                    if (result.success) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert(result.message || 'Gagal menghapus pengguna.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                }
            }
        });
    });


    // ============================
    // PROGRAM CRUD OPERATIONS
    // ============================
    const addProgramBtn = document.getElementById('add-program-btn');
    if (addProgramBtn) {
        addProgramBtn.addEventListener('click', () => {
            document.getElementById('program-modal-title').textContent = 'Tambah Program';
            resetForm('program');
            document.getElementById('program-modal').classList.remove('hidden');
        });
    }

    const programForm = document.getElementById('program-form');
    if (programForm) {
        programForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const programId = document.getElementById('program-id').value;
            const isEdit = programId !== '';

            const data = {
                name: document.getElementById('program-name').value,
                description: document.getElementById('program-description').value,
                total_meetings: document.getElementById('program-total-meetings').value,
                duration_months: document.getElementById('program-duration').value,
                level: document.getElementById('program-level').value,
                max_participants: document.getElementById('program-max-participants').value,
                price: document.getElementById('program-price').value || 0
            };

            if (isEdit) {
                data._method = 'PUT';
            }

            try {
                const url = isEdit ? `/admin/programs/${programId}` : '/admin/programs';
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await handleResponse(response);

                if (response.ok && result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    let errorMessage = 'Terjadi kesalahan:\n';
                    if (result.errors) {
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += `- ${error}\n`;
                            });
                        });
                    } else {
                        errorMessage = result.message || 'Gagal menyimpan program.';
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    document.querySelectorAll('.edit-program-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const programId = btn.dataset.id;
            try {
                const response = await fetch(`/admin/programs/${programId}`);
                const result = await handleResponse(response);
                if (result.success) {
                    resetForm('program');
                    document.getElementById('program-modal-title').textContent = 'Edit Program';
                    document.getElementById('program-id').value = result.data.id;
                    document.getElementById('program-name').value = result.data.name || '';
                    document.getElementById('program-description').value = result.data.description || '';
                    document.getElementById('program-duration').value = result.data.duration_months || '';
                    document.getElementById('program-level').value = result.data.level || '';
                    document.getElementById('program-max-participants').value = result.data.max_participants || '';
                    document.getElementById('program-price').value = result.data.price || '';
                    document.getElementById('program-modal').classList.remove('hidden');
                } else {
                    alert(result.message || 'Gagal mengambil data program.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    });

    document.querySelectorAll('.delete-program-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Apakah Anda yakin ingin menghapus program ini?')) {
                const programId = btn.dataset.id;
                try {
                    const response = await fetch(`/admin/programs/${programId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await handleResponse(response);
                    if (result.success) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert(result.message || 'Gagal menghapus program.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                }
            }
        });
    });


    // ============================
    // SCHEDULE CRUD OPERATIONS
    // ============================
    const addScheduleBtn = document.getElementById('add-schedule-btn');
    if (addScheduleBtn) {
        addScheduleBtn.addEventListener('click', () => {
            document.getElementById('schedule-modal-title').textContent = 'Tambah Jadwal';
            resetForm('schedule');
            document.getElementById('schedule-modal').classList.remove('hidden');
        });
    }

    const scheduleForm = document.getElementById('schedule-form');
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const scheduleId = document.getElementById('schedule-id').value;
            const isEdit = scheduleId !== '';

            const data = {
                title: document.getElementById('schedule-title').value,
                program_id: document.getElementById('schedule-program').value,
                instructor_id: document.getElementById('schedule-instructor').value || null,
                start_date: document.getElementById('schedule-start-date').value,
                end_date: document.getElementById('schedule-end-date').value,
                location: document.getElementById('schedule-location').value || null,
                max_participants: document.getElementById('schedule-max-participants').value || null
            };

            if (isEdit) {
                data._method = 'PUT';
            }

            try {
                const url = isEdit ? `/admin/schedules/${scheduleId}` : '/admin/schedules';
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await handleResponse(response);

                if (response.ok && result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    let errorMessage = 'Terjadi kesalahan:\n';
                    if (result.errors) {
                        Object.values(result.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += `- ${error}\n`;
                            });
                        });
                    } else {
                        errorMessage = result.message || 'Gagal menyimpan jadwal.';
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    document.querySelectorAll('.edit-schedule-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const scheduleId = btn.dataset.id;
            try {
                const response = await fetch(`/admin/schedules/${scheduleId}`);
                const result = await handleResponse(response);
                if (result.success) {
                    resetForm('schedule');
                    document.getElementById('schedule-modal-title').textContent = 'Edit Jadwal';
                    document.getElementById('schedule-id').value = result.data.id;
                    document.getElementById('schedule-title').value = result.data.title || '';
                    document.getElementById('schedule-program').value = result.data.program_id || '';
                    document.getElementById('schedule-instructor').value = result.data.instructor_id || '';
                    document.getElementById('schedule-start-date').value = result.data.start_date || '';
                    document.getElementById('schedule-end-date').value = result.data.end_date || '';
                    document.getElementById('schedule-location').value = result.data.location || '';
                    document.getElementById('schedule-max-participants').value = result.data.max_participants || '';
                    document.getElementById('schedule-modal').classList.remove('hidden');
                } else {
                    alert(result.message || 'Gagal mengambil data jadwal.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    });

    document.querySelectorAll('.delete-schedule-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                const scheduleId = btn.dataset.id;
                try {
                    const response = await fetch(`/admin/schedules/${scheduleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await handleResponse(response);
                    if (result.success) {
                        alert(result.message || 'Jadwal berhasil dihapus');
                        window.location.reload();
                    } else {
                        alert(result.message || 'Gagal menghapus jadwal.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                }
            }
        });
    });


    // ============================
    // ENROLLMENT OPERATIONS
    // ============================
    document.querySelectorAll('.edit-enrollment-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const enrollmentId = btn.dataset.id;
            const row = btn.closest('tr');
            const currentStatus = row.dataset.status || 'pending';

            resetForm('enrollment');
            document.getElementById('enrollment-modal-title').textContent = 'Edit Status Pendaftaran';
            document.getElementById('enrollment-id').value = enrollmentId;
            document.getElementById('enrollment-status').value = currentStatus;
            document.getElementById('enrollment-modal').classList.remove('hidden');
        });
    });

    const enrollmentForm = document.getElementById('enrollment-form');
    if (enrollmentForm) {
        enrollmentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const enrollmentId = document.getElementById('enrollment-id').value;
            const data = {
                status: document.getElementById('enrollment-status').value,
                notes: document.getElementById('enrollment-notes').value,
                _method: 'PUT'
            };

            try {
                const response = await fetch(`/admin/enrollments/${enrollmentId}`, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await handleResponse(response);
                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal mengupdate status pendaftaran.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        });
    }

    document.querySelectorAll('.delete-enrollment-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')) {
                const enrollmentId = btn.dataset.id;
                try {
                    const response = await fetch(`/admin/enrollments/${enrollmentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await handleResponse(response);
                    if (result.success) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert(result.message || 'Gagal menghapus pendaftaran.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                }
            }
        });
    });

    document.querySelectorAll('.force-approve-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini tanpa pembayaran?')) {
                const enrollmentId = btn.dataset.id;
                try {
                    const data = {
                        status: 'diterima',
                        notes: 'Disetujui manual oleh admin',
                        _method: 'PUT'
                    };
                    const response = await fetch(`/admin/enrollments/${enrollmentId}`, {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await handleResponse(response);
                    if (result.success) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert(result.message || 'Gagal menyetujui pendaftaran.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                }
            }
        });
    });


    const enrollmentFilter = document.getElementById('enrollment-filter');
    if (enrollmentFilter) {
        enrollmentFilter.addEventListener('change', function() {
            const filterValue = this.value;
            document.querySelectorAll('.enrollment-row').forEach(row => {
                row.style.display = (filterValue === '' || row.dataset.status === filterValue) ? '' : 'none';
            });
        });
    }

    
    const galleryCategoryFilter = document.getElementById('gallery-category-filter');
    if (galleryCategoryFilter) {
        galleryCategoryFilter.addEventListener('change', function() {
            const filterValue = this.value;
            document.querySelectorAll('.gallery-row').forEach(row => {
                row.style.display = (filterValue === '' || row.dataset.category === filterValue) ? '' : 'none';
            });
        });
    }

    const refreshBtn = document.getElementById('refresh-enrollments');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => window.location.reload());
    }


    function resetForm(type) {
        const form = document.getElementById(`${type}-form`);
        if (form) {
            form.reset();
            const idField = document.getElementById(`${type}-id`);
            if (idField) {
                idField.value = '';
            }
            if (type === 'user') {
                const nikError = document.querySelector('#nik-error');
                if (nikError) nikError.remove();
            }
        }
    }
});