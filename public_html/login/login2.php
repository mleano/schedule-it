<?php
# Login.php
// This page processes the login form submission.
// Upon successful login, the user is redirected.
// Two included files are necessary.
// Send NOTHING to the Web browser prior to the setcookie() lines!

// Unset any previous errors
unset($_SESSION['loginError']);

// Check if the form has been submitted:
if(isset($_POST['user']) || isset($_POST['pass'])) {
  // For processing the login:
  include('loginfunction.php');


  // Need the database connection:
  include('../db.php');

  $user = $_POST['user'];
  $pass = $_POST['pass'];

  // Check the login:
  list ($check, $data) = check_login($cnxn, $user, $pass);

  if($check) { // OK!
    // Set the cookies:
    setcookie('user', $user);
    //set sessions
    $_SESSION['username'] = $user;

  }
  else { // Unsuccessful!
    // Assign $data to $errors for error reporting.
    $_SESSION['loginError'] = check_login($cnxn, $user, $pass);
  }

  mysqli_close($cnxn); // Close the database connection.
} // End of the main submit conditional.

?>
