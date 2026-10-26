
<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $admission = trim($_POST["admission"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if (
        $fullname === "" ||
        $admission === "" ||
        $course === "" ||
        $phone === ""
    ) {
        exit("Please fill in all fields.");
    }

    $sql = "INSERT INTO students
            (fullname, admission, course, phone)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Check whether the SQL statement prepared successfully
    if ($stmt === false) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param(
        "ssss",
        $fullname,
        $admission,
        $course,
        $phone
    );

    if ($stmt->execute()) {
        echo "<h2>Registration Successful!</h2>";
        echo "Full Name: " . htmlspecialchars($fullname) . "<br>";
        echo "Admission Number: " . htmlspecialchars($admission) . "<br>";
        echo "Course: " . htmlspecialchars($course) . "<br>";
        echo "Phone Number: " . htmlspecialchars($phone);
    } else {
        if ($stmt->errno == 1062) {
            echo "Error: This admission number is already registered.";
        } else {
            echo "Database Error: " . htmlspecialchars($stmt->error);
        }
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Please submit the registration form first.";
}
?>
