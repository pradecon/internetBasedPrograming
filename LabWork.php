<?php
// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define error messages
$errors = [];
$successMessage = "";

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = validateInput($_POST["fullName"]);
    $email = validateInput($_POST["email"]);
    $gender = validateInput($_POST["gender"]);

    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email Address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    // Insert data into database if there are no errors
    if (empty($errors)) {
        $sql = "INSERT INTO students (full_name, email, gender) VALUES ('$fullName', '$email', '$gender')";

        if ($conn->query($sql) === true) {
            $successMessage = "Student registered successfully.";
        } else {
            $errors[] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to validate input and prevent SQL injection
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration Form - Results</title>
</head>
<body>
    <h1>Student Registration Form - Results</h1>
    
    <?php
    // Display errors if any
    if (!empty($errors)) {
        echo "<h3>Error:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        // Display success message
        if (!empty($successMessage)) {
            echo "<h3>Success:</h3>";
            echo "<p>$successMessage</p>";
        } else {
            echo "<p>Please fill out the registration form.</p>";
        }
    }
    ?>
</body>
</html>
