<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];


    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'todo_app';


    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }


    $query = "SELECT username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($fetched_username, $hashed_password);
        $stmt->fetch();


            setcookie('user', $fetched_username, time() + 3600, '/');
            header('Location: todo.php');
            exit();
       
    } else {
        echo "User not found. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
