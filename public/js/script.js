document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.nav-toggle');
    const links = document.querySelector('.nav-links');
    const avatarInput = document.querySelector('[data-avatar-input]');
    const avatarPreview = document.querySelector('[data-avatar-preview]');

    if (toggle && links) {
        toggle.addEventListener('click', function () {
            links.classList.toggle('open');
        });
    }

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function () {
            const file = avatarInput.files && avatarInput.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                avatarPreview.src = String(event.target?.result || '');
                avatarPreview.hidden = false;
            };
            reader.readAsDataURL(file);
        });
    }

    document.querySelectorAll('.flash').forEach(function (flash) {
        flash.addEventListener('click', function () {
            flash.remove();
        });
    });
});
