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
 $HTMLOUT .="<div class='medium-3 columns'>";
if (!empty($CURUSER['avatar']) && $CURUSER['av_w'] > 5 && $CURUSER['av_h'] > 5) $HTMLOUT.= "
		<table class='table'><tr><td><img class='img-polaroid' src='{$CURUSER['avatar']}' width='{$CURUSER['av_w']}' height='{$CURUSER['av_h']}' alt='' /></td></tr></table>";
else $HTMLOUT.= "<table class='table'><tr><td><img class='img-polaroid' src='{$INSTALLER09['pic_base_url']}forumicons/default_avatar.gif' alt='' /></td></tr>
		</table>";
$HTMLOUT .="<ul class='vertical menu'>
	<li class='button'><b>{$lang['usercp_lnk_men']}</b></li>
	<li><a href='mytorrents.php'>{$lang['usercp_edit_torrents']}</a></li>
	<li><a href='friends.php'>{$lang['usercp_edit_friends']}</a></li>
	<li><a href='users.php'>{$lang['usercp_search']}</a></li>
	<li><a href='invite.php'>{$lang['usercp_lnk_inv']}</a></li>
	<li><a href='tenpercent.php'>{$lang['usercp_lnk_life']}</a></li>
	<li class='button'><b>{$lang['usercp_lnk_enter']}</b></li>
	<li><a href='topmoods.php'>{$lang['usercp_lnk_top']}</a></li>
	<li><a href='lottery.php'>{$lang['usercp_lnk_lott']}</a></li>";
    if ($CURUSER['class'] >= UC_POWER_USER) {
        $HTMLOUT.= "<li><a href='blackjack.php'>{$INSTALLER09['site_name']} {$lang['usercp_lnk_black']}</a></li>";
        $HTMLOUT.= "<li><a href='casino.php'>{$INSTALLER09['site_name']} {$lang['usercp_lnk_casi']}</a></li>";
	}
$HTMLOUT.= "</ul>";
$HTMLOUT.= "</div>";
//==End
// End Class
// End File