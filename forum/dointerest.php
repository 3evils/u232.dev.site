<?php
/**********************************************\
| SMFSHOP (Shop MOD for Simple Machines Forum) |
|         (c) 2005 DanSoft Australia           |
|      http://www.dansoftaustralia.com/        |
\**********************************************/

//File: dointerest.php
//      The file to add interest to member's bank

//VERSION: 1.31 (Build 7)
//DATE: 10th December 2005
// $Id: dointerest.php 4 2006-07-08 10:09:08Z daniel15 $

//if(!isset($_SERVER["HTTP_HOST"])) {

    //include("../../SSI.php");
    //require_once("SSI.php");
    include("SSI.php");  
    $interest_rate = $modSettings['shopInterest'] / 100;
    db_query("UPDATE {$db_prefix}members
              SET moneyBank = moneyBank + (moneyBank*{$interest_rate})", __FILE__, __LINE__);
    
    echo "Interest added at ".date("d/m/Y h:i:s A");
//}
?>