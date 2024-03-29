<?php

/********** Include PHPMailer Library **********/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("includes/mail/src/Exception.php");
require("includes/mail/src/PHPMailer.php");
require("includes/mail/src/SMTP.php");


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
 * @param string $email The user's email address
 * 
 * @return boolean True if it exist and false if not
 */
function email_exist($email)
{
    $sql = "SELECT id from users WHERE email = '$email'";

    $result = query($sql);
    confirm($result);

    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}

/** Check if the username is in use
 * 
 * @param string $username The user's username
 * 
 * @return boolean True if it exist and false if not
 */
function username_exist($username)
{
    $sql = "SELECT id from users WHERE username = '$username'";

    $result = query($sql);
    confirm($result);

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
 * @return true Return true if the message was sent false if not
 */
function send_email($email, $subject, $message)
{
    $mail = new PHPMailer(TRUE);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'user@example.com';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';                                //Use extended character set for language support

        //Recipients
        $mail->setFrom('noreply@meliorateafrica.com');
        $mail->addAddress($email);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('info@MeliorateAfrica.com', 'Meliorate Africa');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        echo "<p class='callout-success'>The email has been sent.</p>";
        return true;
    } catch (Exception $e) {
        echo "<p class='callout-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        return false;
    }
}


/********** Validation functions **********/

/** Validate registration fields
 * 
 * Cleans input values. 
 * 
 * Check to see if $first_name, $last_name and $username is between the minimum and maximum allowed characters
 * 
 * Check that the username and email is not in use
 * 
 * @return string $error Returns error messages if any is found
 */
function validate_user_registration()
{
    $errors = [];
    $min = 3;
    $max = 25;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // assign and clean the posted data from the form
        $first_name = clean($_POST["first_name"]);
        $last_name = clean($_POST["last_name"]);
        $username =  clean($_POST["username"]);
        $email = clean($_POST["email"]);
        $password = clean($_POST["password"]);
        $confirm_password = clean($_POST["confirm_password"]);

        // validate to see if the data is the correct length
        if (strlen($first_name) <= $min || strlen($first_name) >= $max) {
            $errors[] = "First name length must be between {$min} and {$max} characters long";
        }
        if (strlen($last_name) <= $min || strlen($last_name) >= $max) {
            $errors[] = "Last name length must be between {$min} and {$max} characters long";
        }
        if (strlen($username) <= $min || strlen($username) >= $max) {
            $errors[] = "Username length must not be between {$min} and {$max} characters long";
        }

        // Check if username has been taken
        if (username_exist($username)) {
            $errors[] = "This username is already in use";
        }

        // check if email has been taken
        if (email_exist($email)) {
            $errors[] = "This email is already in use";
        }

        // Check that the password and confirm password fields match
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
                echo "<p class='callout-danger'>We where not able to register you, please try again</p>";
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

        // Check that the required fields are not empty
        if (empty($_POST['email'])) {
            $errors[] = "Your login email is required";
        }
        if (empty($_POST['password'])) {
            $errors[] = "Your password is required";
        }

        // Clean inputs
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        // check if the user selected the remember me checkbox
        $remember = isset($_POST['remember']);
        $remember = clean($remember);


        // Display errors when found
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='callout-danger'>$error</p><br>";
            }
        } else {

            // Call the user login functionality
            if (login_user($email, $password, $remember)) {

                // Redirect the user if login is successful
                redirect("index.php");
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
    confirm($result);

    // Check to see if a record was found
    if (row_count($result) == 1) {

        // retrieve the record from the DB as an array
        $row = fetch_array($result);

        // extract the user's password
        $db_password = $row['password'];
        $active = $row['active'];

        if ($active != 1) {
            echo "<p class='callout-danger'>User not active, please check your email for activation link</p>";
            return true;
        } elseif (password_verify($password, $db_password) && $active == 1) {

            // if remember was checked set a cookie to expire in 10years (315360000 seconds)
            if ($remember == "1") {
                setcookie('save_login', 'true', time() + 315360000);
            }

            // save the authentication in the session
            $_SESSION['logged_in'] = 'true';

            // if the password match return true
            return true;
        } else {
            // If the password does not match return false
            echo "<p class='callout-danger'>Incorrect login details</p>";
            return false;
        }

        return true;
    } else {
        return false;
        echo "<p class='callout-danger'>Incorrect login details</p>";
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
 * @param string $first_name    First name from the registration form
 * 
 * @param string $last_name     Last name from the registration form
 * 
 * @param string $username      Username from the registration form
 * 
 * @param string $email         Email from the registration form
 * 
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

    // encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // create a validation code
    $validation_code = md5($username . microtime());

    $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)
                VALUES ('$first_name','$last_name','$username','$email','$password','$validation_code','0')";

    // Execute the query
    $result = query($sql);
    confirm($result);

    // Prepare the email
    $subject = "Activate your account";
    $message = "Please click the link to activate you account. <br>";
    $message .= "http://localhost/authentication_stack/activate.php?email=$email&code=$validation_code";
    $headers = "From: noreply@yourwebsite.com";

    // Send the email by calling the email function
    send_email($email, $subject, $message);

    return true;
}

/********** Activate functions **********/


/** Activate the user
 * This function will activate the user status in the DB when the user clicks on the activation email
 * 
 * @return void Set a success message in sessions and redirect the user to the login page
 */
function activate_user()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_GET['email']) && isset($_GET['code'])) {
            // clean the data
            $email = clean($_GET['email']);
            $validation_code = clean($_GET['code']);
            // Escape the data
            $email = escape($email);
            $validation_code = escape($validation_code);

            // Get the user from the DB
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
                // Execute the statement
                $result = query($sql2);
                confirm($result);

                // Set successfull activation message in a session
                set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Account Activated!</strong> <a class='text-decoration-none text-reset' href='login.php'>Please login<a>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

                // Redirect to login
                redirect("login.php");
            } else {
                echo "<p class='callout-danger'>This user is not registered</p>";
            }
        } else {
            echo "<p class='callout-danger'>No/Incorrect activation data received.</p>";
        }
    } else {
        echo "<p class='callout-danger'>No/Incorrect activation data received.</p>";
    }
}

