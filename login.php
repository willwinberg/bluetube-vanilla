<?php
require_once("includes/entryHeader.php"); 
require_once("includes/config.php");
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/EntryHandler.php");
require_once("includes/markupRenderers/FormBuilder.php"); 
?>
<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php
$result = "";

if (isset($_POST["login"])) {   
   $data = FormInputSanitizer::sanitize($_POST);

   $result = EntryHandler::login($db, $data);
   
   if ($result === Success::$login) {
      $_SESSION["loggedIn"] = $data["username"];
      header("Location: index.php");
   } 
}

$form = new FormBuilder();

echo $form->openEntryFormTag("Sign In");
   echo $form->entryTextInput("Username", "username");
   echo $form->entryTextInput("Password", "password", "password");

   echo $result;
   echo $form->submitButton("SUBMIT", "login");
echo $form->closeEntryFormTag("register.php", "Don't have an account yet? Register here!");

require_once("includes/entryFooter.php"); ?>