<?php
namespace App;

class ClashPlayer
{
    private $tag;
    private $name;
    private $exp;
    private $trophies;
    private $wins;
    private $losses;
    private $battleCount;
    private $clanTag;

    public function __construct($player)
    {
        $playerInfo = null;
        
        if (!is_null($player) && !empty($player)):
            if (!is_object($player)):
                $playerInfo = ClashFinder::getMemberInfo($player);
                if (!$playerInfo || empty($playerInfo)):
                    $playerInfo = ClashFinder::handler(ClashFinder::handler(str_replace("@@", urlencode($player), Config::API_PLAYER)));
                endif;
            else: 
                $playerInfo = $player;
            endif;
        endif;

        if (!empty($playerInfo)):        
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
}