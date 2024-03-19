<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form inputs
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Database connection
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbname = "budgetproject";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to check if email already exists
        $SELECT = "SELECT email FROM users WHERE email=?";
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum == 0) {
            $stmt->close();

            // Prepare SQL statement to insert new user
            $INSERT = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();
            echo "New record inserted successfully";
        } else {
            echo "Someone already registered using this email";
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required";
    }
}
?>
