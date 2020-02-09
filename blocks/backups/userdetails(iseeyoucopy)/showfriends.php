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
//== Users friends list
$dt = TIME_NOW - 180;
$keys['user_friends'] = 'user_friends_' . $id;
if (($users_friends = $mc1->get_value($keys['user_friends'])) === false) {
    $fr = sql_query("SELECT f.friendid as uid, f.userid AS userid, u.last_access, u.id, u.ip, u.avatar, u.username, u.class, u.donor, u.title, u.warned, u.enabled, u.chatpost, u.leechwarn, u.pirate, u.king, u.downloaded, u.uploaded, u.perms FROM friends AS f LEFT JOIN users as u ON f.friendid = u.id WHERE userid=" . sqlesc($id) . " ORDER BY username ASC LIMIT 100") or sqlerr(__file__, __line__);
    while ($user_friends = mysqli_fetch_assoc($fr)) $users_friends[] = $user_friends;
    $mc1->cache_value($keys['user_friends'], $users_friends, 0);
}
if (count($users_friends) > 0) {
    $user_friends = "<table class='stack'>
		<thead>
		<tr>
			<td>{$lang['userdetails_avatar']}</td>
			<td>{$lang['userdetails_username']}" . ($CURUSER['class'] >= UC_STAFF ? $lang['userdetails_fip'] : "") . "</td>
			<td>{$lang['userdetails_uploaded']}</td>" . ($INSTALLER09['ratio_free'] ? "" : "
			<td>{$lang['userdetails_downloaded']}</td>") . "
			<td>{$lang['userdetails_ratio']}</td>
			<td>{$lang['userdetails_status']}</td>
		</tr>
	</thead>";
    if ($users_friends) {
        foreach ($users_friends as $a) {
            $avatar = (($user['opt1'] & user_options::AVATARS) ? ($a['avatar'] == '' ? '<img src="' . $INSTALLER09['pic_base_url'] . 'default_avatar.gif"  width="40" alt="default avatar" />' : '<img src="' . htmlsafechars($a['avatar']) . '" alt="avatar"  width="40" />') : '');
            $status = "<img style='vertical-align: middle;' src='{$INSTALLER09['pic_base_url']}" . ($a['last_access'] > $dt && $a['perms'] < bt_options::PERMS_STEALTH ? "online.png" : "offline.png") . "' border='0' alt='' />";
            $user_stuff = $a;
            $user_stuff['id'] = (int)$a['id'];
            $user_friends.= "<tr>
			<tbody>
				<td>" . $avatar . "</td>
				<td>" . format_username($user_stuff) . ($CURUSER['class'] >= UC_STAFF ? "" . htmlsafechars($a['ip']) . "" : "") . "</td>
				<td>" . mksize($a['uploaded']) . "</td>" . ($INSTALLER09['ratio_free'] ? "" : "
				<td>" . mksize($a['downloaded']) . "</td>") . "
				<td>" . member_ratio($a['uploaded'], $INSTALLER09['ratio_free'] ? '0' : $a['downloaded']) . "</td>
				<td>" . $status . "</td>
			</tr>
		</tbody>";
        }
        $user_friends.= "</table>";
        $HTMLOUT.= "<div class='card secondary'>
		<ul class='accordion' data-accordion data-allow-all-closed='true'>
		  <li class='accordion-item' data-accordion-item>
			<a href='#' class='accordion-title'><i class='fas fa-users'></i>{$lang['userdetails_friends']}</a>
			<div class='accordion-content' data-tab-content>
			  $user_friends
			</div>
		  </li>
		  </ul>
		  </div>";
    } else {
        if (empty($users_friends)) 
			$HTMLOUT.= "<div class='row'>
		<div class='card large-12 columns'>
			<h6 class='subheader'><span class='label primary'><i class='fas fa-users'></i>{$lang['userdetails_friends']}</label></h6><hr>
			<div class='card-section'><p>{$lang['userdetails_no_friends']}</p></div></div></div>";
    }
}
//== thee end
//==end
// End Class
// End File
