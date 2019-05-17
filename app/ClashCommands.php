<?php
namespace App;

class ClashCommands
{
    private $pdo;
    private $cmd;
    private $arg;
    private $state;
    private $tasks = array();

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->state = false;
    }

    public function cmdHelp()
    {
        print(PHP_EOL);
        print("  _______________________________________".PHP_EOL);
        print("(*                                       *)".PHP_EOL);
        printf("(*    AnaClash CLI %.1f by Henrygolant    *)".PHP_EOL, Config::VERSION);
        print("(* https://github.com/Henrysoto/anaClash *)".PHP_EOL);
        print("(*_______________________________________*)".PHP_EOL);
        print("\n\nListe des commandes:\n".PHP_EOL);
        print("\t-u\tFull update du clan".PHP_EOL);
        print("\t-s\tScan des membres du clan".PHP_EOL);
        print(PHP_EOL);
    }

    public function addTasks($cmd, $arg = null)
    {
        if (!is_null($arg))
            $this->tasks[$cmd] = $arg;
        else
            $this->tasks[$cmd] = "@run@";
        
        $this->runTasks($this->tasks);
    }

    public function runTasks(array $tasks)
    {
        if (!empty($tasks)):
            foreach (array_keys($tasks) as $cmd):
                switch ($cmd):
                    case '-u':
                    {
                        // $clash = (new ClashFinder($this->pdo))->getClanMembers();
                        // $clash = (new ClashFinder($this->pdo))->addClanMembers((new ClashFinder($this->pdo))->getClanMembersApi());
                        // $clash = (new ClashDailyWar($this->pdo))->updateWarLog();
                        $clash = (new ClashClanManager($this->pdo, "#P9PLUVL"));
                        var_dump($clash);
                    }
                endswitch;
            endforeach;
        endif;
    }
}