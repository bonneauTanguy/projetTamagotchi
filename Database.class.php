<?php
class Database {
    private $pdo;

    public function __construct($dbname, $username, $password) 
    {
        $this->pdo = new PDO("mysql:host=localhost:3306;dbname=$dbname", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
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

        $stmt = $this->pdo->prepare($sql);
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

        // SELECT * FROM [table]
        $stmt = $this->pdo->prepare("SELECT * FROM " . $table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Exécute une requête SQL DML "en brut" dans la BDD
     * Data Manipulation Language
     */
    public function rawSelect(string $sql, array $args = []) : array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Exécute une requête SQL DDL "en brut" dans la BDD
     * Data Definition Language
     */
    public function rawAlter($sql, $args = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($args);
        // Pas de fetch ou fetchAll ici, il s'agit de création/modification de table
    }
}