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
        Wish List of <?php echo htmlentities($_GET["user"]) . "<br/>"; ?>

<?php
    require_once("Includes/db.php");

//  The below was refactored using the WishDB include file/class and it's methods
//  
//  $con = mysqli_connect("localhost", "phpuser", "phpuserpw");
//  if (!$con) {
//     exit('Connect Error (' . mysqli_connect_errno() . ') '
//            . mysqli_connect_error());
//  }
//
//  //set the default client character set 
//  mysqli_set_charset($con, 'utf-8');
//// using the $con (preceding variable) find the db add the previous GET request to a
////  new user variable and query against existing wisher value, if not existing
//  //tell user it's not found.
//  mysqli_select_db($con, "tut_wishlist");
//  $user = mysqli_real_escape_string($con, htmlentities($_GET["user"]));
//  $wisher = mysqli_query($con, "SELECT id FROM wishers WHERE name='" . $user . "'");
//  if (mysqli_num_rows($wisher) < 1) {
//     exit("The person " . htmlentities($_GET["user"]) . " is not found. Please check the spelling and try again");
//  }
//  $row = mysqli_fetch_row($wisher);
//  $wisherID = $row[0];
//  mysqli_free_result($wisher);
    
    // our new code calls the getInstance function from our new DB class. getInstance returns an instance
    // wishDB and the code calls the get_wisher_id_by_name function within the instance. If the req. wisher
    // is not found in the db, the code kills the process and throws up error msg. The connection is opened
    //to the db by the wishdb class.
    
    $wisherID = WishDB::getInstance()->get_wisher_id_by_name($_GET["user"]);
    if (!$wisherID) {
        exit("The person " .$_GET["user"]. " is not found. Please check the spelling and try again");
    }
    
  ?>
  
  <table border="black">
    <tr>
        <th>Item</th>
        <th>Due Date</th>
    </tr>
     <?php
     // load the data from the previous request into a table using result variable
        /** $result = mysqli_query($con, "SELECT description, due_date FROM wishes WHERE wisher_id=" . $wisherID); */
        $result = WishDB::getInstance()->get_wishes_by_wisher_id($wisherID);
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr><td>" . htmlentities($row["description"]) . "</td>";
            echo "<td>" . htmlentities($row["due_date"]) . "</td></tr>\n";
        }
        mysqli_free_result($result);
//        mysqli_close($con);
    ?>
  </table>
        
 
        

        
    </body>
</html>
