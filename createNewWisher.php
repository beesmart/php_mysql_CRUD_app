<?php
require_once("Includes/db.php");

/**other variables */
$userNameIsUnique = true;
$passwordIsValid = true;
$userIsEmpty = false;
$passwordIsEmpty = false;
$password2IsEmpty = false;

/** Check that the page was requested from itself via the POST method. */
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    /** Check whether the user has filled in the wisher's name in the text field "user" */
    if ($_POST['user']==""){
        $userIsEmpty = true;
    }
    
    /** Create database connection - not needed */
//    $con = mysqli_connect("localhost", "phpuser", "phpuserpw");
//    if (!$con) {
//        exit('Connect Error (' . mysqli_connect_errno() . ') '
//                . mysqli_connect_error());
//    }
//    //set the default client character set 
//    mysqli_set_charset($con, 'utf-8');
//
//    /** Check whether a user whose name matches the "user" field already exists */
//    mysqli_select_db($con, "tut_wishlist");
//    $user = mysqli_real_escape_string($con, $_POST['user']);
//    $wisher = mysqli_query($con, "SELECT id FROM wishers WHERE name='".$user."'");
//    $wisherIDnum=mysqli_num_rows($wisher);
//    if ($wisherIDnum) {
//        $userNameIsUnique = false;
//    }
    
    /** Create database connection */
    
    $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_POST['user']);
    if ($wisherID) {
        $userNameIsUnique = false;
    }

    /** Check whether a password was entered and confirmed correctly */
    if ($_POST['password']=="")
    $passwordIsEmpty = true;
    if ($_POST['password2']=="")
    $password2IsEmpty = true;
    if ($_POST['password']!=$_POST['password2']) {
        $passwordIsValid = false;
    }


    /** Check whether the boolean values show that the input data was validated successfully.
     * If the data was validated successfully, add it as a new entry in the "wishers" database.
     * After adding the new entry, close the connection and redirect the application to editWishList.php.
     not needed, see following below */ 
//    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) {
//        $password = mysqli_real_escape_string($con, $_POST['password']);
//        mysqli_select_db($con, "tut_wishlist");
//        mysqli_query($con, "INSERT wishers (name, password) VALUES ('" . $user . "', '" . $password . "')");
//        mysqli_free_result($wisher);
//        mysqli_close($con);
//        header('Location: editWishList.php');
//        exit;
//    }
    
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) {
    WishDB::getInstance()->create_wisher($_POST["user"], $_POST["password"]);
    session_start();
    $_SESSION['user'] = $_POST['user'];
    header('Location: editWishList.php' );
    exit;
    
}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><meta charset=UTF-8">
    <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>
        Welcome!<br>
        <form action="createNewWisher.php" method="POST">
            Your name: <input type="text" name="user"/><br/>
            <?php
            /** Display error messages if "user" field is empty or there is already a user with that name */
            if ($userIsEmpty) {
                echo ("Enter your name, please!");
                echo ("<br/>");
            }
            if (!$userNameIsUnique) {
                echo ("The person already exists. Please check the spelling and try again");
                echo ("<br/>");
            }
            ?>
            Password: <input type="password" name="password"/><br/>
            <?php
            /** Display error messages if the "password" field is empty */
            if ($passwordIsEmpty) {
                echo ("Enter the password, please");
                echo ("<br/>");
            }
            ?>
            Please confirm your password: <input type="password" name="password2"/><br/>
            <input type="submit" value="Register"/>
            <?php
            /** Display error messages if the "password2" field is empty
             * or its contents do not match the "password" field
             */
            if ($password2IsEmpty) {
                echo ("Confirm your password, please");
                echo ("<br/>");
            }
            if (!$password2IsEmpty && !$passwordIsValid) {
                echo ("<div>The passwords do not match!</div>");
                echo ("<br/>");
            }
            ?>

        </form>

    </body>
</html>