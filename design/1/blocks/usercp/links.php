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
     $HTMLOUT.= "<div class='{$design['large_9']} {$design['columns']}'>
	<div class='panel panel-default bordered-heading'>
	<div class='panel-heading'><h5 class='panel-title'><i class='fa fa-list'></i>{$lang['usercp_lnk_opt']}</h5></div>
	<div class='panel'>
					<div class='panel-body'>
						<div class='row'>
							<div class='col-lg-5'>
								<ul class='list-group'><li class='list-group-item btn btn-default'><b>{$lang['usercp_lnk_men']}</b></li>
	<li class='list-group-item'><a href='mytorrents.php'>{$lang['usercp_edit_torrents']}</a></li>
	<li class='list-group-item'><a href='friends.php'>{$lang['usercp_edit_friends']}</a></li>
	<li class='list-group-item'><a href='users.php'>{$lang['usercp_search']}</a></li>
	<li class='list-group-item'><a href='invite.php'>{$lang['usercp_lnk_inv']}</a></li>
	<li class='list-group-item'><a href='tenpercent.php'>{$lang['usercp_lnk_life']}</a></li></ul>
							</div>
							<div class='col-lg-5'>
								<ul class='list-group'>
	<li class='list-group-item btn btn-default'><b>{$lang['usercp_lnk_enter']}</b></li>
	<li class='list-group-item'><a href='topmoods.php'>{$lang['usercp_lnk_top']}</a></li>
	<li class='list-group-item'><a href='lottery.php'>{$lang['usercp_lnk_lott']}</a></li>";
    $HTMLOUT.= "<!--if ($CURUSER['class'] >= UC_POWER_USER) {
        $HTMLOUT.= "<li class='list-group-item'><a href='blackjack.php'>{$INSTALLER09['site_name']} {$lang['usercp_lnk_black']}</a></li>";
        $HTMLOUT.= "<li class='list-group-item'><a href='casino.php'>{$INSTALLER09['site_name']} {$lang['usercp_lnk_casi']}</a></li>"?>
        </ul>
						</div></div></div></div></div>";
    }
    $HTMLOUT.= "</div>";
 
 //==End
// End Class
// End File