<?php
require_once('./vendor/autoload.php');

require_once('./bdd/Database.class.php');
require_once('./bdd/Migration.class.php');


// Point de départ, création des migrations
$db = new Database([
    "projet_tama" => [
        "username" => "root",
        "password" => "29031999",
        "dbname" => "projet_tama"
    ]
]);
$db->in('projet_tama');

function migrate($db)
{
    $migration = new Migration($db);
    $migration->addQuery("CREATE TABLE Users(
        user_id INT AUTO_INCREMENT,
        username VARCHAR(50),
        PRIMARY KEY(user_id)
    );")
    ->addQuery("CREATE TABLE Tamagotshi(
        Tamagotshi_id INT AUTO_INCREMENT,
        name VARCHAR(50),
        level INT DEFAULT 1,
        creation_date DATETIME DEFAULT NOW(),
        action_number INT DEFAULT 0,
        hunger INT DEFAULT 70,
        thirst INT DEFAULT 70,
        sleep INT DEFAULT 70,
        boredom INT DEFAULT 70,
        user_id INT NOT NULL,
        PRIMARY KEY(Tamagotshi_id),
        FOREIGN KEY(user_id) REFERENCES Users(user_id)
    );")
    ->addQuery("CREATE TABLE Actions(
       action_id INT AUTO_INCREMENT,
       name VARCHAR(50),
       action_date DATETIME DEFAULT NOW(),
       Tamagotshi_id INT NOT NULL,
       PRIMARY KEY(action_id),
       FOREIGN KEY(Tamagotshi_id) REFERENCES Tamagotshi(Tamagotshi_id)
    );")
    ->addQuery("CREATE TABLE Death(
        death_id VARCHAR(50) AUTO_INCREMENT,
        death_date DATETIME,
        Tamagotshi_id INT NOT NULL,
        PRIMARY KEY(death_id),
        UNIQUE(Tamagotshi_id),
        FOREIGN KEY(Tamagotshi_id) REFERENCES Tamagotshi(Tamagotshi_id)
    );");
    $migration->execute();
}
/**
 * On execute tout d'abord avec migrate($db);puis on peut le commenter :)  
 */
//migrate($db);
?>
<?php 
require_once('./vues/login.php'); 
require_once('./vues/register.php');
require_once('./vues/v_usersTams.php');

?>
