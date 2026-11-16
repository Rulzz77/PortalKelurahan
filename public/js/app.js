document.addEventListener('DOMContentLoaded', function () {
    // Responsive administrator sidebar.
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('sidebarToggle');
    let backdrop = document.querySelector('.sidebar-backdrop');
    if (sidebar && !backdrop) {
        backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        document.body.appendChild(backdrop);
    }
    const closeSidebar = () => {
        sidebar?.classList.remove('is-open');
        backdrop?.classList.remove('is-open');
    };
    toggle?.addEventListener('click', function () {
        sidebar?.classList.toggle('is-open');
        backdrop?.classList.toggle('is-open');
    });
    backdrop?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', event => { if (event.key === 'Escape') closeSidebar(); });

    // Password visibility controls.
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.togglePassword);
            if (!input) return;
            const reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            const icon = button.querySelector('i');
            if (icon) icon.className = reveal ? 'bi bi-eye-slash' : 'bi bi-eye';
            button.setAttribute('aria-label', reveal ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    // Confirm destructive actions.
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.dataset.confirm || 'Lanjutkan tindakan ini?')) event.preventDefault();
        });
    });

    // Preview selected image files before upload.
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const target = document.getElementById(input.dataset.preview);
            const file = input.files && input.files[0];
            if (!target || !file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = () => { target.src = reader.result; target.classList.remove('d-none'); };
            reader.readAsDataURL(file);
        });
    });

    // Lightweight gallery lightbox.
    const galleryLinks = document.querySelectorAll('a[data-lightbox]');
    if (galleryLinks.length) {
        const overlay = document.createElement('div');
        overlay.className = 'lightbox-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', 'Pratinjau foto');
        overlay.innerHTML = '<button type="button" class="lightbox-close" aria-label="Tutup">&times;</button><img alt="Pratinjau foto">';
        document.body.appendChild(overlay);
        const image = overlay.querySelector('img');
        const close = () => { overlay.classList.remove('is-open'); image.removeAttribute('src'); };
        overlay.querySelector('.lightbox-close').addEventListener('click', close);
        overlay.addEventListener('click', event => { if (event.target === overlay) close(); });
        galleryLinks.forEach(link => link.addEventListener('click', event => {
            event.preventDefault();
            image.src = link.href;
            image.alt = link.querySelector('img')?.alt || 'Foto galeri';
            overlay.classList.add('is-open');
        }));
        document.addEventListener('keydown', event => { if (event.key === 'Escape') close(); });
    }
});
