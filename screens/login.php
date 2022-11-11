<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="stylelogin.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="container">
           
            <form action="verification.php" method="POST">
                <h2>Connexion</h2>
                
                <h4><b>Nom d'utilisateur</b></h4>
                <input type="text" placeholder="Username 🤗" name="username">
                <p>
                </p>
                <input type="submit" id='submit' value='LOGIN' >
                
                <?php
                if(isset($_GET['erreur'])){
                    $err = $_GET['erreur'];
                    if($err==1 || $err==2)
                        echo "<p style='color:red'>Utilisateur incorrect</p>";
                }
                ?>
            </form>
        <form action="creationAccount.php" method="POST">
                
                <h2>Register</h2>

                <input type="text" placeholder="Entrer your username!😍" name="username" required>
                <input type="text" placeholder="Name your first Tamagotchi!😋" name="username" required>
                <p>
                </p>
                <input type="submit" id='submit' value='REGISTER HERE 🙌' >

                <?php
                if(isset($_GET['erreur'])){
                    $err = $_GET['erreur'];
                    if($err==1 || $err==2)
                        echo "<p style='color:red'>Utilisateur incorrect</p>";
                }
                ?>
            </form>
        </div>
    </body>
</html>