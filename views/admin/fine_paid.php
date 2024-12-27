<?php
require_once('../layouts/header.php');
require_once __DIR__ . '/../../models/Borrowed_books.php';

$Borrowed_BooksModel = new Borrowed_Books();
$finetable = $Borrowed_BooksModel->paidfine();

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Paid Fines

    
    <div class="row m-3">
        <div class="col-6">
            <div class="d-flex align-items-center m-3">
                <i class="bx bx-search  btn btn-outline-dark"></i>
                <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Search paid fine by member id  " aria-label="Search..." />
            </div>
        </div>
        <div class="col-2">
            <div class="form-group my-3">
                <button class="btn btn-outline-dark d-inline" id="clear">Clear</button>
            </div>
        </div>
    </div>
    <hr>
    </h4>



    <!-- /.card-header -->
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                    <th class="text-nowrap">Member Id</th>
                    <th class="text-nowrap">Member Name</th>
                    <th class="text-nowrap">Fine</th>
                    <th class="text-nowrap">Fine Status</th>
                    <th class="text-nowrap">Paid Date</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($finetable)) {
                    foreach ($finetable as $ft) {
                ?>
                        <tr>
                            
                            <td class="text-nowrap"> <?= $ft['user_id'] ?? ""; ?> </td>
                            <td class="text-nowrap"> <?= $ft['member_name'] ?? ""; ?> </td>
                            <td class="text-nowrap"> <?= $ft['fine'] ?? ""; ?> </td>
                            <td class="text-nowrap"> <?= $ft['fine_status'] ?? ""; ?> </td>
                            <td class="text-nowrap"> <?= $ft['paid_date'] ?? ""; ?> </td>

                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<?php require_once('../layouts/footer.php'); ?>
<!-- search bar script -->
<script>
    $(document).ready(function() {
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



        // Function to update table rows based on the selected date
        function filterAppointmentsByDate(selectedDate) {
            console.log("selectedDate Date:", selectedDate); // Log each appointment date for debugging


            // Loop through each row in the table body
            $('tbody tr').each(function() {
                var appointmentDate = $(this).find('.appointment_date').text().trim();
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
            alert(selectedDate);
            filterAppointmentsByDate(selectedDate);
        });

    });
</script>