/********** Recovery functions **********/

/** Recover the user's password
 * 
 * Check to see if the SESSION token matches the POST token
 * 
 * If the email exist create a validation code, set a cookie and send the email
 * 
 * @return void Send the user a email link to reset password
 */
function recover_password()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_SESSION['token']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {

            // Clean and escape the data from the form on submission
            $email = clean($_POST['email']);
            $email = escape($email);

            // Send the email when a correct email is entered into the form
            if (email_exist($email)) {

                // Create a validation code
                $validation_code = md5($email . microtime());

                // Set cookie for reset to 1 hour (3600 seconds)
                setcookie('temp_access_code', $validation_code, time() + 3600);

                // send the validation code to the database
                $sql = "UPDATE users SET validation_code = '$validation_code' WHERE email = '$email'";
                // Execute the query
                $result = query($sql);
                confirm($result);

                // Assign email variable values
                $email = "$email";
                $subject = "Reset your password";
                $message = "Here is your password reset code: {$validation_code} click here to reset you password http://localhost/authentication_stack/code.php?email=$email&code=$validation_code";
                $headers = "From: noreply@yourwebsite.com";

                // Send the email 
                // *** Attention => remove the exclamation(!) from the send_email function
                if (!send_email($email, $subject, $message)) {

                    // Set a message
                    set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    An email has been sent to $email to reset your password. Email=> $email and code => $validation_code
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

                    // redirect user
                    redirect("code.php");
                }

                // *** Attention: add else statement back in for production
                // else {
                //     echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                //     <strong>Unable to send reset email</strong> Please try again later
                //     <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                // </div>";
                // };
            } else {
                // Show error if the email does not exist
                echo "<p class='callout-danger'>This email address does not exist</p>";
            }
        } else {
            echo "<p class='callout-danger'>The security token does not match</p>";
        }
    }
}

