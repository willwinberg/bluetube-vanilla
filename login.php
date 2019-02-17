<?php
require_once("includes/entryHeader.php"); 
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/dataProcessors/Error.php");
require_once("includes/markupRenderers/FormBuilder.php"); 

$dataSanitizer = new FormInputSanitizer;
$account = new AccountHandler($db);

if (isset($_POST["submitLoginForm"])) {   
   $data = $dataSanitizer->sanitize($_POST);

   $account->login($data);

   if ($account->success) {
      $_SESSION["loggedIn"] = $data["username"];
      header("Location: index.php");
   }
}
?>

<div class="entryContainer">
   <div class="column">
      <div class="entryHeader">
         <img src="assets/images/logo.png" title="logo" alt="BlueTube logo">
         <h3>Log in</h3>
         <span>to continue to BlueTube</span>
      </div>
      <?php
      $form = new FormBuilder(null, $custom = true);

      echo $form->openFormTag("login.php");
         echo $form->textInput("Username", "username");
         echo $form->textInput("Password", "password");

         echo $account->error();
         echo $form->submitButton("SUBMIT", "submitLoginForm");
      echo $form->closeFormTag();
      ?>
      <br>
      <a class="entryMessage" href="register.php">Don't have an account yet? Register here!</a>   
   </div>
</div>

<?php require_once("includes/entryFooter.php"); ?>