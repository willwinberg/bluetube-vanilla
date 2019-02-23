<?php
require_once("includes/entryHeader.php"); 
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/AccountHandler.php");
require_once("includes/markupRenderers/FormBuilder.php"); 
?>
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php

$dataSanitizer = new FormInputSanitizer;
$account = new AccountHandler($db);

if (isset($_POST["submitLoginForm"])) {   
   $data = $dataSanitizer->sanitize($_POST);

   $account->login($data);

   if ($account->message === Success::$login) {
      $_SESSION["loggedIn"] = $data["username"];
      header("Location: index.php");
   }
}

$form = new FormBuilder();

echo $form->openEntryFormTag("Sign In");
   echo $form->entryTextInput("Username", "username");
   echo $form->entryTextInput("Password", "password", "password");

   echo "<span class='errorMessage'>$account->message</span>";
   echo $form->submitButton("SUBMIT", "submitLoginForm");
echo $form->closeEntryFormTag("register.php", "Don't have an account yet? Register here!");

require_once("includes/entryFooter.php"); ?>