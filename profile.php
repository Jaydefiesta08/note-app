<?php 
// Include session and database connection files
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




// Add new note functionality
if(isset($_POST['submit']) && isset($_POST['title']) && isset($_POST['note'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    date_default_timezone_set("Africa/Accra");
    $datetime_now = date("Y-m-d H:i:s"); // Format: Year-Month-Day Hour:Minute:Second

    // Check if note_id is set (if it's set, it means we're updating an existing note)
    if(isset($_POST['note_id'])) {
        $note_id = $_POST['note_id'];
        // Update existing note
        $query = "UPDATE notes SET title = '$title', note = '$note', last_updated_at = '$datetime_now' WHERE note_id = '$note_id'";
    } else {
        // Insert new note
        $query = "INSERT INTO notes(user_id, title, note, last_updated_at) VALUES('$session_id', '$title', '$note', '$datetime_now')";
    }

    if(mysqli_query($conn, $query)){
        // Redirect back to the same page after successfully adding/updating the note
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        // Failure
        echo 'query error: '. mysqli_error($conn);
    }
}



// Check if user is logged in
if (isset($_SESSION['alogin'])) {
    $userId = $_SESSION['alogin'];

    // Query to fetch user's full name
    $query = "SELECT fullName FROM register WHERE user_ID = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fullName = $row['fullName'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Picture</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div id="topbar">
        <div class="logo">
          <!-- <img src="your-logo.png" alt="Logo"> -->
          <h1>eNote</h1>
        </div>
        <button class="openbtn" onclick="toggleNav()"><i class="fas fa-bars"></i></button>
      </div>
    </div>
    <div id="mySidebar" class="sidebar">
    <a href="notebook.php"><i class="fas fa-pen"></i>Notes</a>
    <a href="favorite.php"><i class="fas fa-heart"></i>Favorites</a>
    <a href="archived.php"><i class="fas fa-archive"></i>Archived</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    <?php if (isset($fullName)) : ?>
    <div class="welcome-message">
        <?php
        // Retrieve the image path for the logged-in user from the database
        $userId = $_SESSION['alogin'];
        $sql = "SELECT image_path FROM register WHERE user_ID = '$userId'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $imagePath = $row['image_path'];
            // Check if the user has an image
            if (!empty($imagePath)) {
                echo '<a href="profile.php" class="profile-link"><img src="' . $imagePath . '" alt="User Image" class="user-image1" style="border-radius: 60%; height: 50px;"></a>';
            } else {
                echo '<a href="profile.php" class="profile-link"><i class="far fa-user"></i></a>';
            }
            // Display welcome message
            echo '<a href="profile.php" class="profile-link">Hi, ' . $fullName . ' Welcome</a>';
        } else {
            echo 'Error retrieving image';
        }
        
        ?>
    </div>
<?php endif; ?>
</div>
    <div id="main" class="notebook-container">
        <h2>Change Profile Picture</h2>
        <!-- Form for uploading profile picture -->
        <form action="upload_profile.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
        <!-- Display current profile picture -->
        <div class="profile-picture">
            <?php if (!empty($imagePath)): ?>
                <img src="<?php echo $imagePath; ?>" alt="Profile Picture">
            <?php else: ?>
                <p>No profile picture uploaded</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
function toggleNav() {
  var sidebar = document.getElementById("mySidebar");
  var topbar = document.getElementById("topbar");
  var main = document.getElementById("main");
  
  // Get the computed left position of the sidebar
  var sidebarLeft = window.getComputedStyle(sidebar).left;
  
  if (sidebarLeft === "0px") {
    sidebar.style.left = "-250px"; /* Slide the sidebar off-screen */
    topbar.style.marginLeft = "0";
    main.style.marginLeft = "0";
  } else {
    sidebar.style.left = "0"; /* Slide the sidebar back into view */
    // topbar.style.marginLeft = "250px";
    main.style.marginLeft = "250px";
  }
}
</script>
</body>
</html>
