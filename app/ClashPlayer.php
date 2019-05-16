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
            throw new Exception("Tag joueur invalide");
        endif;
    }

    public function formatPlayerData(array $data)
    {
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
    }
}