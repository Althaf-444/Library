<?php
require_once('../layouts/header.php');
include BASE_PATH . '/models/Borrowed_books.php';

$id = $_GET['id'] ?? null;
$Borrowed_BooksModel = new Borrowed_Books();
$Borrowed_Books = $Borrowed_BooksModel->getById($id);

?>


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-8">
            <h4 class=""><span class="text-muted fw-light">Dashboard /</span>Edit Borrowed Book </h4>
        </div>
        <div class="col-4">
            <div class="form-group d-flex justify-content-end ">
                <a href="<?= url('views/admin/fine.php') ?>" class="btn btn-dark active" id="courier_service_modal_btn">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card m-3 p-5">

        <!-- /.card-header -->
        <div class="container">
            <form id="fine_status_form" action="<?= url('services/ajax_functions.php') ?>">
                <div class="row">
                    <div class="col-12">
                        <div id="alert-container"></div>
                    </div>
                    <input type="hidden" name="id" value="<?= $Borrowed_Books['id']; ?>">
                    <input type="hidden" name="action" value="Borrowed_Books_update">
                    <div class=" mb-3 col-6">
                                <label for="fine" class="form-label">fine</label>
                                <input type="text" class="form-control"   value="<?= $Borrowed_Books['fine']; ?>"  name="book_status" readonly>

                            </div>
           
                            <div class=" mb-3 col-6">
                                <label for="fine_status" class="form-label">Fine Status:</label>
                                <select class="form-select" id="fine_status" aria-label="Default select example" name="fine_status" value="<?= $Borrowed_Books['fine_status']; ?>"  required>
                                    <option value="" class=" text-info "></option>
                                    <option value="no_fine" class=" text-info ">No Fine</option>
                                    <option value="paid" class=" text-success ">Paid</option>
                                    <option value="pending" class=" text-danger ">Pending</option>
                                </select>
                            </div> 
                            <div class="mb-3 col-6">
                        <label for="paid_date" class="form-label">Paid Date:</label>
                        <input type="date" class="form-control"   name="paid_date"  >
                    </div>
                    <div class="mt-4 col-6 text-end">
                        <button type="button" class="btn rounded-pill btn-success" id="update_fine_status">Update</button>
                    </div>


                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
</div>

</div>
<?php require_once('../layouts/footer.php'); ?>
<!-- <script src="<?= asset('assets/forms-js/borrowed.js') ?>"></script> -->
<script>
    $(document).ready(function() {
        // Handle modal button click
        $('#update_fine_status').on('click', function(e) {
            e.preventDefault();

            // Get the form element
            var form = $('#fine_status_form')[0];
            $('#fine_status_form')[0].reportValidity();

            // Check form validity
            if (form.checkValidity()) {

                // Serialize the form data
                var formData = $('#fine_status_form').serialize();
                var formAction = $('#fine_status_form').attr('action');

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