document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const menuItems = document.querySelectorAll('.menu-text');

    menuItems.forEach(item => item.style.opacity = '0');

    menuToggle.addEventListener('click', function() {
        if (sidebar.classList.contains('expanded')) {
            sidebar.classList.remove('expanded');
            menuItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-10px)';
            });
        } else {
            sidebar.classList.add('expanded');
            menuItems.forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            });
        }
    });

    // Sidebar height adjustment for 1536px+
    function adjustSidebarHeight() {
        if (!sidebar) return;
        if (window.innerWidth >= 1536) {
            sidebar.style.height = '105vh';
        } else {
            sidebar.style.height = '';
        }
    }

    adjustSidebarHeight();
    window.addEventListener('resize', adjustSidebarHeight);
});
