<?php
require_once 'BaseModel.php';

class Payment extends BaseModel
{
    public $user_id;
    public $total_fine;
    public $fine_status;
    public $created_at;
    public $updated_at;

    protected function getTableName()
    {
        return "payments";
    }

    protected function addNewRec()
    {
        $params = array(
            ':user_id' => $this->user_id,
            ':fine_status' => $this->fine_status,
            ':updated_at' => $this->updated_at,

        );

        $result = $this->pm->run(
            "INSERT INTO 
                payments(
                    user_id,
                    fine_status, 
                    updated_at
                )
            VALUES(
                :user_id, 
                :fine_status, 
                :updated_at
                )",
            $params
        );

        // Check the result and return success or failure accordingly
        return $result ? true : false;
    }

    protected function updateRec()
    {
        $params = array(
            ':fine_status' => $this->fine_status,
            ':updated_at' => $this->updated_at,
            ':id' => $this->id
        );

        $result = $this->pm->run(
            "UPDATE 
            payments 
            SET 
                fine_status = :fine_status, 
                updated_at = :updated_at
            WHERE id = :id",
            $params
        );

        // Check the result and return success or failure accordingly
        return $result ? true : false;
    }

    public function getAllWithTreatmentAndAppointment()
    {
        return $this->pm->run("SELECT pmt.*, tmt.name AS treatment_name,tmt.id AS treatment_id, apt.appointment_no AS appointment_no FROM payments AS pmt INNER JOIN appointments AS apt ON apt.id = pmt.appointment_id INNER JOIN treatments AS tmt ON tmt.id = apt.treatment_id");
    }
    public function getalluseridandtotalfine()
    {
        return $this->pm->run("SELECT 
        pay.*, 
        CASE 
            WHEN pay.fine_status = 'paid' THEN 0.00
            ELSE SUM(bb.fine)
        END AS total_fine 
    FROM 
        payments AS pay
    INNER JOIN 
        borrowedbooks AS bb ON pay.user_id = bb.user_id
    GROUP BY 
        pay.id
    ORDER BY 
        pay.id DESC;
    
       ");
    }
    public function markFineAsPaid($userId) {
        // Update the payments table to set fine_status as 'paid' and total_fine to 0.00
        $query = "
            UPDATE payments 
            SET fine_status = 'paid', total_fine = 0.00 
            WHERE user_id = :userId
        ";
    
        $this->pm->run($query, [':userId' => $userId]);
        return "Fine marked as paid for user ID: $userId.";
    }
    
    public function getpaymentById($id)
    {
        $param = array(':id' => $id);
        return $this->pm->run("SELECT * FROM " . $this->getTableName() . " WHERE id = :id", $param, true);
    }
    function updatefine_status($user_id,  $fine_status, $updated_at)
    {
        $paymentModel = new Payment();
        $paymentModel->user_id = $user_id;
        $paymentModel->fine_status = $fine_status;
        $paymentModel->updated_at = $updated_at;

        $paymentModel->addNewRec();

        if ($paymentModel) {
            return true; // payment save successfully
        } else {
            return false; // payment save failed (likely due to database error)
        }
    }

}
