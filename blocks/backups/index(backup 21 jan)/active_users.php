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
//==Start activeusers - pdq
$keys['activeusers'] = 'activeusers';
if (($active_users_cache = $mc1->get_value($keys['activeusers'])) === false) {
    $dt = $_SERVER['REQUEST_TIME'] - 180;
    $activeusers = '';
    $active_users_cache = array();
    $res = sql_query('SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, perms ' . 'FROM users WHERE last_access >= ' . $dt . ' ' . 'AND perms < ' . bt_options::PERMS_STEALTH . ' ORDER BY username ASC') or sqlerr(__FILE__, __LINE__);
    $actcount = mysqli_num_rows($res);
    $v = ($actcount != 1 ? 's' : '');
    while ($arr = mysqli_fetch_assoc($res)) {
        if ($activeusers) $activeusers.= ",\n";
        $activeusers.= '<b>' . format_username($arr) . '</b>';
    }
    $active_users_cache['activeusers'] = $activeusers;
    $active_users_cache['actcount'] = $actcount;
    $active_users_cache['au'] = number_format($actcount);
    $last24_cache['v'] = $v;
    $mc1->cache_value($keys['activeusers'], $active_users_cache, $INSTALLER09['expires']['activeusers']);
}
if (!$active_users_cache['activeusers']) $active_users_cache['activeusers'] = $lang['index_active_users_no'];
$active_users = '
<div class="header panel panel-default">
<div class="panel-heading">
<label for="checkbox_4" class="text-left">' . $lang['index_active'] . '&nbsp;&nbsp;<span class="badge btn btn-success disabled" style="color:#fff">' . $active_users_cache['actcount'] . '</span></label>
	</div>

			 <div class="container-fluid panel-body">

			 <!--<a href=\'javascript: klappe_news("a1")\'><img border=\'0\' src=\'pic/plus.gif\' id=\'pica1\' alt=\'' . $lang['index_hide_show'] . '\' /></a><div id=\'ka1\' style=\'display: none;\'>-->
			 <!--<a class="altlink"  title="' . $lang['index_click_more'] . '" id="div_open1" style="font-weight:bold;cursor:pointer;"><img border=\'0\' src=\'pic/plus.gif\' alt=\'' . $lang['index_hide_show'] . '\' /></a>
			 <div id="div_info1" style="display:none;background-color:#FEFEF4;max-width:940px;padding: 5px 5px 5px 10px;">-->
			 ' . $active_users_cache['activeusers'] . '
			</div>
	</div>';
$HTMLOUT.= $active_users;
//== end activeusers
// End Class
// End File