/** Reset the password
 * 
 * Check to see if the session is still valid
 * 
 * Check to see if email and validation code was received via GET method
 * 
 * Check that the submitted data comes from the form on the page
 * 
 * Check that the submitted passwords match
 * 
 * Clean the data
 * 
 * Has the password
 * 
 * @return void If all is in order update the user'spassword in the database and redirect the user to login page
 */
function reset_password()
{
    // Check that the cookie is still valid
    if (isset($_COOKIE['temp_access_code'])) {

        // Check that the email and code is received via get
        if (isset($_GET['email']) && isset($_GET['code'])) {

            // Check that the information comes from the submitted form
            if (isset($_GET['email']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']) {

                // Clean the received data
                $email = clean($_GET['email']);
                $verification_code = clean($_GET['code']);
                // Escape data
                $email = escape($email);
                $verification_code = escape($verification_code);

                // Check that passwords match
                if ($_POST['password'] === $_POST['confirm_password']) {
                    $password = clean($_POST['password']);
                    $password = escape($password);

                    // Hash the password
                    $password = password_hash($password, PASSWORD_DEFAULT);
                } else {
                    echo "<p class='callout-danger'>Passwords does not match</p>";
                }

                // Update the user's password in the database
                $sql = "UPDATE users
                        SET password = '$password',
                        validation_code = 0
                        WHERE email = '$email'
                        AND validation_code = '$verification_code'";
                // Execute the statement
                $result = query($sql);
                confirm($result);

                set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Your password has been changed
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

                // Redirect to login
                redirect("login.php");
            } else {
                echo "<p class='callout-danger'>Token does not match</p>";
            }
        } else {
            echo "<p class='callout-danger'>Email and verification code was not received</p>";
        }
    } else {
        echo "<p class='callout-danger'>Password reset time expired, please try again.</p>";
    }
}

/** Validate the validation code
 * 
 * Check to see if any values was passed via the URL
 * 
 * Check to see if the cookie has expired
 * 
 * Clean and escape the data automatically populated via the $_GET
 * 
 * Retrieve the user from the database
 * 
 * @return void If everything is in order redirect the user to the password reset page to reset the user's password
 *  
 */
function validate_code()
{
    if (isset($_COOKIE['temp_access_code'])) {

        // Check to see if any information is passed using the GET method (e.g. URL + ?name=value)
        if (
            isset($_GET['email']) &&
            $_GET['email'] != '' &&
            isset($_GET['code']) &&
            $_GET['code'] != ''

        ) {

            // Check to see if the submit button was pressed
            if (isset($_POST['code-submit'])) {

                // clean the data
                $email = clean($_POST['email']);
                $validation_code = clean($_POST['code']);
                // escape the data
                $email = escape($email);
                $validation_code = escape($validation_code);

                // Select the user from the database
                $sql = "SELECT * FROM users 
                        WHERE validation_code = '$validation_code' 
                        AND email = '$email'"; // maybe remove the email incase the user copy and paste the validation code || add a field for email

                // Execute and assign the results to $result
                $result = query($sql);
                confirm($result);

                // Check to see if the record was found
                if (row_count($result) == 1) {
                    // Set a message for the user to change the password
                    set_messages("<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Change your password.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

                    // Set a cookie for the password to be reset 5min (300 seconds)
                    setcookie('temp_access_code', $validation_code, time() + 3600);
                    // if all is good redirect to reset page
                    redirect("reset.php?email=$email&code=$validation_code");
                } else {
                    // if the user was not found show error
                    echo "<p class='callout-danger'>Please confirm reset code to reset your password </p>";
                }
            } elseif (isset($_POST['code-cancel'])) {
                // If the user clicks cancel then redirect to login
                redirect("login.php");
            }
        } else {
            echo "<p class='callout-danger'>Please enter your email and the verification code sent to you.</p>";
        }
    } else {

        // Set message if the cookie is expired
        set_messages("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Your validation code has expired, please use password recovery function again. 
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");

        // If the cookie is expired redirect the user to recover to send recovery email again.
        redirect("recover.php");
    }
}
