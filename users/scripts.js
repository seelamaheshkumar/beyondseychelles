$(document).ready(function () {
    showUsers();

    function showUsers() {
        $.ajax({
            url: "usersController.php",
            type: "POST",
            data: { action: "view" },
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                $("#showUsers").html(response);
                // Initialize DataTables here
                $("#usersTable").DataTable({
                    order: [1, 'desc']
                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Handle form submission
    $('#add-user-form').submit(function (e) {
        e.preventDefault();
        // Serialize form data
        var formData = $(this).serialize();
        console.log("Form data:", formData); // Check form data before sending
        // Send AJAX request to usersController.php
        $.ajax({
            url: "usersController.php",
            type: "POST",
            data: formData + "&action=add",
            success: function (response) {
                //console.log("Response from server:", response); // Check response from server
                // Display success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'User Added',
                    text: 'User has been added successfully.',
                });
                // Close the modal
                $('#addModal').modal('hide');
                // Reset the form
                $('#add-user-form')[0].reset();
                // Reload the user list
                showUsers();
            },
            error: function (xhr, status, error) {
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add user. Please try again later.',
                });
            }
        });
    });
    

    // Populate the edit modal with user details
    $(document).on('click', '.editBtn', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'usersController.php',
            type: 'POST',
            data: { action: 'get_user', id: userId },
            success: function(response) {
                // Populate the edit modal with retrieved user data
                $('#editModal .modal-content').html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });

    // Handle form submission for editing a user
// Handle form submission for editing a user
$('#edit-user-form').submit(function (e) {
    e.preventDefault();
    // Serialize form data
    var formData = $(this).serialize();
    // Send AJAX request to usersController.php
    $.ajax({
        url: "usersController.php",
        type: "POST",
        data: formData + "&action=edit",
        success: function (response) {
            // Check the response from the server
            if (response === "success") {
                // Display success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'User Updated',
                    text: 'User information has been updated successfully.',
                });
                // Close the modal
                $('#editModal').modal('hide');
                // Reload the user list
                showUsers();
            } else {
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update user information. Please try again later.',
                });
            }
        },
        error: function (xhr, status, error) {
            // Display error message using SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update user information. Please try again later.',
            });
        }
    });
});

    // Delete user button click event
    $(document).on('click', '.delBtn', function() {
        var userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'usersController.php',
                    type: 'POST',
                    data: { action: 'delete_user', id: userId },
                    success: function(response) {
                        if (response == 'success') {
                            // Reload user list
                            showUsers();
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'User Deleted',
                                text: 'User has been deleted successfully.',
                            });
                        } else {
                            // Show error message
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
    // Insert, Edit, and Delete functionality
    // Add your jQuery code for handling user interactions
});
