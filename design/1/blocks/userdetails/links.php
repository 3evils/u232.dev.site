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
 //== links to make invincible method 1(PERMS_NO_IP/ no log ip) and 2(PERMS_BYPASS_BAN/cannot be banned)
 $links_invincible = '';
 $links_invincible.= ($CURUSER['class'] === UC_MAX ? (($user['perms'] & bt_options::PERMS_NO_IP) ? '<a class="button" title="'.$lang['userdetails_invincible_def1'] . $lang['userdetails_invincible_def2'].'" href="userdetails.php?id=' . $id . '&amp;invincible=no">' . $lang['userdetails_invincible_remove'].'</a>' . (($user['perms'] & bt_options::PERMS_BYPASS_BAN) ? ' <a class="button" title="'.$lang['userdetails_invincible_def3'] . $lang['userdetails_invincible_def4'].'" href="userdetails.php?id=' . $id . '&amp;' . "\n" . 'invincible=remove_bypass">'.$lang['userdetails_remove_bypass'].'</a>' : '<a class="button" title="'.$lang['userdetails_invincible_def5']. $lang['userdetails_invincible_def6'] . $lang['userdetails_invincible_def7'] . $lang['userdetails_invincible_def8'].'" href="userdetails.php?id=' . $id . '&amp;invincible=yes">' . $lang['userdetails_add_bypass'].'</a>') : '<a class="button" title="'.$lang['userdetails_invincible_def9'].'' . "\n" . ' '.$lang['userdetails_invincible_def0']. 'href="userdetails.php?id=' . $id . '&amp;invincible=yes">'.$lang['userdetails_make_invincible'].'</a>') : '');
 $links_stealth = '';
 $links_stealth.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_STEALTH) ? '<a class="button" title='.$lang['userdetails_stelth_def1'].$lang['userdetails_stelth_def2'].'href="userdetails.php?id=' . $id . '&amp;stealth=no">'.$lang['userdetails_stelth_disable'].'</a>' : '<a class="button" title="'.$lang['userdetails_stelth_def1'].$lang['userdetails_stelth_def2']. 'href="userdetails.php?id=' . $id . '&amp;stealth=yes">'.$lang['userdetails_stelth_enable'].'</a>') : '');
 
if ($CURUSER["id"] <> $user["id"]) {
    if (($friends = $mc1->get_value('Friends_' . $id)) === false) {
        $r3 = sql_query("SELECT id FROM friends WHERE userid=" . sqlesc($CURUSER['id']) . " AND friendid=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        $friends = mysqli_num_rows($r3);
        $mc1->cache_value('Friends_' . $id, $friends, $INSTALLER09['expires']['user_friends']);
    }
    if (($blocks = $mc1->get_value('Blocks_' . $id)) === false) {
        $r4 = sql_query("SELECT id FROM blocks WHERE userid=" . sqlesc($CURUSER['id']) . " AND blockid=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        $blocks = mysqli_num_rows($r4);
        $mc1->cache_value('Blocks_' . $id, $blocks, $INSTALLER09['expires']['user_blocks']);
    }
}
	$friends_link = '';
	$friends_link.= (($CURUSER['id'] == $user['id']) ? "" : ($friends > 0 ? "<p><a class='button' href='friends.php?action=delete&amp;type=friend&amp;targetid=$id'>{$lang['userdetails_remove_friends']}</a></p>" : "<p><a class='button' href='friends.php?action=add&amp;type=friend&amp;targetid=$id'>{$lang['userdetails_add_friends']}</a></p>"));		
	$blocks_link = '';
	$blocks_link.= (($CURUSER['id'] == $user['id']) ? "" : ($blocks > 0 ? "<p><a class='button' href='friends.php?action=delete&amp;type=block&amp;targetid=$id'>{$lang['userdetails_remove_blocks']}</a></p>" : "<p><a class='button' href='friends.php?action=add&amp;type=block&amp;targetid=$id'>{$lang['userdetails_add_blocks']}</a></p>"));
//== 09 Shitlist by Sir_Snuggles
if ($CURUSER['class'] >= UC_STAFF) {
    $shitty = '';
    if (($shit_list = $mc1->get_value('shit_list_' . $id)) === false) {
        $check_if_theyre_shitty = sql_query("SELECT suspect FROM shit_list WHERE userid=" . sqlesc($CURUSER['id']) . " AND suspect=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        list($shit_list) = mysqli_fetch_row($check_if_theyre_shitty);
        $mc1->cache_value('shit_list_' . $id, $shit_list, $INSTALLER09['expires']['shit_list']);
    }
	$shitty_link = '';
	$shitty_link.=(($CURUSER['id'] == $user['id']) ? "" : ($CURUSER["id"] <> $user["id"] && $shit_list > 0 ? "" : "<a class='button' href='staffpanel.php?tool=shit_list&amp;action=shit_list&amp;action2=new&amp;shit_list_id=" . $id . "&amp;return_to=userdetails.php?id=" . $id . "'><b>{$lang['userdetails_shit3']}</b></a>"));
}
	$sharemarks_link = '';
if ($CURUSER['id'] != $user['id'])	{
	$sharemarks_link.= "<a class='button' href='{$INSTALLER09['baseurl']}/sharemarks.php?id=$id'>{$lang['userdetails_sharemarks']}</a>";
}
	$user_link = '';
if ($CURUSER['id'] == $user['id'])
{
	$user_link.= "<a class='button' href='{$INSTALLER09['baseurl']}/usercp.php?action=default'>{$lang['userdetails_editself']}</a>
	<a class='button' href='{$INSTALLER09['baseurl']}/view_announce_history.php'>{$lang['userdetails_announcements']}</a>";
}
 //==end
// End Class
// End File