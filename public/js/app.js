(() => {
    'use strict';

    const shell = document.querySelector('.app-shell');
    const sidebar = document.getElementById('app-sidebar');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const profile = document.querySelector('[data-profile-menu]');
    const main = document.getElementById('main-content');
    const topbar = document.querySelector('.app-topbar');
    const mobile = window.matchMedia('(max-width: 900px)');
    if (!shell || !sidebar || !toggle) return;

    let collapsed = false;
    try { collapsed = localStorage.getItem('career-compass.sidebar-collapsed') === 'true'; } catch { /* Storage can be unavailable in private browsing. */ }

    const mobileOpen = () => mobile.matches && shell.classList.contains('sidebar-open');
    const sync = () => {
        const open = mobile.matches ? mobileOpen() : !collapsed;
        shell.classList.toggle('sidebar-collapsed', !mobile.matches && collapsed);
        sidebar.inert = !open;
        main.inert = mobileOpen();
        [...topbar.children].forEach((element) => { element.inert = mobileOpen() && element !== toggle; });
        backdrop.hidden = !mobileOpen();
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Tutup sidebar' : 'Buka sidebar');
        if (mobileOpen()) {
            sidebar.setAttribute('role', 'dialog');
            sidebar.setAttribute('aria-modal', 'true');
        } else {
            sidebar.removeAttribute('role');
            sidebar.removeAttribute('aria-modal');
        }
    };

    const closeDrawer = (restoreFocus = true) => {
        const wasOpen = mobileOpen();
        shell.classList.remove('sidebar-open');
        sync();
        if (wasOpen && restoreFocus) toggle.focus();
    };

    shell.classList.add('app-shell--enhanced');
    sync();

    toggle.addEventListener('click', () => {
        if (mobile.matches) {
            if (mobileOpen()) return closeDrawer();
            profile.removeAttribute('open');
            shell.classList.add('sidebar-open');
            sync();
            sidebar.querySelector('[data-sidebar-close]').focus();
        } else {
            collapsed = !collapsed;
            try { localStorage.setItem('career-compass.sidebar-collapsed', String(collapsed)); } catch { /* Keep the toggle usable without storage. */ }
            sync();
        }
    });

    document.querySelector('[data-sidebar-close]').addEventListener('click', () => closeDrawer());
    backdrop.addEventListener('click', () => closeDrawer());
    mobile.addEventListener('change', () => {
        const focusWasInside = sidebar.contains(document.activeElement);
        closeDrawer(false);
        if (focusWasInside && sidebar.inert) toggle.focus();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (mobileOpen()) closeDrawer();
            if (profile.open) {
                profile.removeAttribute('open');
                profile.querySelector('summary').focus();
            }
        }
        if (event.key === 'Tab' && mobileOpen()) {
            const focusable = [...sidebar.querySelectorAll('button:not([disabled]), a[href], [tabindex="0"]')];
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && (document.activeElement === first || !sidebar.contains(document.activeElement))) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && (document.activeElement === last || !sidebar.contains(document.activeElement))) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    document.addEventListener('click', (event) => {
        if (profile.open && !profile.contains(event.target)) profile.removeAttribute('open');
    });
})();
