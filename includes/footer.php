<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        // Universal DataTable Init
        var commonConfig = {
            dom: '<"row mb-2"<"col-sm-12"l>>' +
                '<"row mb-2"<"col-sm-12"f>>' +
                'rt' +
                '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            searching: true,
            paging: true,
            info: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries"
            }
        };

        // Initialize all tables
        var tables = ['#itemTable', '#saleTable', '#customerTable', '#purchaseTable', '#vendorTable'];

        tables.forEach(function (selector) {
            if ($(selector).length) {
                var table = $(selector).DataTable(commonConfig);
            }
        });

        // Bootstrap Tab Change Event - Adjustment for DataTables hidden visibility
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
    });

    // Logout confirmation
    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../auth/logout.php";
        }
    }
</script>
</body>

</html>