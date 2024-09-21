<?php
include('includes/session.php');
include('includes/db_connection.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was selected
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        // Define upload directory and target file
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            exit();
        }

        // Check file size (limit to 5MB)
        if ($_FILES["profile_picture"]["size"] > 5 * 1024 * 1024) {
            echo "Sorry, your file is too large (max 5MB).";
            exit();
        }

        // Generate a unique filename
        $newFileName = uniqid() . "." . $imageFileType;
        $targetFilePath = $targetDir . $newFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
            // Update image_path in the database
            $updateQuery = "UPDATE register SET image_path = '$targetFilePath' WHERE user_ID = '$session_id'";
            if (mysqli_query($conn, $updateQuery)) {
                // Redirect back to profile page
                header("Location: profile.php");
                exit();
            } else {
                echo "Error updating database: " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file selected.";
    }
}
?>
