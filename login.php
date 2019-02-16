<?php
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/dataProcessors/Error.php");

$dataSanitizer = new FormInputSanitizer;
$account = new AccountHandler($db);

if (isset($_POST["submitLoginForm"])) {   
   $data = $dataSanitizer->sanitize($_POST);

   $account->login($data);

   if (!$account->error) {
      $_SESSION["loggedIn"] = $data["username"];
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
            <?php echo $account->error(Error::$loginFailed); ?>
            <input type="submit" name="submitLoginForm" value="SUBMIT">
         </form>
      </div>
      <a class="entryMessage" href="register.php">Don't have an account yet? Register here!</a>   
   </div>
</div>

<?php require_once("includes/entryFooter.php"); ?>