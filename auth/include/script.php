<script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert']['type']; ?>',
                title: '<?php echo $_SESSION['alert']['message']; ?>',
                showConfirmButton: true
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>