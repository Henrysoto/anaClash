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

    public function __construct($pdo, $clan)
    {
        $this->pdo = $pdo;
        $clanInfo = null;
        
        if (!is_null($clan) && !empty($clan)):
            if (!is_object($clan)):
                $clanInfo = (new ClashFinder($this->pdo))->getMemberInfo($clan);
                $clanInfo = $this->formatClanData($clanInfo);
                if (!$clanInfo || empty($clanInfo)):
                    $clanInfo = ClashFinder::handler(str_replace("@@", urlencode($clan), Config::API_CLAN));
                endif;
            else: 
                $clanInfo = $clan;
            endif;
        endif;

        if (!is_null($clanInfo) && !empty($clanInfo)):        
            $clanInfo = json_decode($clanInfo);
            $this->tag       = $clanInfo->tag;
            $this->name      = $clanInfo->name;
            $this->size      = $clanInfo->expLevel;
            $this->score     = $clanInfo->trophies;
            $this->members   = $clanInfo->wins;
        else:
            throw new Exception("[ClashClanManager] -> clan tag invalid");
        endif;
    }

    public function formatClanData(array $data)
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

    public function cData(string $type)
    {
        switch (strtolower($type)):
            case "name":
                return $this->name;
                break;
            case "tag":
                return $this->tag;
                break;
            case "score":
                return $this->score;
                break;
            case "size":
                return $this->size;
                break;
            case "members":
                return $this->members;
                break;
            default:
                throw new Exception("[ClashClanManager] -> cData() invalid type");
                break;
        endswitch;
    }
}