<?php

namespace App;

class SQLiteConnection
{
    private $pdo;
  
    public function connect()
    {
        if ($this->pdo == null)
            $this->pdo = new \PDO("sqlite:".Config::SQLITE_FILE_PATH);
            //$this->pdo->set_charset("utf8");
        return $this->pdo;
    }
}