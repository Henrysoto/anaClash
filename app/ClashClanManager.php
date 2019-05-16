<?php
namespace App;

class ClashClanManager
{
    private $pdo;
    private $tag;
    private $name;
    private $size;
    private $score;
    private $members;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        
    }
}