<?php
//importing all the classes that will be nedded here 
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Cookie.php';
//declaration of the class which use the core controller function
//param $AuthModel refer to the authintcation medel that comunicate with the database
//it has 4 functions:__construct,register,login,and logout.
class AuthController extends Controller{
    private $AuthModel;
// make object of the Authintication model
    public function __construct()
    {
        $this->AuthModel = new AuthModel();
    }
//function for doing the logic of registration from taking the input and validat it until adding the data to user table or return error if found
//param:$data:contain the data from the user 
//$rules:contain rule that will be pass to a validater to validate the data from the user.
//$errors,$passwordErrors,$confirmPasswordErrors each one will contain the errors returnd by the validater.
//$userId this one will store the id for checking if the query was a success now but it could be usful for future devolobment
    public function register()
    {
        //if statement check if the request is post and id it is is start the logic of registering a user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'location' => $_POST['location'] ?? '',
                'description' => $_POST['description'] ?? '',
                'occupation' => $_POST['occupation'] ?? ''
            ];

            $rules = [
                'first_name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8'],
                'confirm_password' => ['required'],
                'location' => ['optional'],
                'description' => ['optional'],
                'occupation' => ['optional']
            ];
            //errors check using validator for fields
            $errors = Validator::validate($data, $rules);
            $passwordErrors = Validator::validatePassword($data['password']);
            $confirmPasswordErrors = Validator::validateConfirmPassword($data['password'], $data['confirm_password']);
            //these if's return array with the types of errors
            if (!empty($passwordErrors)) {
                $errors['password'] = array_merge($errors['password'] ?? [], $passwordErrors);
            }

            if (!empty($confirmPasswordErrors)) {
                $errors['confirm_password'] = array_merge($errors['confirm_password'] ?? [], $confirmPasswordErrors);
            }
            // if the input errors are clear the process continue to database check and creation
            if (empty($errors)) {
                // check if a dublicate email .and return the error to the view with the data so it will 
                // be displayde normally 
                if ($this->AuthModel->emailExists($data['email'])) {
                    $errors['email'][] = 'Email already exists.';
                    $this->view('auth/register', ['errors' => $errors, 'data' => $data]);
                    return;
                }
                // If no errors, create the user
                $userId = $this->AuthModel->createUser($data);
                if ($userId) {
                    // Redirect to a success page or login page
                    header('Location: /auth/login');
                    exit;
                } else {
                    // Handle user creation failure.
                    $errors['general'][] = 'Failed to create user. Please try again.';
                }
            }

            // If there are validation errors, pass them to the view
            $this->view('auth/register', ['errors' => $errors, 'data' => $data]);
        } else {
            // If not a POST request, just show the registration form
            $this->view('auth/register');
        }
    }
    //function for handling login logic 
    //param:$email,$password for storing the input from the frontend.
    //$errors for storing errors of validaition and authrization.
    //$user to store the an array of user data returnd from the database where the email match the one from the user
    public function login()
    {
        //if checks if it post to procede with validating the data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate email and password
            $errors = [];
            if (empty($email)) {
                $errors['email'][] = 'Email is required.';
            // check if it is an email structure.
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = 'Please enter a valid email address.';
            }

            if (empty($password)) {
                $errors['password'][] = 'Password is required.';
            }

            if (empty($errors)) {
                // Check if the user exists and the password is correct
                $user = $this->AuthModel->getUserByEmail($email);
                // if the array $user carry data wich mean that there is a user with this email
                //and the password match procede the login logic 
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Successful login, set session or token as needed
                    Session::login($user);
                    Cookie::setLastLoginCookie();
                    header('Location: /gallery'); // Redirect to a dashboard or home page
                    exit;
                } else {
                    $errors['general'][] = 'Invalid email or password.';
                }
            }

            // If there are validation errors, pass them to the view
            $this->view('auth/login', ['errors' => $errors, 'data' => ['email' => $email]]);
        } else {
            //if it is not post show the login page with the last login.
            $this->view('auth/login', ['lastLogin' => Cookie::getLastLoginCookie()]);
        }
    }

    //fucntion that delete the session and direct the user to the gallary page
    public function logout()
    {
        // Destroy the session or remove the authentication token
        Session::logout();
        header('Location: /gallery'); // Redirect to the gallery page
        exit;
    }
    
}