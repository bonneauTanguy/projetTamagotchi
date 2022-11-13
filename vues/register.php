<?php
// Start PHP session at the beginning 
session_start();

require_once ("./bdd/Database.class.php");

$db = new Database([
    "projet_tama" => [
        "username" => "root",
        "password" => "29031999",
        "dbname" => "projet_tama"
    ]
]);
$db->in('projet_tama');

// create_account($username, $name);
?>

<html>
<head>
    <title>Register</title>
</head>

<body>
<link rel="stylesheet" href="./style/register.css" media="screen" type="text/css" />
    <br>
    <form action="register.php" method="post" onsubmit="return validate()">
                
                <h2>Register</h2>

                <input type="text" placeholder="Entrer your username!😍" name="username" required>
                <input type="text" placeholder="Name your first Tamagotchi!😋" name="name" required>
                <p>
                </p>
                <input type="submit" id='submit' value='REGISTER HERE 🙌' >
            </form>

    </form>
</body>

</html>
