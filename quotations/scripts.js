$(document).ready(function () {
    console.log("Document ready");

    // --- Custom DataTables Sorting Plugin for ID (XXXX/YY) ---
    $.extend($.fn.dataTableExt.oSort, {
        "id-year-asc": function (a, b) {
            var getParts = function(str) {
                var m = String(str).match(/(?:QT-)?(\d+)\/(\d{4})/);
                if(m) return { id: parseInt(m[1], 10), year: parseInt(m[2], 10) };
                return { id: 0, year: 0 };
            };
            var pA = getParts(a);
            var pB = getParts(b);

            if (pA.year !== pB.year) {
                return pA.year - pB.year;
            }
            return pA.id - pB.id;
        },

        "id-year-desc": function (a, b) {
            var getParts = function(str) {
                var m = String(str).match(/(?:QT-)?(\d+)\/(\d{4})/);
                if(m) return { id: parseInt(m[1], 10), year: parseInt(m[2], 10) };
                return { id: 0, year: 0 };
            };
            var pA = getParts(a);
            var pB = getParts(b);

            if (pA.year !== pB.year) {
                return pB.year - pA.year;
            }
            return pB.id - pA.id;
        }
    });

    // Variable to hold the DataTable instance
    var quotationsTable;
function showUsers() {
    $.ajax({
        url: "quotationsController.php",
        type: "POST",
        data: { action: "view" },
        success: function (response) {
            $("#showUsers").html(response);

            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#usersTable')) {
                quotationsTable.destroy(); // Use the stored instance variable
                $('#usersTable').empty(); // Clear table content to prevent issues
            }

            quotationsTable = $("#usersTable").DataTable({
                "columnDefs": [
                    { "type": "id-year", "targets": 0 } // Apply custom sort to the first column (index 0)
                ],
                "order": [
                    [0, 'desc'] // Initial sort: first column, descending
                ],
                "retrieve": true, // Allows DataTables to re-initialize without error if it already exists

                // Add these two options for export buttons
                dom: 'lBfrtip', // 'l'ength, 'B'uttons, 'f'ilter, 'r'ecord_count, 't'able, 'i'nfo, 'p'agination
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                    // You can customize the buttons array with the export options you need:
                    // 'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print'
                    // For more options and customization, refer to: https://datatables.net/extensions/buttons/
                ]
            });

            // Apply current filter after table is loaded (if any year is selected)
            applyYearFilter();
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}
    // This function will fetch data and initialize/re-initialize DataTable
    function showUsers_BAK() {
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: { action: "view" },
            success: function (response) {
                $("#showUsers").html(response);

                // Destroy existing DataTable instance if it exists
                if ($.fn.DataTable.isDataTable('#usersTable')) {
                    quotationsTable.destroy(); // Use the stored instance variable
                    $('#usersTable').empty(); // Clear table content to prevent issues
                }

                quotationsTable = $("#usersTable").DataTable({
                    "columnDefs": [
                        { "type": "id-year", "targets": 0 } // Apply custom sort to the first column (index 0)
                    ],
                    "order": [
                        [0, 'desc'] // Initial sort: first column, descending
                    ],
                    // Ensure the table is re-drawn when new data comes in
                    "retrieve": true // Allows DataTables to re-initialize without error if it already exists
                });

                // Apply current filter after table is loaded (if any year is selected)
                applyYearFilter();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Function to apply the year filter to the DataTable
    function applyYearFilter() {
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

                    var dateColumnData = data[1] ? String(data[1]) : ''; // Get data from the second column (index 1)
                    // Find any 4-digit year in the column string
                    var match = dateColumnData.match(/\b(20\d{2})\b/);
                    var year = match ? match[1] : '';

                    return selectedYear === '' || year === selectedYear;
                }
            );
        }
        // Redraw the table to apply the filter (or remove it)
        if (quotationsTable) { // Check if the table instance exists
            quotationsTable.draw();
        }
    }


    // --- Event Listeners ---

    // Initial load of users
    showUsers();

    // Event listener for the year filter dropdown
    $('#yearFilter').on('change', function () {
        applyYearFilter(); // Call the dedicated function to apply the filter
    });

    // Event delegation for dynamically generated elements (already good)
    $(document).on('click', '.editBtn', function(event) {
        event.preventDefault();
        var qid = $(this).data('id');
        var targetUrl = '../editquotation/index.php?qid=' + qid;
        window.location.href = targetUrl;
    });

    $(document).on('click', '.printBtn', function(event) {
        event.preventDefault();
        var qid = $(this).data('id');
        var targetUrl = '../print_quotation/index.php?Rep=' + qid;
        // Use window.open for opening in new tab, as window.location.href might replace current page
        window.open(targetUrl, '_blank');
    });

    $(document).on('click', '.convertBtn', function(event) {
        event.preventDefault();
        var qid = $(this).data('id');
        $.ajax({
            url: 'quotationsController.php',
            type: 'POST',
            data: { action: 'getQuoteDetails', qid: qid },
            dataType: 'json',
            success: function(response) {
                $('#company_name').val(response.company_name);
                $('#contact_person').val(response.contact_person);
                $('#tqty').val(response.tqty);
                $('#qvalue').val(response.qvalue);
                $('#qid').val(response.qid);
                $('#editModal').modal('show'); // Manually show the modal if not automatically triggered by data-bs-target
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });

    $('#frmustatus').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: formData + "&action=updatestatus",
            success: function(response) {
                if (response.trim() === "Status Updated successfully") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Update',
                        text: 'Status has been updated and Converted to Job successfully.',
                    }).then(function() {
                        window.location.href = '../pjobs';
                    });
                    $('#frmustatus')[0].reset();
                    $('#editModal').modal('hide');
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

    $('#add-user-form').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        console.log("Form data:", formData);
        $.ajax({
            url: "quotationsController.php",
            type: "POST",
            data: formData + "&action=add",
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Quotation Added',
                    text: 'Quotation has been added successfully.',
                });
                $('#addModal').modal('hide');
                $('#add-user-form')[0].reset();
                showUsers();
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add Quotation. Please try again later.',
                });
            }
        });
    });

    $(document).on('click', '.delBtn', function() {
        var userId = $(this).data('id');
        var confirmation = confirm('Are you sure you want to delete this Quotation?');
        if (confirmation) {
            $.ajax({
                url: 'quotationsController.php',
                type: 'POST',
                data: { action: 'delete_user', id: userId },
                success: function(response) {
                    if (response.trim() == 'success') { // Use .trim() for string comparison
                        showUsers();
                        Swal.fire({
                            icon: 'success',
                            title: 'Quotation Deleted',
                            text: 'Quotation has been deleted successfully.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete user. Please try again later.',
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