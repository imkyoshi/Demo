<script src="../../../public/js/jquery-3.6.0.min.js"></script>
<script src="../../../public/js/feather.min.js"></script>
<script src="../../../public/js/jquery.slimscroll.min.js"></script>
<script src="../../../public/js/jquery.dataTables.min.js"></script>
<script src="../../../public/js/dataTables.bootstrap4.min.js"></script>
<script src="../../../public/js/bootstrap.bundle.min.js"></script>
<script src="../../../public/plugins/select2/js/select2.min.js"></script>
<script src="../../..//public/plugins/fileupload/fileupload.min.js"></script>
<script src="../../../public/js/moment.min.js"></script>
<script src="../../../public/js/bootstrap-datetimepicker.min.js"></script>
<script src="../../../public/plugins/apexchart/apexcharts.min.js"></script>
<script src="../../../public/plugins/apexchart/chart-data.js"></script>
<!-- <script src="../../../public/js/testScript.js"></script> -->
<script src="../../../public/js/script.js"></script>
<script src="../../../public/js/toogle.js"></script>
<!-- <script src="/demo/public/js/inspect.js"></script> -->
<script src="/demo/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
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
</body>

</html>