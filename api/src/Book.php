<?php

class Book implements JsonSerializable {

    private $id;
    private $name;
    private $author;
    private $description;

    //User constructor

    public function __construct() {

        $this->id = -1;
        $this->name = "";
        $this->author = "";
        $this->description = "";
    }

    /*
     * Set and Get methods:
     */

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setAuthor($newAuthor) {
        $this->author = $newAuthor;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getDescription() {
        return $this->description;
    }
    public function jsonSerialize() {
         //funkcja zwraca nam dane z obiektu do json_encode

        return ['id' => $this->id,
                'name' => $this->name,
                'author' => $this->author,
                'description' => $this->description];
   
      
    }

      static public function loadFromDBById(mysqli $conn, $id) {
        
        $sql = "SELECT author, name, description "
                . "FROM Book WHERE id = $id";
        if ($result = $conn->query($sql)) {
            $row = $result->fetch_assoc();
            $book = new Book();
            $book->author = $row['author'];
            $book->name = $row['name'];
            $book->description = $row['description'];
            $book->id = $id;
            return $book;
        } else {
            return false;
        } 
    }
    
    static public function loadAllFromDB(mysqli $conn) {
        $books = [];
        
        $sql = "SELECT * FROM Book";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $book = new Book ();
                $book->author = $row['author'];
                $book->description = $row['description'];
                $book->name = $row['name'];
                $book->id = $row['id'];
                $books[$book->id] = $book;
            }
            return $books;
        } else {
            return false;
        }
    }

    public function create (mysqli $conn, $name, $author, $description) {

        $sql = "INSERT INTO Book (author, name, description)"
                . " VALUES ('$author', '$name', '$description')";
        if ($conn->query($sql)) {
            $this->author = $author;
            $this->name = $name;
            $this->description = $description;
            $this->id = $conn->insert_id; 
            return true;
        } else {
            return false;
        }
    }

    public function update (mysqli $conn, $name, $author, $description, $id) {
        $sql = "UPDATE Book SET author = '$author', name = '$name', "
                . "description = '$description' WHERE id = $id";
        if ($conn->query($sql)) {
            $this->author = $author;
            $this->name = $name;
            $this->description = $description;
            return true;
        } else {
            return false;
        }
    }

    static public function delete(mysqli $conn, $id) {
        
        $sql = "DELETE FROM Book WHERE id = $id";
        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

}
 

?>
