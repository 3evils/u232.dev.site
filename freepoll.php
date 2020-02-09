<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5                                            |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.               |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'bbcode_functions.php');
dbconn(false);
loggedinorreturn();
$userid = intval($CURUSER["id"]);
$torrentid = intval($_POST["torrentid"]);

if ((!$torrentid))
    header("Location: browse.php");
else
    $checkfreepoll = sql_query("SELECT userid FROM freepoll WHERE torrentid=".sqlesc($torrentid)." AND userid=".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
$trows = mysqli_fetch_row($checkfreepoll);
if ($trows[0] > 0) {
    header("Location: details.php?id=$torrentid&poll=0");
} else {
    $res = sql_query("INSERT INTO freepoll (torrentid, userid) VALUES (".sqlesc($torrentid).", ".sqlesc($userid).")") or sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE users SET seedbonus = seedbonus-".sqlesc($INSTALLER09['torrent']['freepoll_points'])." WHERE id=".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);  
    $update['seedbonus_donator'] = ($CURUSER['seedbonus'] - $INSTALLER09['torrent']['freepoll_points']);
    $mc1->begin_transaction('userstats_' . $CURUSER["id"]);
    $mc1->update_row(false, array(
    'seedbonus' => $update['seedbonus_donator']
));
    $mc1->commit_transaction($INSTALLER09['expires']['u_stats']);
    $mc1->begin_transaction('user_stats_' . $CURUSER["id"]);
    $mc1->update_row(false, array(
    'seedbonus' => $update['seedbonus_donator']
));
$mc1->commit_transaction($INSTALLER09['expires']['user_stats']);
    header("Location: details.php?id=$torrentid");
}
?>
