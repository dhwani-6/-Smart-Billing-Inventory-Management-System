<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        // Universal DataTable Init
        // Layout: 
        // Row 1: Length Menu (Show 10 entries)
        // Row 2: Buttons (Left) --- Search (Right)

        var commonConfig = {
            // "l" = length, "B" = buttons, "f" = filtering, "rt" = table, "i" = info, "p" = pagination
            // We want length "l" on top. Then a new div with "B" and "f".
            dom: '<"row mb-2"<"col-sm-12"l>>' +
                '<"row mb-2"<"col-sm-6"B><"col-sm-6"f>>' +
                'rt' +
                '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                { extend: 'copy', text: '<i class="fas fa-copy me-1"></i> Copy', className: 'btn btn-sm btn-light border' },
                { extend: 'csv', text: '<i class="fas fa-file-csv me-1 text-primary"></i> CSV', className: 'btn btn-sm btn-light border' },
                { extend: 'excel', text: '<i class="fas fa-file-excel me-1 text-success"></i> Excel', className: 'btn btn-sm btn-light border' },
                { extend: 'pdf', text: '<i class="fas fa-file-pdf me-1 text-danger"></i> PDF', className: 'btn btn-sm btn-light border' },
                { extend: 'print', text: '<i class="fas fa-print me-1 text-secondary"></i> Print', className: 'btn btn-sm btn-light border' }
            ],
            searching: true,
            paging: true,
            info: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries"
            },
            // Fix for Tabs: Recalculate column widths when tab is shown
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