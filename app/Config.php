<?php
namespace App;

require 'vendor/autoload.php';

class Config 
{  
    const VERSION = 0.2;
    const SQLITE_FILE_PATH = "db/qp.db";
    const API_KEY = "M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjIyNzYxYjA4LTg0ZTQtNDQzOS1hZTQ2LWJlMGI3MzdiN2VmMCIsImlhdCI6MTU2MDk2NTI1NSwic3ViIjoiZGV2ZWxvcGVyLzkxZTVmZDNmLTA5OGMtMzNlZS01MmJmLTJlYmEyNTA5NThiNSIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5MC40My4xMjAuMTkzIl0sInR5cGUiOiJjbGllbnQifV19.I8t2yeWs9votHU8Lb5Qp5fAg6ZL0kUmdMQkZk9bGQCG9V67f-9qQjroqoLkt_L_YKok_fPqtpv_If8jt_9NYpA";
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
