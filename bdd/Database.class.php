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
    
    //Creation d'un compte
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

    //Creation d'un tamagotshi
    public function create_tamagotshi(string $name)
    {
        //On récupère l'utilisateur afin de faire une requete SQL pour récupérer son id
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
        $call_procedure = $this->getPDO()->query("call create_tamagotshi");
        $call_procedure->execute();
    }

    //Fonction appelée quand l'utilisateur nourrit le Tamagotshi
    public function eat(INT $Tamagotshi_id){
        //Dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE eat(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('EAT',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call eat");
        $call_procedure->execute();
    }

    //Fonction appelée quand l'utilisateur fait boire le Tamagotshi
    public function drink(INT $Tamagotshi_id){
        //Dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE drink(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('DRINK',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call drink");
        $call_procedure->execute();
    }

    //Fonction appelée quand l'utilisateur fait dormir le Tamagotshi
    public function bedtime(INT $Tamagotshi_id){
        //Dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE bedtime(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('BEDTIME',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call bedtime");
        $call_procedure->execute();
    }

    //Fonction appelée quand l'utilisateur divertit le Tamagotshi
    public function enjoy(INT $Tamagotshi_id){
        //Dans la table action on ajoute une ligne avec le nom de l'action et l'id du tamagotshi qui effectue l'action
        $stmt = $this->getPDO()->prepare("
        CREATE PROCEDURE enjoy(IN tamagotshi_id INT)
            BEGIN
                INSERT INTO Actions (name, Tamagotshi_id) VALUES ('ENJOY',". $Tamagotshi_id .");
            END;
            ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call enjoy");
        $call_procedure->execute();
    }

    //Fonction appelée pour connaitre le niveau du tamagotshi
    public function level(INT $Tamagotshi_id){
        $stmt = $this->getPDO()->prepare("
        DELIMITER //
        CREATE FUNCTION LEVEL(tamagotshi_id INTEGER) RETURNS int DETERMINISTIC
        BEGIN
            DECLARE level_tamagotshi INTEGER;
            select level INTO level_tamagotshi from projet_tama.tamagotshi WHERE Tamagotshi_id = ". $Tamagotshi_id .";
            RETURN level_tamagotshi;
        END //
        ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call level");
        $call_procedure->execute();
    }

    //Fonction appelée pour savoir si le tamagotshi est vivant ou non
    public function is_alive(INT $Tamagotshi_id){
        $stmt = $this->getPDO()->prepare("
        DELIMITER //
        CREATE FUNCTION IS_ALIVE(tamagotshi_id INTEGER) RETURNS bool DETERMINISTIC
        BEGIN
            DECLARE death_tamagotshi INTEGER;
            select COUNT(*) INTO death_tamagotshi from projet_tama.death WHERE Tamagotshi_id = ". $Tamagotshi_id .";
            RETURN death_tamagotshi > 0;
        END // 
        ");
        $stmt->execute();
        $call_procedure = $this->getPDO()->query("call IS_ALIVE");
        $call_procedure->execute();
    }
    //Trigger permettant de modifier les statistiques du tamagotshi lorsqu'une ligne est ajouter dans la table Actions
    public function ins_actions (INT $Tamagotshi_id){
        $stmt = $this->getPDO()->prepare("
        DELIMITER //

        CREATE TRIGGER ins_actions 
        BEFORE INSERT 
        ON actions FOR EACH ROW
        BEGIN
            DECLARE oldTamagotshiParameter INTEGER;
            DECLARE oldTamagotshiLevel INTEGER;
        
            DECLARE death_tamagotshi INTEGER;
        
            select is_alive(NEW.Tamagotshi_id) INTO death_tamagotshi;

            IF death_tamagotshi = 0 THEN
        
                -- Récupération du level
                SELECT level INTO oldTamagotshiLevel FROM tamagotshi WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
            
                -- Faim
                IF strcmp(NEW.name, 'EAT') = 0 THEN
                    SELECT hunger INTO oldTamagotshiParameter FROM tamagotshi WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                    IF oldTamagotshiParameter <= 80 THEN
                    
                        UPDATE tamagotshi SET 
                            hunger = hunger + (30 + (oldTamagotshiLevel - 1)),
                            thirst = thirst - (10 + (oldTamagotshiLevel - 1)),
                            sleep = sleep - (5 + (oldTamagotshiLevel - 1)),
                            boredom = boredom - (5 + (oldTamagotshiLevel - 1))
                        WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                
                        -- Stat pas au dessus de 100
                        IF oldTamagotshiParameter + (30 + (oldTamagotshiLevel - 1)) > 100 THEN
                        UPDATE tamagotshi SET sleep = 100 WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                    END IF;
                END IF;
            END IF;

            -- Soif
            IF strcmp(NEW.name, 'DRINK') = 0 THEN
                SELECT thirst INTO oldTamagotshiParameter FROM tamagotshi WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                IF oldTamagotshiParameter <= 80 THEN
                    
                    UPDATE tamagotshi SET 
                        hunger = hunger - (10 + (oldTamagotshiLevel - 1)),
                        thirst = thirst + (30 + (oldTamagotshiLevel - 1)),
                        sleep = sleep - (5 + (oldTamagotshiLevel - 1)),
                        boredom = boredom - (5 + (oldTamagotshiLevel - 1))
                    WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                
                    -- Stat pas au dessus de 100
                    IF oldTamagotshiParameter + (30 + (oldTamagotshiLevel - 1)) > 100 THEN
                        UPDATE tamagotshi SET sleep = 100 WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                    END IF;
                END IF;
            END IF;
        
            -- Dormir
            IF strcmp(NEW.name, 'BEDTIME') = 0 THEN
                SELECT sleep INTO oldTamagotshiParameter FROM tamagotshi WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                IF oldTamagotshiParameter <= 80 THEN
                    
                    UPDATE tamagotshi SET 
                        hunger = hunger - (10 + (oldTamagotshiLevel - 1)),
                        thirst = thirst - (15 + (oldTamagotshiLevel - 1)),
                        sleep = sleep + (30 + (oldTamagotshiLevel - 1)),
                        boredom = boredom - (15 + (oldTamagotshiLevel - 1))
                    WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                
                    -- Stat pas au dessus de 100
                    IF oldTamagotshiParameter + (30 + (oldTamagotshiLevel - 1)) > 100 THEN
                        UPDATE tamagotshi SET sleep = 100 WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                    END IF;
                END IF;
            END IF;
        
            -- Jouer
            IF strcmp(NEW.name, 'ENJOY') = 0 THEN
                SELECT boredom INTO oldTamagotshiParameter FROM tamagotshi WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                IF oldTamagotshiParameter <= 80 THEN
                    
                    UPDATE tamagotshi SET 
                        hunger = hunger - (5 + (oldTamagotshiLevel - 1)),
                        thirst = thirst - (5 + (oldTamagotshiLevel - 1)),
                        sleep = sleep - (5 + (oldTamagotshiLevel - 1)),
                        boredom = boredom + (15 + (oldTamagotshiLevel - 1))
                    WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                
                    -- Stat pas au dessus de 100
                    IF oldTamagotshiParameter + (15 + (oldTamagotshiLevel - 1)) > 100 THEN
                        UPDATE tamagotshi SET sleep = 100 WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
                    END IF;
                END IF;
            
        end if;
        
            -- Monter de level
            IF oldTamagotshiParameter <= 80 THEN
                UPDATE tamagotshi SET level = ((SELECT COUNT(*) FROM actions WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id) DIV 10 + 1) WHERE ". $Tamagotshi_id ." = NEW.Tamagotshi_id;
            END IF;
        
        END IF;	
		
        END// ");
    }

    //Trigger permettant d'ajouter le tamagotshi dans la table Death lorsque une des statistiques est à 0
    public function after_actions (INT $Tamagotshi_id){
        $stmt = $this->getPDO()->prepare("
        DELIMITER //

        CREATE TRIGGER after_actions 
        AFTER INSERT 
        ON actions FOR EACH ROW
        BEGIN
            DECLARE tama_hunger INTEGER;
            DECLARE tama_thrink INTEGER;
            DECLARE tama_sleep INTEGER;
            DECLARE tama_boredom INTEGER;
        
            SELECT hunger, thrink, sleep, boredom into tama_hunger, tama_thrink, tama_sleep, tama_boredom
            FROM tamagotshi
            WHERE Tamagotshi_id = NEW.Tamagotshi_id;
        
            IF (tama_hunger <= 0 OR tama_thrink <= 0 OR tama_sleep <= 0 OR tama_boredom <= 0) THEN
                INSERT INTO death (Tamagotshi_id) VALUES (NEW.Tamagotshi_id);
            END IF;
        END");
    }
}