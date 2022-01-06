<?php

/** Connection to the database
 * @return boolean $con Connection to the database
 */
$con = mysqli_connect("localhost", "loginDB_admin", "hOUxUA6!Ljw]K]r0", "login_db");

/** Escape special characters
 * @param string $string Contains the string to be Escaped/cleaned
 * 
 * @return string $string all special characters removed
 */
function escape($string)
{
    global $con;

    return mysqli_real_escape_string($con, $string);
}


/** Query the database
 * 
 * @param string $query SQL statement
 * 
 * @return boolean Result of the query
 */
function query($query)
{
    global $con;

    return mysqli_query($con, $query);
};

/** Confirm that a query is correct
 * @param mixed $result a SQL query
 * 
 * @return boolean true if the query is good false plus error if not
 */
function confirm($result)
{

    global $con;

    if (!$result) {
        die("Query Failed" . mysqli_error($con));
    }
}


/** Fetch arrays from the database
 * 
 * @param mixed $result Results from a database query
 * 
 * @return array $result Results Array
 */
function fetch_array($result)
{
    global $con;

    return mysqli_fetch_array($result);
}


/** get the number of rows
 * 
 * @return integer Number of rows in a result set
 */
function row_count($result)
{
    return mysqli_num_rows($result);
}
