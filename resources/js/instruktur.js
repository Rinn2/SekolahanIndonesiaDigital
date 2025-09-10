document.addEventListener('DOMContentLoaded', function() {
    const refreshButton = document.getElementById('refresh-schedules');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            location.reload();
        });
    }

    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.dataset.tab;

            tabButtons.forEach(btn => btn.classList.remove('active', 'border-blue-500', 'text-blue-600'));
            this.classList.add('active', 'border-blue-500', 'text-blue-600');

            tabContents.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            const activeContent = document.getElementById(tabName + '-tab');
            if (activeContent) {
                activeContent.style.display = 'block';
                activeContent.classList.add('active');
            }
        });
    });

    const viewScheduleButtons = document.querySelectorAll('.view-schedule-btn');
    viewScheduleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-id');
            showScheduleDetail(scheduleId);
        });
    });

    const editScheduleButtons = document.querySelectorAll('.edit-schedule-btn');
    editScheduleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-id');
            editSchedule(scheduleId);
        });
    });

    const exportStudentsButtons = document.querySelectorAll('.export-students-btn');
    exportStudentsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.getAttribute('data-id');
            exportStudents(scheduleId);
        });
    });

    const filterSchedule = document.getElementById('filter-schedule');
    if (filterSchedule) {
        filterSchedule.addEventListener('change', function() {
            const selectedScheduleId = this.value;
            const enrollmentRows = document.querySelectorAll('.enrollment-row');
            
            enrollmentRows.forEach(row => {
                if (selectedScheduleId === '' || row.getAttribute('data-schedule-id') === selectedScheduleId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    const editEnrollmentButtons = document.querySelectorAll('.edit-enrollment-btn');
    editEnrollmentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const enrollmentId = this.getAttribute('data-id');
            editEnrollment(enrollmentId);
        });
    });

    const viewProgressButtons = document.querySelectorAll('.view-progress-btn');
    viewProgressButtons.forEach(button => {
        button.addEventListener('click', function() {
            const enrollmentId = this.getAttribute('data-id');
            viewProgress(enrollmentId);
        });
    });

    const gradeForm = document.getElementById('grade-form');
    if (gradeForm) {
        gradeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i data-lucide="loader" class="h-4 w-4 inline mr-2 animate-spin"></i>Menyimpan...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showAlert(data.message, 'success');
                    gradeForm.reset();
                    
                    const enrollmentSelect = document.getElementById('enrollment-select');
                    if (enrollmentSelect.value) {
                        loadStudentGrades(enrollmentSelect.value);
                    }
                    
                    if (data.data.status_updated) {
                        updateEnrollmentStatusInTable(formData.get('enrollment_id'), data.data.enrollment_status);
                        
                        if (data.data.final_grade) {
                            showFinalGradeNotification(data.data.final_grade, data.data.enrollment_status);
                        }
                    }
                } else {
                    showAlert('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menyimpan nilai.', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    }

    const enrollmentSelect = document.getElementById('enrollment-select');
    if (enrollmentSelect) {
        enrollmentSelect.addEventListener('change', function() {
            const enrollmentId = this.value;
            if (enrollmentId) {
                loadStudentGrades(enrollmentId);
                loadStudentProgress(enrollmentId);
            } else {
                resetGradesDisplay();
            }
        });
    }

    addFinalGradeCalculationButton();
    
    addEvaluationReadyButton();
});

function loadStudentGrades(enrollmentId) {
    const gradesList = document.getElementById('grades-list');
    const studentNameForGrades = document.getElementById('student-name-for-grades');
    const enrollmentSelect = document.getElementById('enrollment-select');
    
    if (!enrollmentId) {
        resetGradesDisplay();
        return;
    }

    const studentName = enrollmentSelect.options[enrollmentSelect.selectedIndex].text;
    studentNameForGrades.textContent = 'untuk ' + studentName;
    gradesList.innerHTML = '<p class="text-sm text-gray-500">Memuat nilai...</p>';

    fetch(`/instruktur/enrollments/${enrollmentId}/grades`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayGradesTable(data.data, data.progress, data.enrollment);
        } else {
            gradesList.innerHTML = `<p class="text-sm text-red-500">Gagal memuat nilai: ${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        gradesList.innerHTML = '<p class="text-sm text-red-500">Terjadi kesalahan saat mengambil data nilai.</p>';
    });
}

function displayGradesTable(grades, progress, enrollment) {
    const gradesList = document.getElementById('grades-list');
    
    if (grades.length === 0) {
        gradesList.innerHTML = '<p class="text-sm text-gray-500">Belum ada nilai yang diinput untuk siswa ini.</p>';
        return;
    }

        let tableHtml = `
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Progress:</span>
                    <span class="text-gray-900">${progress.completed_meetings}/${progress.total_meetings} pertemuan (${progress.progress_percentage}%)</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Rata-rata Nilai:</span>
                    <span class="text-gray-900">${progress.average_grade}</span>
                </div>`;
    
    if (progress.final_grade !== null) {
        const statusClass = enrollment.status === 'lulus' ? 'text-green-600' : 'text-red-600';
        const statusText = enrollment.status === 'lulus' ? 'LULUS' : 'TIDAK LULUS';
        tableHtml += `
                <div>
                    <span class="font-medium text-gray-700">Nilai Akhir:</span>
                    <span class="font-bold ${statusClass}">${progress.final_grade}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status:</span>
                    <span class="font-bold ${statusClass}">${statusText}</span>
                </div>`;
    }
    
    tableHtml += `
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pertemuan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">`;
    
    grades.forEach(grade => {
        const gradeClass = grade.grade >= 75 ? 'text-green-600' : 'text-red-600';
        const gradeLetter = getGradeLetter(grade.grade);
        
        tableHtml += `
            <tr>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-center">${grade.meeting_number}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-center ${gradeClass} font-medium">${grade.grade}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-center font-medium">${gradeLetter}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-center">${grade.notes || '-'}</td>
            </tr>`;
    });

    tableHtml += '</tbody></table>';
    gradesList.innerHTML = tableHtml;
}

function getGradeLetter(grade) {
    if (grade >= 90) return 'A';
    if (grade >= 80) return 'B';
    if (grade >= 75) return 'C';
    if (grade >= 60) return 'D';
    return 'E';
}

function loadStudentProgress(enrollmentId) {
    fetch(`/instruktur/enrollments/${enrollmentId}/progress`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateProgressDisplay(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading progress:', error);
    });
}

function updateProgressDisplay(progress) {
    console.log('Progress updated:', progress);
}

function resetGradesDisplay() {
    const gradesList = document.getElementById('grades-list');
    const studentNameForGrades = document.getElementById('student-name-for-grades');
    
    gradesList.innerHTML = '<p class="text-sm text-gray-500">Pilih siswa untuk melihat nilai yang sudah ada.</p>';
    studentNameForGrades.textContent = '';
}

function updateEnrollmentStatusInTable(enrollmentId, newStatus) {
    const enrollmentRows = document.querySelectorAll('.enrollment-row');
    enrollmentRows.forEach(row => {
        const editBtn = row.querySelector(`[data-id="${enrollmentId}"]`);
        if (editBtn) {
            const statusCell = row.querySelector('td:nth-child(4) span');
            if (statusCell) {
                statusCell.textContent = getStatusLabel(newStatus);
                statusCell.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(newStatus)}`;
            }
        }
    });
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Menunggu',
        'diterima': 'Diterima', 
        'ditolak': 'Ditolak',
        'lulus': 'Lulus',
        'tidak_lulus': 'Tidak Lulus'
    };
    return labels[status] || status;
}

function showFinalGradeNotification(finalGrade, status) {
    const message = status === 'lulus' 
        ? ` Selamat! Siswa telah LULUS dengan nilai akhir ${finalGrade}!`
        : ` Siswa TIDAK LULUS dengan nilai akhir ${finalGrade}.`;
    
    const alertType = status === 'lulus' ? 'success' : 'error';
    showAlert(message, alertType);
    
    if (status === 'lulus') {
        showConfetti();
    }
}

function showConfetti() {
    const confetti = document.createElement('div');
    confetti.innerHTML = '';
    confetti.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        z-index: 9999;
        animation: confetti-fall 2s ease-out forwards;
    `;
    
    if (!document.getElementById('confetti-style')) {
        const style = document.createElement('style');
        style.id = 'confetti-style';
        style.textContent = `
            @keyframes confetti-fall {
                0% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                50% { transform: translate(-50%, -60%) scale(1.2); }
                100% { opacity: 0; transform: translate(-50%, -70%) scale(0.8); }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(confetti);
    setTimeout(() => confetti.remove(), 2000);
}

function addFinalGradeCalculationButton() {
    const gradesTabContent = document.getElementById('grades-tab');
    if (!gradesTabContent) return;
    
    const header = gradesTabContent.querySelector('.flex.justify-between.items-center');
    if (!header) return;
    
    const calculateBtn = document.createElement('button');
    calculateBtn.innerHTML = '<i data-lucide="calculator" class="h-4 w-4 inline mr-2"></i>Hitung Nilai Akhir';
    calculateBtn.className = 'bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition ml-2';
    calculateBtn.addEventListener('click', calculateAllFinalGrades);
    
    header.appendChild(calculateBtn);
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function addEvaluationReadyButton() {
    const gradesTabContent = document.getElementById('grades-tab');
    if (!gradesTabContent) return;
    
    const header = gradesTabContent.querySelector('.flex.justify-between.items-center');
    if (!header) return;
    
 
    header.appendChild(evaluationBtn);
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}



function showStudentsReadyForEvaluation() {
    fetch('/instruktur/students/ready-for-evaluation')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showEvaluationReadyModal(data.data);
        } else {
            showAlert('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengambil data siswa.', 'error');
    });
}

function showEvaluationReadyModal(students) {
    let content = '<div class="max-h-96 overflow-y-auto">';
    
    if (students.length === 0) {
        content += '<p class="text-sm text-gray-500 text-center py-4">Tidak ada siswa yang siap untuk evaluasi akhir.</p>';
    } else {
        content += `
            <p class="text-sm text-gray-600 mb-4">
                ${students.length} siswa siap untuk evaluasi akhir:
            </p>
            <div class="space-y-3">`;
        
        students.forEach(item => {
            const enrollment = item.enrollment;
            const progress = item.progress;
            const statusClass = progress.average_grade >= 75 ? 'text-green-600' : 'text-red-600';
            
            content += `
                <div class="border rounded-lg p-3 bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${enrollment.user.name}</h4>
                            <p class="text-sm text-gray-600">${enrollment.program.name}</p>
                            <p class="text-sm text-gray-600">${enrollment.schedule.title}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium ${statusClass}">
                                Rata-rata: ${progress.average_grade}
                            </p>
                            <p class="text-xs text-gray-500">
                                ${progress.completed_meetings}/${progress.total_meetings} pertemuan
                            </p>
                        </div>
                    </div>
                </div>`;
        });
        
        content += '</div>';
    }
    
    content += '</div>';
    
    const modal = createModal('Siswa Siap Evaluasi Akhir', content);
    document.body.appendChild(modal);
    showModal(modal);
}
    
function showScheduleDetail(scheduleId) {
    console.log('Viewing schedule detail for ID:', scheduleId);
    
    const scheduleRow = document.querySelector(`[data-id="${scheduleId}"].view-schedule-btn`).closest('tr');
    const scheduleData = extractScheduleDataFromRow(scheduleRow);
    
    const modal = createModal('Detail Jadwal', createScheduleDetailContent(scheduleData));
    document.body.appendChild(modal);
    showModal(modal);
}

function editSchedule(scheduleId) {
    console.log('Editing schedule with ID:', scheduleId);
    
    const scheduleRow = document.querySelector(`[data-id="${scheduleId}"].edit-schedule-btn`).closest('tr');
    const scheduleData = extractScheduleDataFromRow(scheduleRow);
    
    const modal = createModal('Edit Jadwal', createScheduleEditContent(scheduleData, scheduleId));
    document.body.appendChild(modal);
    showModal(modal);
}


function editEnrollment(enrollmentId) {
    console.log('Editing enrollment with ID:', enrollmentId);
    
    const enrollmentRow = document.querySelector(`[data-id="${enrollmentId}"].edit-enrollment-btn`).closest('tr');
    const enrollmentData = extractEnrollmentDataFromRow(enrollmentRow);
    
    const modal = createModal('Edit Status Pendaftaran', createEnrollmentEditContent(enrollmentData, enrollmentId));
    document.body.appendChild(modal);
    showModal(modal);
}

function viewProgress(enrollmentId) {
    console.log('Viewing progress for enrollment ID:', enrollmentId);
    
    const enrollmentRow = document.querySelector(`[data-id="${enrollmentId}"].view-progress-btn`).closest('tr');
    const enrollmentData = extractEnrollmentDataFromRow(enrollmentRow);
    
    const modal = createModal('Progress Siswa', createProgressContent(enrollmentData, enrollmentId));
    document.body.appendChild(modal);
    showModal(modal);
}

// Modal functions
function showScheduleModal(scheduleId) {
    // Create and show modal for schedule details
    fetch(`/instruktur/schedules/${scheduleId}/show`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = createModal('Detail Jadwal', createScheduleDetailContent(data.data));
            document.body.appendChild(modal);
            showModal(modal);
        } else {
            showAlert('Gagal memuat detail jadwal', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat memuat detail jadwal', 'error');
    });
}

function showEnrollmentEditModal(enrollmentId) {
    // Create and show modal for editing enrollment
    fetch(`/instruktur/enrollments/${enrollmentId}/edit`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = createModal('Edit Status Pendaftaran', createEnrollmentEditContent(data.data));
            document.body.appendChild(modal);
            showModal(modal);
        } else {
            showAlert('Gagal memuat data pendaftaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat memuat data pendaftaran', 'error');
    });
}

// Utility functions
function extractScheduleDataFromRow(row) {
    const cells = row.querySelectorAll('td');
    return {
        title: cells[0].querySelector('.text-sm').textContent.trim(),
        program: cells[1].textContent.trim(),
        period: cells[2].textContent.trim(),
        location: cells[3].textContent.trim(),
        participants: cells[4].textContent.trim(),
        status: cells[5].querySelector('span').textContent.trim()
    };
}

function extractEnrollmentDataFromRow(row) {
    const cells = row.querySelectorAll('td');
    return {
        studentName: cells[0].querySelector('.text-sm.font-medium').textContent.trim(),
        program: cells[1].textContent.trim(),
        schedule: cells[2].textContent.trim(),
        status: cells[3].querySelector('span').textContent.trim(),
        registerDate: cells[4].textContent.trim()
    };
}

function generateStudentCSV(scheduleId, scheduleTitle) {
    // Get students data from the students tab for the specific schedule
    const enrollmentRows = document.querySelectorAll(`.enrollment-row[data-schedule-id="${scheduleId}"]`);
    
    let csvContent = 'Nama Siswa,Email,Program,Status,Tanggal Daftar\n';
    
    enrollmentRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const studentName = cells[0].querySelector('.text-sm.font-medium').textContent.trim();
        const email = cells[0].querySelector('.text-sm.text-gray-500').textContent.trim();
        const program = cells[1].textContent.trim();
        const status = cells[3].querySelector('span').textContent.trim();
        const registerDate = cells[4].textContent.trim();
        
        csvContent += `"${studentName}","${email}","${program}","${status}","${registerDate}"\n`;
    });
    
    return csvContent;
}

function downloadCSV(content, filename) {
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
function createModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">${title}</h3>
                <button class="close-modal-btn text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <div class="modal-content">
                ${content}
            </div>
        </div>
    `;
    
    // Add event listener to close button
    const closeBtn = modal.querySelector('.close-modal-btn');
    closeBtn.addEventListener('click', function() {
        modal.remove();
    });
    
    // Also allow clicking outside modal to close
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    return modal;
}


function showModal(modal) {
    modal.style.display = 'flex';
    
    // Add form submit handlers for modals
    const editScheduleForm = modal.querySelector('#edit-schedule-form');
    if (editScheduleForm) {
        editScheduleForm.addEventListener('submit', handleScheduleEdit);
    }
    
    const editEnrollmentForm = modal.querySelector('#edit-enrollment-form');
    if (editEnrollmentForm) {
        editEnrollmentForm.addEventListener('submit', handleEnrollmentEdit);
    }
    
    // Re-initialize lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function handleScheduleEdit(e) {
    e.preventDefault();
    const form = e.target;
    const scheduleId = form.dataset.scheduleId;
    const formData = new FormData(form);
    
    // Simulate API call
    showAlert('Jadwal berhasil diperbarui', 'success');
    closeModal(form.querySelector('button[type="button"]'));
    
    // In real implementation, you would send this data to backend
    console.log('Updating schedule:', scheduleId, Object.fromEntries(formData));
}

function handleEnrollmentEdit(e) {
    e.preventDefault();
    const form = e.target;
    const enrollmentId = form.dataset.enrollmentId;
    const formData = new FormData(form);
    const newStatus = formData.get('status');
    
    // Update the status in the table
    const enrollmentRow = document.querySelector(`[data-id="${enrollmentId}"].edit-enrollment-btn`).closest('tr');
    const statusCell = enrollmentRow.querySelector('td:nth-child(4) span');
    
    // Update status display
    statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    
    // Update status styling
    statusCell.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(newStatus)}`;
    
    showAlert('Status pendaftaran berhasil diperbarui', 'success');
    closeModal(form.querySelector('button[type="button"]'));
    
    // In real implementation, you would send this data to backend
    console.log('Updating enrollment:', enrollmentId, Object.fromEntries(formData));
}

function getStatusClass(status) {
    switch (status) {
        case 'diterima': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'ditolak': return 'bg-red-100 text-red-800';
        case 'lulus': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function closeModal(button) {
    const modal = button.closest('.fixed');
    if (modal) {
        modal.remove();
    }
}

function createScheduleDetailContent(schedule) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <p class="text-sm text-gray-900">${schedule.title}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Program</label>
                <p class="text-sm text-gray-900">${schedule.program}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Periode</label>
                <p class="text-sm text-gray-900">${schedule.period}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                <p class="text-sm text-gray-900">${schedule.location}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Peserta</label>
                <p class="text-sm text-gray-900">${schedule.participants}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="text-sm text-gray-900">${schedule.status}</p>
            </div>
        </div>
    `;
}

function createScheduleEditContent(schedule, scheduleId) {
    return `
        <form id="edit-schedule-form" data-schedule-id="${scheduleId}">
            <div class="space-y-4">
                <div>
                    <label for="schedule-title" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" id="schedule-title" name="title" value="${schedule.title}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label for="schedule-location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" id="schedule-location" name="location" value="${schedule.location}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label for="schedule-description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="schedule-description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Masukkan deskripsi jadwal..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal(this)" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    `;
}

function createEnrollmentEditContent(enrollment, enrollmentId) {
    return `
        <form id="edit-enrollment-form" data-enrollment-id="${enrollmentId}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Siswa</label>
                    <p class="text-sm text-gray-900">${enrollment.studentName}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Program</label>
                    <p class="text-sm text-gray-900">${enrollment.program}</p>
                </div>
                <div>
                    <label for="enrollment-status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="enrollment-status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="pending" ${enrollment.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="diterima" ${enrollment.status === 'Diterima' ? 'selected' : ''}>Diterima</option>
                        <option value="ditolak" ${enrollment.status === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                        <option value="lulus" ${enrollment.status === 'Lulus' ? 'selected' : ''}>Lulus</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal(this)" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    `;
}

function createProgressContent(enrollment, enrollmentId) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Siswa</label>
                <p class="text-sm text-gray-900">${enrollment.studentName}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Program</label>
                <p class="text-sm text-gray-900">${enrollment.program}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Saat Ini</label>
                <p class="text-sm text-gray-900">${enrollment.status}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Daftar</label>
                <p class="text-sm text-gray-900">${enrollment.registerDate}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-md">
                <h4 class="font-medium text-gray-900 mb-2">Riwayat Nilai</h4>
                <p class="text-sm text-gray-600">Fitur ini akan menampilkan riwayat nilai siswa yang sudah diinput melalui tab "Input Nilai".</p>
            </div>
        </div>
    `;
}

function showAlert(message, type = 'info') {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
        'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    
    alert.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert && alert.parentNode) {
            alert.remove();
        }
    }, 5000);
    
    // Re-initialize lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}
document.addEventListener('DOMContentLoaded', function() {
 
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.dataset.tab;

            tabButtons.forEach(btn => btn.classList.remove('active', 'border-blue-500', 'text-blue-600'));
            this.classList.add('active', 'border-blue-500', 'text-blue-600');

            tabContents.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            const activeContent = document.getElementById(tabName + '-tab');
            if (activeContent) {
                activeContent.style.display = 'block';
                activeContent.classList.add('active');
            }
        });
    });

  

    const enrollmentSelect = document.getElementById('enrollment-select');
    enrollmentSelect.addEventListener('change', function() {
        const enrollmentId = this.value;
        const studentName = this.options[this.selectedIndex].text;
        const gradesList = document.getElementById('grades-list');
        const studentNameForGrades = document.getElementById('student-name-for-grades');

        if (!enrollmentId) {
            gradesList.innerHTML = '<p class="text-sm text-gray-500">Pilih siswa untuk melihat nilai yang sudah ada.</p>';
            studentNameForGrades.textContent = '';
            return;
        }

        studentNameForGrades.textContent = 'untuk ' + studentName;
        gradesList.innerHTML = '<p class="text-sm text-gray-500">Memuat nilai...</p>';

        fetch(`/instruktur/enrollments/${enrollmentId}/grades`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pertemuan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">`;
                
                data.data.forEach(grade => {
                    tableHtml += `
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">${grade.meeting_number}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">${grade.grade}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">${grade.notes || '-'}</td>
                        </tr>`;
                });

                tableHtml += '</tbody></table>';
                gradesList.innerHTML = tableHtml;
            } else if (data.success) {
                gradesList.innerHTML = '<p class="text-sm text-gray-500">Belum ada nilai yang diinput untuk siswa ini.</p>';
            } else {
                gradesList.innerHTML = `<p class="text-sm text-red-500">Gagal memuat nilai: ${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            gradesList.innerHTML = '<p class="text-sm text-red-500">Terjadi kesalahan saat mengambil data nilai.</p>';
        });
    });
});