<?php
class Database {
    private $pdos = [];
    private $connectionName;

    public function __construct(array $config)
    {
        foreach($config as $name => $conf) {
            $this->connect($name, $conf['dbname'], $conf['username'], $conf['password']);
        }
    }

    public function in(string $connectionName)
    {
        $this->connectionName = $connectionName;
        return $this;
    }

    public function connect(string $connectionName, string $dbname, string $username, string $password) : void
    {
        $this->pdos[$connectionName] = new PDO("mysql:host=localhost:3306;dbname=$dbname", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function getPDO() {
        if(!isset($this->pdos[$this->connectionName])) {
            throw new InvalidArgumentException("La connexion n'existe pas");
        }
        return $this->pdos[$this->connectionName];
    }
    /**
     * Crée une ressource dans la BDD
     */
    public function create() : int
    {
        return 0;
    }
    /**
     * Lit une ligne dans la BDD
     */
    public function read() : array
    {
        return [];
    }
   
    /**
     * Supprime une ligne dans la BDD
     */
    public function delete() : int
    {
        return 0;
    }
    /**
     * Récupère des résultats dans la BDD
     * Equivalent à SELECT * sans LIMIT
     */
    public function rawSelect(string $sql, array $args = []) : array
    {
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Exécute une requête SQL DDL "en brut" dans la BDD
     * Data Definition Language
     */
    public function rawAlter($sql, $args = []) {
        $stmt = $this->getPDO()->prepare($sql);
        return $stmt->execute($args);
        // Pas de fetch ou fetchAll ici, il s'agit de création/modification de table
    }
    
    //creation d'un compte
    public function create_account(string $username, string $name)
    {
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE create_account(IN username CHAR(50), IN name CHAR(50), IN user_id INT)
        BEGIN
            DECLARE @user_id INT
            INSERT INTO Users(username) VALUES(". $username .");
            SET @user_id = 
                (SELECT user_id
                FROM Users
                WHERE username = ". $username .")
            INSERT INTO Tamagotshi(name, user_id) VALUES(". $name .", @user_id);
        END; 
        ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }

    //creation d'un tamagotshi
    public function create_tamagotshi(string $name)
    {
        //on récupère l'utilisateur afin de faire une requete SQL pour récupérer son id
        // et ainsi pourvoir liée le nouveau tamagotshi à l'utilisateur
        $current_user = get_current_user();
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE create_tamagotshi(IN name CHAR(50), IN user_id INT)
        BEGIN

            SET @user_id = 
                (SELECT user_id
                FROM Users
                WHERE username = ". $current_user .")
            INSERT INTO Tamagotshi(name, user_id) VALUES(". $name .", @user_id);
        END; 
        ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }

    //fonction appelée quand l'utilisateur nourrit le Tamagotshi
    public function eat(INT $Tamagotshi_id){
        //dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE eat(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('EAT',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }

    //fonction appelée quand l'utilisateur fait boire le Tamagotshi
    public function drink(INT $Tamagotshi_id){
        //dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE drink(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('DRINK',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }

    //fonction appelée quand l'utilisateur fait dormir le Tamagotshi
    public function bedtime(INT $Tamagotshi_id){
        //dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE bedtime(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('BEDTIME',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }

    //fonction appelée quand l'utilisateur divertit le Tamagotshi
    public function enjoy(INT $Tamagotshi_id){
        //dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE enjoy(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('ENJOY',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call create_account");
        $call_procedure->execute();
    }
}