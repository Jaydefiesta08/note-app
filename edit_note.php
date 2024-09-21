<?php
// Include session and database connection files
include('includes/session.php');
include('includes/db_connection.php');

// Check if note ID is provided in the URL
if(isset($_GET['note_id'])) {
    $noteId = $_GET['note_id'];

    // Fetch note data from the database
    $query = "SELECT title, note FROM notes WHERE note_id = '$noteId'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0) {
        $note = mysqli_fetch_assoc($result);
    } else {
        // Note not found or query failed
        // Handle error or redirect
        header("Location: notebook.php");
        exit;
    }
} else {
    // Note ID not provided in URL
    // Redirect or display error message
    header("Location: notebook.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div id="topbar">
    <div class="logo">
        <h1>eNote</h1>
    </div>
    <button class="openbtn" onclick="toggleNav()"><i class="fas fa-bars"></i></button>
</div>

<div id="mySidebar" class="sidebar">
    <a href="notebook.php"><i class="fas fa-pen"></i>Notes</a>
    <a href="favorite.php"><i class="fas fa-heart"></i>Favorites</a>
    <a href="archived.php"><i class="fas fa-archive"></i>Archived</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
</div>

    <div class="edit-note-container">
        
    </div>
    <div id="main" class="notebook-container">
    <h2>Edit Note</h2>
        <form method="POST" action="update_note.php" class="note_form">
            <input type="hidden" name="note_id" value="<?php echo $noteId; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $note['title']; ?>"><br>
            <label for="note">Note:</label><br>
            <textarea id="note" name="note"><?php echo $note['note']; ?></textarea><br>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
