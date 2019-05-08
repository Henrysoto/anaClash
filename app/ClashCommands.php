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
        print("[-] AnaClash CLI v0.1 by Henrygolant".PHP_EOL);
        print("\n\tListe des commandes:".PHP_EOL);
        print("\t-u\tFull update du clan".PHP_EOL);
        print("\t-m\tScan des membres du clan".PHP_EOL);
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
                        $clash = (new ClashFinder($this->pdo))->createClan();
                        $clash = (new ClashFinder($this->pdo))->addClanMembers((new ClashFinder($this->pdo))->getClanMembersApi());
                        $clash = (new ClashDailyWar($this->pdo))->updateWarLog();
                    }
                endswitch;
            endforeach;
        endif;
    }
}