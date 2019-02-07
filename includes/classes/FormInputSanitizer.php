<?php

class FormInputSanitizer {

    public function sanitizeNewUserData($postData) {
        $sanitizedData = $postData;

        $sanitizedData["firstName"] = $this->sanitizeString($postData["firstName"]);
        $sanitizedData["lastName"] = $this->sanitizeString($postData["lastName"]);
        $sanitizedData["username"] = $this->sanitizeUsername($postData["username"]);
        $sanitizedData["email"] = $this->sanitizeEmail($postData["email"]);
        $sanitizedData["emailConfirm"] = $this->sanitizeEmail($postData["emailConfirm"]);
        $sanitizedData["password"] = $this->sanitizePassword($postData["password"]);
        $sanitizedData["passwordConfirm"] = $this->sanitizePassword($postData["passwordConfirm"]);

        return $sanitizedData;
    }

    private function sanitizeString($textInput) {
        $textInput = strip_tags($textInput);
        $textInput = str_replace(" ", "", $textInput);
        $textInput = strtolower($textInput);
        $textInput = ucfirst($textInput);
        return $textInput;
    }

    private function sanitizeUsername($usernameInput) {
        $usernameInput = strip_tags($usernameInput);
        $usernameInput = str_replace(" ", "", $usernameInput);
        return $usernameInput;
    }
    
    private function sanitizeEmail($emailInput) {
        $emailInput = strip_tags($emailInput);
        $emailInput = str_replace(" ", "", $emailInput);
        return $emailInput;
    }

    private function sanitizePassword($passwordInput) {
        $passwordInput = strip_tags($passwordInput);
        return $passwordInput;
    }
}

?>
