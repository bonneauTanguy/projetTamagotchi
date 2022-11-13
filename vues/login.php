<?php
// Start PHP session at the beginning 
session_start();

// Create database connection using config file
include_once("./bdd/Database.class.php");

$db = new Database([
    "projet_tama" => [
        "username" => "root",
        "password" => "29031999",
        "dbname" => "projet_tama"
    ]
]);
$db->in('projet_tama');

// If form submitted, collect username form
if (isset($_POST['login'])) {
    $username    = $_POST['username'];

    // Check if a user exists with given username & password
    $result = mysqli_query($mysqli, "select 'username' from users
        where username =".$username."'" );

    // Count the number of user/rows returned by query 
    $user_matched = mysqli_num_rows($result);

    // Check If user matched/exist, store user username in session and redirect to page of tamagochis
    if ($user_matched > 0) {

        $_SESSION["username"] = $username;
        header("location: v_usersTams.php");
    } else {
        echo "User username is not matched <br/><br/>";
    }
}
?>

<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="./style/stylelogin.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="container">
        
        <form action="login.php" method="post" onsubmit="return validate()">
                <h2>Connexion</h2>
                
                <h4><b>Nom d'utilisateur</b></h4>
                <input type="text" placeholder="Username 🤗" name="username">
                <p>
                </p>
                <input type="submit" value='LOGIN' >
                
            </form>
        </div>
    </body>
</html>

