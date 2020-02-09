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
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once(INCL_DIR . 'user_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('coins'));
//== Torrent free for one day == based on dokty points for uploader ==//
$id = intval($_GET["id"]);
if (empty($_GET['id'])) {
    die('Silly Rabbit - Twix are for kids - You cant post no id !');
}
$tf_points = intval($_GET["tf_points"]);
if (empty($_GET['tf_points'])) {
    die('Silly Rabbit - Twix are for kids - You cant post no points !');
}
if (!is_valid_id($id) || !is_valid_id($tf_points))
    die();
$pointstogive = array(
    "10",
    "20",
    "50",
    "100",
    "200",
    "500"
);

if (!in_array($tf_points, $pointstogive))
    stderr($lang['gl_error'], $lang['coins_you_cant_give_that_amount_of_points']);
$res = sql_query("SELECT 1 FROM coins WHERE torrentid=" . sqlesc($id) . " AND userid =" . sqlesc($CURUSER["id"])) or sqlerr(__FILE__, __LINE__);
$resd = mysqli_fetch_assoc($res);
if ($resd)
    stderr($lang['gl_error'], $lang['coins_you_already_gave_points_to_this_torrent']);
$res1 = sql_query("SELECT owner, name, f_points FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_assoc($res1) or stderr($lang['gl_error'], $lang['coins_torrent_was_not_found']);
$userid = intval($row["owner"]);
if ($userid == $CURUSER["id"])
    stderr($lang['gl_error'], $lang['coins_you_cant_give_your_self_points']);
if ($CURUSER["seedbonus"] < $tf_points)
    stderr($lang['gl_error'], $lang['coins_you_dont_have_enough_points']);
$sql = sql_query('SELECT seedbonus ' . 'FROM users ' . 'WHERE id = ' . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
$User = mysqli_fetch_assoc($sql);
sql_query("INSERT INTO coins (userid, torrentid, tf_points) VALUES (" . sqlesc($CURUSER["id"]) . ", " . sqlesc($id) . ", " . sqlesc($tf_points) . ")") or sqlerr(__FILE__, __LINE__);
sql_query("UPDATE users SET seedbonus=seedbonus-" . sqlesc($tf_points) . " WHERE id=" . sqlesc($CURUSER["id"])) or sqlerr(__FILE__, __LINE__);
sql_query("UPDATE torrents SET f_points=f_points+" . sqlesc($tf_points) . " WHERE id=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);

//Enough points donated -> Extra query added for points total -> Revise this
$FreeTime = (TIME_NOW + 86400);
$resfree = sql_query("SELECT f_points FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$rows = mysqli_fetch_assoc($resfree);
if($rows['f_points'] >= $INSTALLER09['torrent']['free_target']){
$Which_Free = (XBT_TRACKER === true ? 'freetorrent' : 'free');
$What_Var = (XBT_TRACKER === true ? '1' : $FreeTime);
sql_query("UPDATE torrents SET $Which_Free=".sqlesc($What_Var).", f_points='0' WHERE id=".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
//sql_query("DELETE FROM coins WHERE torrentid=".sqlesc($id)." AND userid=" . sqlesc($CURUSER["id"])." AND tf_points > 0") or sqlerr(__FILE__, __LINE__);
}

$update['freepoints'] = ($row['f_points'] + $tf_points);
$update['seedbonus_donator'] = ($CURUSER['seedbonus'] - $tf_points);
//==The torrent
$mc1->begin_transaction('torrent_details_' . $id);
$mc1->update_row(false, array(
    'f_points' => $update['freepoints'],
    'free' => $FreeTime
));
$mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
//==The donator
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
//== delete the points cache
$mc1->delete_value('free_for_day_counter_'.$id);
$mc1->delete_value('torrent_free_points_' . $id);
header("Refresh: 3; url=details.php?id=$id");
stderr($lang['coins_done'], $lang['coins_successfully_gave_points_to_this_torrent']);
?>
