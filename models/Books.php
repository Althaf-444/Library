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
            ':photo' => $this->photo,
            ':id' => $this->id
        );

        return $this->pm->run("UPDATE books SET title = :title, author = :author, category = :category, isbn = :isbn, quantity = :quantity,added_at = :added_at,photo = :photo WHERE id = :id", $param);
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

    function updateBooks($id, $title, $author, $category, $isbn , $quantity,$added_at,$photo)
    {
        // Initialize the Books model
        $booksModel = new Books();

        // Retrieve the books by ID
        $existingBooks = $booksModel->getById($id); // Assuming findById method exists

        if (!$existingBooks) {
            return false; // Books not found
        }

        // Update properties only if values are provided
        if ($title !== null) {
            $existingBooks->title = $title;
        }
        if ($author !== null) {
            $existingBooks->author = $author;
        }
        if ($category !== null) {
            $existingBooks->category = $category;
        }
        if ($isbn !== null) {
            $existingBooks->isbn = $isbn;
        } if ($quantity !== null) {
            $existingBooks->quantity = $quantity;
        }
        if ($added_at !== null) {
            $existingBooks->added_at = $added_at;
        }
        if ($photo !== null) {
            $existingBooks->photo = $photo;
        }

        // Save the changes
        $updated = $existingBooks->save(); // Assuming save method exists

        return $updated ? true : false;
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
