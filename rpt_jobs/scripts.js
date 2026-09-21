$(document).ready(function () {
    // Set default date values
    let currentYear = new Date().getFullYear();
    $('#fyear').val(currentYear);

    // Initial load of today's records
    showUsers(currentYear);

    // Variable to hold the DataTable instance
    var jobsTable;

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

    // Event listener for the status filter dropdown
    $('#statusFilter').on('change', function () {
        applyStatusFilter(); // Call the dedicated function to apply the filter
    });

    // Function to apply the status filter to the DataTable
    function applyStatusFilter() {
        var selectedStatus = $('#statusFilter').val();

        // Clear all existing custom search functions to prevent stacking
        $.fn.dataTable.ext.search = [];

        if (selectedStatus) {
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    if (settings.nTable.id !== 'usersTable') {
                        return true;
                    }

                    // Status is at index 8 and contains HTML (a span tag)
                    var statusColumnData = data[8] ? $(data[8]).text().trim() : '';

                    return selectedStatus === '' || statusColumnData === selectedStatus;
                }
            );
        }
        // Redraw the table to apply the filter
        if (jobsTable) {
            jobsTable.draw();
        }
    }


    
// Event delegation for dynamically generated elements
    $(document).on('click', '.viewJobBtn', function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        var jobId = $(this).data('id');
        var targetUrl = '../viewjob/index.php?JobID=' + jobId; // Construct the URL
        window.location.href = targetUrl; // Redirect to the new page
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
                jobsTable = $("#usersTable").DataTable({
                    destroy: true,
                    order: [[1, 'desc']],
                    pageLength: 25,
                    dom: 'lBfrtip', // Added 'l' to allow length changing, 'B' for buttons
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
                
                // Apply current status filter after table is loaded
                applyStatusFilter();
                // Bind click event for print button
                bindPrintButton();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    
    
    
});