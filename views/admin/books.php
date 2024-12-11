<?php
require_once('../layouts/header.php');
include BASE_PATH . '/models/Books.php';

$booksModel = new Books();
$data = $booksModel->getAll();

// if ($role != 'admin') dd('Access Denied...!');

?>

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Book-collection
        <!-- Button trigger modal -->
        <?php if ($role == 'admin') : ?>
            <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#modalCenter">
                Add New Books
            </button>
        <?php endif; ?>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                <?php if ($role == 'admin') : ?>
                    <th>Edit</th>
                    <?php endif; ?>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Isbn</th>
                    <th>Quantity</th>
                    <th>Added_at</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $key => $book) {
                ?>
                    <tr>
                    <?php if ($role == 'admin') : ?>
                        <td>
                            <div>
                                <a class="btn btn-sm  btn-outline-info m-2" data-bs-toggle="modal" data-bs-target="#edit-user-modal" data-id="<?= $book['id']; ?>"><i class="bx bx-edit"></i></a>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td>
                            <?php if (isset($book['photo']) || !empty($book['photo'])) : ?>
                                <img src="<?= $book['photo'] ?>" alt="user-avatar" class="d-block rounded m-3" width="80" id="uploadedAvatar">
                            <?php else : ?>
                                <img src="<?= asset('assets/img/avatars/1.png') ?>" alt="user-avatar" class="d-block rounded m-3" width="80" id="uploadedAvatar">
                            <?php endif; ?>
                        </td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?= $book['title'] ?? '' ?></strong></td>
                        <td><?= $book['author'] ?? '' ?></td>
                        <td><?= $book['category'] ?? '' ?></td>
                        <td><?= $book['isbn'] ?? '' ?></td>
                        <td><?= $book['quantity'] ?? '' ?></td>
                        <td><?= $book['added_at'] ?? '' ?></td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!--/ Basic Bootstrap Table -->

    <hr class="my-5" />


</div>

<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="create-form" action="<?= url('services/ajax_functions.php') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="create_user">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameWithTitle" class="form-label">User Name</label>
                            <input type="text" required id="nameWithTitle" name="user_name" class="form-control" placeholder="Enter Name" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="emailWithTitle" class="form-label">Email</label>
                            <input required type="text" name="email" id="emailWithTitle" class="form-control" placeholder="xxxx@xxx.xx" />
                        </div>
                    </div>


                    <div class="row gy-2">
                        <div class="col orm-password-toggle">
                            <label class="form-label" for="basic-default-password1">Password</label>
                            <div class="input-group">
                                <input type="password" required name="password" class="form-control" id="passwordInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="basic-default-password1" />
                                <span id="basic-default-password1" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="col form-password-toggle">
                            <label class="form-label" for="basic-default-password2">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" required name="confirm_password" class="form-control" id="confirmPasswordInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="basic-default-password2" />
                                <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Role</label>
                            <select class="form-select" id="permission" aria-label="Default select example" name="permission" required>
                                <option value="operator">Operator</option>
                                <option value="doctor">Doctor</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div id="alert-container"></div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div id="additional-fields">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="create">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Udpate Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="update-form" action="<?= url('services/ajax_functions.php') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" id="user_id" name="id" value="">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameWithTitle" class="form-label">User Name</label>
                            <input type="text" required id="user-name" name="user_name" class="form-control" placeholder="Enter Name" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="emailWithTitle" class="form-label">Email</label>
                            <input required type="text" name="email" id="email" class="form-control" placeholder="xxxx@xxx.xx" />
                        </div>
                    </div>


                    <div class="row gy-2">
                        <div class="col orm-password-toggle">
                            <label class="form-label" for="basic-default-password1">Password</label>
                            <div class="input-group">
                                <input type="password" required name="password" class="form-control" id="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="basic-default-password1" />
                                <span id="basic-default-password1" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="col form-password-toggle">
                            <label class="form-label" for="basic-default-password2">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" required name="confirm_password" class="form-control" id="confirm-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="basic-default-password2" />
                                <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" aria-label="Default select example" name="role" required>
                                <option value="admin">Operator</option>
                                <option value="doctor">Doctor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Status</label>
                            <select class="form-select" id="is_active" aria-label="Default select example" id="is_active" name="is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div id="edit-alert-container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="update-user">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once('../layouts/footer.php');
?>
<!-- <script src="<?= asset('assets/forms-js/users.js') ?>"></script> -->