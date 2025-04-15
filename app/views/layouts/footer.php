<!-- Footer -->
<footer class="text-center text-gray-500 text-sm py-4">
    &copy; <?= date('Y'); ?> Kasir Jeni. Hak Cipta Dilindungi.
</footer>
</main>
</div>
</div>

<script>
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    // Pastikan elemen ada sebelum menambahkan event listener
    if (openSidebarBtn) {
        openSidebarBtn.addEventListener('click', (e) => {
            sidebar.classList.add('active');
            e.stopPropagation(); // Menghentikan event bubbling
        });
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', (e) => {
            sidebar.classList.remove('active');
            e.stopPropagation(); // Menghentikan event bubbling
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 768) {
            if (sidebar && !sidebar.contains(e.target) && e.target !== openSidebarBtn) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>
</body>
</html>
