<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve form data
    $username = trim($_GET['username']);
    $password = trim($_GET['password']);

    // Database connection
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "auth";

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("An error occurred. Please try again later.");
    }

    // Validate login authentication
    $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login success
            header("Location: success.html");
            exit();
        } else {
            // Login failed
            header("Location: failed.html");
            exit();
        }
    } else {
        // Login failed
        header("Location: failed.html");
        exit();
    }
}
?>
