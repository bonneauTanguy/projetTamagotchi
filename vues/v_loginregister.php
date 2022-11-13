
<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="./style/stylelogin.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="container">
           
        <form name="f1" action = "./vues/authentication.php" onsubmit = "return validation()" method = "POST">
                <h2>Connexion</h2>
                
                <h4><b>Nom d'utilisateur</b></h4>
                <input type="text" placeholder="Username 🤗" name="username">
                <p>
                </p>
                <input type="submit" id='submit' value='LOGIN' >
                
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

