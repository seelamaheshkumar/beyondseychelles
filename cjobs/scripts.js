$(document).ready(function () {
    console.log("Document ready for Jobs");

    // --- Custom DataTables Sorting Plugin for ID (XXXX/YY) ---
    // (Ensure this part is already present from the previous solution)
    $.extend($.fn.dataTableExt.oSort, {
        "id-year-asc": function (a, b) {
            var aParts = a.split('/');
            var bParts = b.split('/');
            var aYear = parseInt(aParts[1], 10);
            var bYear = parseInt(bParts[1], 10);
            var aID = parseInt(aParts[0], 10);
            var bID = parseInt(bParts[0], 10);

            if (aYear < bYear) return -1;
            if (aYear > bYear) return 1;
            if (aID < bID) return -1;
            if (aID > bID) return 1;
            return 0;
        },
        "id-year-desc": function (a, b) {
            var aParts = a.split('/');
            var bParts = b.split('/');
            var aYear = parseInt(aParts[1], 10);
            var bYear = parseInt(bParts[1], 10);
            var aID = parseInt(aParts[0], 10);
            var bID = parseInt(bParts[0], 10);

            if (aYear < bYear) return 1;
            if (aYear > bYear) return -1;
            if (aID < bID) return 1;
            if (aID > bID) return -1;
            return 0;
        }
    });

    // Set default date values
    let today = new Date().toISOString().split('T')[0];
    $('#fdate').val(today);
    $('#tdate').val(today);

    // Variable to hold the DataTable instance for jobs
    var jobsTable;

    // This function will fetch data and initialize/re-initialize DataTable
    function showUsers(fdate, tdate) {
        $.ajax({
            url: "jobsController.php",
            type: "POST",
            data: { action: "view", fdate: fdate, tdate: tdate },
            success: function (response) {
                $("#showUsers").html(response);

                // Destroy existing DataTable instance if it exists
                if ($.fn.DataTable.isDataTable('#usersTable')) {
                    jobsTable.destroy(); // Use the stored instance variable
                    $('#usersTable').empty(); // Clear table content to prevent issues
                }

                jobsTable = $("#usersTable").DataTable({
                    pageLength: 100,
                    "columnDefs": [
                        { "type": "id-year", "targets": 0 } // Apply custom sort to the first column (Job ID)
                    ],
                    "order": [
                        [0, 'desc'] // Initial sort: first column (Job ID), descending
                    ],
                    "retrieve": true, // Allows DataTables to re-initialize without error if it already exists
                    // Add these two options for export buttons
                    dom: 'lBfrtip', // 'l'ength, 'B'uttons, 'f'ilter, 'r'ecord_count, 't'able, 'i'nfo, 'p'agination
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });

                // Apply current year filter after table is loaded
                applyYearFilterForJobs();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Function to apply the year filter to the DataTable for Jobs
    function applyYearFilterForJobs() {
        var selectedYear = $('#yearFilter').val();

        // Clear all existing custom search functions to prevent stacking
        $.fn.dataTable.ext.search = [];

        if (selectedYear) {
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    // Make sure this filter only applies to your specific table
                    if (settings.nTable.id !== 'usersTable') {
                        return true;
                    }

                    // Use regex to find a 4-digit year in the column string
                    var dateColumnData = data[1] ? String(data[1]) : '';
                    var match = dateColumnData.match(/\b(20\d{2})\b/);
                    var year = match ? match[1] : '';

                    return selectedYear === '' || year === selectedYear;
                }
            );
        }
        // Redraw the table to apply the filter (or remove it)
        if (jobsTable) { // Check if the table instance exists
            jobsTable.draw();
        }
    }

    // --- Event Listeners ---

    // Initial load of today's records
    showUsers(today, today);

    // Handle form submission for date range
    $('#frmsearch').submit(function (e) {
        e.preventDefault();
        let fdate = $('#fdate').val();
        let tdate = $('#tdate').val();
        showUsers(fdate, tdate);
        // After fetching new data, re-apply the year filter
        // The showUsers success callback already calls applyYearFilterForJobs()
    });

    // Event listener for the year filter dropdown
    $('#yearFilter').on('change', function () {
        applyYearFilterForJobs(); // Call the dedicated function to apply the filter
    });

    // Other existing event listeners (edit, print, convert, delete, etc.)
    // ... (Keep your other event delegation code here)
    // For example:
    /*
    $(document).on('click', '.editBtn', function(event) { ... });
    $(document).on('click', '.printBtn', function(event) { ... });
    // etc.
    */
    
// Event delegation for dynamically generated elements
    $(document).on('click', '.viewJobBtn', function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        var jobId = $(this).data('id');
        var targetUrl = '../viewjob/index.php?JobID=' + jobId; // Construct the URL
        window.location.href = targetUrl; // Redirect to the new page
    });

    // Example for the print button
    $(document).on('click', '.printBtn', function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        var jobId = $(this).data('id');
        var targetUrl = '../printjob/index.php?JobID=' + jobId; // Construct the URL
        window.open(targetUrl, '_blank'); // Open the URL in a new tab
    });
    
        // Pay Modal
        $(document).on('click', '.addMoneyBtn', function(event) {
        event.preventDefault();
        var jobId = $(this).data('id');
       /// console.log("enter 2");
        $.ajax({
            url: 'jobsController.php',
            type: 'POST',
            data: { action: 'getJobDetails', jobId: jobId },
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                $('#company_name').val(response.company_name);
                $('#contact_person').val(response.contact_person);
                $('#netvalue').val(response.netvalue);
                $('#balance').val(response.balance);
                $('#jobId').val(response.jobid);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });

    
        // Status Change Modal
        $(document).on('click', '.editBtn', function(event) {
        event.preventDefault();
        var jobId = $(this).data('id');
        $.ajax({
            url: 'jobsController.php',
            type: 'POST',
            data: { action: 'getJobDetails', jobId: jobId },
            dataType: 'json',
            success: function(response) {
                $('#company_name1').val(response.company_name);
                $('#contact_person1').val(response.contact_person);
                $('#jbid').val(response.jobid);
                $('#balance2').val(response.balance);
                $('#cbill2').val(response.credit_bill);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });


    // Handle form submission
  /* $('#frmupay').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    //console.log("Form data:", formData); 
    $.ajax({
        url: "jobsController.php",
        type: "POST",
        data: formData + "&action=updatepay",
        success: function (response) {
            //console.log("Response from server:", response); 
            if (response.trim() === "Payment Updated successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Updated',
                    text: 'Payment has been updated successfully.',
                }).then(function() {
                    //window.location.href = '../jobs';
                    showUsers(today, today); 
                });
                $('#frmupay')[0].reset();
                //showUsers();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update the payment. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
         //   console.error("AJAX Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save Job. Please try again later.',
            });
        }
    });
});*/

    $('.canpaybtn').click(function() {
        $('#addPayModal').modal('hide');
    });   
    
 $('#frmupay').submit(function(e) {
        e.preventDefault();
        
                // Check if the cancel button was clicked
        if ($(document.activeElement).is('.btn-cancel')) {
            return;
        }
        
        // Get the value of the paidAmount field
        var paidAmount = parseFloat($('#paidAmount').val());
        
        // Check if paidAmount is greater than 0
        if (paidAmount <= 0 || isNaN(paidAmount)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a valid payment amount greater than 0.',
            });
            return; // Stop form submission
        }

        var formData = $(this).serialize();
        
        $.ajax({
            url: "jobsController.php",
            type: "POST",
            data: formData + "&action=updatepay",
            success: function(response) {
                //console.log("Response from server:", response); 
                if (response.trim() === "Payment Updated successfully") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Updated',
                        text: 'Payment has been updated successfully.',
                    }).then(function() {
                        //window.location.href = '../jobs';
                        showUsers(today, today); 
                    });
                    $('#addPayModal').modal('hide');
                    $('#frmupay')[0].reset();
                    //showUsers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update the payment. Please try again later.',
                    });
                }
            },
            error: function(xhr, status, error) {
                //console.error("AJAX Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save Job. Please try again later.',
                });
            }
        });
    });
    
    
        // Handle form submission
 /*  $('#frmustatus').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "jobsController.php",
            type: "POST",
            data: formData + "&action=updatestatus",
            success: function(response) {
                //console.log("Response from server:", response); 
                if (response.trim() === "Status Updated successfully") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: 'Status has been updated successfully.',
                    }).then(function() {
                        //window.location.href = '../jobs';
                        showUsers(today, today); 
                    });
                    $('#frmustatus')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update the status. Please try again later.',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update Job. Please try again later.',
                });
            }
        });
    });*/
    // Handle form submission
