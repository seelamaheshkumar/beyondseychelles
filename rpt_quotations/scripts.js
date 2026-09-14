$(document).ready(function () {
    // Set default date values
    let today = new Date().toISOString().split('T')[0];
    $('#fdate').val(today);
    $('#tdate').val(today);

    // Initial load of today's records
    showUsers(today, today);

    // Handle form submission / search button click
    $('#btnSearch').click(function (e) {
        e.preventDefault();
        let fdate = $('#fdate').val();
        let tdate = $('#tdate').val();
        showUsers(fdate, tdate);
    });

    function showUsers(fdate, tdate) {
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: { action: "view", fdate: fdate, tdate: tdate },
            success: function (response) {
                $("#showUsers").html(response);

                // Initialize DataTables here
                $("#usersTable").DataTable({
                    destroy: true,
                    order: [[1, 'desc']],
                    pageLength: 25,
                    dom: 'lBfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
                // Bind click event for print button (if defined elsewhere)
                if(typeof bindPrintButton === "function") {
                    bindPrintButton();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    
    
});
