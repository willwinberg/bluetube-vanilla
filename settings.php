<?php 
require_once("includes/header.php"); 
require_once("includes/markupRenderers/channelView.php"); 
require_once("includes/dataProcessors/FormInputSanitizer.php");
require_once("includes/dataProcessors/FormInputValidator.php");

if (User::isNotLoggedIn()) {
   header("Location: login.php");
}

require_once("includes/footer.php");
?>