<?php
session_start();

// This page can be accessed only after login
// Redirect user to login page, if user username is not available in session
if (!isset($_SESSION["username"])) {
    header("location: login.php");
}
?>

<html>
    <H1>hello</H1>

    
    
</html>