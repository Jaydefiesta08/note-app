<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>eNote| Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
    <div class="container aside-xxl">
      <a class="navbar-brand block" href="index.php">Welcome to eNotes</a>
      <section class="panel panel-default bg-white m-t-lg">
        <header class="panel-heading text-center">
          <strong>Login Form</strong>
        </header>
        <form name="signin" method="post">
          <div class="panel-body wrapper-lg">
            <div class="form-group">
              <label class="control-label">Email</label>
              <input name="email" type="email" placeholder="mojado@example.com" class="form-control input-lg">
            </div>
            <div class="form-group">
              <label class="control-label">Password</label>
              <input name="password" type="password" id="inputPassword" placeholder="Password" class="form-control input-lg">
            </div>
            <div class="line line-dashed"></div>
            <button name="signin" type="submit" class="btn btn-primary btn-block">Login</button>
            <div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>Do not have an account?</small></p>
            <a href="signup.php" class="btn btn-default btn-block">Register</a>
          </div>
        </form>
      </section>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder">
      <p>
      </p>
    </div>
  </footer>
  <!-- / footer -->
  <script src="js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="js/bootstrap.js"></script>
  <!-- App -->
  <script src="js/app.js"></script>
  <script src="js/app.plugin.js"></script>
  <script src="js/slimscroll/jquery.slimscroll.min.js"></script>

  <!-- PHP script goes here -->
  <?php
  session_start();
  include('includes/db_connection.php');

  if(isset($_POST['signin'])) {
      // Validate email and password
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = md5($_POST['password']);

      if(empty($email) || empty($password)) {
          // Empty fields, show SweetAlert
          echo "<script>
                  swal({
                    title: 'Error',
                    text: 'Please enter both email and password',
                    icon: 'error',
                    button: 'OK'
                  });
                </script>";
      } else {
          // Perform database query
          $sql = "SELECT * FROM register WHERE email ='$email' AND password ='$password'";
          $query = mysqli_query($conn, $sql);
          $count = mysqli_num_rows($query);
          
          if($count > 0) {
              // Successful login, redirect to notebook.php
              while ($row = mysqli_fetch_assoc($query)) {
                  $_SESSION['alogin'] = $row['user_ID'];
                  echo "<script type='text/javascript'> document.location = 'notebook.php'; </script>";
              }
          } else {
              // Invalid details, show SweetAlert
              echo "<script>
                      swal({
                        title: 'Invalid Details',
                        text: 'Please check your email and password',
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
