import './bootstrap';

// Flowbite-compatible Drawer, Collapse, and Dropdown event handlers
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

    // Dropdown toggles
    document.querySelectorAll('[data-dropdown-toggle]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const targetId = btn.getAttribute('data-dropdown-toggle');
            const target = document.getElementById(targetId);
            if (target) {
                // Close all other dropdowns first
                document.querySelectorAll('[id$="-dropdown"]').forEach((dropdown) => {
                    if (dropdown.id !== targetId) {
                        dropdown.classList.add('hidden');
                    }
                });
                target.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('[id$="-dropdown"]').forEach((dropdown) => {
            dropdown.classList.add('hidden');
        });
    });
});
