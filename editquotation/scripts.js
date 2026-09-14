$(document).ready(function () {
    const qid = getQueryParam('qid');

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.has(param) ? urlParams.get(param) : null;
    }

    if (qid) {
        loadQuotationDetails(qid);
    }

    showUsers();

    function loadQuotationDetails(qid) {
        $.ajax({
            url: 'editquotationController.php',
            type: 'POST',
            data: { action: 'getQuotationDetails', qid: qid },
            success: function (response) {
                try {
                    const job = JSON.parse(response);
                    $('#main_qid').val(job.qid);
                    $('#company_name').val(job.company_name);
                    $('#contact_person').val(job.contact_person);
                    $('#contact_no').val(job.contact_no);
                    $('#address').val(job.address);
                    $('#tqty').val(job.tqty);
                    $('#qvalue').val(job.qvalue);
                } catch (e) {
                    console.error("Failed to parse response: ", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    }

    function showUsers() {
        $.ajax({
            url: "editquotationController.php",
            type: "POST",
            data: { action: "viewQuotationProducts", qid: qid },
            success: function (response) {
                $("#showUsers").html(response);

                if ($.fn.DataTable.isDataTable("#usersTable")) {
                    $('#usersTable').DataTable().destroy();
                }

                $('#usersTable').DataTable({
                    pageLength: 100,
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]]
                });

                attachEditButtonListeners();
                calculateTotals();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    }

    function attachEditButtonListeners() {
        $('.editBtn').off('click').on('click', function (event) {
            event.preventDefault(); 

            const productId = $(this).data('id');
            fetchProductDetails(productId);
        });

        $('.delBtn').off('click').on('click', function (event) {
            event.preventDefault(); 

            const productId = $(this).data('id');
            deleteProduct(productId);
        });
    }

function fetchProductDetails(productId) {
    $.ajax({
        url: 'editquotationController.php',
        type: 'POST',
        data: { action: 'getProductDetails', id: productId },
        success: function (response) {
            console.log("Raw response from server:", response); // Log the raw response to see what is returned
            try {
                if (response.trim() === "") {
                    throw new Error("Empty response received");
                }

                const product = JSON.parse(response);

                // Populate the form fields in the Edit Modal
                $('#edit_pid').val(product.id); 
                $('#edit_qid').val(product.qid);
                $('#edit_pname').val(product.product_name);
                $('#edit_pqty').val(product.qty);
                $('#edit_ucost').val(product.unitcost);
                $('#edit_tot1').val((product.qty * product.unitcost).toFixed(2));

                $('#editModal').modal('show');
            } catch (e) {
                console.error("Failed to parse product details response: ", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch product details. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error fetching product details: ", status, error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching product details. Please try again later.',
            });
        }
    });
}


    $('#frmustatus').submit(function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "editquotationController.php",
            type: "POST",
            data: formData + "&action=updateProduct",
            success: function (response) {
                if (response.trim() === "Product updated successfully") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Updated',
                        text: 'Product has been updated successfully.',
                    });
                    $('#editModal').modal('hide');
                    $('#frmustatus')[0].reset();
                    showUsers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update Product. Please try again later.',
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update Product. Please try again later.',
                });
            }
        });
    });

    $('#add-user-form').submit(function (e) {
        e.preventDefault();

        $('#modal_qid').val(qid);

        const formData = $(this).serialize();

        $.ajax({
            url: "editquotationController.php",
            type: "POST",
            data: formData + "&action=addProduct",
            success: function (response) {
                if (response.trim() === "Product added successfully") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Added',
                        text: 'Product has been added successfully.',
                    });
                    $('#addModal').modal('hide');
                    $('#add-user-form')[0].reset();
                    showUsers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add Product. Please try again later.',
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add Product. Please try again later.',
                });
            }
        });
    });

    function deleteProduct(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'editquotationController.php',
                    type: 'POST',
                    data: { action: 'deleteProduct', id: productId },
                    success: function (response) {
                        if (response.trim() === "Product deleted successfully") {
                            showUsers();
                            Swal.fire('Deleted!', 'Product has been deleted.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to delete Product.', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire('Error!', 'Failed to delete Product.', 'error');
                    }
                });
            }
        });
    }

    function calculateTotals() {
        let totalQty = 0;
        let totalValue = 0;

        $('#usersTable tbody tr').each(function () {
            const qty = parseFloat($(this).find('td').eq(1).text());
            const unitCost = parseFloat($(this).find('td').eq(2).text());

            if (!isNaN(qty)) {
                totalQty += qty;
            }

            if (!isNaN(qty) && !isNaN(unitCost)) {
                totalValue += qty * unitCost;
            }
        });

        $('#tqty').val(totalQty);
        $('#qvalue').val(totalValue.toFixed(2));
    }
$('#frmquote').submit(function (e) {
    e.preventDefault();
    
    const formData = {
        action: 'updateQuotationHeader',
        qid: $('#main_qid').val(),
        company_name: $('#company_name').val(),
        contact_person: $('#contact_person').val(),
        contact_no: $('#contact_no').val(),
        address: $('#address').val()
    };

    $.ajax({
        url: "editquotationController.php",
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.trim() === "Quotation updated successfully") {
                Swal.fire({
                    icon: 'success',
                    title: 'Quotation Updated',
                    text: 'Quotation has been updated successfully.',
                });
                // Optional: Redirect or perform other actions
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update quotation. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update quotation. Please try again later.',
            });
        }
    });
});

    function calculateAmount() {
        const qty = parseFloat($('#add_pqty').val());
        const unitCost = parseFloat($('#add_unitcost').val());

        if (!isNaN(qty) && !isNaN(unitCost)) {
            const total = qty * unitCost;
            $('#add_amount').val(total.toFixed(2));
        } else {
            $('#add_amount').val('');
        }
    }

    $(document).on('input', '#add_pqty, #add_unitcost', function () {
        calculateAmount();
    });

    $(document).on('input', '#edit_pqty, #edit_ucost', function () {
        calculateAmountEdit();
    });

    function calculateAmountEdit() {
        const qty = parseFloat($('#edit_pqty').val());
        const unitCost = parseFloat($('#edit_ucost').val());

        if (!isNaN(qty) && !isNaN(unitCost)) {
            const total = qty * unitCost;
            $('#edit_tot1').val(total.toFixed(2));
        } else {
            $('#edit_tot1').val('');
        }
    }

    showUsers();
});
