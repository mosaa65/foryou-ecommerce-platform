/**
 * JavaScript لوحة تحكم متجر "من أجلك"
 */

document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initAlerts();
    initDropdowns();
    initImageUpload();
    initModals();
    initDeleteConfirm();
});

/**
 * التحكم في القائمة الجانبية (الموبايل)
 */
function initSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const close = document.getElementById('sidebarClose');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar) return;

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', openSidebar);
    if (close) close.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
}

/**
 * إغلاق رسائل التنبيه
 */
function initAlerts() {
    document.querySelectorAll('.alert-close').forEach(btn => {
        btn.addEventListener('click', function () {
            const alert = this.closest('.alert');
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    });

    // إخفاء تلقائي بعد 5 ثواني
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}

/**
 * القوائم المنسدلة
 */
function initDropdowns() {
    document.querySelectorAll('.admin-dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.admin-dropdown.active').forEach(d => {
            d.classList.remove('active');
        });
    });
}

/**
 * رفع الصور
 */
function initImageUpload() {
    document.querySelectorAll('.image-upload').forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        const preview = upload.parentElement.querySelector('.image-preview');

        upload.addEventListener('click', () => input.click());

        upload.addEventListener('dragover', (e) => {
            e.preventDefault();
            upload.classList.add('dragover');
        });

        upload.addEventListener('dragleave', () => {
            upload.classList.remove('dragover');
        });

        upload.addEventListener('drop', (e) => {
            e.preventDefault();
            upload.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                handleImagePreview(input, preview);
            }
        });

        input.addEventListener('change', () => {
            handleImagePreview(input, preview);
        });
    });
}

function handleImagePreview(input, previewContainer) {
    if (!previewContainer) return;

    const file = input.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('يرجى اختيار ملف صورة');
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        previewContainer.innerHTML = `
            <img src="${e.target.result}" alt="معاينة">
            <button type="button" class="remove-btn" onclick="removeImage(this)">
                <i class="fa-solid fa-times"></i>
            </button>
        `;
        previewContainer.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function removeImage(btn) {
    const preview = btn.closest('.image-preview');
    const upload = preview.parentElement.querySelector('.image-upload');
    const input = upload.querySelector('input[type="file"]');

    input.value = '';
    preview.innerHTML = '';
    preview.style.display = 'none';
}

/**
 * النوافذ المنبثقة
 */
function initModals() {
    // فتح المودال
    document.querySelectorAll('[data-modal]').forEach(trigger => {
        trigger.addEventListener('click', function () {
            const modalId = this.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) openModal(modal);
        });
    });

    // إغلاق المودال
    document.querySelectorAll('.modal-close, .modal-overlay').forEach(el => {
        el.addEventListener('click', function () {
            const modal = this.closest('.modal');
            if (modal) closeModal(modal);
        });
    });

    // إغلاق بـ Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(closeModal);
        }
    });
}

function openModal(modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

/**
 * تأكيد الحذف
 */
function initDeleteConfirm() {
    document.querySelectorAll('.btn-delete, [data-delete]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const message = this.dataset.confirm || 'هل أنت متأكد من الحذف؟';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * دالة مساعدة لإظهار رسالة
 */
function showMessage(type, message) {
    const alertHtml = `
        <div class="alert alert-${type}">
            <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
            <button class="alert-close">&times;</button>
        </div>
    `;

    const content = document.querySelector('.admin-content');
    if (content) {
        content.insertAdjacentHTML('afterbegin', alertHtml);
        initAlerts();
    }
}
