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
     * Met à jour une ligne dans la BDD
     */
    public function update(string $table, array $fields, array $wheres = []) : int
    {
        // Un test vraiment simpliste pour éviter les injections.
        // Une table ne peut pas en théorie (ou ne devrait pas) avoir d'espace.
        if(strpos($table, " ") !== false) {
            // Non.
            throw new InvalidArgumentException("Une table ne doit pas avoir d'espace");
        }

        // Ici, c'est une sorte de QueryBuilder
        // Une partie de notre code qui crée une requête
        // Ca pourrait être une classe et des objets à côté
        $sql = "UPDATE " . $table . " SET ";
        foreach(array_keys($fields) as $fieldKey) {
            $sql .= "`" . $fieldKey . "` = ?,";
        }
        $sql = rtrim($sql, ",") . " WHERE ";

        $whereKeys = array_keys($wheres);
        foreach($whereKeys as $i => $whereKey) {
            $sql .= "`" . $whereKey . "` = ? ";
            // Tant que ce n'est pas le dernier
            if($i != count($whereKeys) - 1) {
                $sql = " AND ";
            }
        }
        $sql = rtrim($sql, " ") . ";";

        $arguments = array_merge(
            array_values($fields), // ["Toto", "2022-11..."]
            array_values($wheres) // ["Action"]
        );

        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($arguments);
        return $stmt->rowCount();

        // UPDATE table <- table
        // SET clef = valeur, clef = valeur, clef = valeur... <- modifications, array
        // WHERE clef = valeur AND clef = valeur AND clef = valeur... <- conditions, array
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
    public function list(string $table) : array
    {
        // Un test vraiment simpliste pour éviter les injections.
        // Une table ne peut pas en théorie (ou ne devrait pas) avoir d'espace.
        if(strpos($table, " ") !== false) {
            // Non.
            throw new InvalidArgumentException("Une table ne doit pas avoir d'espace");
        }
        
        // Système de cache : nom du fichier contenant les résultats de la requête
        //$filename = realpath('.') . DIRECTORY_SEPARATOR . md5($table) . ".cache.txt";
        // Si ce fichier existe, on renvoie son contenu
        // if(file_exists($filename)) {
        //     return json_decode(file_get_contents($filename));
        // }

        // SELECT * FROM [table]
        $stmt = $this->getPDO()->prepare("SELECT * FROM " . $table);
        $stmt->execute();
        
        // On stocke le contenu de la requête dans un fichier
        // pour accélérer les requêtes suivantes
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //file_put_contents($filename, json_encode($results));

        // On retourne les résultats
        return $results;
    }

    public function listClass(string $table, string $className) : array
    {
        // Sans système de cache pour ne pas complexifier
        $stmt = $this->getPDO()->prepare("SELECT * FROM " . $table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, $className);
    }
    /**
     * Exécute une requête SQL DML "en brut" dans la BDD
     * Data Manipulation Language
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
    public function create_account(string $username)
    {
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE create_account(IN username CHAR(50))
        BEGIN
            INSERT INTO Users(username) VALUES(". $username .");
        END; 
        ");
        $stmt->execute();
        $create_account = $this->getPDO()->query("call create_account");
        $create_account->execute();
    }
}