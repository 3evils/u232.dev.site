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
//== Online time
if ($user['onlinetime'] > 0) {
    $onlinetime = time_return($user['onlinetime']);
    $HTMLOUT.= "<div class='card secondary large-6 columns'>
		<h4 class='subheader'>{$lang['userdetails_time_online']}
		<span class='label primary float-right'>{$onlinetime}</span></h4>
	</div>";
} else {
    $onlinetime = $lang['userdetails_notime_online'];
    $HTMLOUT.= "<div class='card secondary large-6 columns'>
		<h4 class='subheader'>{$lang['userdetails_time_online']}
		<span class='label primary float-right'>{$onlinetime}</span></h4>
	</div>";
}
// end
// End Class
// End File

