<?php
// Include session and database connection files
include('includes/session.php');
include('includes/db_connection.php');

// Check if form data is submitted via POST request
if (isset($_POST['submit'])) {
    // Sanitize and retrieve form data
    $noteId = mysqli_real_escape_string($conn, $_POST['note_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    // Query to update note data
    $query = "UPDATE notes SET title = '$title', note = '$note' WHERE note_id = '$noteId'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Note data updated successfully
        echo "Note data updated successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Error: Form data not submitted";
}
?>
