<?php
namespace BattleNet\Api;

interface ApiResponse
{
    function getData($asArray = false);
    
    function getTTL();
}