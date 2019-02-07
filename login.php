<?php
require_once("includes/config.php");
require_once("includes/classes/FormInputSanitizer.php");
require_once("includes/classes/UserAccountHandler.php");
require_once("includes/classes/ErrorMessage.php");

$loginDataSanitizer = new FormInputSanitizer;
$userAccount = new UserAccountHandler($dbConnection);

if (isset($_POST["submitLoginForm"])) {
    
   $sanitizedLoginData = $loginDataSanitizer->sanitizeLoginData($_POST);

   $userAccount->login($sanitizedLoginData);

   $noError = !$userAccount->error;

   if ($noError) {
      $_SESSION["loggedIn"] = $sanitizedLoginData["username"];
      header("Location: index.php");
   }
}

function getValue($key) {
   if (isset($_POST[$key])) {
      echo $_POST[$key];
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>BlueTube</title>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="assets/css/style.css">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</head>

<body>
   <div class="entryContainer">
      <div class="column">
         <div class="entryHeader">
            <img src="assets/images/logo.png" title="logo" alt="Site logo">
            <h3>Log in</h3>
            <span>to continue to BlueTube</span>
         </div>
         <div class="entryForm">
            <form action="login.php" method="POST">
               <input
                  required
                  type="text"
                  name="username"
                  placeholder="Username"
                  value=<?php echo getValue("username"); ?>
               >
               <input
                  required
                  type="password"
                  name="password"
                  placeholder="Password"
               >
               <?php echo $userAccount->getError(ErrorMessage::$loginFailed); ?>
               <input type="submit" name="submitLoginForm" value="SUBMIT">
            </form>
         </div>
         <a class="entryMessage" href="register.php">Don't have an account yet? Register here!</a>   
      </div>
   </div>
</body>
</html>