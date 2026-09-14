$(document).ready(function () {
    // Set default date values
    let today = new Date().toISOString().split('T')[0];
    $('#fdate').val(today);
    $('#tdate').val(today);

    // Initial load of today's records
    showUsers(today, today);

    // Handle form submission
    $('#frmsearch').submit(function (e) {
        e.preventDefault();
        let fdate = $('#fdate').val();
        let tdate = $('#tdate').val();
        console.log(fdate+'-'+ tdate);
        showUsers(fdate, tdate);
    });


    
    function showUsers(fdate, tdate) {
        $.ajax({
            url: "jobsController.php",
            type: "POST",
            data: { action: "view", fdate: fdate, tdate: tdate },
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