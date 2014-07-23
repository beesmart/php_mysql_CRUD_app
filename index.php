<?php

    require_once("Includes/db.php");
    $logonSuccess = false;

    /** verify user's credentials - Is the request method = POST? If yes it's because the user was redirected after
     * submitting the logon form. Check their credentials with the verify_wisher_credentials function.
     * If it returns true a wisher is already registered with the matching details in the db.
     * This kicks-off the session.
     */
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $logonSuccess = (WishDB::getInstance()->verify_wisher_credentials($_POST['user'], $_POST['userpassword']));
        if ($logonSuccess == true) {
            session_start();
            $_SESSION['user'] = $_POST['user'];
            header('location: editWishList.php');
            exit;
        }
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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Wish List Application</title>
        <link href="wishlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>

    <div id="content">
        <div class="logo">
            <img src="static/logo1.jpg" alt="logo"/>
            <img src="static/logo2.jpg" alt="logo"/>
            <br/>
            <img src="static/logo3.jpg" alt="logo"/>
            <img src="static/logo5.jpg" alt="logo"/>
        </div>
        <div class="logon">
            <input type="submit" name="myWishList" value="My Wish List >>" onclick="javascript:showHideLogonForm()"/>
            <form name="logon" action="index.php" method="POST"
                  style="visibility:<?php if ($logonSuccess)
                        echo "hidden"; else
                        echo "visible"; ?>">
                Username:
                <input type="text" name="user"/>

                Password:
                <input type="password" name="userpassword"/><br/>
                
                <div class="error">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        if (!$logonSuccess)
                            echo "Invalid name and/or password";
                    }
                    ?>
                </div>
                <input type="submit" value="Edit My Wish List"/>
            </form>
        </div>
        <div class="showWishList">
            <input type="submit" name="showWishList" value="Show Wish List of >>" onclick="javascript:showHideShowWishListForm()"/>

            <form name="wishList" action="wishlist.php" method="GET" style="visibility:hidden">
                <input type="text" name="user"/>
                <input type="submit" value="Go" />
            </form>
        </div>
        <div class="createWishList">
            Still don't have a wish list?! <a href="createNewWisher.php">Create now</a>
        </div>
    </div>
        <script type="text/javascript">
            function showHideLogonForm() {
                if (document.all.logon.style.visibility == "visible"){
                    document.all.logon.style.visibility = "hidden";
                    document.all.myWishList.value = "<< My Wish List";
                }
                else {
                    document.all.logon.style.visibility = "visible";
                    document.all.myWishList.value = "My Wish List >>";
                }
            }

            function showHideShowWishListForm() {
                if (document.all.wishList.style.visibility == "visible") {
                    document.all.wishList.style.visibility = "hidden";
                    document.all.showWishList.value = "Show Wish List of >>";
                }
                else {
                    document.all.wishList.style.visibility = "visible";
                    document.all.showWishList.value = "<< Show Wish List of";
                }
            }
        </script>
    </body>
</html>
