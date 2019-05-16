<?php

namespace App;

class ClashFinder
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public static function handler(string $url)
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept: application/json\r\n" .
                            "Authorization: Bearer ".Config::API_KEY."\r\n",
                "ignore_errors" => true
            )
        );

        $ctx = stream_context_create($opts);
        $data = file_get_contents($url, false, $ctx);

        return $data;
    }

    public function userExists(string $user)
    {
        if ($user[0] === '#'):
            $user = strip_tags($user);
        else:
            $user = ucfirst(strip_tags($user));
        endif;
        
        $query = "
            SELECT * 
            FROM prout 
            WHERE ".($user[0]==='#' ? "clash_id" : "clash_name")." = "."\"".$user."\"";
        $stmt = $this->pdo->query($query);

        if (!$stmt):
            if (stristr($user, "#"))
                return $this->createUser($user);
            else
                throw new Exception("[ClashFinder] -> need tag to create user");
        endif;

        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            $data[] = $row; 
        
        return $data;
    }

    public function createUser(string $user)
    {
        $data = ClashFinder::handler(str_replace("@@", urlencode($user), Config::API_PLAYER));
        
        if (!empty($data)):
            $data = json_decode($data);
            $sql = "
                INSERT INTO clash_player(
                    clash_id,
                    clash_name,
                    clash_exp,
                    clash_trophies,
                    clash_wins,
                    clash_losses,
                    clash_battleCount,
                    clash_clan_id) 
                VALUES(
                    :clash_id,
                    :clash_name,
                    :clash_exp,
                    :clash_trophies,
                    :clash_wins,
                    :clash_losses,
                    :clash_battleCount,
                    :clash_clan_id)
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ":clash_id"             => $data->tag,
                ":clash_name"           => $data->name,
                ":clash_exp"            => (int)$data->expLevel,
                ":clash_trophies"       => (int)$data->trophies,
                ":clash_wins"           => (int)$data->wins,
                ":clash_losses"         => (int)$data->losses,
                ":clash_battleCount"    => (int)$data->battleCount,
                ":clash_clan_id"        => $data->clan->tag
            ]);

            if ($this->pdo->lastInsertId()):
                return true;
            else:
                throw new Exception("[ClashFinder] -> failed to insert new user");
                return false;
            endif;
        else:
            throw new Exception("[ClashFinder] -> player data variable is empty");
        endif;
        //return json_decode($data);
    }

    public function createClan(string $clantag = Config::CLAN_TAG)
    {
        $data = ClashFinder::handler(str_replace("@@", urlencode($clantag), Config::API_CLAN));
        $data = json_decode($data);

        if (!empty($data) && !$data->reason):
            $sql = "
                INSERT INTO clan_info(
                    clash_clan_id,
                    clash_clan_name,
                    clash_clan_score,
                    clash_clan_size,
                    clash_clan_members) 
                VALUES(
                    :clash_clan_id,
                    :clash_clan_name,
                    :clash_clan_score,
                    :clash_clan_size,
                    :clash_clan_members)
            ";
            
            $members = null;
            foreach ($data->memberList as $member)
                $members .= $member->tag.",";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ":clash_clan_id"        => $data->tag,
                ":clash_clan_name"      => $data->name,
                ":clash_clan_score"     => (int)$data->clanScore,
                ":clash_clan_size"      => (int)$data->members,
                ":clash_clan_members"   => $members
            ]);

            if ($this->pdo->lastInsertId())
                return true;
            else
            throw new Exception("[ClashFinder] -> failed to insert new clan");
        else:
            throw new Exception("[ClashFinder] -> clan data variable is empty");
        endif;

    }

    public function getClanMembersApi(string $clantag = Config::CLAN_TAG)
    {
        $data = ClashFinder::handler(str_replace("@@", urlencode($clantag), Config::API_CLAN_MEMBERS));
        return $data; //json
    }

    public function addClanMembers(string $data)
    {
        $data = json_decode($data);
        $newMembers = array();
        foreach ($data->items as $member):
            $query = "
            SELECT * 
            FROM clash_player 
            WHERE clash_id = \"{$member->tag}\"";
            $stmt = $this->pdo->query($query);

            if (!(count($stmt->fetchAll()) > 0)):
                if ($this->createUser($member->tag)):
                    array_push($newMembers, $member->name);
                    printf("%s added to database!".PHP_EOL, $member->name);
                else:
                    throw new Exception("[ClashFinder] -> could not add clan members (createUser() issue?)");
                endif;
            else:
                printf("User: %s already exist".PHP_EOL, $member->name);
            endif;
        endforeach;

        return $newMembers;
    }

    public function getClanMembers(string $clantag = Config::CLAN_TAG)
    {
        $data = [];
        $stmt = $this->pdo->query("SELECT clash_clan_members FROM clan_info WHERE clash_clan_id = \"{$clantag}\"");
        
        if (!$stmt)
            throw new Exception("[ClashFinder] -> getClanMembers() query is invalid");

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        array_push($data, $row['clash_clan_members']);
        return $data;
    }

    public function getClanSize(string $clantag = Config::CLAN_TAG)
    {
        $data = [];
        $stmt = $this->pdo->query("SELECT clash_clan_size FROM clan_info WHERE clash_clan_id = \"{$clantag}\"");
        
        if (!$stmt)
            throw new Exception("[ClashFinder] -> getClanSize() query is invalid");
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        array_push($data, $row['clash_clan_size']);
        return $data;
    }

    public function getMemberInfo(string $playertag = Config::HENRY_TAG)
    {
        $stmt = $this->pdo->query("SELECT * FROM clash_player WHERE clash_id = \"{$playertag}\"");
        
        if (!$stmt)
            throw new Exception("[ClashFinder] -> getMemberInfo() query is invalid");
        
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $data;
    }

    public function getClanInfo(string $clantag = Config::CLAN_TAG)
    {
        $stmt = $this->pdo->query("SELECT * FROM clan_info WHERE clash_clan_id = \"{$clantag}\"");
        
        if (!$stmt)
            throw new Exception("[ClashFinder] -> getClanInfo() query is invalid");
        
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        if(empty($data)):
            if ($this->createClan($clantag))
                $data = $this->getClanInfo($clantag);
            else
                throw new Exception("[ClashFinder] -> could not create a new clan");
     
        endif;
        return $data;
    }
}