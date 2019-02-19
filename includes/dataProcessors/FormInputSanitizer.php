<?php

class FormInputSanitizer {


    public static function sanitize($data) {
        $sanitizedData = array();

        foreach ($data as $name => $value) {
            if (in_array($name, array("username", "email", "emailConfirm"))) {
                $sanitizedData[$name] = FormInputSanitizer::sanitizeIdentifier($value);
            } else if (in_array($name, array("password", "passwordConfirm", "oldPassword"))) {
                $sanitizedData[$name] = FormInputSanitizer::sanitizePassword($value);
            } else if (in_array($name, array("title", "body", "description"))) {
                $sanitizedData[$name] = FormInputSanitizer::sanitizeText($value);
            } else if (in_array($name, array("firstName", "lastName"))) {
                $sanitizedData[$name] = FormInputSanitizer::sanitizeWord($value);
            } else {
                // echo "\nsanitation skipped on $name => $value\n";
            }       
        }
        
        return $sanitizedData;
    }

    private function sanitizeWord($input) {
        $input = trim($input);
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);
        $input = strtolower($input);
        $input = ucfirst($input);
        return $input;
    }

    private function sanitizeIdentifier($input) {
        $input = trim($input);
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);
        return $input;
    }

    private function sanitizePassword($input) {
        $input = strip_tags($input);
        return $input;
    }

    private function sanitizeText($input) {
        $input = trim($input);
        $input = strip_tags($input);
        return $input;
    }
}

?>
