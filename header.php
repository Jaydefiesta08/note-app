<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>eNote</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<div id="topbar">
<div class="logo">
    <img src="images/logo.png" alt="eNote Logo">
    <h1>eNote</h1>
</div>

    <button class="openbtn" onclick="toggleNav()"><i class="fas fa-bars"></i></button>
</div>

<div id="mySidebar" class="sidebar">
    <a href="notebook.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'notebook.php') ? 'class="active"' : ''; ?>><i class="fas fa-pen"></i>Notes</a>
    <a href="favorite.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'favorite.php') ? 'class="active"' : ''; ?>><i class="fas fa-heart"></i>Favorites</a>
    <a href="archived.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'archived.php') ? 'class="active"' : ''; ?>><i class="fas fa-archive"></i>Archived</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    
    <?php if (isset($fullName)) : ?>
    <div class="welcome-message">
    <a href="#" class="profile-link">
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
                echo '<img src="' . $imagePath . '" alt="User Image" class="user-image1" style="border-radius: 60%; height: 50px;"></a>';
            } else {
                echo '<i class="far fa-user"></i></a>';
            }
            // Display welcome message
            echo 'Hi, ' . $fullName . ' Welcome</a>';
      
        } else {
            echo 'Error retrieving image';
        }
        
        ?>
        </a>
    </div>
    <?php endif; ?>
</div>
