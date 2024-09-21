<?php
// Include session and database connection files
include('includes/session.php');
include('includes/db_connection.php');

// Check if the form was submitted
if(isset($_POST['submit'])) {
    // Retrieve form data
    $title = trim($_POST['title']);
    $note = trim($_POST['note']);

    // Validate title length
    if(strlen($title) > 40) {
        // Title length exceeds the limit
        echo "Title must be 40 characters or less.";
    } else {
        // Title length is valid, proceed with database insertion

        // Escape special characters to prevent SQL injection
        $title = mysqli_real_escape_string($conn, $title);
        $note = mysqli_real_escape_string($conn, $note);

        // Perform database insertion
        $query = "INSERT INTO notes (user_id, title, note) VALUES ('$session_id', '$title', '$note')";

        if(mysqli_query($conn, $query)) {
            // Successful insertion
            echo "Note added successfully.";
        } else {
            // Error in insertion
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    // Form was not submitted
    echo "Form was not submitted.";
}
?>
