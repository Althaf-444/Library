<?php

require_once 'BaseModel.php';

class Borrowed_Books extends BaseModel
{
    public $id;
    public $user_id;
    public $book_id;
    public $book_status;
    public $borrowed_at;
    public $due_date;
    public $returned_at;
    public $fine;
    public $fine_status;
    public $paid_date;


    protected function getTableName()
    {
        return "borrowedbooks";
    }

    public function getById($id)
    {
        $param = array(':id' => $id);
        return $this->pm->run(
            "SELECT *, 
            m.username AS member_name, 
            b.title AS book_name, 
            bb.id AS id 
     FROM borrowedbooks AS bb
     JOIN members AS m ON m.id = bb.user_id
     JOIN books AS b ON b.id = bb.book_id
     WHERE bb.id = :id;",
            $param,
            true
        );
    }


    protected function addNewRec()
    {
        $params = array(
            ':user_id' => $this->user_id,
            ':book_id' => $this->book_id,
            ':book_status' => $this->book_status,
            ':borrowed_at' => $this->borrowed_at,
            ':returned_at' => $this->returned_at,
            ':due_date' => $this->due_date,

        );

        $result = $this->pm->run("INSERT INTO borrowedbooks(user_id, book_id, book_status, borrowed_at, due_date,returned_at) 
        VALUES(:user_id, :book_id, :book_status, :borrowed_at, :due_date,:returned_at)", $params);

        // Check the result and return success or failure accordingly
        return $result;
    }

    protected function updateRec()
    {
        $params = array(
            ':returned_at' => $this->returned_at,
            ':fine_status' => $this->fine_status,
            ':paid_date' => $this->paid_date,
            ':id' => $this->id
        );

        return $this->pm->run(
            "UPDATE borrowedbooks
            SET 
            returned_at = :returned_at,
            fine_status = :fine_status,
            paid_date = :paid_date
            WHERE id = :id",
            $params
        );
    }

    public function updateBookStatus()
    {
        $query = "UPDATE borrowedbooks
        SET 
            book_status = 
                CASE 
                    WHEN returned_at IS NOT NULL AND returned_at != '0000-00-00 00:00:00' AND returned_at > due_date THEN 'returned'
                    WHEN returned_at IS NOT NULL AND returned_at != '0000-00-00 00:00:00' AND returned_at <= due_date THEN 'returned'
                    WHEN due_date < CURDATE() AND (returned_at IS NULL OR returned_at = '0000-00-00 00:00:00') THEN 'due time over'
                    WHEN due_date > CURDATE() AND (returned_at IS NULL OR returned_at = '0000-00-00 00:00:00') THEN 'borrowed'
                    ELSE book_status
                END,
            fine = 
                CASE 
                    WHEN returned_at IS NOT NULL AND returned_at != '0000-00-00 00:00:00' AND returned_at > due_date THEN DATEDIFF(returned_at, due_date) * 20
                    ELSE fine
                END
        WHERE book_status IN ('borrowed', 'due time over');
        
        ";

        $this->pm->run($query);
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
    // add borrowed book
    public function add_borrowed_book($member_id,  $book_id, $book_status, $borrowed_at, $due_date, $returned_at)
    {


        $Borrowed_Books = new Borrowed_Books();
        $Borrowed_Books->user_id = $member_id;
        $Borrowed_Books->book_id = $book_id;
        $Borrowed_Books->book_status = $book_status;
        $Borrowed_Books->borrowed_at = $borrowed_at;
        $Borrowed_Books->due_date = $due_date;
        $Borrowed_Books->returned_at = $returned_at;
        $Borrowed_Books->addNewRec();

        if ($Borrowed_Books) {
            return $Borrowed_Books; // Borrowed_books created successfully
        } else {
            return false; // Borrowed_books creation failed (likely due to database error)
        }
    }
    // update borrowed book
    public function update_borrowed_book($id, $user_id, $book_id, $book_status, $borrowed_at,  $due_date, $returned_at)
    {


        $Borrowed_Books = new Borrowed_Books();
        $Borrowed_Books->id = $id;
        $Borrowed_Books->user_id = $user_id;
        $Borrowed_Books->book_id = $book_id;
        $Borrowed_Books->book_status = $book_status;
        $Borrowed_Books->borrowed_at = $borrowed_at;
        $Borrowed_Books->due_date = $due_date;
        $Borrowed_Books->returned_at = $returned_at;
        $Borrowed_Books->updateRec();

        if ($Borrowed_Books) {
            return $Borrowed_Books; // Borrowed_books update successfully
        } else {
            return false; // Borrowed_books creation failed (likely due to database error)
        }
    }

    // book quantity borrowed
    public function borrowBook($bookId)
    {
        // Join books and borrowedbooks to check availability and borrow details
        $query = "SELECT b.id AS book_id, b.quantity, bb.id AS borrowed_id
        FROM books AS b
        LEFT JOIN borrowedbooks AS bb ON b.id = bb.book_id
        WHERE b.id = :bookId";

        $book = $this->pm->run($query, [':bookId' => $bookId], true);

        if (!$book || $book['quantity'] <= 0) {
            return "Book All Borrowed";
        }

        // Decrease the book quantity
        $this->pm->run("UPDATE books SET quantity = quantity - 1 WHERE id = :bookId", [':bookId' => $bookId]);



        return "Book borrowed successfully!";
    }
    // book quantity returned
    public function returnBook($borrowedBookId)
    {
        // Fetch the book_id and current book_status for the borrowed book
        $borrowedBook = $this->pm->run(
            "SELECT book_id
         FROM borrowedbooks 
         WHERE id = :id",
            [':id' => $borrowedBookId],
            true
        );

        if (!$borrowedBook) {
            throw new Exception("Borrowed book not found.");
        }



        // Increase the quantity in the books table
        $this->pm->run(
            "UPDATE books 
         SET quantity = quantity + 1 
         WHERE id = :bookId",
            [':bookId' => $borrowedBook['book_id']]
        );

        return "Book quantity updated successfully!";
    }
    // move user id & user name & fine & fine status fine section
    public function finetotal()
    {
        return $this->pm->run("SELECT user_id ,fine, fine_status, bb.id, paid_date , m.username as member_name
        FROM borrowedbooks as bb JOIN members as m ON bb.user_id = m.id WHERE fine > 0
       ;");
    }
   //paid fine
    public function paidfine()
    {
        return $this->pm->run("SELECT user_id ,fine, fine_status, paid_date , m.username as member_name
        FROM borrowedbooks as bb JOIN members as m ON bb.user_id = m.id WHERE fine > 0 and fine_status = 'paid'
       ;");
    }
   //    pending fine
   public function pendingfine()
   {
       return $this->pm->run("SELECT user_id ,fine, fine_status, paid_date , m.username as member_name
       FROM borrowedbooks as bb JOIN members as m ON bb.user_id = m.id WHERE fine > 0 and fine_status = 'pending'
      ;");
   }
// member dropdown
 public function  member_dropdown()
 {
     return $this->pm->run("SELECT id ,username 
     FROM members 
    ;");
 }
 // book dropdown
 public function  book_dropdown()
 {
     return $this->pm->run("SELECT id ,title
     FROM books 
    ;");
 }
}


