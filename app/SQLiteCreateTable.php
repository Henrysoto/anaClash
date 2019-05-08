<?php

namespace App;

class SQLiteCreateTable
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function createClanTables()
    {
        $queries = ['
            CREATE TABLE IF NOT EXISTS clash_player(
                clash_id VARCHAR(255) PRIMARY KEY,
                clash_name VARCHAR(255) NOT NULL,
                clash_exp INTEGER NOT NULL,
                clash_trophies INTEGER NOT NULL,
                clash_wins INTEGER NOT NULL,
                clash_losses INTEGER NOT NULL,
                clash_battleCount INTEGER NOT NULL,
                clash_clan_id VARCHAR(255) NOT NULL)','
            CREATE TABLE IF NOT EXISTS clan_war(
                clash_war_date VARCHAR(255) PRIMARY KEY,
                clash_clan_id VARCHAR(255) NOT NULL,
                clash_war_seasonId INTEGER NOT NULL,
                clash_war_participants INTEGER NOT NULL,
                clash_war_wins INTEGER NOT NULL,
                clash_war_battlesPlayed INTEGER NOT NULL,
                clash_war_members VARCHAR(255) NOT NULL)','
            CREATE TABLE IF NOT EXISTS clan_info(
                clash_clan_id VARCHAR(255) PRIMARY KEY,
                clash_clan_name VARCHAR(255) NOT NULL,
                clash_clan_score INTEGER NOT NULL,
                clash_clan_size INTEGER NOT NULL,
                clash_clan_members VARCHAR(255) NOT NULL)'];
        
        foreach ($queries as $query)
            $this->pdo->exec($query);
    }

    public function getTableList()
    {
        $stmt = $this->pdo->query("
            SELECT name 
            FROM sqlite_master 
            WHERE type = 'table' 
            ORDER BY name");
        
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            $tables[] = $row['name'];
        
        return $tables;
    }
    
    public function getGet()
    {
        $stmt = $this->pdo->query("SELECT * FROM prout");
        
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            $data[] = $row['clash_id'];
        
        return $data;
    }
}