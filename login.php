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

<?php require_once("includes/entryHeader.php"); ?>

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

<?php require_once("includes/entryHeader.php"); ?>