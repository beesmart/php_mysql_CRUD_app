<?php
        // open a session array to retrieve data and verify the array contains the correct matching user key
        session_start();
        if (array_key_exists("user", $_SESSION)) {
            echo "Hello " . $_SESSION['user'];
        } 
        //redirect a user who's not logged in and cancel the php code execution
        else {
            header('location: index.php');
            exit;
        }
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>
        <table border="black">
            <tr><th>Item</th><th>Due Date</th></tr>
            <?php
            // open connect, grab the id and wishes. while wishes exist display them.
            require_once('Includes/db.php');
            $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_SESSION["user"]);
            $result = WishDB::getInstance()->get_wishes_by_wisher_id($wisherID);
            while($row = mysqli_fetch_array($result)):

               echo "<tr><td>" . htmlentities($row['description']) . "</td>";
               echo "<td>" . htmlentities($row['due_date']) . "</td>";
               // to transfer wish ID we store it in a VAR
               $wishID = $row["id"];
?>
            <td>
                <form name="editWish" action="editWish.php" method="GET">
                    <input type="hidden" name="wishID" value="<?php echo $wishID; ?>"/>
                    <input type="submit" name="editWish" value="Edit"/>
                </form>
            </td>
            
            <td>
                <form name="deleteWish" action="deleteWish.php" method="POST">
                    <input type="hidden" name="wishID" value="<?php echo $wishID; ?>"/>
                    <input type="submit" name="deleteWish" value="Delete"/>
                </form>
            </td>
            
            <?php
            echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
            <form name="addNewWish" action="editWish.php">            
                <input type="submit" value="Add Wish">
            </form>
            <form name="backToMainPage" action="index.php">
                <input type="submit" value="Back To Main Page"/>
            </form>
    </body>
</html>
