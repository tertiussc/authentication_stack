<?php

/********** Helper functions **********/

/** Clean strings from inputs
 * 
 * @param string $string The string to be cleaned
 * 
 * @return string $string The cleaned string
 */
function clean($string)
{
    return htmlentities($string);
}

/** Redirect to page
 * 
 * @param string $location The url of page to redirect to
 * 
 * @return null Redirect to page specified
 */
function redirect($location)
{
    return header("Location: {$location}");
}

/** Set a messages in sessions
 * 
 * @param string $message Set the message that you want to be contained in sessions
 * 
 */
function set_messages($message)
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}

/** Display message if is set in sessions
 * @return string Session message
 */
function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    };
}

/** Generate a token and encrypt it
 * 
 * @return string $token Set the token in sessions and retruns a unique and encrypted Token variable
 */
function token_generator()
{
    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}

function email_exist($email)
{
    $sql = "SELECT id from users WHERE email = '$email'";

    $result = query($sql);

    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}

function username_exist($username)
{
    $sql = "SELECT id from users WHERE username = '$username'";

    $result = query($sql);

    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}


/********** Validation functions **********/

/** Validate registration fields: 
 * Cleans input values. 
 * Check to see if $first_name, $last_name and $username is between the minimum and maximum allowed characters
 * @return string $error Returns error messages if any is found
 */
function validate_user_registration()
{
    $errors = [];
    $min = 3;
    $max = 25;

    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["register-submit"])) {
        $first_name = clean($_POST["first_name"]);
        $last_name = clean($_POST["last_name"]);
        $username =  clean($_POST["username"]);
        $email = clean($_POST["email"]);
        $password = clean($_POST["password"]);
        $confirm_password = clean($_POST["confirm_password"]);
    } else {
        $first_name = "";
        $last_name = "";
        $username = "";
        $email = "";
        $password = "";
        $confirm_password = "";
    }

    if (strlen($first_name) <= $min || strlen($first_name) >= $max) {
        $errors[] = "First name length must be between {$min} and {$max} characters long";
    }
    if (strlen($last_name) <= $min || strlen($last_name) >= $max) {
        $errors[] = "Last name length must be between {$min} and {$max} characters long";
    }
    if (strlen($username) <= $min || strlen($username) >= $max) {
        $errors[] = "Username length must not be between {$min} and {$max} characters long";
    }

    if (username_exist($username)) {
        $errors[] = "This username is already in use";
    }

    if (email_exist($email)) {
        $errors[] = "This email is already in use";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Your passwords do not match";
    }

    // Display errors if there are any otherwise insert the new user into the users table
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='callout-danger'>$error</p><br>";
        }
    } else {
        if (register_user($first_name, $last_name, $username, $email, $password)) {

            // Set successful registration message
            set_messages("
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>User registered!</strong> Please check you email (or spam) for activation.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                        ");

            // Redirect to home page
            redirect("index.php");
        }
    }
}


function register_user($first_name, $last_name, $username, $email, $password)
{
    // Clean the input data
    $first_name     = escape($first_name);
    $last_name      = escape($last_name);
    $username       = escape($username);
    $email          = escape($email);
    $password       = escape($password);

    if (email_exist($email)) {
        return false;
    } elseif (username_exist($username)) {
        return false;
    } else {
        // encrypt password
        $password = md5($password);

        // 
        $validation_code = md5($username . microtime());

        $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)
                VALUES ('$first_name','$last_name','$username','$email','$password','$validation_code','0')";

        $result = query($sql);
        confirm($result);

        return true;
    }
}
