<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                			    |
 |--------------------------------------------------------------------------|
 |   Licence Info: GPL			                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5					    |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.					    |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');

dbconn();
loggedinorreturn();
$HTMLOUT = '';
$lang = array_merge(load_language('global'));
/*
$id = 241;

/**  Mod by Bigjoos    
$my_points = 0;
    if (($torrent['torrent_ffd_points_'] = $mc1->get_value('torrent_free_points_' . $id)) === false) {
        $sql_ffd_points = sql_query('SELECT userid, tf_points FROM coins WHERE torrentid=' . sqlesc($id));
        $torrent['torrent_ffd_points_'] = array();
        if (mysqli_num_rows($sql_ffd_points) !== 0) {
            while ($ffd_cache = mysqli_fetch_assoc($sql_ffd_points)) $torrent['torrent_ffd_points_'][$ffd_cache['userid']] = $ffd_cache['tf_points'];
        }
        $mc1->add_value('torrent_free_points_' . $id, $torrent['torrent_ffd_points_'], 0);
    }
    $my_points = (isset($torrent['torrent_free_points_'][$CURUSER['id']]) ? (int)$torrent['torrent_free_points_'][$CURUSER['id']] : 0);
    $HTMLOUT.= '<tr>
		<td class="heading" valign="top" align="right"><i><b><u>Free for one day</u></b></i></td><br /><br />
		<td valign="top" align="left"><b>In total ' . (int)$torrents['tf_points'] . ' Karma Points have been given to this torrent for 24 hours freeleech of which ' . $my_points . ' from you.</b><br /><br />
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=10">10</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=20">20</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=50">50</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=100">100</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=200">200</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=500">500</a>&nbsp;&nbsp;
		<br /><br />By clicking on the amounts you can give Karma Points making this torrent free for one day.</td></tr><br /><br />';
	
*/

//$xmasday = mktime(0, 0, 0, 12, 25, date("Y")); 
 
//$HTMLOUT .= $xmasday;

$pass ='65465646654';

function make_passhash_login_key($pass)
{
    $options = array(
    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
    'cost' => 12,
  );
    return password_hash($pass, PASSWORD_BCRYPT, $options);
}

function make_passhash($pass)
{
    $options = [
        'cost' => 12,
    ];
    return password_hash($pass, PASSWORD_BCRYPT, $options);
}

$password ='tester1'.$_SERVER["REMOTE_ADDR"];

function hashit3($password, $addtext = "")
{
    return md5("Th15T3xt" . $addtext . $password . $addtext . "is5add3dto66uddy6he@water...");
}

function hashit2($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

$HTMLOUT .= hashit3($password);
$HTMLOUT .='<br /><br />';
$HTMLOUT .= hashit2($password);
//$HTMLOUT .= make_passhash($pass);


echo stdhead("Testing") . $HTMLOUT . stdfoot();
?>
