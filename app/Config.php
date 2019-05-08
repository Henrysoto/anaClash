<?php
namespace App;

require 'vendor/autoload.php';

class Config 
{  
    const SQLITE_FILE_PATH = "db/qp.db";
    const API_KEY = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImVhMjlhODk4LTY3ZjMtNGY4NC04MzA0LWNkODQ2OTg2MzE1ZiIsImlhdCI6MTU1NzE1OTU5OCwic3ViIjoiZGV2ZWxvcGVyLzkxZTVmZDNmLTA5OGMtMzNlZS01MmJmLTJlYmEyNTA5NThiNSIsInNjb3BlcyI6WyJyb3lhbGUiXSwibGltaXRzIjpbeyJ0aWVyIjoiZGV2ZWxvcGVyL3NpbHZlciIsInR5cGUiOiJ0aHJvdHRsaW5nIn0seyJjaWRycyI6WyI5MC40My40NC4xNDQiXSwidHlwZSI6ImNsaWVudCJ9XX0.3oBBjD03iFD1XppTz7iP1PoByZsVLiQ_1EiyY7RRW_YR4qvFYDCi2-rFGhBKdklyLtGzOw5bBnaRzcwUvqSDzw";
    const CLAN_TAG = "#PRVLV2R";
    const HENRY_TAG = "#L0JCCGUQ";
    const API_PLAYER = "https://api.clashroyale.com/v1/players/@@";
    const API_CLAN = "https://api.clashroyale.com/v1/clans/@@";
    const API_CLAN_MEMBERS = "https://api.clashroyale.com/v1/clans/@@/members";
    const API_CLAN_WARLOG = "https://api.clashroyale.com/v1/clans/@@/warlog";
    const API_CLAN_CURRENTWAR = "https://api.clashroyale.com/v1/clans/@@/currentwar";

    const CMD_ARGS = ['-a', '-s'];
    const CMD_EXEC = ['-u', '-m'];
}