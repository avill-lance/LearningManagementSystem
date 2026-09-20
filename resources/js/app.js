import './bootstrap';

// Flowbite-compatible Drawer and Collapse event handlers
document.addEventListener('DOMContentLoaded', () => {
    // Drawer toggles
    document.querySelectorAll('[data-drawer-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-drawer-target') || btn.getAttribute('data-drawer-toggle');
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('-translate-x-full');
            }
        });
    });

    // Collapse / Submenu toggles
    document.querySelectorAll('[data-collapse-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-collapse-toggle') || btn.getAttribute('aria-controls');
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('hidden');
            }
        });
    });
});
