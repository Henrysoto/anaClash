<?php

require 'vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTable as SQLiteCreateTable;
use App\ClashFinder as ClashFinder;
use App\ClashDailyWar as ClashDailyWar;
use App\ClashCommands as ClashCommands;

$pdo = (new SQLiteConnection())->connect();
if ($pdo == null) die("Db connection error");

if (!(file_exists(\App\Config::SQLITE_FILE_PATH)) || filesize(\App\Config::SQLITE_FILE_PATH) <= 0):
    $table = new SQLiteCreateTable($pdo);
    $table->createClanTables();
endif;

$tasks = new ClashCommands($pdo);

if (count($argv) >= 2):
    array_shift($argv);
    for ($i = 0; $i < count($argv); $i++):
        if (stristr($argv[$i], '-')):
            if (in_array($argv[$i], \App\Config::CMD_ARGS)):
                $tasks->addTasks($argv[$i], $argv[$i+1]);
            elseif (in_array($argv[$i], \App\Config::CMD_EXEC)):
                $tasks->addTasks($argv[$i]);
            else:
                $tasks->cmdHelp();
            endif;
        endif;
    endfor;
else:
    $tasks->cmdHelp();
endif;
    // $clash = new ClashFinder($pdo);
    // var_dump($clash->addClanMembers($clash->getMembersFromClan()));
    // $clash = new ClashDailyWar($pdo);
    // var_dump($clash->updateWarLog());
    // var_dump($clash->debugData());