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
 $HTMLOUT.= "<div class='row'>
        <ul class='tabs'>
       <li class='tabs-title is-active' aria-selected='true'><a href='userdetails.php?id={$id}&amp;action=torrents'>{$lang['userdetails_torrents']}</a></li>
       <li class='tabs-title'><a href='userdetails.php?id={$id}&amp;action=snatched'>{$lang['userdetails_snatched_menu']}</a></li>
       <li class='tabs-title'><a href='userdetails.php?id={$id}&amp;action=general'>{$lang['userdetails_general']}</a></li>
       <li class='tabs-title'><a href='userdetails.php?id={$id}&amp;action=activity'>{$lang['userdetails_activity']}</a></li>
       <li class='tabs-title'><a href='userdetails.php?id={$id}&amp;action=comments'>{$lang['userdetails_usercomments']}</a></li>";
if ($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class']) {
    $HTMLOUT.= '<li class="tabs-title"><a href="userdetails.php?id='.$id.'&amp;action=edit">'.$lang['userdetails_edit_user'].'</a></li>';
}
	$links_invincible;
        $HTMLOUT.= "</ul></div>";
//==end
// End Class
// End File
