document.addEventListener('DOMContentLoaded', () => {
    const desktopSidebar = document.getElementById('dashboard-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const storageKey = 'collcap-dashboard-sidebar';

    const labels = desktopSidebar
        ? desktopSidebar.querySelectorAll('[data-sidebar-label], [data-sidebar-logout-label]')
        : [];
    const logo = desktopSidebar ? desktopSidebar.querySelector('[data-sidebar-logo]') : null;
    const setDesktopState = (expanded) => {
        if (!desktopSidebar) {
            return;
        }
        desktopSidebar.classList.toggle('w-64', expanded);
        desktopSidebar.classList.toggle('w-20', !expanded);
        labels.forEach((label) => {
            label.classList.toggle('opacity-0', !expanded);
            label.classList.toggle('pointer-events-none', !expanded);
            label.classList.toggle('hidden', !expanded);
        });
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', expanded);
        }
        if (logo) {
            logo.classList.toggle('h-28', expanded);
            logo.classList.toggle('w-28', expanded);
            logo.classList.toggle('h-12', !expanded);
            logo.classList.toggle('w-12', !expanded);
        }
        localStorage.setItem(storageKey, expanded);
    };

    const stored = localStorage.getItem(storageKey);
    const shouldExpand = stored === null ? true : stored === 'true';
    setDesktopState(shouldExpand);

    sidebarToggle?.addEventListener('click', () => {
        const currentlyExpanded = desktopSidebar?.classList.contains('w-64');
        setDesktopState(!currentlyExpanded);
    });

    const mobilePanel = document.getElementById('sidebar-mobile-panel');
    const mobileBackdrop = document.getElementById('sidebar-mobile-backdrop');
    const mobileOpen = document.getElementById('sidebar-mobile-open');
    const mobileClose = document.getElementById('sidebar-mobile-close');

    const setMobileOpen = (open) => {
        if (!mobilePanel || !mobileBackdrop) {
            return;
        }
        mobilePanel.classList.toggle('-translate-x-full', !open);
        mobileBackdrop.classList.toggle('opacity-0', !open);
        mobileBackdrop.classList.toggle('pointer-events-none', !open);
    };

    mobileOpen?.addEventListener('click', () => setMobileOpen(true));
    mobileClose?.addEventListener('click', () => setMobileOpen(false));
    mobileBackdrop?.addEventListener('click', () => setMobileOpen(false));

    const userMenuButton = document.getElementById('user-menu-button');
    const userMenuPanel = document.getElementById('user-menu-panel');

    const closeUserMenu = () => {
        if (!userMenuPanel || !userMenuButton) {
            return;
        }
        userMenuPanel.classList.add('opacity-0', 'pointer-events-none');
        userMenuButton.setAttribute('aria-expanded', 'false');
    };

    const openUserMenu = () => {
        if (!userMenuPanel || !userMenuButton) {
            return;
        }
        userMenuPanel.classList.remove('opacity-0', 'pointer-events-none');
        userMenuButton.setAttribute('aria-expanded', 'true');
    };

    userMenuButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        if (!userMenuPanel) {
            return;
        }
        if (userMenuPanel.classList.contains('opacity-0')) {
            openUserMenu();
        } else {
            closeUserMenu();
        }
    });

    document.addEventListener('click', (event) => {
        if (!userMenuPanel || !userMenuButton) {
            return;
        }
        if (!userMenuPanel.contains(event.target) && !userMenuButton.contains(event.target)) {
            closeUserMenu();
        }
    });
});
