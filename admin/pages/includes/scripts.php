<!-- Vendors JS -->
<script src="../assets/vendors/js/vendors.min.js"></script>
<!-- Bootstrap JS -->
<script src="../assets/vendors/js/bootstrap.min.js"></script>
<!-- ApexCharts JS -->
<script src="../assets/vendors/js/apexcharts.min.js"></script>
<!-- DataTables JS -->
<script src="../assets/vendors/js/dataTables.min.js"></script>
<script src="../assets/vendors/js/dataTables.bs5.min.js"></script>
<!-- Fullscreen Helper -->
<script src="../assets/vendors/js/full-screen-helper.min.js"></script>
<!-- Common Init JS -->
<script src="../assets/js/common-init.min.js"></script>
<!-- Theme Customizer -->
<script src="../assets/js/theme-customizer-init.min.js"></script>
<!-- DataTable Auto Init -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.datatable').forEach(function(table) {
        if (!$.fn.DataTable.isDataTable(table)) {
            $(table).DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                responsive: true,
                order: [[0, 'desc']]
            });
        }
    });
});
</script>