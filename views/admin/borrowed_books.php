<?php
require_once('../layouts/header.php');
require_once __DIR__ . '/../../models/Borrowed_books.php';

$Borrowed_BooksModel = new Borrowed_Books();
$borrowedBooks = $Borrowed_BooksModel->updateBookStatus();
$member_dropdown = $Borrowed_BooksModel->member_dropdown();
$book_dropdown = $Borrowed_BooksModel->book_dropdown();

if ($role == 'admin') {
    $borro_books = $Borrowed_BooksModel->getAllWithBookAndMember();
} else {
    $borro_books = $Borrowed_BooksModel->getAllWithBookAndMemberByUserId($userId);
}

?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Borrowed-Books
        <!-- Button trigger modal -->
        <?php if ($role == 'admin') : ?>
            <button type="button" class="btn btn-dark float-end" data-bs-toggle="modal" data-bs-target="#add_borrowed_books">
                <i class="bx bx-plus-medical "></i>
            </button>
        <?php endif; ?>
    </h4>

    <div class="card mb-5">
        <div class="row m-3">
            <div class="col-6">
                <div class="d-flex align-items-center m-3">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Search Member ID  " aria-label="Search..." />
                </div>
            </div>
            <div class="col-4">
                <div class="form-group my-3">
                    <input type="date" id="datePicker" class="form-control" />
                </div>
            </div>
            <div class="col-2">
                <div class="form-group my-3">
                    <button class="btn btn-primary d-inline" id="clear">Clear</button>
                </div>
            </div>
        </div>
        <hr>
        <!-- /.card-header -->
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <?php if ($role == 'admin') : ?>
                            <th></th>
                        <?php endif; ?>
                        <th class="text-nowrap">Member Id</th>
                        <th class="text-nowrap">Member Name</th>
                        <th class="text-nowrap">Book Id</th>
                        <th class="text-nowrap">Book Name</th>
                        <th class="text-nowrap">Book Status</th>
                        <th class="text-nowrap">Borrowed At</th>
                        <th class="text-nowrap">Due Date</th>
                        <th class="text-nowrap">Returned At</th>
                        <th class="text-nowrap">Fine</th>
                        <th class="text-nowrap">Fine Status</th>
                        <th class="text-nowrap">Paid Date</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($borro_books)) {
                        foreach ($borro_books as $c) {
                    ?>
                            <tr>
                                <?php if ($role == 'admin') : ?>
                                    <td>
                                        <div>
                                            <a class="btn btn-sm btn-outline-dark m-2" href="<?= url('views/admin/edit_borrowed.php?id=' . $c['id'] ?? '') ?>"><i class="bx bx-edit btn-outline-dark"></i></a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <td class="text-nowrap"> <?= $c['user_id'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['member_name'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['book_id'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['book_name'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['book_status'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['borrowed_at'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['due_date'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['returned_at'] ?? ""; ?> </td>
                                <td class="text-nowrap"> <?= $c['fine'] ?? 0; ?> </td>
                                <td class="text-nowrap"> <?= $c['fine_status'] ?? ''; ?> </td>
                                <td class="text-nowrap"> <?= $c['paid_date'] ?? ''; ?> </td>

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

</div>
<!-- add borrowed books -->
<div class="modal fade" id="add_borrowed_books" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="create-form" action="<?= url('services/ajax_functions.php') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Add Borrowed Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_borrowed">
                    <div class="row">
                     

                        <div class=" mb-3 col-12">
                            <label for="member_id" class="form-label">Member Id:</label>
                            <select class="form-select" id="member_id" aria-label="Default select example" name="member_id" required>
                            <option class=" text-dark "> </option>
                            <?php
                    if (isset($member_dropdown)) {
                        foreach ($member_dropdown as $m) {
                    ?>
                            <option value="<?= $m['id'] ?? ""; ?>" class=" text-dark ">ID: <?= $m['id'] ?? '' ;?> ||   Name--><?= $m['username'] ?? ''?>  </option>
                            <?php
                        }
                    }
                    ?>           
                            </select>
                        </div>
                        <div class=" mb-3 col-12">
                            <label for="book_id" class="form-label">Book Id:</label>
                            <select class="form-select" id="book_id" aria-label="Default select example" name="book_id" required>
                            <option class=" text-dark "> </option>
                            <?php
                    if (isset($book_dropdown)) {
                        foreach ($book_dropdown as $b) {
                    ?>
                            <option value="<?= $b['id'] ?? ""; ?>" class=" text-dark ">ID: <?= $b['id'] ?? '' ;?> ||  Title--><?= $b['title'] ?? ''?>  </option>
                            <?php
                        }
                    }
                    ?>           
                            </select>
                        </div>
                        <div class=" mb-3 col-6">
                            <label for="book_status" class="form-label">Book Status:</label>
                            <select class="form-select" id="book_status" aria-label="Default select example" name="book_status" required>
                                <option value="borrowed" class=" text-dark ">Borrowed</option>
                                <option value="returned" class=" text-dark ">Returned</option>
                                <option value="due_time_over" class=" text-dark ">Due Time Over</option>
                            </select>
                        </div>

                        <div class="mb-3 col-6">
                            <label for="html5-datetime-local-input" class="form-label">Borrowed At:</label><br>
                            <input class="form-control" type="date" name="borrowed_at" />
                        </div>
                        <div class="mb-3 col-6">
                            <label for="html5-datetime-local-input" class="form-label">Due Date:</label><br>
                            <input class="form-control" type="date" name="due_date" />
                        </div>

                        <div class="mb-3 col-6">
                            <label for="html5-datetime-local-input" class="form-label">Returned At:</label><br>
                            <input class="form-control" type="date" name="returned_at" />
                        </div>

                        <div class="mb-3 mt-3">
                            <div id="additional-fields">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <div id="alert-container"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-dark" id="create">Create</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php require_once('../layouts/footer.php'); ?>
<script src="<?= asset('assets/forms-js/borrowed.js') ?>"></script>

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

        // Function to format date as YYYY-MM-DD
        function getFormattedDate(date) {
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

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