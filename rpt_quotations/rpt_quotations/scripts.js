$(document).ready(function () {
    console.log("Document ready"); // Log to confirm document ready
    showUsers();
    function showUsers_bak() {
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: { action: "view" },
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#showUsers").html(response);
                // Initialize DataTables here
                $("#usersTable").DataTable({
                    //order: [1, 'desc']
                });
                // Bind click event for print button
                bindPrintButton();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    
    
    function showUsers() {
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: { action: "view" },
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#showUsers").html(response);
                // Initialize DataTables here
                $("#usersTable").DataTable({
                    order: [1, 'desc'],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
                // Bind click event for print button
                bindPrintButton();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    
    
});
