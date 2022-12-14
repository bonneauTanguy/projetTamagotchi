<?php
require_once('./bdd/Database.class.php');

class Migration
{
    private array $queries = [];
    private Database $db;
    private int $i;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function addQuery(string $query): Migration
    {
        $this->queries[] = $query;
        return $this;
    }

    public function execute(): void
    {
        foreach ($this->queries as $query) {
            $this->db->rawAlter($query);
        }
    }

}
