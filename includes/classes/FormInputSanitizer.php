<?php

class FormInputSanitizer {

    public static function getSanitizedFormInputAssocArray() {
        // possible consolidate this later
    }

    public static function sanitizeString($textInput) {
        $textInput = strip_tags($textInput);
        $textInput = str_replace(" ", "", $textInput);
        $textInput = strtolower($textInput);
        $textInput = ucfirst($textInput);
        return $textInput;
    }

    public static function sanitizeUsername($usernameInput) {
        $usernameInput = strip_tags($usernameInput);
        $usernameInput = str_replace(" ", "", $usernameInput);
        return $usernameInput;
    }

    public static function sanitizePassword($passwordInput) {
        $passwordInput = strip_tags($passwordInput);
        return $passwordInput;
    }

    public static function sanitizeEmail($emailInput) {
        $emailInput = strip_tags($emailInput);
        $emailInput = str_replace(" ", "", $emailInput);
        return $emailInput;
    }
}

?>
