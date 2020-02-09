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
 if ($user['added'] == 0 OR $user['perms'] & bt_options::PERMS_STEALTH) $joindate = "{$lang['userdetails_na']}";
	else 
		$joindate = get_date($user['added'], '');
		$lastseen = $user["last_access"];
if ($lastseen == 0 OR $user['perms'] & bt_options::PERMS_STEALTH) $lastseen = "{$lang['userdetails_never']}";
	else {
		$lastseen = get_date($user['last_access'], '', 0, 1);
	}
$HTMLOUT.= "<tr><td class='rowhead' width='1%'>{$lang['userdetails_joined']}</td><td align='left' width='99%'>{$joindate}</td></tr>
<tr><td class='rowhead'>{$lang['userdetails_seen']}</td><td align='left'>{$lastseen}</td></tr>";
//==end
// End Class
// End File
