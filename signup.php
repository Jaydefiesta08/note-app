
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>eNote | Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInDown">
    <div class="container aside-xxl">
      <a class="navbar-brand block" href="signup.php">Notebook</a>
      <section class="panel panel-default m-t-lg bg-white">
        <header class="panel-heading text-center">
          <strong>Sign up</strong>
        </header>
        <form name="signup" method="POST">
          <div class="panel-body wrapper-lg">
          	 <div class="form-group">
	            <label class="control-label">Name</label>
	            <input name="name" type="text" placeholder="Your Account name " class="form-control input-lg">
	          </div>
	          <div class="form-group">
	            <label class="control-label">Email</label>
	            <input name="email" type="email" placeholder="test@example.com" class="form-control input-lg">
	          </div>
	          <div class="form-group">
	            <label class="control-label">Password</label>
	            <input name="password" type="password" id="inputPassword" placeholder="Type a password" class="form-control input-lg">
	          </div>
	          <div class="line line-dashed"></div>
	          <button name="signup" type="submit" class="btn btn-primary btn-block">Sign up</button>
	          <div class="line line-dashed"></div>
	          <p class="text-muted text-center"><small>Already have an account?</small></p>
	          <a href="index.php" class="btn btn-default btn-block">Login</a>
          </div>
        </form>
      </section>
    </div>
  </section>

  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>Notebook | Web Application by CodeLytical<br>&copy; 2021</small>
      </p>
    </div>
  </footer>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/app.js"></script>
  <script src="js/app.plugin.js"></script>
  <script src="js/slimscroll/jquery.slimscroll.min.js"></script>
  <?php
session_start();
include('includes/db_connection.php');

if(isset($_POST['signup'])) {
    // Retrieve form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
// Hash the password
$password = md5($password);
    // Validate inputs
    $errors = [];

    // Check for whitespace in name, email, and password
    if (preg_match('/\s/', $name)) {
        $errors[] = "Name cannot contain whitespace.";
    }
    if (preg_match('/\s/', $email)) {
        $errors[] = "Email cannot contain whitespace.";
    }
    if (preg_match('/\s/', $password)) {
        $errors[] = "Password cannot contain whitespace.";
    }

    if(empty($name)) {
        $errors[] = "Name is required.";
    }
    if(empty($email)) {
        $errors[] = "Email is required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if(empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d).+$/', $password)) {
        $errors[] = "Password must contain both letters and numbers.";
    }

    // Check if email already exists
    $query_email = mysqli_query($conn, "SELECT * FROM register WHERE email = '$email'");
    $count_email = mysqli_num_rows($query_email);

    if($count_email > 0) {
        $errors[] = "Email already exists.";
    }

    // Check if name already exists
    $query_name = mysqli_query($conn, "SELECT * FROM register WHERE fullName = '$name'");
    $count_name = mysqli_num_rows($query_name);

    if($count_name > 0) {
        $errors[] = "Name already exists.";
    }

    // If there are errors, display them using SweetAlert
    if(!empty($errors)) {
        echo "<script>
                swal({
                  title: 'Error',
                  text: '" . implode("<br>", $errors) . "',
                  icon: 'error',
                  button: 'OK'
                });
              </script>";
    } else {
        // Insert user into database
        $insert_query = mysqli_query($conn, "INSERT INTO register(fullName, email, password) VALUES('$name', '$email', '$password')");
        if($insert_query) {
            // Success, display SweetAlert and redirect to login page
            echo "<script>
                    swal({
                      title: 'Success',
                      text: 'You have successfully registered.',
                      icon: 'success',
                      button: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                  </script>";
        } else {
            // Database error
            echo "<script>
                    swal({
                      title: 'Error',
                      text: 'An error occurred while registering. Please try again later.',
                      icon: 'error',
                      button: 'OK'
                    });
                  </script>";
        }
    }
}
?>


</body>
</html>