<?php
include('includes/session.php');
include('includes/db_connection.php');

// Fetch all favorite notes for the current user
$query = "SELECT * FROM notes WHERE user_id = '$session_id' AND is_favorite = 1  AND is_archived = 0";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo 'Error fetching favorite notes: ' . mysqli_error($conn);
    exit;
}


$favoriteNotes = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

?>


<body>
<?php include('header.php'); ?>
    <div id="main" class="notebook-container">
        <h2>Favorite Notes</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search notes by title or date">
            <button onclick="searchNotes()"><i class="fas fa-search"></i></button>
        </div>

        <div class="note-card-container">
            <?php foreach ($favoriteNotes as $note): ?>
                <div class="note-card">
                <a href="#" class="favorite-toggle" data-noteid="<?php echo $note['note_id']; ?>" data-isfavorite="<?php echo $note['is_favorite']; ?>">
                <?php if ($note['is_favorite'] == 1): ?>
                    <i class="fas fa-heart"></i>
                <?php else: ?>
                    <i class="far fa-heart"></i>
                <?php endif; ?>
            </a>
                    <h3><?php echo $note['title']; ?></h3>
                    <div class="break-line"></div>
                    <p class="note-text"><?php echo $note['note']; ?></p>
                    <div class="break-line"></div>
                    <p class="time-stamp">Date & Time: <?php echo $note['last_updated_at']; ?></p> 
                    <div class="break-line"></div>
                    <div class="note-actions">
                       <!-- Trash icon with the appropriate data attribute for note ID -->
                        <a href="#">
                            <i class="fas fa-trash-alt archive-note" data-noteid="<?php echo $note['note_id']; ?>"></i>
                        </a>
                       
                        <a href="#" class="edit-note" data-noteid="<?php echo $note['note_id']; ?>">
                    <i class="fas fa-edit"></i>
                </a>
                    </div>
                    <div class="favorite-added-message">Added to Favorites</div>
                <div class="favorite-removed-message">Removed from Favorites</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="POST" class="note_form" id="editForm">
    <input type="hidden" id="editNoteId" name="note_id"> <!-- Place the hidden input field here -->
    <button id="closeButton" onclick="closeForm()"><i class="fas fa-times"></i></button>
    <label for="title">Title:</label><br>
    <input type="text" placeholder="Title" id="editTitle" name="title"><br>
    <label for="note">Write Note:</label><br>
    <textarea id="editNote" name="note" placeholder="Take a Note ......"></textarea><br>
    <button name="submit" type="submit">Update Note</button>
</form>


    <script>

// JavaScript function to handle click event on edit icon
document.addEventListener("DOMContentLoaded", function() {
    var editButtons = document.querySelectorAll('.edit-note');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior

            // Retrieve note ID from data attribute
            var noteId = this.getAttribute('data-noteid');

            // AJAX request to fetch note data
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'notebook.php?note_id=' + noteId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        // Populate edit form fields with retrieved data
                        document.getElementById('editNoteId').value = noteId;
                        document.getElementById('editTitle').value = data.note.title;
                        document.getElementById('editNote').value = data.note.note;
                        // Show the edit form
                        document.getElementById('editForm').style.display = 'block';
                        document.getElementById('overlay').style.display = 'block';
                    } else {
                        console.error('Error fetching note data');
                    }
                }
            };
            xhr.send();
        });
    });
});




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
// Updated event listener for archive icon buttons
document.querySelectorAll('.note-card .archive-note').forEach(archiveIcon => {
    archiveIcon.addEventListener('click', event => {
        event.preventDefault();
        const noteId = archiveIcon.getAttribute('data-noteid');
        archiveNote(noteId); // Call the function to archive the note
    });
});

// Function to archive the note via AJAX
function archiveNote(noteId) {
    // Send AJAX request to archive the note
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Note archived successfully
                // Optionally, you can handle UI changes here
                console.log('Note archived successfully');
                // Reload the page or update the UI as needed
                location.reload();
            } else {
                // Error handling
                console.error('Error: ' + xhr.status);
            }
        }
    };
    xhr.open('GET', 'archived.php?archive=' + noteId, true);
    xhr.send();
}



// Function to handle favorite toggle
document.querySelectorAll('.favorite-toggle').forEach(item => {
    item.addEventListener('click', event => {
        event.preventDefault();
   
        const noteId = item.getAttribute('data-noteid');
        const isFavorite = item.getAttribute('data-isfavorite');

        // Send AJAX request to toggle favorite status
        const xhr = new XMLHttpRequest();
        const message = item.querySelector('.favorite-message');
        xhr.open('POST', 'notebook.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = xhr.responseText;
                    if (response === 'success') {
                        // Toggle heart icon
                        const heartIcon = item.querySelector('i');
                        if (isFavorite == '1') {
                        heartIcon.classList.remove('fas');
                        heartIcon.classList.add('far');
                        item.setAttribute('data-isfavorite', '0');
                        const removedMessage = item.parentElement.querySelector('.favorite-removed-message');
                        removedMessage.classList.add('show'); // Show the removed message
                        // Hide the message after 2 seconds
                        setTimeout(() => {
                            removedMessage.classList.remove('show');
                        }, 2000);
                    } else {
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                        item.setAttribute('data-isfavorite', '1');
                        const addedMessage = item.parentElement.querySelector('.favorite-added-message');
                        addedMessage.classList.add('show'); // Show the added message
                        // Hide the message after 2 seconds
                        setTimeout(() => {
                            addedMessage.classList.remove('show');
                        }, 2000);
                    }
                    } else {
                        // Handle error
                    }
                } else {
                    // Handle error
                }
            }
        };
        xhr.send('note_id=' + noteId + '&is_favorite=' + isFavorite);
    });
});

</script>
</body>
</html>
