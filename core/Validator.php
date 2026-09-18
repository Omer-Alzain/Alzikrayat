<?php

class Validator {
    
    public static function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = isset($data[$field]) ? trim($data[$field]) : '';

            foreach ($ruleSet as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = 'This field is required.';
                } elseif ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Please enter a valid email address.';
                } elseif (strpos($rule, 'min:') === 0) {
                    $minLength = (int) substr($rule, 4);
                    if (strlen($value) < $minLength) {
                        $errors[$field][] = "This field must be at least {$minLength} characters long.";
                    }
                }
            }
        }

        return $errors;
    }
    public static function validatePassword($password) {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = 'Password must contain at least one special character.';
        }

        return $errors;
    }
    public static function validateConfirmPassword($password, $confirmPassword) {
        $errors = [];

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        return $errors;
    }
}