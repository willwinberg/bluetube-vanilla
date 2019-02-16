<?php

class FormInputSanitizer {

    public function sanitize($data) {
        foreach ($data as $name => $value) {
            switch ($name) {
                case "firstName":
                    $sanitizedData[$name] = $this->sanitizeString($value);
                    break;
                case "lastName":
                    $sanitizedData[$name] = $this->sanitizeString($value);
                    break;
                case "username":
                    $sanitizedData[$name] = $this->sanitizeUsername($value);
                    break;
                case "email":
                    $sanitizedData[$name] = $this->sanitizeEmail($value);
                    break;
                case "emailConfirm":
                    $sanitizedData[$name] = $this->sanitizeEmail($value);
                    break;
                case "password":
                    $sanitizedData[$name] = $this->sanitizePassword($value);
                    break;
                case "passwordConfirm":
                    $sanitizedData[$name] = $this->sanitizePassword($value);
                    break;
                case "body":
                    $sanitizedData[$name] = $this->sanitizeBody($value);
                    break;
            }
        }

        return $data;
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

    private function sanitizeBody($bodyInput) {
        $passwordInput = strip_tags($passwordInput);
        return $passwordInput;
    }
}

?>
