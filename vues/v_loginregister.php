<?php      
session_start();
if(isset($_POST['submit']))
{
    $username=htmlspecialchars(trim($_POST['username']));

    if($username !== "")
    {
        
        $requete = "SELECT * FROM users where username =".$username;
        $exec_requete = mysqli_query($db,$requete);
        $reponse      = mysqli_fetch_array($exec_requete);
        $count = $reponse['count(*)'];
        if($count!=0) // nom d'utilisateur et mot de passe correctes
        {
           $_SESSION['username'] = $username;
           header('Location: v_usersTam.php');
        }
        else
        {
           header('Location: v_loginregister.php?erreur=1'); // utilisateur ou mot de passe incorrect
        }
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
           
        <form name="f1" action="v_loginregister.php" method = "POST">
                <h2>Connexion</h2>
                
                <h4><b>Nom d'utilisateur</b></h4>
                <input type="text" placeholder="Username 🤗" name="username">
                <p>
                </p>
                <input type="submit" value='LOGIN' >
                
            </form>

        <form action="./v_usersTams.php" method="POST">
                
                <h2>Register</h2>

                <input type="text" placeholder="Entrer your username!😍" name="username" required>
                <input type="text" placeholder="Name your first Tamagotchi!😋" name="name" required>
                <p>
                </p>
                <input type="submit" id='submit' value='REGISTER HERE 🙌' >
            </form>
        </div>
    </body>
</html>

