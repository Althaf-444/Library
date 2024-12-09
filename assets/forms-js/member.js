$('.edit-user-btn').on('click', async function () {
    var user_id = $(this).data('id');
    console.log(user_id); // For debugging
    await getUserById(user_id); // Fetch the user data
});

$('#update-user').on('click', function () {
    if (!validatePasswords('password', 'confirm-password')) {
        showAlert('Passwords do not match..!', 'danger', 'edit-alert-container'); // Prevent form submission if passwords do not match
        return;
    }

    // Get the form element
    var form = $('#update-form')[0];
    form.reportValidity(); // Check validity of the form fields

    // Check form validity
    if (form.checkValidity()) {
        // Serialize the form data
        var url = $('#update-form').attr('action');
        var formData = new FormData($('#update-form')[0]);

        // Perform AJAX request
        $.ajax({
            url: url,
            type: 'POST',
            data: formData, // Form data
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                showAlert(response.message, response.success ? 'primary' : 'danger', 'edit-alert-container');
                if (response.success) {
                    $('#edit-user-modal').modal('hide');
                    setTimeout(function () {
                        location.reload(); // Reload the page after a successful update
                    }, 1000);
                }
            },
            error: function (error) {
                // Handle the error
                console.error('Error submitting the form:', error);
                showAlert('An error occurred while submitting the form.', 'danger', 'edit-alert-container');
            },
            complete: function (response) {
                // This will be executed regardless of success or error
                console.log('Request complete:', response);
            }
        });
    } else {
        var message = 'Form is not valid. Please check your inputs.';
        showAlert(message, 'danger', 'edit-alert-container');
    }
});

// Fetch user data by ID
async function getUserById(id) {
    var url = $('#update-form').attr('action'); // Get the form's action URL
    $.ajax({
        url: url,
        type: 'GET',
        data: {
            user_id: id,
            action: 'get_user'
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Populate the form fields with user data
                $('#edit-user-modal #id').val(response.data.id);
                $('#edit-user-modal #name').val(response.data.member_name);
                $('#edit-user-modal #email').val(response.data.email);
                $('#edit-user-modal #role').val(response.data.role);
                $('#edit-user-modal #datetime').val(response.data.created_at);
                $('#edit-user-modal').modal('show'); // Show the modal after fetching the data
            } else {
                showAlert(response.message || 'Failed to fetch user data.', 'danger', 'edit-alert-container');
            }
        },
        error: function (error) {
            console.error('Error fetching user data:', error);
            alert('An error occurred while fetching user data.');
        }
    });
}

// Show alert message function
function showAlert(message, type, containerId) {
    $('#' + containerId).html('<div class="alert alert-' + type + '">' + message + '</div>');
}
