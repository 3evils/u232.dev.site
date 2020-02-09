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
$browser = $_SERVER['HTTP_USER_AGENT'];
if (preg_match("/MSIE/i", $browser)) //browser is IE
{
    $HTMLOUT.= "<div class='card'>
		<div class='card-divider portlet-header'>{$lang['index_ie_warn']}</div>
		<div class='content portlet-content card-section'>
			{$lang['index_ie_not']}{$INSTALLER09['site_name']}{$lang['index_ie_suggest']}<a href='http://browsehappy.com'><b>{$lang['index_ie_bhappy']}</b></a>{$lang['index_ie_consider']}<br /><br /><a href='http://www.mozilla.com/firefox'><img alt='{$lang['index_ie_firefox']}' title='{$lang['index_ie_firefox']}' src='{$INSTALLER09['pic_base_url']}getfirefox.gif' /></a><br /><strong>{$lang['index_ie_get']}</strong>
		</div>
	</div>";
}
//==End
// End Class
// End File

