<?php
require_once 'BaseModel.php';

class Books extends BaseModel
{
    public $title;
    public $author;
    public $category;
    public $isbn;
    public $quantity;
    public $added_at;
    public $photo;
   

    protected function getTableName()
    {
        return "books";
    }

    protected function addNewRec()
    {
        $param = array(
            ':title' => $this->title,
            ':author' => $this->author,
            ':category' => $this->category,
            ':isbn' => $this->isbn,
            ':quantity' => $this->quantity,
            ':added_at' => $this->added_at,
            ':photo' => $this->photo
        );

        return $this->pm->run("INSERT INTO books(title, author, category, isbn, quantity,added_at,photo) VALUES (:title, :author, :category, :isbn, :quantity,:added_at,:photo)", $param);
    }

    protected function updateRec()
    {
        $param = array(
            ':title' => $this->title,
            ':author' => $this->author,
            ':category' => $this->category,
            ':isbn' => $this->isbn,
            ':quantity' => $this->quantity,
            ':added_at' => $this->added_at,
            ':id' => $this->id
        );

        return $this->pm->run("UPDATE books SET title = :title, author = :author, category = :category, isbn = :isbn, quantity = :quantity,added_at = :added_at WHERE id = :id", $param);
    }


    function createBooks($title, $author, $category, $isbn , $quantity,$added_at,$photo )
    {
        $booksModel = new Books();
        $booksModel->title = $title;
        $booksModel->author = $author;
        $booksModel->category = $category;
        $booksModel->quantity = $quantity;
        $booksModel->isbn = $isbn;
        $booksModel->added_at = $added_at;
        $booksModel->photo = $photo;
        $booksModel->save();

        if ($booksModel) {
            return true; // Books created successfully
        } else {
            return false; // Books creation failed (likely due to database error)
        }
    }

    function updateBooks($id, $title, $author, $category, $isbn , $quantity , $added_at)
    {
        // Initialize the Books model
        $booksModel = new Books();

        // Retrieve the books by ID
        $existingBooks = $booksModel->getBooksById($id); // Assuming findById method exists
        if (!$existingBooks) {
            // Handle the error (return an appropriate message or throw an exception)
            return false; // Or throw an exception with a specific error message
        }

        $Books = new Books();
        $Books->id = $id;
        $Books->title = $title;
        $Books->author = $author;
        $Books->category = $category;
        $Books->isbn = $isbn;
        $Books->quantity = $quantity;
        $Books->added_at = $added_at;
        $Books->updateRec();

        if ($Books) {
            return true; // book udapted successfully
        } else {
            return false; // book update failed (likely due to database error)
        }
    }

    function deleteBooksById($Id)
    {
        // Find the books associated with the user ID
        $books = $this->getBooksById($Id);
        if (!$books) {
            return true; // No books found 
        }

        if (empty($books['id']))  return false;
        $booksId = $books['id'];

       

        // Delete books record
        return $this->deleteRec($booksId);
    }
   public function getBooksById($id)
    {
        $param = array(':id' => $id);
        return $this->pm->run("SELECT * FROM " . $this->getTableName() . " WHERE id = :id", $param, true);
    }
}