$('#frmustatus').submit(function(e) {
    e.preventDefault();
    
    // Get the selected order status
    var orderStatus = $('#order_status').val();
    
    // Check if the selected status is "Delivered"
    if (orderStatus === "Delivered") {
        // Get the values of balance2 and cbill2
        var balance2 = parseFloat($('#balance2').val());
        var cbill2 = $('#cbill2').val();
        
        // Check the condition for balance2 and cbill2
        if (balance2 > 0 && cbill2 === 'No') {
            Swal.fire({
                icon: 'warning',
                title: 'Pending Balance',
                text: 'Balance is pending. Please clear the balance before changing the status to Delivered.',
            });
            return; // Stop the form submission
        }
    }
    
    var formData = $(this).serialize();
    $.ajax({
        url: "jobsController.php",
        type: "POST",
        data: formData + "&action=updatestatus",
        success: function(response) {
            //console.log("Response from server:", response); 
            if (response.trim() === "Status Updated successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: 'Status has been updated successfully.',
                }).then(function() {
                    //window.location.href = '../jobs';
                    showUsers(today, today); 
                });
                $('#frmustatus')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update the status. Please try again later.',
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update Job. Please try again later.',
            });
        }
    });
});


        function calculateTotals() {
        let cash = parseFloat($('#cash').val()) || 0;
        let card = parseFloat($('#card').val()) || 0;
        let cheque = parseFloat($('#cheque').val()) || 0;
        let wallet = parseFloat($('#wallet').val()) || 0;
        let balance = parseFloat($('#balance').val()) || 0;
        let disc = parseFloat($('#disc').val()) || 0;
           // Calculate Paid Amount
        let paidAmount = cash + card + cheque + wallet;
    
        // Calculate Balance
        let nbalance = (balance - paidAmount)-disc;
            $('#paidAmount').val(paidAmount);
            $('#newbalance').val(nbalance);
        // Update the UI with calculated values
        // Validate payment amount
        balance=balance-disc;
        if (paidAmount > balance) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Paid amount cannot exceed Net Amount!',
            });
            // Reset paid amount to avoid exceeding net amount
            $('#cash, #card, #cheque, #wallet, $disc').val(0);
            $('#paidAmount').val('0.00');
            $('#newbalance').val('0.00');
        }
    }
    // Event listeners for input fields

    $('#cash, #card, #cheque, #wallet, #disc').on('input', calculateTotals);

    
    
    
        // Delete Job button click event
$(document).on('click', '.delBtn', function() {
    var userId = $(this).data('id');
    var confirmation = confirm('Are you sure you want to delete this Job?');
    if (confirmation) {
        $.ajax({
            url: 'jobsController.php',
            type: 'POST',
            data: { action: 'delete_user', id: userId },
            success: function(response) {
                if (response == 'success') {
                    // Reload user list
                    showUsers(today, today);
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Job Deleted',
                        text: 'Job has been deleted successfully.',
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete Job. Please try again later.',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
});

});
