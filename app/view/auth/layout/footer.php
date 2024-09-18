    
    <!-- <script src="/demo/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <script src="/demo/public/js/inspect.js"></script>
    <script src="/demo/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/demo/public/js/modal.js"></script>
    <script>
    <?php if (isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['alert']['type']; ?>',
            title: '<?php echo $_SESSION['alert']['message']; ?>',
            showConfirmButton: true,
            // timer: 3000, // 3000 milliseconds = 3 seconds
            // timerProgressBar: true // Optional: adds a progress bar
        });
        <?php unset($_SESSION['alert']);?>
    <?php endif;?>
    </script>
    <!-- Footer -->
    <footer class="fixed bottom-0 right-0 p-4 text-sm text-gray-600 bg-gray-200 rounded-tl-lg shadow-lg">
        <p>&copy; 2024 Your Company</p>
    </footer>
</body>
</html>
