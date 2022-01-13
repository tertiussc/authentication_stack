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

/** Check if the email is in use
 * 
 * @param string $email The user's email addressed
 * 
 * @return boolean True if it exist and false if not
 */
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

/** Check if the username is in use
 * 
 * @param string $username The user's email addressed
 * 
 * @return boolean True if it exist and false if not
 */
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

/** Send email
 * 
 * @param string $email The user's email address
 * 
 * @param string $subject The email's subject
 * 
 * @param string $message The email message
 * 
 * @param string $headers The email header (from email information)
 * 
 * @return void Send the email
 */
function send_email($email, $subject, $message, $headers)
{
    // php mail built in function
    return mail($email, $subject, $message, $headers);
}


/********** Validation functions **********/

/** Validate registration fields: 
 * Cleans input values. 
 * Check to see if $first_name, $last_name and $username is between the minimum and maximum allowed characters
 * 
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
                set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>User registered!</strong> Please check you email (or spam) for activation.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>");

                // Redirect to home page
                redirect("index.php");
            } else {
                set_messages("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                <strong>User not registered!</strong> Please try again
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>");
            }
        }
    } else {
        $first_name = "";
        $last_name = "";
        $username = "";
        $email = "";
        $password = "";
        $confirm_password = "";
    }
}

/** Validate user login data and then call the login function
 * 
 * @param string $email The user's registered email
 * 
 * @param string $password The user's password
 * 
 * @return void Log user in and redirect to home page
 */
function validate_login()
{
    // Store errors when they occur
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Clean inputs
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        // check if the user selected the remember me checkbox
        $remember = isset($_POST['remember']);
        $remember = clean($remember);

        // Check that the required fields are not empty
        if (empty($email)) {
            $errors = "Your login email is required";
        }
        if (empty($password)) {
            $errors = "Your password is required";
        }

        // Display errors when found
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='callout-danger'>$error</p><br>";
            }
        } else {

            // Call the user login functionality
            if (login_user($email, $password, $remember)) {

                // Redirect the user if login is successful
                redirect("admin.php");
            } else {
                echo "<p class='callout-danger'>Login credentials not correct</p><br>";
            }
        }
    }
}

/** Login the user
 * 
 * @param string $email The user's resgitered email
 * 
 * @param string $password The user's password
 * 
 * @return boolean True if login is successful false if not
 * 
 */
function login_user($email, $password, $remember)
{

    // Escape the parameters
    $email = escape($email);
    $password = escape($password);


    // SQL query to retrieve user
    $sql = "SELECT id, password, active FROM users
            WHERE email = '$email'";

    // Save the result from the query
    $result = query($sql);

    // check that the query was correct
    confirm($result);

    // Check to see if a record was found
    if (row_count($result) == 1) {

        // retrieve the record from the DB as an array
        $row = fetch_array($result);

        // extract the user's password
        $db_password = $row['password'];
        $active = $row['active'];

        if ($active != 1) {
            set_messages("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <strong>Account not active!</strong> Please use activation email to activate your account.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");
            return true;
        } elseif (password_verify($password, $db_password) && $active == 1) {

            // if remember was checked set a cookie
            if ($remember == "1") {
                setcookie('save_login', 'true', time() + 86400);
            }

            // save the authentication in the session
            $_SESSION['logged_in'] = 'true';

            // if the password match return true
            return true;
        } else {
            // If the password does not match return false
            return false;
        }

        return true;
    } else {
        return false;
    }
}


/** Check to see if the user is logged in
 * 
 * @return boolean True if the user is logged in false if not
 */
function logged_in()
{
    if (isset($_SESSION['logged_in']) || isset($_COOKIE['save_login'])) {
        return true;
    } else {
        redirect("login.php");
        return false;
    }
}

/********** Register functions **********/

/** Register the user in the DB
 * 
 * Escape the inputs to avoid SQL injections
 * 
 * @param string $first_name    First name from the registration form
 * @param string $last_name     Last name from the registration form
 * @param string $username      Username from the registration form
 * @param string $email         Email from the registration form
 * @param string $password      Password from the registration form
 * 
 * Create a hashed validation code
 * 
 * @return void Send an email to the user to activate their account 
 */
function register_user($first_name, $last_name, $username, $email, $password)
{
    // Clean the input data
    $first_name     = escape($first_name);
    $last_name      = escape($last_name);
    $username       = escape($username);
    $email          = escape($email);
    $password       = escape($password);

    if (email_exist($email)) {
        return true;
    } elseif (username_exist($username)) {
        return true;
    } else {
        // encrypt password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // 
        $validation_code = md5($username . microtime());

        $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)
                VALUES ('$first_name','$last_name','$username','$email','$password','$validation_code','0')";

        $result = query($sql);
        confirm($result);

        // Send email
        $subject = "Activate your account";
        $message = "Please click the link to activate you account. <br>";
        $message .= "http://localhost/authentication_stack/activate.php?email=$email&code=$validation_code";
        $headers = "From: noreply@yourwebsite.com";
        // call email function
        send_email($email, $subject, $message, $headers);

        return true;
    }
}



/********** Activate functions **********/


/**Activate the user
 * This function will activate the user status in the DB when the user clicks on the activation email
 * 
 * @return void Set a success message in sessions and redirect the user to the login page
 */
function activate_user()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['email'])) {
            // clean the data
            $email = clean($_GET['email']);
            $validation_code = clean($_GET['code']);
            // Escape the data
            $email = escape($email);
            $validation_code = escape($validation_code);

            // Get the user
            $sql = "SELECT id FROM users 
                    WHERE email = '$email' 
                    AND validation_code = '$validation_code'";
            $result = query($sql);
            confirm($result);

            // Activate the user in the DB
            if (row_count($result) == 1) {

                // Set activation to 1
                $sql2 = "UPDATE users 
                        SET active=1, validation_code = 0 
                        WHERE email = '$email'
                        AND validation_code = '$validation_code'";
                $result2 = query($sql2);
                confirm($result2);

                // Set successfull activation message in a session
                set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Account Activated!</strong> <a class='text-decoration-none text-reset' href='login.php'>Please login<a>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

                // Redirect to login
                redirect("login.php");
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <strong>Account not activated, user not found</strong> Please try and click (or copy exactly) the activation email link again
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            }
        }
    }
}
