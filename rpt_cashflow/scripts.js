$(document).ready(function () {
    // Set default date values
    let currentYear = new Date().getFullYear();
    $('#fyear').val(currentYear);

    // Initial load of today's records
    showUsers(currentYear);

    // Handle form submission / search button click
    $('#btnSearch').click(function (e) {
        e.preventDefault();
        let year = $('#fyear').val();
        showUsers(year);
    });

    // Auto-search when dropdown changes
    $('#fyear').on('change', function() {
        let year = $(this).val();
        showUsers(year);
    });


    
    function showUsers(year) {
        $.ajax({
            url: "jobsController.php",
            type: "POST",
            data: { action: "view", year: year },
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
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
                // Bind click event for print button
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