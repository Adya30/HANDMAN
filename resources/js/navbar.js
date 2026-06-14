document.addEventListener('DOMContentLoaded', () => {
    function getSidebar() {
        return document.getElementById('sidebar');
    }
    function getSidebarBackdrop() {
        return document.getElementById('sidebar-backdrop');
    }
    function getUserMenu() {
        return document.getElementById('user-menu');
    }
    function getNotifMenu() {
        return document.getElementById('notif-menu');
    }

    function openSidebar() {
        const sidebar = getSidebar();
        const backdrop = getSidebarBackdrop();
        if (!sidebar || !backdrop) return;

        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        backdrop.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        }, 20);
    }

    function closeSidebar() {
        const sidebar = getSidebar();
        const backdrop = getSidebarBackdrop();
        if (!sidebar || !backdrop) return;

        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');

        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
    }

    function toggleUserMenu(event) {
        const userMenu = getUserMenu();
        if (!userMenu) return;

        event.stopPropagation();
        closeNotifMenu();

        if (userMenu.classList.contains('hidden')) {
            userMenu.classList.remove('hidden');
            setTimeout(() => {
                userMenu.classList.remove('opacity-0', 'scale-95');
                userMenu.classList.add('opacity-100', 'scale-100');
            }, 20);
        } else {
            closeUserMenu();
        }
    }

    function closeUserMenu() {
        const userMenu = getUserMenu();
        if (userMenu && !userMenu.classList.contains('hidden')) {
            userMenu.classList.remove('opacity-100', 'scale-100');
            userMenu.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                userMenu.classList.add('hidden');
            }, 100);
        }
    }

    function toggleNotifMenu(event) {
        const notifMenu = getNotifMenu();
        if (!notifMenu) return;

        event.stopPropagation();
        closeUserMenu();

        if (notifMenu.classList.contains('hidden')) {
            notifMenu.classList.remove('hidden');
            setTimeout(() => {
                notifMenu.classList.remove('opacity-0', 'scale-95');
                notifMenu.classList.add('opacity-100', 'scale-100');
            }, 20);
        } else {
            closeNotifMenu();
        }
    }

    function closeNotifMenu() {
        const notifMenu = getNotifMenu();
        if (notifMenu && !notifMenu.classList.contains('hidden')) {
            notifMenu.classList.remove('opacity-100', 'scale-100');
            notifMenu.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                notifMenu.classList.add('hidden');
            }, 100);
        }
    }

    document.addEventListener('click', (event) => {
        const openSidebarBtn = event.target.closest('#open-sidebar');
        const closeSidebarBtn = event.target.closest('#close-sidebar');
        const sidebarBackdrop = event.target.closest('#sidebar-backdrop');
        const userMenuBtn = event.target.closest('#user-menu-btn');
        const notifMenuBtn = event.target.closest('#notif-menu-btn');

        if (openSidebarBtn) {
            openSidebar();
        } else if (closeSidebarBtn) {
            closeSidebar();
        } else if (sidebarBackdrop) {
            closeSidebar();
        } else if (userMenuBtn) {
            toggleUserMenu(event);
        } else if (notifMenuBtn) {
            toggleNotifMenu(event);
        } else {
            const userMenu = getUserMenu();
            const notifMenu = getNotifMenu();
            if (userMenu && !userMenu.contains(event.target)) {
                closeUserMenu();
            }
            if (notifMenu && !notifMenu.contains(event.target)) {
                closeNotifMenu();
            }
        }
    });
});
