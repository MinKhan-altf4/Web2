document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = () => {
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main_content');

        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                mobileToggle.classList.toggle('active');
                if (mainContent) {
                    mainContent.classList.toggle('sidebar-active');
                }
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                    mobileToggle.classList.remove('active');
                    if (mainContent) {
                        mainContent.classList.remove('sidebar-active');
                    }
                }
            });
        }
    };

    // Table responsiveness
    const makeTablesResponsive = () => {
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('table_container');
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        });
    };

    // Form responsiveness
    const makeFormsResponsive = () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.classList.add('responsive-form');
        });
    };

    // Initialize all responsive features
    const initResponsive = () => {
        sidebarToggle();
        makeTablesResponsive();
        makeFormsResponsive();
    };

    // Run initialization
    initResponsive();

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Reinitialize responsive features if needed
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth > 768 && sidebar) {
                sidebar.classList.remove('active');
                document.querySelector('.mobile-toggle')?.classList.remove('active');
                document.querySelector('.main_content')?.classList.remove('sidebar-active');
            }
        }, 250);
    });
});