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
//==invincible no iplogging and ban bypass by pdq
$invincible = $mc1->get_value('display_' . $CURUSER['id']);
if ($invincible) 
	$HTMLOUT.= '<h3>' . htmlsafechars($user['username']) . ' '.$lang['userdetails_is'].' ' . $invincible . ' '.$lang['userdetails_invincible'].'</h3>';

//==Stealth mode
$stealth = $mc1->get_value('display_stealth' . $CURUSER['id']);
if ($stealth) 
	$HTMLOUT.= '<h4>' . htmlsafechars($user['username']) . '&nbsp;' . $stealth . ' '.$lang['userdetails_in_stelth'].'</h4>';

//== 09 Shitlist by Sir_Snuggles
if ($CURUSER['class'] >= UC_STAFF) {
    $shitty = '';
    if (($shit_list = $mc1->get_value('shit_list_' . $id)) === false) {
        $check_if_theyre_shitty = sql_query("SELECT suspect FROM shit_list WHERE userid=" . sqlesc($CURUSER['id']) . " AND suspect=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        list($shit_list) = mysqli_fetch_row($check_if_theyre_shitty);
        $mc1->cache_value('shit_list_' . $id, $shit_list, $INSTALLER09['expires']['shit_list']);
    }
	$shitty = "<img src='pic/smilies/shit.gif' alt='Shit' title='Shit'>";
	$shitty_alert = '';
	
	$shitty_alert = ($shit_list > 0 ? "<b>" . $shitty . "&nbsp;{$lang['userdetails_shit1']} <a class='altlink' href='staffpanel.php?tool=shit_list&amp;action=shit_list'>{$lang['userdetails_here']}</a> {$lang['userdetails_shit2']}&nbsp;" . $shitty . "</b>" : "");
}
// ===donor count down
if ($user["donor"] && $CURUSER["id"] == $user["id"] || $CURUSER["class"] == UC_SYSOP) {
	$donoruntil_alert = '';
    $donoruntil = htmlsafechars($user['donoruntil']);
    if ($donoruntil == '0') 
		$HTMLOUT.= "";
    else {
        $donoruntil_alert.= $lang['userdetails_donatedtill'] . " - " . get_date($user['donoruntil'], 'DATE');
		$donoruntil_alert.= "[ " . mkprettytime($donoruntil - TIME_NOW) . " ] {$lang['userdetails_togo']}...<font size=\"-2\"> {$lang['userdetails_renew']} <a class='altlink' href='{$INSTALLER09['baseurl']}/donate.php'>{$lang['userdetails_here']}</a>.</font>";
    }
}
$parked_alert = '';
if ($user['opt1'] & user_options::PARKED) 
	$parked_alert.= "<p><b>{$lang['userdetails_parked']}</b></p>\n";
$enabled = $user["enabled"] == 'yes';
$enable_alert = '';
if (!$enabled) 
	$enable_alert.= "<p><b>{$lang['userdetails_disabled']}</b></p>";
	$watched_user = '';
if ($CURUSER["id"] <> $user["id"] && $CURUSER['class'] >= UC_STAFF)
	$watched_user.= ($user['watched_user'] == 0 ? '' : '&nbsp;&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'smilies/excl.gif" align="middle" alt="'.$lang['userdetails_watched'].'" title="'.$lang['userdetails_watched'].'"> <b>'.$lang['userdetails_watchlist1'].' <a href="staffpanel.php?tool=watched_users" >'.$lang['userdetails_watchlist2'].'</a></b> <img src="' . $INSTALLER09['pic_base_url'] . 'smilies/excl.gif" align="middle" alt="'.$lang['userdetails_watched'].'" title="'.$lang['userdetails_watched'].'">');
	$suspended = '';
if ($CURUSER["id"] <> $user["id"] && $CURUSER['class'] >= UC_STAFF)
	$suspended.= ($user['suspended'] == 'yes' ? '&nbsp;&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'smilies/excl.gif" alt="'.$lang['userdetails_suspended'].'" title="'.$lang['userdetails_suspended'].'">&nbsp;<b>'.$lang['userdetails_usersuspended'].'</b>&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'smilies/excl.gif" alt="'.$lang['userdetails_suspended'].'" title="'.$lang['userdetails_suspended'].'">' : '');
$h1_thingie = '';
$h1_thingie.= ((isset($_GET['sn']) || isset($_GET['wu'])) ? '<div class="callout success">'.$lang['userdetails_updated'].'</div>' : '');
 //==end
// End Class
// End File