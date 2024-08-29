    
    <!-- <script src="/demo/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <script src="/demo/public/js/inspect.js"></script>
    <script src="/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert']['type']; ?>',
                title: '<?php echo $_SESSION['alert']['message']; ?>',
                showConfirmButton: true
            });
            <?php unset($_SESSION['alert']);?>
        <?php endif;?>
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalTrigger = document.querySelector('[data-modal-target]');
            const modal = document.querySelector(modalTrigger.getAttribute('data-modal-target'));
            const agreeBtn = document.getElementById('agreeBtn');
            const closeModal = document.getElementById('closeModal');

            modalTrigger.addEventListener('click', (event) => {
                event.preventDefault();
                modal.classList.remove('hidden');
            });

            agreeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                document.getElementById('terms').checked = true;
            });

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
    <!-- Footer -->
    <footer class="fixed bottom-0 right-0 p-4 text-sm text-gray-600 bg-gray-200 rounded-tl-lg shadow-lg">
        <p>&copy; 2024 Your Company</p>
    </footer>
</body>
</html>
