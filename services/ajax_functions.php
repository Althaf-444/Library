<?php
require_once '../config.php';
require_once '../helpers/AppManager.php';
require_once '../models/Members.php';
require_once '../models/Books.php';
require_once '../models/Borrowed_books.php';
require_once '../models/Payment.php';
require_once '../models/Treatment.php';


// Define target directory
$target_dir = "../assets/uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_treatment') {
    echo json_encode(['success' => false, 'message' => "Test"]);
    exit;
}

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

        $image = $_FILES["image"] ?? null;
        $photo = null;

        $target_dir = "../../assets/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (isset($image) && !empty($image)) {
            if ($image["error"] > 0) {
                echo json_encode(['success' => false, 'message' => "Error uploading file: " . $image["error"]]);
                exit;
            }

            if (getimagesize($image["tmp_name"]) !== false) {
                $extension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extension, $allowed_extensions)) {
                    echo json_encode(['success' => false, 'message' => "Invalid file type. Allowed types: " . implode(', ', $allowed_extensions)]);
                    exit;
                }

                if ($image["size"] < 500000) {
                    $new_filename = uniqid('', true) . '.' . $extension;

                    if (move_uploaded_file($image["tmp_name"], $target_dir . $new_filename)) {
                        $photo = $new_filename;
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

        // If no new photo, use the existing one (if applicable)
        $photo = $photo ?? ($_POST['current_image'] ?? null);


        // Validate inputs
        if (empty($title) || empty($author) || empty($category) || empty($isbn) || empty($quantity)) {
            echo json_encode(['success' => false, 'message' => 'Required fields are missing!']);
            exit;
        }


        $bookModel = new Books();
        $updated =  $bookModel->updateBooks($id, $title, $author, $category, $isbn, $quantity, $added_at, $photo);
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
        $borrowed_at = $_POST['borrowed_at'];
        $due_date = $_POST['due_date'];
        $returned_at = $_POST['returned_at'];
        $fine = $_POST['fine'];

    
        // Call the model to create the book
        $Borrowed_BooksModel = new Borrowed_Books();
        $created = $Borrowed_BooksModel->add_borrowed_book($member_id, $book_id, $book_status, $borrowed_at, $due_date, $returned_at,$fine);

        if ($created) {
            echo json_encode(['success' => true, 'message' => "borrowed books created successfully!"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create member. Member may already exist!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

//payment-save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'payment-save') {

    try {

        $payment_id = $_POST['payment_id'] ?? null;
        $treatment_fee_paid = $_POST['treatment_fee_paid'] ? 1 : 0;
        $quantity = $_POST['quantity'] ?? 1;

        $payment = new Payment();
        $paymentData = $payment->getById($payment_id);

        if (isset($paymentData)) {
            $payment->id = $payment_id;
            $payment->treatment_fee_paid = $treatment_fee_paid ?? 0;
            $payment->quantity = $quantity ?? 0;
            $udpated = $payment->save();

            // Response to send back
            echo json_encode(['success' => true, 'message' => 'Payment udpated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Payment have an error!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

//update appointment
// if (
//     $_SERVER['REQUEST_METHOD'] === 'POST'
//     && isset($_POST['action'])
//     && $_POST['action'] === 'appointment-update'
// ) {
//     try {
//         $appointment_id = $_POST['appointment_id'] ?? null;
//         $patient_name = $_POST['patient_name'] ?? "";
//         $address = $_POST['address'] ?? "";
//         $telephone = $_POST['telephone'] ?? "";
//         $email = $_POST['email'] ?? "";
//         $nic = $_POST['nic'] ?? "";

//         $appointment = new Appointment();
//         $appointmentData = $appointment->getById($appointment_id);

//         if (!empty($appointmentData)) {
//             $appointment->id = $appointment_id;
//             $appointment->patient_name = $patient_name;
//             $appointment->address = $address;
//             $appointment->telephone = $telephone;
//             $appointment->address = $address;
//             $appointment->email = $email;
//             $appointment->nic = $nic;
//             $appointment->save();


//             // Response to send back
//             echo json_encode(['success' => true, 'message' => 'Appointment udpated successfully']);
//         } else {
//             echo json_encode(['success' => false, 'message' => 'Appointment have an error!']);
//         }
//     } catch (PDOException $e) {
//         // Handle database connection errors
//         echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
//     }
//     exit;
// }

dd('Access denied..!');
