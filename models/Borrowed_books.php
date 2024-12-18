<?php

require_once 'BaseModel.php';

class Borrowed_Books extends BaseModel
{
    public $user_id;
    public $book_id;
    public $book_status;
    public $borrowed_at;
    public $due_date;
    public $returned_at;
    public $fine;
    

    protected function getTableName()
    {
        return "borrowedbooks";
    }

    public function getById($id)
    {
        $param = array(':id' => $id);
        return $this->pm->run(
            "SELECT *, 
            m.name AS member_name, 
            b.title AS book_name, 
            bb.id AS id 
     FROM borrowedbooks AS bb
     JOIN members AS m ON u.id = bb.user_id
     JOIN books AS b ON b.id = bb.book_id
     WHERE bb.id = :id;",
            $param,
            true
        );
    }

    // Method to retrieve a record by its ID or appointment_no from the associated table
    public function getByIdOrAppointmentNo($id, $appointmentNo)
    {
        // Check if either $id or $appointmentNo is provided
        if (!empty($id)) {
            $condition = "id = :id";
            $param = array(':id' => $id);
        } elseif (!empty($appointmentNo)) {
            $condition = "appointment_no = :appointment_no";
            $param = array(':appointment_no' => $appointmentNo);
        } else {
            // Both $id and $appointmentNo are empty, return null or handle it accordingly
            return null;
        }

        // Build and execute the SQL query
        return $this->pm->run("SELECT * FROM " . $this->getTableName() . " WHERE $condition", $param, true);
    }

    // Method to retrieve a record by both ID and appointment_no from the associated table
    public function getByIdAndAppointmentNo($id, $appointmentNo)
    {
        // Check if both $id and $appointmentNo are provided
        if (!empty($id) && !empty($appointmentNo)) {
            $condition = "id = :id AND appointment_no = :appointment_no";
            $param = array(':id' => $id, ':appointment_no' => $appointmentNo);
        } else {
            // Either $id or $appointmentNo is missing, return null or handle it accordingly
            return null;
        }

        // Build and execute the SQL query
        return $this->pm->run("SELECT * FROM " . $this->getTableName() . " WHERE $condition", $param, true);
    }

    protected function addNewRec()
    {
        $params = array(
            ':user_id' => $this->user_id,
            ':book_id' => $this->book_id,
            ':book_status' => $this->book_status,
            ':borrowed_at' => $this->borrowed_at,
            ':due_date' => $this->due_date,
           
        );

        $result = $this->pm->insertAndGetLastRowId("INSERT INTO borrowedbooks(user_id, book_id, book_status, borrowed_at, due_date) 
        VALUES(:user_id, :book_id, :book_status, :borrowed_at, :due_date)", $params);

        // Check the result and return success or failure accordingly
        return $result;
    }

    protected function updateRec()
    {
        $params = array(
            ':book_status' => $this->book_status,
            ':id' => $this->id
        );

        return $this->pm->run(
            "UPDATE borrowedbooks
            SET 
            book_status = :book_status, 
            WHERE id = :id",
            $params
        );
    }

    public function getAllWithBookAndMember()
    {
        return $this->pm->run("SELECT bb.*, m.username AS member_name, b.title AS book_name 
                               FROM borrowedbooks AS bb
                               INNER JOIN members AS m ON bb.user_id = m.id
                               INNER JOIN books AS b ON bb.book_id = b.id
                               ORDER BY bb.id DESC");
    }
    

    public function getAllWithBookAndMemberByUserId($user_id)
{
    $param = array(':user_id' => $user_id);
    return $this->pm->run(
        "SELECT bb.*, m.username AS member_name, b.title AS book_name 
         FROM borrowedbooks AS bb
         INNER JOIN members AS m ON bb.user_id = m.id
         INNER JOIN books AS b ON bb.book_id = b.id
         WHERE bb.user_id = :user_id
         ORDER BY bb.id DESC",
        $param
    );
} 

public function getMembersByMembersnameOrmember_id($mamber_name, $member_id)
    {
        $param = array(
            ':member_name' => $mamber_name,
            ':member_id' => $member_id
        );

        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE username = :member_name OR user_id = :member_id";
        $result = $this->pm->run($sql, $param);

       
    }




function add_borrowed_book($member_id,  $book_id,  $book_status, $borrowed_at, $due_date, $returned_at,$fine )
{
    

    $Borrowed_Books = new Borrowed_Books();
    $Borrowed_Books->user_id = $member_id;
    $Borrowed_Books->book_id = $book_id;
    $Borrowed_Books->book_status = $book_status;
    $Borrowed_Books->borrowed_at = $borrowed_at;
    $Borrowed_Books->due_date = $due_date;
    $Borrowed_Books->returned_at = $returned_at;
    $Borrowed_Books->fine = $fine;
    $Borrowed_Books->addNewRec();

    if ($Borrowed_Books) {
        return $Borrowed_Books; // Borrowed_books created successfully
    } else {
        return false; // Borrowed_books creation failed (likely due to database error)
    }
}

}
