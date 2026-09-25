<?php
//class for validating data coming from the frontend.
//has 4 functions.
class Validator {
    /**
     * validate the fields by a set of rules.
     * Params $errors to store errors if found.
     * return the errors array.
     */
    public static function validate($data, $rules) {
        $errors = [];

        //itrate through every field in rules.
        foreach ($rules as $field => $ruleSet) {
            //set the value to the data in the field or '' if there is no data
            $value = isset($data[$field]) ? trim($data[$field]) : '';

            //itrate through every ruleSet
            foreach ($ruleSet as $rule) {
                //check if requiered but no data and return error
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = 'This field is required.';
                //check the email field
                } elseif ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Please enter a valid email address.';
                //check the min posible charachters.
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
    /**
     * validate the password by a set of rules.
     * Params $errors to store errors if found.
     * return the errors array.
     */
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
    /**
     * validate the confirmation of password.
     * Params $errors to store errors if found.
     * return the errors array.
     */
    public static function validateConfirmPassword($password, $confirmPassword) {
        $errors = [];

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        return $errors;
    }
    /**
     * validate the image.
     * Params $errors to store errors if found.
     * $allowedTypes store types of image that are allowed
     * return the errors array.
     */
    public static function validateImage($file)
    {
        $errors = [];

        // if the file does not have a value null or the error enttity in a file have no value then there is no file 
        if (!$file || !isset($file['error'])) {
            $errors[] = 'No file uploaded.';
            return $errors;
        }
    
        //if error value equal to upload error the return the error 
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'No file uploaded.';
            return $errors;
        }
    
        //if file error value not equal to ok then return the error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading file.';
            return $errors;
        }
    
        //if the file size is bigger than 2mb return error
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'File size must be less than 2MB.';
        }
    
        //store allowed file types
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];
    
        //make object of the php tool that inspect the file type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        //check type of uploded file
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
    
        //check if the file type is not in the allowed types and store the error
        if (!in_array($mimeType, $allowedTypes, true)) {
            $errors[] = 'Only JPG, PNG, and WEBP files are allowed.';
        }
    
        //checks if this is not an image and return the error
        if (@getimagesize($file['tmp_name']) === false) {
            $errors[] = 'The uploaded file is not a valid image.';
        }
    
        return $errors;
    }
}