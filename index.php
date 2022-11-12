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
        user_id INT,
        username VARCHAR(50),
        PRIMARY KEY(user_id)
    );")
    ->addQuery("CREATE TABLE Tamagotshi(
        Tamagotshi_id INT,
        name VARCHAR(50),
        level INT,
        creation_date DATETIME,
        eath_date DATETIME,
        action_number INT,
        hunger INT DEFAULT 70,
        thirst INT DEFAULT 70,
        sleep INT DEFAULT 70,
        boredom INT DEFAULT 70,
        user_id INT NOT NULL,
        PRIMARY KEY(Tamagotshi_id),
        FOREIGN KEY(user_id) REFERENCES Users(user_id)
    );")
    ->addQuery("CREATE TABLE Actions(
       action_id INT,
       name VARCHAR(50),
       action_date DATETIME,
       Tamagotshi_id INT NOT NULL,
       PRIMARY KEY(action_id),
       FOREIGN KEY(Tamagotshi_id) REFERENCES Tamagotshi(Tamagotshi_id)
    );")
    ->addQuery("CREATE TABLE Death(
        death_id VARCHAR(50),
        death_date DATE,
        Tamagotshi_id INT NOT NULL,
        PRIMARY KEY(death_id),
        UNIQUE(Tamagotshi_id),
        FOREIGN KEY(Tamagotshi_id) REFERENCES Tamagotshi(Tamagotshi_id)
    );");
    $migration->execute();
}
migrate($db);
?>
<?php require_once('screens/login.php') ?>
