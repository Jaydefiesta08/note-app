<?php
include('includes/session.php');
include('includes/db_connection.php');

// Restore note functionality
if (isset($_GET['restore'])) {
  $restore = $_GET['restore'];
  // Restore the archived note and remove favorite status
  $sql = "UPDATE notes SET is_archived = 0, is_favorite = 0 WHERE note_id = $restore";
  $result = mysqli_query($conn, $sql);
  if ($result) {
      // Redirect back to archived page after restoration
      header("Location: archived.php");
      exit();
  } else {
      echo "Error: " . mysqli_error($conn);
  }
}



// Check if the 'archive' parameter is set in the GET request
if(isset($_GET['archive'])) {
  // Sanitize the note ID
  $noteId = mysqli_real_escape_string($conn, $_GET['archive']);

  // Update the note to mark it as archived
  $query = "UPDATE notes SET is_archived = 1 WHERE note_id = '$noteId'";

  if(mysqli_query($conn, $query)) {
      // Note archived successfully
      // Optionally, you can provide feedback or perform other actions
      echo "Note archived successfully"; // For AJAX response
  } else {
      // Error handling
      echo "Error: " . mysqli_error($conn);
  }
}


// Check if the 'delete' parameter is set in the GET request
if (isset($_GET['delete'])) {
  // Sanitize the note ID
  $noteId = mysqli_real_escape_string($conn, $_GET['delete']);

  // Delete the note from the database
  $query = "DELETE FROM notes WHERE note_id = '$noteId'";

  if(mysqli_query($conn, $query)) {
      // Note deleted successfully
      // Optionally, you can provide feedback or perform other actions
      echo "Note deleted successfully";
  } else {
      // Error handling
      echo "Error: " . mysqli_error($conn);
  }
}



// Fetch all archived notes for the current user
$query = "SELECT note_id, title, note, last_updated_at FROM notes WHERE user_id = '$session_id' AND is_archived = 1";

if (mysqli_query($conn, $query)) {
  $result = mysqli_query($conn, $query);
  $archivedNotesArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
  echo 'query error: ' . mysqli_error($conn);
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


<body>
<?php include('header.php'); ?>

    <div id="main" class="notebook-container">
        <h2>Archived Notes</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search notes by title or date">
            <button onclick="searchNotes()"><i class="fas fa-search"></i></button>
        </div>

        <div class="note-card-container">
    <?php foreach ($archivedNotesArray as $note): ?>
        <div class="note-card">
            <h3><?php echo $note['title']; ?></h3>
            <div class="break-line"></div>
            <p class="note-text"><?php echo $note['note']; ?></p>
            <div class="break-line"></div>
            <p class="time-stamp">Time Stamp: <?php echo $note['last_updated_at']; ?></p>
            <div class="break-line"></div>
            <!-- Delete Button with Sweet Alert Confirmation -->
            <a href="#" class="delete-button" data-noteid="<?php echo $note['note_id']; ?>">Delete</a>
            <!-- Restore Button -->
            <a href="archived.php?restore=<?php echo $note['note_id']; ?>" class="restore-button" style="color:green;">Restore</a>
        </div>
    <?php endforeach; ?>
</div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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


document.addEventListener("DOMContentLoaded", function() {
  // Add event listener for search input
  document.getElementById("searchInput").addEventListener("input", function() {
    searchNotes();
  });

  // Add event listener for sort select
  document.getElementById("sortSelect").addEventListener("change", function() {
    searchNotes();
  });
});

function searchNotes() {
  var input, filter, cards, card, title, note, timestamp, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  cards = document.getElementsByClassName("note-card");
  
  for (i = 0; i < cards.length; i++) {
    card = cards[i];
    title = card.getElementsByTagName("h3")[0];
    note = card.getElementsByTagName("p")[0];
    timestamp = card.getElementsByClassName("time-stamp")[0];

    // Check if the card title, note, or timestamp contains the search filter
    if (title.textContent.toUpperCase().indexOf(filter) > -1 || 
        note.textContent.toUpperCase().indexOf(filter) > -1 || 
        timestamp.textContent.toUpperCase().indexOf(filter) > -1) {
      card.style.display = "";
    } else {
      card.style.display = "none";
    }
  }
}




document.querySelectorAll('.delete-button').forEach(deleteBtn => {
    deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const noteId = deleteBtn.getAttribute('data-noteid');
        // Display Sweet Alert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete the note
                deleteNote(noteId);
            }
        });
    });
});

function deleteNote(noteId) {
    // Send AJAX request to delete the note
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Note deleted successfully
                // Optionally, you can handle UI changes here
                console.log('Note deleted successfully');
                // Reload the page to reflect changes
                location.reload();
            } else {
                // Error handling
                console.error('Error: ' + xhr.status);
            }
        }
    };
    xhr.open('GET', 'archived.php?delete=' + noteId, true);
    xhr.send();
}


</script>
</body>
</html>
