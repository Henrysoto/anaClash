<?php

namespace App;

class ClashDailyWar
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function formatClashDate(string $date)
    {
        $date = explode('T', $date);
        return sprintf("%s/%s/%s %s:%s:%s",
            substr($date[0], 6, 2), // day
            substr($date[0], 4, 2), // month
            substr($date[0], 0, 4), // year
            substr($date[1], 0, 2), // hours
            substr($date[1], 2, 2), // minutes
            substr($date[1], 4, 2));// seconds
    }

    public function updateWarLog(string $clantag = Config::CLAN_TAG)
    {
        $data = ClashFinder::handler(str_replace("@@", urlencode($clantag), Config::API_CLAN_WARLOG));
        $data = json_decode($data);

        foreach ($data->items as $war):
            $query = "
            SELECT * 
            FROM clan_war 
            WHERE clash_war_date = \"{$war->createdDate}\"";
            $stmt = $this->pdo->query($query);

            if (!(count($stmt->fetchAll()) > 0)):
                if ($this->createWarLog($war, $clantag)):
                    printf("Clanwar from %s added to database!".PHP_EOL, $this->formatClashDate($war->createdDate));
                else:
                    throw new Exception("[ClashDailyWar] -> could not add warlog (createWarLog() issue?)");
                endif;
            else:
                printf("Clanwar from %s already exist".PHP_EOL, $this->formatClashDate($war->createdDate));
            endif;
        endforeach;

        return true;
    }

    public function createWarLog(object $data, string $clantag)
    {   
        if (!empty($data) && !empty($clantag)):
            $sql = "
                INSERT INTO clan_war(
                    clash_war_date,
                    clash_clan_id,
                    clash_war_seasonId,
                    clash_war_participants,
                    clash_war_wins,
                    clash_war_battlesPlayed,
                    clash_war_members) 
                VALUES(
                    :clash_war_date,
                    :clash_clan_id,
                    :clash_war_seasonId,
                    :clash_war_participants,
                    :clash_war_wins,
                    :clash_war_battlesPlayed,
                    :clash_war_members)";
            
            $wins = 0;
            $battlesPlayed = 0;
            $members = "";
            foreach ($data->participants as $war):
                $wins = $wins + (int)$war->wins;
                $battlesPlayed = $battlesPlayed + (int)$war->battlesPlayed;
                $members = $war->tag.",".$members;
            endforeach;

            // var_dump($members, $battlesPlayed, $wins, $data->createdDate, $data->seasonId);

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ":clash_clan_id"            => $clantag,
                ":clash_war_seasonId"       => (int)$data->seasonId,
                ":clash_war_date"           => $data->createdDate,
                ":clash_war_participants"   => count($data->participants),
                ":clash_war_wins"           => $wins,
                ":clash_war_battlesPlayed"  => $battlesPlayed,
                ":clash_war_members"        => $members
            ]);

            if ($this->pdo->lastInsertId())
                return true;
            else
                throw new Exception("[ClashDailyWar] -> could not create warlog");
        else:
            throw new Exception("[ClashDailyWar] -> warlog data is empty");
        endif;
    }
}