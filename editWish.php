<?php
    session_start();
    if (!array_key_exists("user", $_SESSION)) {
        header('Location: index.php');
        exit;
    }
    
    /*
     * load up db. Create instance.
     * retrieves the user id of user who is trying to add wish
     * init the wishdescempty var for displaying errors if needed
     * checks that the req method is POST, which means that data is from
     * the current page - editwish.php. does post array contain a back key?
     * if yes - the button was pressed before submitting the form, redirect the user
     * to the editwishlist.php, dont save data stop process.
     * 
     * if no - data submitted by pressing save changes. The code checks if the desc field
     * is empty. reload.
     * 
     * if desc field is entered - call insert function and return useer to editwishlist. 
     */
    require_once("Includes/db.php");
    $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_SESSION['user']);

    $wishDescriptionIsEmpty = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (array_key_exists("back", $_POST)) {
           header('Location: editWishList.php' ); 
           exit;
        } else
        if ($_POST['wish'] == "") {
            $wishDescriptionIsEmpty =  true;
        } 
          else if ($_POST["wishID"]=="") {
            WishDB::getInstance()->insert_wish($wisherID, $_POST["wish"], $_POST["dueDate"]);
            header('Location: editWishList.php' );
            exit;
        }
        else if ($_POST["wishID"]!="") {
            WishDB::getInstance()->update_wish($_POST["wishID"], $_POST["wish"], $_POST["dueDate"]);
            header('Location: editWishList.php' );
            exit;
} 
    }
    
/* 
 * Check the request method, if POST (means that the user made an unsuccessful attempt to save a wish,
 * with an empty description. 
 * If not POST (the user is newly arrived) the elements desc. date are empty
 * Both create an array $wish for storing the data.
 * EDIT: The code initializes the $wish array with three elements: id, description, and due_date. 
 * The values of these elements depend on the Server Request method. If the Server Request method is POST, 
 * the values are received from the input form. Otherwise, if the Server Request method is GET and the $_GET array 
 * contains an element with the key "wishID", the values are retrieved from the database by the function get_wish_by_wish_id. 
 * Finally, if the Server Request method is neither POST nor GET, which means the Add New Wish use case takes place, 
 * the elements are empty.
 */
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        $wish = array("id" => $_POST["wishID"], "description" => 
            $_POST["wish"], "due_date" => $_POST["dueDate"]);
    else if (array_key_exists("wishID", $_GET))
            $wish = mysqli_fetch_array (WishDB::getInstance()->get_wish_by_wish_id($_GET["wishID"]));
    else
        $wish = array("id" => "", "description" => "", "due_date" => "");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>

       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
       <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>
        <form name="editWish" action="editWish.php" method="POST">
            <input type="hidden" name="wishID" value="<?php echo $wish["id"];?>" />
            Describe your wish: <input type="text" name="wish"  value="<?php echo $wish['description'];?>" /><br/>
                <?php
                    // displays if the wishdescisempty var is true
                    if ($wishDescriptionIsEmpty) echo "Please enter description<br/>";
                ?> 
            When do you want to get it? <input type="text" name="dueDate" value="<?php echo $wish['due_date']; ?>"/><br/>
            <input type="submit" name="saveWish" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html> 

