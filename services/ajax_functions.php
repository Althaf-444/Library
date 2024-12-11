<?php
require_once '../config.php';
require_once '../helpers/AppManager.php';
require_once '../models/Members.php';
require_once '../models/Books.php';
require_once '../models/Appointment.php';
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

      
        // Call the model to create the member
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
//Get user by id
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

//update user
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




//book_appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_appointment') {

    try {
        $appointment = new Appointment();

        if (isset($_POST['id'])) {
            $appointment = $appointment->getById($_POST['id']);
        }

        $appointment->appointment_no = $_POST['appointment_no'] ?? '';
        $appointment->doctor_id = $_POST['doctor_id'] ?? null;
        $appointment->patient_name = $_POST['patient_name'] ?? null;
        $appointment->address = $_POST['address'] ?? null;
        $appointment->telephone = $_POST['telephone'] ?? null;
        $appointment->email = $_POST['email'] ?? null;
        $appointment->nic = $_POST['nic'] ?? null;
        $appointment->treatment_id = $_POST['treatment_id'] ?? null;
        $appointment->time_slot_from = $_POST['time_slot_from'] ?? null;
        $appointment->time_slot_to = $_POST['time_slot_to'] ?? null;
        $appointment->appointment_date = $_POST['appointment_date'] ?? null;

        $insertedId = $appointment->save();
        $treatment = new Treatment();
        $appointmentTreatment = $treatment->getById($appointment->treatment_id);

        if (isset($insertedId) && isset($appointmentTreatment)) {

            $payment = new Payment();
            $payment->appointment_id = $insertedId;
            $payment->registration_fee = $appointmentTreatment['registration_fee'] ?? 0;
            $payment->registration_fee_paid = 1;
            $payment->treatment_fee = $appointmentTreatment['treatment_fee'] ?? 0;
            $payment->quantity = 1;
            $payment->treatment_fee_paid = 0;
            $payment->save();

            // Response to send back
            echo json_encode(['success' => true, 'message' => 'Appointment booked successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Appointment booking have an error!']);
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
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'appointment-update'
) {
    try {
        $appointment_id = $_POST['appointment_id'] ?? null;
        $patient_name = $_POST['patient_name'] ?? "";
        $address = $_POST['address'] ?? "";
        $telephone = $_POST['telephone'] ?? "";
        $email = $_POST['email'] ?? "";
        $nic = $_POST['nic'] ?? "";

        $appointment = new Appointment();
        $appointmentData = $appointment->getById($appointment_id);

        if (!empty($appointmentData)) {
            $appointment->id = $appointment_id;
            $appointment->patient_name = $patient_name;
            $appointment->address = $address;
            $appointment->telephone = $telephone;
            $appointment->address = $address;
            $appointment->email = $email;
            $appointment->nic = $nic;
            $appointment->save();


            // Response to send back
            echo json_encode(['success' => true, 'message' => 'Appointment udpated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Appointment have an error!']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

dd('Access denied..!');
