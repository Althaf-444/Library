$(document).ready(function () {
    // When the Edit button is clicked
    $('.edit-user-btn').on('click', function () {
        var user_id = $(this).data('id');  // Get the user ID from the button's data attribute
        getUserById(user_id);  // Fetch user data to populate the form
    });

    // When the Update button is clicked
    $('#update-user').on('click', function () {
        // Get form data
        var form = $('#update-form')[0];
        var formData = new FormData(form); // Serialize the form data

        // Perform AJAX request to update user data
        $.ajax({
            url: $('#update-form').attr('action'), // The action URL of the form
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    $('#edit-user-modal').modal('hide');
                    showAlert('User updated successfully!', 'primary', 'edit-alert-container');
                    setTimeout(function () {
                        location.reload(); // Reload page after success
                    }, 1000);
                } else {
                    showAlert(response.message || 'Failed to update user.', 'danger', 'edit-alert-container');
                }
            },
            error: function (error) {
                console.error('Error updating user:', error);
                showAlert('An error occurred while updating user.', 'danger', 'edit-alert-container');
            }
        });
    });
});

// Fetch user data by ID to populate the form fields
function getUserById(id) {
    $.ajax({
        url:  $('#update-form').attr('action'),  // Server endpoint to fetch user data (could be a PHP file)
        type: 'GET',
        data: { user_id: id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Populate the form with the fetched data
                $('#edit-user-modal #id').val(response.data.id);
                $('#edit-user-modal #name').val(response.data.member_name);
                $('#edit-user-modal #email').val(response.data.email);
                $('#edit-user-modal #role').val(response.data.role);
                $('#edit-user-modal #datetime').val(response.data.created_at);
                $('#edit-user-modal').modal('show');  // Show the modal
            } else {
                showAlert(response.message || 'Failed to fetch user data.', 'danger', 'edit-alert-container');
            }
        },
        error: function (error) {
            console.error('Error fetching user data:', error);
            showAlert('An error occurred while fetching user data.', 'danger', 'edit-alert-container');
        }
    });
}

// Show alert message function
function showAlert(message, type, containerId) {
    $('#' + containerId).html('<div class="alert alert-' + type + '">' + message + '</div>');
}
