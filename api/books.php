<?php
//plik do którego będzie łączył się JS
require_once 'src/Connection.php';
require_once 'src/Book.php';

//$dir = dirname(__FILE__); //zwraca aktualny katalog

//plik zawsze zwraca JSONa, ustawienia do nagłówka
header('Content-Type: application/json');

//sprawdzamy metodę polaczenia GET, POST, PUT, DELETE
//zgodnie z REST GET odczytuje dane

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $book = Book::loadFromDBById($conn, $_GET['id']);
        $serializedData = json_encode($book);
        echo $serializedData;
    } else {
        $books = Book::loadAllFromDB($conn);
        $serializedData = json_encode($books);
        echo $serializedData; 
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['author']) && trim($_POST['author']) != ""
       && isset($_POST['name']) && trim($_POST['name']) != ""
       && isset($_POST['description']) && trim($_POST['description']) != "") {
        
        $author = $_POST['author'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        
        $book = new Book();
        $book->create($conn, $name, $author, $description);
        echo json_encode($book);
       } 
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $del_vars);
    $book = Book::delete($conn, $del_vars['id']);
    echo json_encode($book);
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'];
    $author = $put_vars['author'];
    $name = $put_vars['name'];
    $description = $put_vars['description'];
    
    $book = new Book();
    $book->update($conn, $name, $author, $description, $id);
    echo json_encode($book);
}

//zamykamy połaczenie z baza danych
    $conn->close();
    $conn = null;
/*
$kolory = array (
    '000000', 'FFFFFF', '0000FF', 'FF0000'
);*/

//header('Content-type: application/json; charset=utf-8');
//echo json_encode($kolory);

?>
