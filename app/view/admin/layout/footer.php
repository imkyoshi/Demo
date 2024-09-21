<script src="/demo/public/js/jquery-3.6.0.min.js"></script>
<script src="/demo/public/js/feather.min.js"></script>
<script src="/demo/public/js/jquery.slimscroll.min.js"></script>
<script src="/demo/public/js/jquery.dataTables.min.js"></script>
<script src="/demo/public/js/dataTables.bootstrap4.min.js"></script>
<script src="/demo/public/js/bootstrap.bundle.min.js"></script>
<script src="/demo/public/plugins/select2/js/select2.min.js"></script>
<script src="/demo/public/plugins/fileupload/fileupload.min.js"></script>
<script src="/demo/public/js/moment.min.js"></script>
<script src="/demo/public/js/bootstrap-datetimepicker.min.js"></script>
<script src="/demo/public/plugins/apexchart/apexcharts.min.js"></script>
<script src="/demo/public/plugins/apexchart/chart-data.js"></script>
<script src="/demo/public/js/script.js"></script>
<script src="/demo/public/js/toogle.js"></script>
<script src="/demo/public/js/inspect.js"></script>
<script src="/demo/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/demo/node_modules/jspdf/dist/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script src="/demo/node_modules/jspdf-autotable/dist/jspdf.plugin.autotable.min.js"></script>
<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script>
    <?php if (isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['alert']['type']; ?>',
            title: '<?php echo $_SESSION['alert']['status']; ?>',
            text: '<?php echo $_SESSION['alert']['message']; ?>',
            showConfirmButton: true,
            timer: 3000, // 3000 milliseconds = 3 seconds
            timerProgressBar: true // Optional: adds a progress bar
        });
        <?php unset($_SESSION['alert']);?>
    <?php endif;?>
    </script>
    <script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set the user ID in the hidden form
                document.getElementById('deleteUserId').value = userId;
                // Submit the form
                document.getElementById('deleteUserForm').submit();
            }
        });
    }
</script>

</body>

</html>