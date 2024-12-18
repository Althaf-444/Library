<?php
require_once('../layouts/header.php');
include BASE_PATH . '/models/Borrowed_books.php';

$Borrowed_BooksModel = new Borrowed_Books();

?>


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-8">
            <h4 class=""><span class="text-muted fw-light">Dashboard /</span>Add Borrowed Books </h4>
        </div>
        <div class="col-4">
            <div class="form-group d-flex justify-content-end ">
                <a href="<?= url('views/admin/borrowed_books.php') ?>" class="btn btn-dark active" id="courier_service_modal_btn">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card m-3 p-5">

        <!-- /.card-header -->
        <div class="modal fade" id="add_borrowed_books" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="borrowed_book_form" action="<?= url('services/ajax_functions.php') ?>">
                        <div class="row">
                            <div class="col-12">
                                <div id="alert-container"></div>
                            </div>
                            <!-- Include hidden field for appointment ID -->
                            <input type="hidden" name="id" name="">
                            <input type="hidden" name="action" value="add_borrowed">

                            <div class="mb-3 col-6">
                                <label for="member_id" class="form-label">Member Id:</label>
                                <input type="number" class="form-control" name="member_id" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="book_id" class="form-label">Book Id:</label>
                                <input type="number" class="form-control" name="book_id" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="book_status" class="form-label">Book Status:</label>
                                <select class="form-select" id="book_status" aria-label="Default select example" name="book_status" required>
                                    <option value="borrowed" class=" text-info ">Borrowed</option>
                                    <option value="returned" class=" text-success ">Returned</option>
                                    <option value="due_time_over" class=" text-danger ">Due Time Over</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="html5-datetime-local-input" class="form-label">Borrowed At:</label><br>
                                <input class="form-control" type="datetime-local" name="borrowed_at" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="html5-datetime-local-input" class="form-label">Due Date:</label><br>
                                <input class="form-control" type="datetime-local" name="due_date" />
                            </div>

                            <div class="mb-3 col-6">
                                <label for="html5-datetime-local-input" class="form-label">Returned At:</label><br>
                                <input class="form-control" type="datetime-local" name="returned_at" />
                            </div>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="fine" class="form-label">Fine:</label>
                            <input type="number" class="form-control" name="fine" required>
                        </div>
                        <div class="mt-4 col-6 text-end">
                            <button type="button" class="btn rounded-pill btn-dark" id="add_borrowed_books">Add Borrowed Books</button>
                        </div>


                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
</div>
</div>

</div>
<?php require_once('../layouts/footer.php'); ?>
<script>
    $(document).ready(function() {
        // Handle modal button click
        $('#add_borrowed_books').on('click', function(e) {
            e.preventDefault();

            // Get the form element
            var form = $('#borrowed_book_form')[0];
            $('#borrowed_book_form')[0].reportValidity();

            // Check form validity
            if (form.checkValidity()) {

                // Serialize the form data
                var formData = $('#borrowed_book_form').serialize();
                var formAction = $('#borrowed_book_form').attr('action');

                // Perform AJAX request
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData, // Form data
                    dataType: 'json',
                    success: function(response) {
                        showAlert(response.message, response.success ? 'success' : 'danger');
                    },
                    error: function(error) {
                        // Handle the error
                        console.error('Error submitting the form:', error);
                    },
                    complete: function(response) {
                        // This will be executed regardless of success or error
                        console.log('Request complete:', response);
                    }
                });
            } else {
                var message = ('Form is not valid. Please check your inputs.');
                showAlert(message, 'danger');
            }
        });
        $("#searchInput").on("input", function() {
            var searchTerm = $(this).val().toLowerCase();

            // Loop through each row in the table body
            $("tbody tr").filter(function() {
                // Toggle the visibility based on the search term
                $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
            });
        });

        // Initial setup for the date picker
        $('#datePicker').val(getFormattedDate(new Date()));

        // Function to format date as YYYY-MM-DD
        function getFormattedDate(date) {
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Function to update table rows based on the selected date
        function filterAppointmentsByDate(selectedDate) {
            // Loop through each row in the table body
            $('tbody tr').each(function() {
                var appointmentDate = $(this).find('.appointment_date').text(); // Assuming date is in the 12th column
                $(this).toggle(appointmentDate === selectedDate);
            });
        }

        // Event handler for the "Filter" button
        $('#clear').on('click', function() {
            location.reload();
        });

        // Event handler for date picker change
        $('#datePicker').on('change', function() {
            var selectedDate = $(this).val();
            filterAppointmentsByDate(selectedDate);
        });

    });
</script>