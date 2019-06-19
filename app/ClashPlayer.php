<?php
namespace App;

class ClashPlayer
{
    private $pdo;
    private $tag;
    private $name;
    private $exp;
    private $trophies;
    private $wins;
    private $losses;
    private $battleCount;
    private $clanTag;

    public function __construct($pdo, $player)
    {
        $this->pdo = $pdo;
        $playerInfo = null;
        
        if (!is_null($player) && !empty($player)):
            if (!is_object($player)):
                $playerInfo = (new ClashFinder($this->pdo))->getMemberInfo($player);
                $playerInfo = $this->formatPlayerData($playerInfo);
                if (!$playerInfo || empty($playerInfo)):
                    $playerInfo = ClashFinder::handler(str_replace("@@", urlencode($player), Config::API_PLAYER));
                endif;
            else: 
                $playerInfo = $player;
            endif;
        endif;

        if (!is_null($playerInfo) && !empty($playerInfo)):        
            $playerInfo = json_decode($playerInfo);
            $this->tag          = $playerInfo->tag;
            $this->name         = $playerInfo->name;
            $this->exp          = $playerInfo->expLevel;
            $this->trophies     = $playerInfo->trophies;
            $this->wins         = $playerInfo->wins;
            $this->losses       = $playerInfo->losses;
            $this->battleCount  = $playerInfo->battleCount;
            $this->clanTag      = $playerInfo->clan->tag;
        else:
            throw new Exception("[ClashPlayer] -> Player tag invalid");
        endif;
    }

    public function formatPlayerData(array $data)
    {
        if (!empty($data)):
            $newData = array(
                "name"          => $data[0]["clash_name"],
                "tag"           => $data[0]["clash_id"],
                "expLevel"      => $data[0]["clash_exp"],
                "trophies"      => $data[0]["clash_trophies"],
                "wins"          => $data[0]["clash_wins"],
                "losses"        => $data[0]["clash_losses"],
                "battleCount"   => $data[0]["clash_battleCount"],
                "clan"          => ["tag" => $data[0]["clash_clan_id"]]
            );
            $newData = json_encode($newData);
            return $newData;
        else:
            throw new Exception("[ClashPlayer] -> player data variable is empty in formatPlayerData() method");
        endif;
    }

    public function pData(string $type)
    {
        switch (strtolower($type)):
            case "name":
                return $this->name;
                break;
            case "tag":
                return $this->tag;
                break;
            case "exp":
                return $this->exp;
                break;
            case "trophies":
                return $this->trophies;
                break;
            case "wins":
                return $this->wins;
                break;
            case "losses":
                return $this->losses;
                break;
            case "battlecount":
                return $this->battleCount;
                break;
            case "clan":
                return $this->clanTag;
                break;
        endswitch;
    }

    public function updatePlayer(string $playerTag = Config::HENRY_TAG)
    {
        $finder = new ClashFinder($this->pdo);
        if ($playerTag[0] === '#'):
            if ($playerInfo = $finder->getMemberInfo($playerTag)):
                $newPlayerInfo = $finder->handler(str_replace("@@", urlencode($playerTag), Config::API_PLAYER));
                if (!is_null($newPlayerInfo) && !empty($newPlayerInfo)):        
                    $newPlayerInfo = json_decode($newPlayerInfo);
                    $this->tag          = $newPlayerInfo->tag;
                    $this->name         = $newPlayerInfo->name;
                    $this->exp          = $newPlayerInfo->expLevel;
                    $this->trophies     = $newPlayerInfo->trophies;
                    $this->wins         = $newPlayerInfo->wins;
                    $this->losses       = $newPlayerInfo->losses;
                    $this->battleCount  = $newPlayerInfo->battleCount;
                    $this->clanTag      = $newPlayerInfo->clan->tag;
                    
                    // $clanInfo = ClashFinder::getMemberInfo($this->tag);
                    $clanInfo = $playerInfo[0]['clash_clan_id'];
                    if ($clanInfo != $this->clanTag):
                        try
                        {
                            (new ClashClanManager($this->pdo, $this->clanTag))->updateClanMembers($clanInfo);
                        }
                        catch (Exception $e)
                    {
                        print($e->getMessage);
                        continue;
                    }
                    
                    $sql = "
                        UPDATE 
                            clash_player 
                        SET 
                            clash_exp           = :clash_exp,
                            clash_trophies      = :clash_trophies,
                            clash_wins          = :clash_wins,
                            clash_losses        = :clash_losses,
                            clash_battleCount   = :clash_battleCount,
                            clash_clan_id       = :clash_clan_id 
                        WHERE 
                            clash_id = :clash_id
                    ";

                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([
                        ":clash_id"             => $this->tag,
                        ":clash_exp"            => $this->exp,
                        ":clash_trophies"       => $this->trophies,
                        ":clash_wins"           => $this->wins,
                        ":clash_losses"         => $this->losses,
                        ":clash_battleCount"    => $this->battleCount,
                        ":clash_clan_id"        => $this->clanTag
                    ]);

                    if ($stmt):
                        printf("User %s also known as %s has been updated!", $this->name, $this->tag);
                        return true;
                    else:
                        throw new Exception("[ClashPlayer] -> failed to update player");
                        return false;
                    endif;
                endif;
            else:
                $finder->createUser($playerTag);
                return true;
            endif;
        else:
            throw new Exception("[ClashPlayer] -> Could not update player info, invalid tag"); 
        endif;
    }
}