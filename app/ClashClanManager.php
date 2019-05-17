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
                $clanInfo = (new ClashFinder($this->pdo))->getClanInfo($clan);
                $clanInfo = $this->formatClanData($clanInfo);
                if (!$clanInfo || empty($clanInfo)):
                    $clanInfo = ClashFinder::handler(str_replace("@@", urlencode($clan), Config::API_CLAN));
                    $clanInfo = json_decode($clanInfo);
                    $clanMembers = null;

                    foreach($clanInfo->memberList as $member)
                        $clanMembers .= $member->tag.',';
                    
                    $clanInfo->memberList = $clanMembers;
                endif;
            else: 
                $clanInfo = $clan;
            endif;
        endif;

        if (!is_null($clanInfo) && !empty($clanInfo)):        
            $clanInfo = json_decode($clanInfo);
            $this->tag       = $clanInfo->tag;
            $this->name      = $clanInfo->name;
            $this->size      = $clanInfo->size;
            $this->score     = $clanInfo->score;
            $this->members   = $clanInfo->memberList;
        else:
            throw new Exception("[ClashClanManager] -> clan tag invalid");
        endif;
    }

    public function formatClanData(array $data)
    {
        $newData = array(
            "name"          => $data[0]["clash_clan_name"],
            "tag"           => $data[0]["clash_clan_id"],
            "score"         => $data[0]["clash_clan_score"],
            "size"          => $data[0]["clash_clan_size"],
            "memberList"    => $data[0]["clash_clan_members"]);
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

    public function updateClanMembers(string $clantag = Config::CLAN_TAG)
    {
        
        $data = ClashFinder::handler(str_replace("@@", urlencode($clantag), Config::API_CLAN));
        $data = json_decode($data);
        $newmembers = array();
        if (!empty($data) && !$data->reason):
            $clanInfo = (new ClashFinder($this->pdo))->getClanMembers($clantag);
            $clanInfo = explode(",", $clanInfo);
            foreach($data->memberList as $member):
                if (!in_array($member->tag, $clanInfo))
                    array_push($newmembers, $member->tag);
            endforeach;

            if (!empty($newmembers)):
                foreach ($newmembers as $tag)
                    try
                    {
                        if ($clanInfo = (new ClashFinder($this->pdo))->userExists($tag))
                            return true;
                    }
                    catch (Exception $e)
                    {
                        print($e->getMessage);
                        continue;
                    }
        else:
            throw new Exception("[ClashClanManager] -> updateClanMembers(): data is invalid");
        endif;
    }
}