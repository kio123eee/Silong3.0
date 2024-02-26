<?php
include 'components/connect.php'; // Include the file to connect to the database

session_start();

$message = array(); // Initialize the message array

if(isset($_POST['submit'])){
   // Sanitize and validate input
   $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $pass = filter_var($_POST['pass'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   
   // Hash the password securely
   $pass_hash = sha1($pass); // Note: SHA-1 is not recommended for password hashing, consider using stronger algorithms like bcrypt
   
   // Prepare and execute the SQL query
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass_hash]);
   
   // Check if any rows were returned
   if($select_admin->rowCount() > 0){
      // Fetch the admin ID from the result
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      
      // Store admin ID in session
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      
      // Redirect to admin_events.php
      header('Location: admin_events.php');
      exit; // It's good practice to exit after a redirect to prevent further execution
   } else {
      // Incorrect username or password
      $message[] = 'Incorrect username or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" type="text/css" href="css/admin_style.css">

</head>
<body style="padding-left: 0 !important;">

<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<div class="login-header">
   <a href="index.php" class="logo-area">
      <img src="img/logo.png" alt="logo">
   </a>
</div>

<!-- admin login form section starts  -->
<section class="form-container">
   <!-- entering credentials -->
   <form action="" method="POST">
      <h3>Admin Login</h3>
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login" name="submit" class="login-btn">
   </form>
</section>
<!-- admin login form section ends -->
</body>
</html>
