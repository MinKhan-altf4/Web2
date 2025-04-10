<?php
session_start();
if(isset($_SESSION['email'])){
  header('location:dashboard.php');
}

if(isset($_POST['signin'])){
  $email = $_POST['email'];
  $password = $_POST['password'];
  if($email == 'admin@gmail.com' && $password == 'admin123123'){
    $_SESSION['email'] = $email;
    header('location:dashboard.php');
  }
  else{
    echo'sai mat khau ';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Admin</title>
  <link rel="stylesheet" href="../css/login.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link rel="stylesheet" href="../css/responsive.css">
  
</head>

<body>
  
    
    <div class="login-box ">
       <div class="login-header ">
      <h1>Login's MALE FASHION</h1>
    </div>
    
    <form method="POST" action="login.php">
      <div class="input-box">
        <input type="email" class="input-field" placeholder="Email" name="email" required>
        
      </div>
      <div class="input-box">
        <input type="password" class="input-field" placeholder="Password" name="password" required>
        
      </div>
      <div class="forgot">
        <section>
          <input type="checkbox" id="check">
          <label for="check">Remember me</label>
        </section>
      </div>
      <div class="input-submit">
        <button type="submit" class="submit-btn" name="signin">Sign In</button>
      </div>
    </form>
  </div>
   
   
</body>
</html>
