$(document).ready(function () {
    
        function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Get JobID from URL
    var qid = getQueryParam('qid');

    if (qid) {
        // Make AJAX call to fetch job details
        $.ajax({
            url: 'editquotationController.php',
            type: 'POST',
            data: { action: 'getQuotationDetails', qid: qid },
            success: function(response) {
               // console.log(response);
                var job = JSON.parse(response);
                // Populate text boxes with job details
                $('#qid').val(job.qid);
                $('#company_name').val(job.company_name);
                $('#contact_person').val(job.contact_person);
                $('#contact_no').val(job.contact_no);
                $('#address').val(job.address);
                $('#tqty').val(job.tqty);
                $('#qvalue').val(job.qvalue);
                $('#qid1').val(job.qid);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    showUsers();

    function showUsers() {
        //var qid = qid;
        //console.log(qid);
        $.ajax({
            url: "editquotationController.php",
            type: "POST",
            data: { action: "view", qid: qid  },
            success: function (response) {
                console.log("Response from server:", response); // Check response from server
                $("#showUsers").html(response);
                // Initialize DataTables here
               // if ($.fn.DataTable.isDataTable("#usersTable")) {
                //$("#usersTable").DataTable().destroy();
                //}
               
                $("#usersTable").DataTable({
                     pageLength: 25, 
                     lengthMenu: [ [25, 50, 100, -1], [25, 50, 100, "All"] ], 
                     //order: [1, 'desc']
                });
                calculateTotals();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Handle form submission
    /*$('#add-user-form').submit(function (e) {
        e.preventDefault();
        // Serialize form data
        var formData = $(this).serialize();
        console.log("Form data:", formData); // Check form data before sending
        // Send AJAX request to usersController.php
        $.ajax({
            url: "newquotationController.php",
            type: "POST",
            data: formData + "&action=add",
            success: function (response) {
                console.log("Response from server:", response); // Check response from server
                // Display success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added',
                    text: 'Product has been added successfully.',
                });
                // Close the modal
                $('#addModal').modal('hide');
                // Reset the form
                $('#add-user-form')[0].reset();
                // Reload the user list
                showUsers();

                // Update the total quantity and value fields
                var qty = parseInt($("#qty").val());
                var total = parseFloat($("#total").val());

                var tqty = parseInt($("#tqty").val());
                var qvalue = parseFloat($("#qvalue").val());

                tqty += qty;
                qvalue += total;

                $("#tqty").val(tqty);
                $("#qvalue").val(qvalue.toFixed(2)); // Assuming 2 decimal places for the total value
            },
            error: function (xhr, status, error) {
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add Product. Please try again later.',
                });
            }
        });
    });    */

   $('#add-user-form').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
   // console.log("Form data:", formData);  // Log form data for debugging

    $.ajax({
        url: "editquotationController.php",
        type: "POST",
        data: formData + "&action=add",
        success: function (response) {
            console.log("Response from server:", response);  // Log server response

            if (response.trim() === "Product added successfully" || response.trim() === "User added successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added',
                    text: 'Product has been added successfully.',
                });
                $('#addModal').modal('hide');
                $('#add-user-form')[0].reset();
                showUsers();  // Assuming this function refreshes the product list
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add Product. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);  // Log detailed AJAX error for debugging
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add Product. Please try again later.',
            });
        }
    });
});


   // Handle form submission
$('#frmquote').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    //console.log("Form data:", formData); 
    $.ajax({
        url: "editquotationController.php",
        type: "POST",
        data: formData + "&action=addquotation",
        success: function (response) {
           // console.log("Response from server:", response); 
            if (response.trim() === "Quotation saved successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Quotation Updation',
                    text: 'Quotation has been updated successfully.',
                }).then(function() {
                    window.location.href = '../quotations';
                });
                $('#frmquote')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save Quotation. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save Quotation. Please try again later.',
            });
        }
    });
});

    
    // Delete user button click event
$(document).on('click', '.delBtn', function() {
    var productid = $(this).data('id');
    var confirmation = confirm('Are you sure you want to delete this Product?');
    if (confirmation) {
        $.ajax({
            url: '../newquotation/newquotationController.php',
            type: 'POST',
            data: { action: 'delete_user', id: productid },
            success: function(response) {
                if (response == 'success') {
                    // Reload user list
                    showUsers();
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Deleted',
                        text: 'Product has been deleted successfully.',
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete Product. Please try again later.',
                    });
                }
            },
            error: function(xhr, status, error) {
             //   console.error("AJAX Error:", error);
            }
        });
    }
});

        $(document).on('click', '.editBtn', function(event) {
            event.preventDefault();
            var pid = $(this).data('id');
            $.ajax({
                url: 'editquotationController.php',
                type: 'POST',
                data: { action: 'updateProduct', pid: pid },
                dataType: 'json',
                success: function(response) {
                    var qprod = response;
                    if (qprod.error) {
                        console.error("Server Error:", qprod.error);
                    } else {
                        $('#pname').val(qprod.product_name);
                        $('#pqty').val(qprod.qty);
                        $('#ucost').val(qprod.unitcost);
                        $('#tot1').val(qprod.total);
                        $('#qid2').val(qprod.qid);
                        $('#pid1').val(qprod.id);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });

   $('#frmustatus').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
   // console.log("Form data:", formData);  // Log form data for debugging

    $.ajax({
        url: "editquotationController.php",
        type: "POST",
        data: formData + "&action=UpdateQ1",
        success: function (response) {
           // console.log("Response from server:", response);  // Log server response

            if (response.trim() === "Product Updated successfully" || response.trim() === "User added successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Product Updated',
                    text: 'Product has been updated successfully.',
                });
                $('#editModal').modal('hide');
                $('#frmustatus')[0].reset();
                showUsers();  // Assuming this function refreshes the product list
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update Product. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);  // Log detailed AJAX error for debugging
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add Product. Please try again later.',
            });
        }
    });
});


//product total calculation
        function calculateUPTotal() {
            var qty = parseFloat($('#pqty').val());
            var unitcost = parseFloat($('#ucost').val());
            var total = qty * unitcost;

            if (!isNaN(total)) {
                $('#tot1').val(total.toFixed(2));
            } else {
                $('#tot1').val('');
            }
        }
        $('#pqty, #ucost').on('input', calculateUPTotal);
//product total calculation
        function calculatePTotal() {
            var qty = parseFloat($('#qty').val());
            var unitcost = parseFloat($('#unitcost').val());
            var total = qty * unitcost;

            if (!isNaN(total)) {
                $('#total').val(total.toFixed(2));
            } else {
                $('#total').val('');
            }
        }
        $('#qty, #unitcost').on('input', calculatePTotal);

    function calculateTotals() {
        let totalQty = 0;
        let totalValue = 0;

        $('#usersTable tbody tr').each(function() {
            const qty = parseFloat($(this).find('td').eq(2).text());
            const value = parseFloat($(this).find('td').eq(4).text());

            if (!isNaN(qty)) {
                totalQty += qty;
            }
            if (!isNaN(value)) {
                totalValue += value;
            }
        });

        $('#tqty').val(totalQty);
        $('#qvalue').val(totalValue.toFixed(2));
    }

    // Add event listener to recalculate totals after the table data is modified
    $('#showUsers').on('DOMSubtreeModified', calculateTotals);
});
    // Insert, Edit, and Delete functionality
    // Add your jQuery code for handling user interactions

