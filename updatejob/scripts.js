$(document).ready(function() {
    // Function to get query parameters from URL
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Get JobID from URL
    var jobId = getQueryParam('JobID') || getQueryParam('id');

    if (jobId) {
        // Make AJAX call to fetch job details
        $.ajax({
            url: 'updatejobController.php',
            type: 'POST',
            data: { action: 'getJobDetails', jobId: jobId },
            success: function(response) {
                var job = JSON.parse(response);
                // Populate text boxes with job details
                $('#jobid').val(job.jobid);
                $('#orderdate').val(job.orderdate);
                $('#order_status').val(job.order_status);
                $('#company_name').val(job.company_name);
                $('#contact_person').val(job.contact_person);
                $('#contact_no').val(job.contact_no);
                $('#address').val(job.address);
                $('#oqty').val(job.oqty);
                $('#ovalue').val(job.ovalue);
                $('#discount').val(job.discount);
                $('#vat').val(job.vat);
                $('#vat1').val(job.vat);
                $('#netvalue').val(job.netvalue);
                $('#credit_bill').val(job.credit_bill);
                $('#paid_amount').val(job.paid_amount);
                $('#balance').val(job.balance);
                $('#qno').val(job.qid);
                $('#po_no').val(job.po_no);
                $('#jobid1').val(job.jobid);
                $('#jobid3').val(job.jobid);
                $('#opcreditbill').val(job.credit_bill);
                $('#cmpname').val(job.company_name);
                $('#cmprep').val(job.contact_person);
                $('#cmpcontact').val(job.contact_no);
                $('#cmpaddress').val(job.address);
                checkOrderStatus();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    function checkOrderStatus() {
                var orderStatus = $('#order_status').val().toLowerCase();
                if (orderStatus === 'pending') {
                    $('#newProductButton').prop('disabled', false);
                } else {
                    $('#newProductButton').prop('disabled', true);
                }
            }
        showJobList(jobId);
     function showJobList(jobId) {
        $.ajax({
            url: "updatejobController.php",
            type: "POST",
            data: { action: "viewProducts",jobId: jobId},
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#joblist").html(response);
                // Initialize DataTables here
                $("#jobListTable").DataTable({
                    order: [1, 'desc']
                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    showPayList(jobId);
     function showPayList(jobId) {
        $.ajax({
            url: "updatejobController.php",
            type: "POST",
            data: { action: "viewPayments",jobId: jobId},
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#paylist").html(response);
                // Initialize DataTables here
               /* $("#payListTable").DataTable({
                    order: [1, 'desc']
                });*/
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    
            function calculatePTotal() {
            console.log("calculating");
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
        
        
    $('#add-user-form').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    //console.log("Form data:", formData);  // Log form data for debugging
    var jbid = $('#jobid').val();
    $.ajax({
        url: "updatejobController.php",
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
               // showUsers();  // Assuming this function refreshes the product list
               showJobList(jbid);
               //START
                   if (jobId) {
        // Make AJAX call to fetch job details
        $.ajax({
            url: 'updatejobController.php',
            type: 'POST',
            data: { action: 'getJobDetails', jobId: jobId },
            success: function(response) {
                var job = JSON.parse(response);
                // Populate text boxes with job details
                $('#jobid').val(job.jobid);
                $('#orderdate').val(job.orderdate);
                $('#order_status').val(job.order_status);
                $('#company_name').val(job.company_name);
                $('#contact_person').val(job.contact_person);
                $('#contact_no').val(job.contact_no);
                $('#address').val(job.address);
                $('#oqty').val(job.oqty);
                $('#ovalue').val(job.ovalue);
                $('#discount').val(job.discount);
                $('#vat').val(job.vat);
                $('#vat1').val(job.vat);
                $('#vat2').val(job.vat);
                $('#netvalue').val(job.netvalue);
                $('#credit_bill').val(job.credit_bill);
                $('#paid_amount').val(job.paid_amount);
                $('#balance').val(job.balance);
                $('#qno').val(job.qid);
                $('#po_no').val(job.po_no);
                $('#jobid1').val(job.jobid);
                $('#jobid3').val(job.jobid);
                $('#opcreditbill').val(job.credit_bill);
                $('#cmpname').val(job.company_name);
                $('#cmprep').val(job.contact_person);
                $('#cmpcontact').val(job.contact_no);
                $('#cmpaddress').val(job.address);                
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
    // END
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



        $(document).on('click', '.editBtn', function(event) {
            event.preventDefault();
            var pid = $(this).data('id');
            $.ajax({
                url: 'updatejobController.php',
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
                        $('#jobid2').val(qprod.jobid);
                        $('#pid2').val(qprod.id);
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
        url: "updatejobController.php",
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
                var jid = parseFloat($('#jobid2').val());
                showJobList(jid);  // Assuming this function refreshes the product list
                if (jid) {
                    // Make AJAX call to fetch job details
                    $.ajax({
                        url: 'updatejobController.php',
                        type: 'POST',
                        data: { action: 'getJobDetails', jobId: jobId },
                        success: function(response) {
                            var job = JSON.parse(response);
                            // Populate text boxes with job details
                            $('#jobid').val(job.jobid);
                            $('#orderdate').val(job.orderdate);
                            $('#order_status').val(job.order_status);
                            $('#company_name').val(job.company_name);
                            $('#contact_person').val(job.contact_person);
                            $('#contact_no').val(job.contact_no);
                            $('#address').val(job.address);
                            $('#oqty').val(job.oqty);
                            $('#ovalue').val(job.ovalue);
                            $('#discount').val(job.discount);
                            $('#vat').val(job.vat);
                            $('#vat1').val(job.vat);
                            $('#netvalue').val(job.netvalue);
                            $('#credit_bill').val(job.credit_bill);
                            $('#paid_amount').val(job.paid_amount);
                            $('#balance').val(job.balance);
                            $('#qno').val(job.qid);
                            $('#po_no').val(job.po_no);
                            $('#jobid1').val(job.jobid);
                            $('#jobid3').val(job.jobid);
                            $('#opcreditbill').val(job.credit_bill);
                            $('#cmpname').val(job.company_name);
                            $('#cmprep').val(job.contact_person);
                            $('#cmpcontact').val(job.contact_no);
                            $('#cmpaddress').val(job.address);                            
                            checkOrderStatus();
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                }
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


$('#frmujob').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
   // console.log("Form data:", formData);  // Log form data for debugging

    $.ajax({
        url: "updatejobController.php",
        type: "POST",
        data: formData + "&action=UpdateJobData",
        success: function (response) {
           // console.log("Response from server:", response);  // Log server response

            if (response.trim() === "Job Updated successfully" || response.trim() === "Job added successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Job Updated',
                    text: 'Job has been updated successfully.',
                });
                $('#editJobModal').modal('hide');
                $('#frmujob')[0].reset();
                var jid = parseFloat($('#jobid3').val());
                showJobList(jid);  // Assuming this function refreshes the product list
                if (jid) {
                    // Make AJAX call to fetch job details
                    $.ajax({
                        url: 'updatejobController.php',
                        type: 'POST',
                        data: { action: 'getJobDetails', jobId: jobId },
                        success: function(response) {
                            var job = JSON.parse(response);
                            // Populate text boxes with job details
                            $('#jobid').val(job.jobid);
                            $('#orderdate').val(job.orderdate);
                            $('#order_status').val(job.order_status);
                            $('#company_name').val(job.company_name);
                            $('#contact_person').val(job.contact_person);
                            $('#contact_no').val(job.contact_no);
                            $('#address').val(job.address);
                            $('#oqty').val(job.oqty);
                            $('#ovalue').val(job.ovalue);
                            $('#discount').val(job.discount);
                            $('#vat').val(job.vat);
                            $('#vat1').val(job.vat);
                            $('#netvalue').val(job.netvalue);
                            $('#credit_bill').val(job.credit_bill);
                            $('#paid_amount').val(job.paid_amount);
                            $('#balance').val(job.balance);
                            $('#qno').val(job.qid);
                            $('#po_no').val(job.po_no);
                            $('#jobid1').val(job.jobid);
                            $('#jobid3').val(job.jobid);
                            $('#opcreditbill').val(job.credit_bill);
                            $('#cmpname').val(job.company_name);
                            $('#cmprep').val(job.contact_person);
                            $('#cmpcontact').val(job.contact_no);
                            $('#cmpaddress').val(job.address);                            
                            checkOrderStatus();
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update Job. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);  // Log detailed AJAX error for debugging
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add Job. Please try again later.',
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
        
           // Delete product button click event
        $(document).on('click', '.delBtn', function() {
            var productid = $(this).data('id');
            var jobid = $('#jobid').val(); // Get the job ID from the #jobid textbox
            console.log(jobid);
            var confirmation = confirm('Are you sure you want to delete this Product?');
        
            if (confirmation) {
                $.ajax({
                    url: 'updatejobController.php',
                    type: 'POST',
                    data: {
                        action: 'delete_prod',
                        id: productid,
                        jobid: jobid // Pass the job ID in the request
                    },
                    success: function(response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Product Deleted',
                                text: 'Product has been deleted successfully.',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(); // Reload the page
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete Product. Please try again later.',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }
        });

    
        // Pay total calculation
        function calculatePayTotal() {
            var pcash = parseFloat($('#pcash').val()) || 0;
            var pcard = parseFloat($('#pcard').val()) || 0;
            var pcheque = parseFloat($('#pcheque').val()) || 0;
            var pwallet = parseFloat($('#pwallet').val()) || 0;
            var total = pcash + pcard + pcheque + pwallet;

            if (!isNaN(total)) {
                $('#ptotal').val(total.toFixed(2));
            } else {
                $('#ptotal').val('0.00');
            }
        }
        $('#pcash, #pcard, #pcheque, #pwallet').on('input', calculatePayTotal);

        // Edit Payment button click event
        $(document).on('click', '.editPayBtn', function(event) {
            event.preventDefault();
            var payId = $(this).data('id');
            $.ajax({
                url: 'updatejobController.php',
                type: 'POST',
                data: { action: 'getPaymentDetails', pid: payId },
                dataType: 'json',
                success: function(response) {
                    $('#pcash').val(response.cash);
                    $('#pcard').val(response.card);
                    $('#pcheque').val(response.cheque);
                    $('#pwallet').val(response.wallet);
                    $('#premarks').val(response.remarks);
                    $('#payid').val(response.id);
                    $('#jobid4').val(response.jobid);
                    calculatePayTotal();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });

        // Submit updated payment
        $('#frmUpdatePay').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "updatejobController.php",
                type: "POST",
                data: formData + "&action=UpdatePaymentData",
                success: function (response) {
                    if (response.trim() === "Payment Updated successfully") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Updated',
                            text: 'Payment has been updated successfully.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); 
                            }
                        });
                        $('#editPayModal').modal('hide');
                        $('#frmUpdatePay')[0].reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update payment. Please try again later.',
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update payment. Please try again later.',
                    });
                }
            });
        });

        // Delete payment button click event
        $(document).on('click', '.delPayBtn', function(event) {
            event.preventDefault();
            var payId = $(this).data('id');
            var jobid = $(this).data('jobid');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this payment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'updatejobController.php',
                        type: 'POST',
                        data: {
                            action: 'delete_payment',
                            id: payId,
                            jobid: jobid 
                        },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Payment has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete payment.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                }
            });
        });

});
