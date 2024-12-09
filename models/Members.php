<?php

require_once 'BaseModel.php';

class Members extends BaseModel
{
    public $username;
    public $role;
    private $email;
    private $password;
    private $created_at;

    function getTableName()
    {
        return 'members';
    }

    protected function addNewRec()
    {
        // Hash the password before storing it
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $param = array(
            ':username' => $this->username,
            ':password' => $this->password,
            ':role' => $this->role,
            ':email' => $this->email,
            ':created_at' => $this->created_at
        );

        return $this->pm->run("INSERT INTO " . $this->getTableName() . "(username, password,role,email,created_at) values(:username, :password,:role,:email,:created_at)", $param);
    }

    protected function updateRec()
    {
        // Check if the new username or email already exists (excluding the current member's record)
        $existingMembers = $this->getMembersByMembersnameOrEmailWithId($this->username, $this->email, $this->id);
        if ($existingMembers) {
            // Handle the error (return an appropriate message or throw an exception)
            return false; // Or throw an exception with a specific error message
        }

        // Hash the password if it is being updated
        if (!empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $param = array(
            ':username' => $this->username,
            ':password' => $this->password,
            ':role' => $this->role,
            ':email' => $this->email,
            ':created_at' => $this->created_at,
            ':id' => $this->id
        );
        return $this->pm->run(
            "UPDATE " . $this->getTableName() . " 
            SET 
                username = :username, 
                password = :password,
                role = :role,  
                email = :email,
                created_at = :created_at
            WHERE id = :id",
            $param
        );
    }

    public function getMembersByMembersnameOrEmailWithId($username, $email, $excludeMembersId = null)
    {
        $param = array(':username' => $username, ':email' => $email);

        $query = "SELECT * FROM " . $this->getTableName() . " 
                  WHERE (username = :username OR email = :email)";

        if ($excludeMembersId !== null) {
            $query .= " AND id != :excludeMembersId";
            $param[':excludeMembersId'] = $excludeMembersId;
        }

        $result = $this->pm->run($query, $param);

        return $result; // Return the member if found, or false if not found
    }

    public function getMembersByMembersnameOrEmail($username, $email)
    {
        $param = array(
            ':username' => $username,
            ':email' => $email
        );

        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE username = :username OR email = :email";
        $result = $this->pm->run($sql, $param);

        if (!empty($result)) {  // Check if the array is not empty
            $member = $result[0]; // Assuming the first row contains the member data
            return $member;
        } else {
            return null;
        }
    }


    function createMembers($username, $password, $role, $email, $created_at )
    {
        $memberModel = new Members();

        // Check if username or email already exists
        $existingMembers = $memberModel->getMembersByMembersnameOrEmail($username, $email);
        if ($existingMembers) {
            // Handle the error (return an appropriate message or throw an exception)
            return false; // Or throw an exception with a specific error message
        }

        $member = new Members();
        $member->username = $username;
        $member->password = $password;
        $member->role = $role;
        $member->email = $email;
        $member->created_at = $created_at;
        $member->addNewRec();

        if ($member) {
            return $member; // Members created successfully
        } else {
            return false; // Members creation failed (likely due to database error)
        }
    }

    function updateMembers($id, $username, $password, $role, $email, $created_at )
    {
        $memberModel = new Members();

        // Check if username or email already exists
        $existingMembers = $memberModel->getMembersByMembersnameOrEmailWithId($username, $email, $id);
        if ($existingMembers) {
            // Handle the error (return an appropriate message or throw an exception)
            return false; // Or throw an exception with a specific error message
        }

        $member = new Members();
        $member->id = $id;
        $member->username = $username;
        $member->password = $password;
        $member->role = $role;
        $member->email = $email;
        $member->created_at = $created_at;
        $member->updateRec();

        if ($member) {
            return true; // Members udapted successfully
        } else {
            return false; // Members update failed (likely due to database error)
        }
    }

    function deleteMembers($id)
    {
        $member = new Members();
        $member->deleteRec($id);

        if ($member) {
            return true; // Members udapted successfully
        } else {
            return false; // Members update failed (likely due to database error)
        }
    }

    public function getLastInsertedMembersId()
    {
        $result = $this->pm->run('SELECT MAX(id) as lastInsertedId FROM users', null, true);
        return $result['lastInsertedId'] ?? 100;
    }
    public function getMembersById($id)
    {
        $param = array(':id' => $id);
        return $this->pm->run("
            SELECT * FROM " . $this->getTableName() . " 
            WHERE id = :id
        ", $param, true);
    }
    
}

   

