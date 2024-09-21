<?php
include('includes/session.php');
include('includes/db_connection.php');

// Function to toggle favorite status via AJAX
if(isset($_POST['note_id']) && isset($_POST['is_favorite'])){
    $note_id = $_POST['note_id'];
    $is_favorite = $_POST['is_favorite'];

    // Toggle favorite status in the database
    $is_favorite = $is_favorite == '1' ? '0' : '1';
    $sql = "UPDATE notes SET is_favorite = '$is_favorite' WHERE note_id = '$note_id'";
    if(mysqli_query($conn, $sql)){
        echo "success";
    } else {
        echo "error";
    }
    exit; // Make sure to exit after processing the AJAX request
}
?>
