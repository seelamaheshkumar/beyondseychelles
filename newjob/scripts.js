$(document).ready(function () {
    showUsers();

    function showUsers() {
        $.ajax({
            url: "newjobController.php",
            type: "POST",
            data: { action: "view" },
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#showUsers").html(response);
                // Initialize DataTables here
                $("#usersTable").DataTable({
                    order: [1, 'desc']
                });
                calculateTotals();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

   $('#add-user-form').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    console.log("Form data:", formData);  // Log form data for debugging

    $.ajax({
        url: "newjobController.php",
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
   $('#frmjob').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    //console.log("Form data:", formData); 
    $.ajax({
        url: "newjobController.php",
        type: "POST",
        data: formData + "&action=addjob",
        success: function (response) {
           // console.log("Response from server:", response); 
            if (response.trim() === "Job saved successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Job Created',
                    text: 'Job has been saved successfully.',
                }).then(function() {
                    window.location.href = '../jobs';
                });
                $('#frmjob')[0].reset();
                //showUsers();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save Job. Please try again later.',
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
});
    
    // Delete user button click event
$(document).on('click', '.delBtn', function() {
    var productid = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to delete this Product?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'newjobController.php',
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
});

        $(document).on('click', '.editBtn', function(event) {
            event.preventDefault();
            var pid = $(this).data('id');
            $.ajax({
                url: 'newjobController.php',
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
                        $('#jid2').val(qprod.jobid);
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
            url: "newjobController.php",
            type: "POST",
            data: formData + "&action=UpdateJ1",
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
/*
    function calculateTotals() {
        let totalQty = 0;
        let totalValue = 0;

        $('#usersTable tbody tr').each(function() {
            const qty = parseFloat($(this).find('td').eq(2).text());
            const value = parseFloat($(this).find('td').eq(3).text());

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
*/
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
    var isChecked = $("#chkvat").is(":checked");
    var vatrate = isChecked ? 15 : 0;
    let discount = parseFloat($('#discount').val()) || 0;
    let cash = parseFloat($('#cash').val()) || 0;
    let card = parseFloat($('#card').val()) || 0;
    let cheque = parseFloat($('#cheque').val()) || 0;
    let wallet = parseFloat($('#wallet').val()) || 0;

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

    // Calculate Gross Amount
    let grossAmt = totalValue - discount;

    // Calculate VAT
    let vat = (grossAmt * vatrate) / 100;

    // Calculate Net Amount
    let netAmount = grossAmt + vat;

    // Calculate Paid Amount
    let paid_amount = cash + card + cheque + wallet;

    // Calculate Balance
    let balance = netAmount - paid_amount;

    // Update the UI with calculated values
    $('#grossAmt').val(grossAmt.toFixed(2));
    $('#vat').val(vat.toFixed(2));
    $('#netvalue').val(netAmount.toFixed(2));
    $('#paid_amount').val(paid_amount.toFixed(2));
    $('#balance').val(balance.toFixed(2));

    // Validate payment amount
    if (paid_amount > netAmount) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Paid amount cannot exceed Net Amount!',
        });
        // Reset paid amount to avoid exceeding net amount
        $('#cash, #card, #cheque, #wallet').val(0);
        $('#paid_amount').val('0.00');
        $('#balance').val(netAmount.toFixed(2));
    }
}
    // Event listeners for input fields

    $('#discount, #cash, #card, #cheque, #wallet').on('input', calculateTotals);
    $("#chkvat").change(function() {
                    calculateTotals();
                });

    // Add event listener to recalculate totals after the table data is modified
    $('#showUsers').on('DOMSubtreeModified', calculateTotals);
    //calculateTotals();
});
    // Insert, Edit, and Delete functionality
    // Add your jQuery code for handling user interactions

