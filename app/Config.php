<?php
namespace App;

require 'vendor/autoload.php';

class Config 
{  
    const VERSION = 0.2;
    const SQLITE_FILE_PATH = "db/qp.db";
    const API_KEY = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjAyOWI0ZWFhLTIwMzYtNGZlYS04NWViLWViZjU2ODRhMWVmZSIsImlhdCI6MTU1ODAyNTE5NSwic3ViIjoiZGV2ZWxvcGVyLzkxZTVmZDNmLTA5OGMtMzNlZS01MmJmLTJlYmEyNTA5NThiNSIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5MC40My4xMTguMjMiXSwidHlwZSI6ImNsaWVudCJ9XX0.-N_JVnw-xdE93YIpQmubjT-OnvY7npCHOjuuXgU6I6d02eM_0y-FBUC0DAK1QgJC2PoH1ocOkbC4T7k846hfIA";
    const CLAN_TAG = "#PRVLV2R";
    const HENRY_TAG = "#L0JCCGUQ";
    const API_PLAYER = "https://api.clashroyale.com/v1/players/@@";
    const API_CLAN = "https://api.clashroyale.com/v1/clans/@@";
    const API_CLAN_MEMBERS = "https://api.clashroyale.com/v1/clans/@@/members";
    const API_CLAN_WARLOG = "https://api.clashroyale.com/v1/clans/@@/warlog";
    const API_CLAN_CURRENTWAR = "https://api.clashroyale.com/v1/clans/@@/currentwar";

    const CMD_ARGS = ['-a', '-m'];
    const CMD_EXEC = ['-u', '-s'];
}