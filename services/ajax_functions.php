<?php
require_once '../config.php';
require_once '../helpers/AppManager.php';
require_once '../models/Members.php';
require_once '../models/Books.php';
require_once '../models/Borrowed_books.php';
require_once '../models/Payment.php';




//create Members
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_member') {
    try {
        // Retrieve and validate form data
        $username = trim($_POST['member_name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        $created_at = date('created_at');

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }


        // Call the model to create the book
        $memberModel = new Members();
        $created = $memberModel->createMembers($username, $password, $role, $email, $created_at);

        if ($created) {
            echo json_encode(['success' => true, 'message' => "Member created successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create member. Member may already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
//Get member by id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id']) && isset($_GET['action']) &&  $_GET['action'] == 'get_user') {

    try {
        $user_id = $_GET['user_id'];
        $userModel = new Members();
        $user = $userModel->getMembersById($user_id);
        if ($user) {
            echo json_encode(['success' => true, 'message' => "Member created successfully!", 'data' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create user. May be user already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

//Delete by Members id
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'delete_user') {
    try {
        $user_id = $_GET['user_id'];

        $memberModel = new Members();
        // Proceed to delete the Members if doctor deletion was successful or not needed
        $userDeleted = $memberModel->deleteMembers($user_id);

        if ($userDeleted) {
            echo json_encode(['success' => true, 'message' => 'Members deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Members.']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

//update member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {

    try {
        $username = $_POST['user_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? "";
        $cpassword = $_POST['confirm_password'] ?? "";
        $role = $_POST['role'] ?? 'member';
        $created_at = date('created_at');
        $id = $_POST['id'];

        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($cpassword)) {
            echo json_encode(['success' => false, 'message' => 'Required fields are missing!']);
            exit;
        }

        // Validate inputs
        if (($password) != $cpassword) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match..!']);
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email address']);
            exit;
        }

        $userModel = new Members();
        $updated =  $userModel->updateMembers($id, $username, $password, $role, $email, $created_at);
        if ($updated) {
            echo json_encode(['success' => true, 'message' => "Member updated successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update user. May be user already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
// ************************
// create book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_book') {
    try {
        // Retrieve and validate form data
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $category = trim($_POST['category']);
        $isbn = trim($_POST['isbn']);
        $quantity = intval($_POST['quantity']);
        $added_at = date('Y-m-d H:i:s');
        // Get file information
        $image = $_FILES["image"] ?? null;
        $imageFileName = null;

        // Check if file is uploaded
        if (isset($image) && !empty($image)) {
            // Check if there are errors
            if ($image["error"] > 0) {
                echo json_encode(['success' => false, 'message' => "Error uploading file: " . $image["error"]]);
                exit;
            } else {
                // Check if file is an image
                if (getimagesize($image["tmp_name"]) !== false) {
                    // Check file size (optional)
                    if ($image["size"] < 500000) { // 500kb limit
                        // Generate unique filename
                        $new_filename = uniqid() . "." . pathinfo($image["name"])["extension"];

                        // Move uploaded file to target directory
                        if (move_uploaded_file($image["tmp_name"], $target_dir . $new_filename)) {
                            $imageFileName = $new_filename;
                        } else {
                            echo json_encode(['success' => false, 'message' => "Error moving uploaded file."]);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => "File size is too large."]);
                        exit;
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => "Uploaded file is not an image."]);
                    exit;
                }
            }
        }


        // Call the model to create the book with the file name
        $bookModel = new Books();
        $created = $bookModel->createBooks($title, $author, $category, $isbn, $quantity, $added_at,  $imageFileName);

        if ($created) {
            echo json_encode(['success' => true, 'message' => "Book created successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create book. Book may already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
//Get book by id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['book_id']) && isset($_GET['action']) &&  $_GET['action'] == 'get_book') {

    try {
        $book_id = $_GET['book_id'];
        $bookModel = new Books();
        $book = $bookModel->getBooksById($book_id);
        if ($book) {
            echo json_encode(['success' => true, 'message' => "Book update successfully!", 'data' => $book]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create user. May be user already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
//update book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_book') {

    try {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $category = trim($_POST['category']);
        $isbn = trim($_POST['isbn']);
        $quantity = intval($_POST['quantity']);
        $added_at = date('Y-m-d H:i:s'); // Current timestamp
        $id = $_POST['id'];

       

        // Validate inputs
        if (empty($title) || empty($author) || empty($category) || empty($isbn) || empty($quantity)) {
            echo json_encode(['success' => false, 'message' => 'Required fields are missing!']);
            exit;
        }


        $bookModel = new Books();
        $updated =  $bookModel->updateBooks($id, $title, $author, $category, $isbn, $quantity, $added_at);
        if ($updated) {
            echo json_encode(['success' => true, 'message' => "Book updated successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update Book. May be Book already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
// delete book
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['book_id']) && isset($_GET['action']) && $_GET['action'] == 'delete_book') {
    try {
        $book_id = $_GET['book_id'];

        $bookModel = new Books();

        $bookDeleted = $bookModel->deleteBooksById($book_id);

        if ($bookDeleted) {
            echo json_encode(['success' => true, 'message' => 'Book deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete Book.']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
// **********************************
// **********************************
// add borrowed books
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_borrowed') {
    try {
        // Retrieve and validate form data
        $member_id = trim($_POST['member_id']);
        $book_id = $_POST['book_id'];
        $book_status = $_POST['book_status'];
        $borrowed_at = $_POST['borrowed_at'] ?? date('Y-m-d H:i:s');
        $due_date = $_POST['due_date'] ?? date('Y-m-d H:i:s', strtotime('+30 days'));
        $returned_at = $_POST['returned_at'] ?? null;


        // Call the model to create the book
        $Borrowed_BooksModel = new Borrowed_Books();
        $message = $Borrowed_BooksModel->borrowBook($book_id);
        if (($message == "Book All Borrowed")) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            $created = $Borrowed_BooksModel->add_borrowed_book($member_id, $book_id, $book_status, $borrowed_at, $due_date, $returned_at);

            if ($created) {

                echo json_encode(['success' => true, 'message' => 'Book Borrowed Success...']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create member. Member may already exist!']);
            }
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    exit;
}
//update borrowed books
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'Borrowed_Books_update') {
    try {
        $id = $_POST['id'] ?? "";
        $returned_at = $_POST['returned_at'] ?? date('Y-m-d H:i:s'); // Default to now if not provided
        $fine_status = $_POST['fine_status'] ?? "";
        $Borrowed_BooksModel = new Borrowed_Books();

        // Fetch the borrowed book data
        $borrowedBookData = $Borrowed_BooksModel->getById($id);
        if (!empty($borrowedBookData)) {
            // Update the returned_at field
            $Borrowed_BooksModel->id = $id;
            $Borrowed_BooksModel->returned_at = $returned_at;
            $Borrowed_BooksModel->fine_status = $fine_status;
            $Borrowed_BooksModel->save();

            // Handle the return logic (e.g., updating book quantity and status)
            $Borrowed_BooksModel->returnBook($id);

            echo json_encode(['success' => true, 'message' => "Borrowed book updated successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update borrowed book. Record may not exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}




// **********************************
// **********************************
// update payment status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_payment') {
    try {
        // Retrieve and validate form data
        $user_id = ($_POST['user_id']);
        $fine_status = ($_POST['fine_status']);
        $updated_at = ($_POST['updated_at']);


        // Call the model to create the book with the file name
        $paymentmodal = new Payment();
        $created = $paymentmodal->updatefine_status( $user_id,  $fine_status, $updated_at );
        $paymentmodal->markFineAsPaid($user_id);

        if ($created) {
            echo json_encode(['success' => true, 'message' => "payment status edit successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to payment status edit']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}


dd('Access denied..!');
