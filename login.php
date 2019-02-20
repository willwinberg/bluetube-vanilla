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

   $message = $account->login($data);

   if ($account->validated) {
      $_SESSION["loggedIn"] = $data["username"];
      header("Location: index.php");
   }
}

$form = new FormBuilder();

echo $form->openEntryFormTag("Sign In");
   echo $form->entryTextInput("Username", "username");
   echo $form->entryTextInput("Password", "password");

   echo $message;
   echo $form->submitButton("SUBMIT", "submitLoginForm");
echo $form->closeEntryFormTag("register.php", "Don't have an account yet? Register here!");

require_once("includes/entryFooter.php"); ?>