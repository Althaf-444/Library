<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../helpers/AppManager.php';


$pm = AppManager::getPM();
$sm = AppManager::getSM();

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if (empty($email) || empty($password) || empty($username)) {
    $sm->setAttribute("error", 'Please fill all required fields!');
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
 else {
    $param = array(':username' => $username,':email' => $email,':password' => $password);
    $add_member= $pm->insertAndGetLastRowId("INSERT INTO members (username, email, password) VALUES (:username, :email, :password)", $param, true);
    if($add_member){
            $sm->setAttribute("userId",$add_member); //$add_member return the insert data last id
            $sm->setAttribute("username", $username);
            $sm->setAttribute("role", 'member');// //  default role is 'member'

            header('location: ../index.php');
             exit;
    }  
    else {
        $sm->setAttribute("error", 'Invalid username or password!');
    }
    
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit;
