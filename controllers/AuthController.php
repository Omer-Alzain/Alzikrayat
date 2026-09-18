<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Cookie.php';
class AuthController extends Controller{
    private $AuthModel;

    public function __construct()
    {
        $this->AuthModel = new AuthModel();
    }

    public function register()
    {
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

            $errors = Validator::validate($data, $rules);
            $passwordErrors = Validator::validatePassword($data['password']);
            $confirmPasswordErrors = Validator::validateConfirmPassword($data['password'], $data['confirm_password']);

            if (!empty($passwordErrors)) {
                $errors['password'] = array_merge($errors['password'] ?? [], $passwordErrors);
            }

            if (!empty($confirmPasswordErrors)) {
                $errors['confirm_password'] = array_merge($errors['confirm_password'] ?? [], $confirmPasswordErrors);
            }

            if (empty($errors)) {
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
                    // Handle user creation failure (e.g., show an error message)
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
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate email and password
            $errors = [];
            if (empty($email)) {
                $errors['email'][] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = 'Please enter a valid email address.';
            }

            if (empty($password)) {
                $errors['password'][] = 'Password is required.';
            }

            if (empty($errors)) {
                // Check if the user exists and the password is correct
                $user = $this->AuthModel->getUserByEmail($email);
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
            $this->view('auth/login', ['lastLogin' => Cookie::getLastLoginCookie()]);
        }
    }

    public function logout()
    {
        // Destroy the session or remove the authentication token
        Session::logout();
        header('Location: /gallery'); // Redirect to the gallery page
        exit;
    }
    
}