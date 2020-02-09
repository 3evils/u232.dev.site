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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once CLASS_DIR . 'page_verify.php';
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'bbcode_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'comment_functions.php';
require_once (INCL_DIR . 'function_onlinetime.php');
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('userdetails'));
if (function_exists('parked')) parked();
$newpage = new page_verify();
$newpage->create('mdk1@@9');
$stdhead = array(
    /** include css **/
    'css' => array(
     'jquery.treeview',
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
	'popup',
        'java_klappe',
        'jquery.treeview.pack',
        'flip_box',
        'sprinkle',
        'flush_torrents'
    )
);
$id = (int)$_GET["id"];
if (!is_valid_id($id)) stderr($lang['userdetails_error'], "{$lang['userdetails_bad_id']}");
if (($user = $mc1->get_value('user' . $id)) === false) {
    $user_fields_ar_int = array(
        'id',
        'added',
        'last_login',
        'last_access',
        'stylesheet',
        'class',
        'override_class',
        'language',
        'av_w',
        'av_h',
        'country',
        'warned',
        'torrentsperpage',
        'topicsperpage',
        'postsperpage',
        'reputation',
        'dst_in_use',
        'auto_correct_dst',
        'chatpost',
        'smile_until',
        'vip_until',
        'freeslots',
        'free_switch',
        'invites',
        'invitedby',
        'uploadpos',
        'forumpost',
        'downloadpos',
        'immunity',
        'leechwarn',
        'last_browse',
        'sig_w',
        'sig_h',
        'forum_access',
        'hit_and_run_total',
        'donoruntil',
        'donated',
        'vipclass_before',
        'passhint',
        'avatarpos',
        'sendpmpos',
        'invitedate',
        'anonymous_until',
        'pirate',
        'king',
        'ssluse',
        'paranoia',
        'parked_until',
        'bjwins',
        'bjlosses',
        'irctotal',
        'last_access_numb',
        'onlinetime',
        'hits',
        'comments',
        'categorie_icon',
        'perms',
        'mood',
        'pms_per_page',
        'watched_user',
        'game_access',
        'reputation',
        'opt1',
        'opt2',
        'can_leech',
        'wait_time',
        'torrents_limit',
        'peers_limit',
        'torrent_pass_version',
    );
    $user_fields_ar_float = array(
        'time_offset',
        'total_donated'
    );
    $user_fields_ar_str = array(
        'username',
        'passhash',
        'secret',
        'torrent_pass',
        'email',
        'status',
        'editsecret',
        'privacy',
        'info',
        'acceptpms',
        'ip',
        'avatar',
        'title',
        'notifs',
        'enabled',
        'donor',
        'deletepms',
        'savepms',
        'show_shout',
        'show_staffshout',
        'shoutboxbg',
        'vip_added',
        'invite_rights',
        'anonymous',
        'disable_reason',
        'clear_new_tag_manually',
        'signatures',
        'signature',
        'highspeed',
        'hnrwarn',
        'parked',
        'hintanswer',
        'support',
        'supportfor',
        'invitees',
        'invite_on',
        'subscription_pm',
        'gender',
        'viewscloud',
        'tenpercent',
        'avatars',
        'offavatar',
        'hidecur',
        'signature_post',
        'forum_post',
        'avatar_rights',
        'offensive_avatar',
        'view_offensive_avatar',
        'google_talk',
        'msn',
        'aim',
        'yahoo',
        'website',
        'icq',
        'show_email',
        'gotgift',
        'hash1',
        'suspended',
        'warn_reason',
        'onirc',
        'birthday',
        'got_blocks',
        'pm_on_delete',
        'commentpm',
        'split',
        'browser',
        'got_moods',
        'show_pm_avatar',
        'watched_user_reason',
        'staff_notes',
        'where_is',
        'browse_icons',
        'forum_mod',
        'forums_mod',
        'altnick',
        'forum_sort',
        'pm_forced'
    );
    $user_fields = implode(', ', array_merge($user_fields_ar_int, $user_fields_ar_float, $user_fields_ar_str));
    $r1 = sql_query("SELECT " . $user_fields . " FROM users WHERE id=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $user = mysqli_fetch_assoc($r1) or stderr($lang['userdetails_error'], "{$lang['userdetails_no_user']}");
    foreach ($user_fields_ar_int as $i) $user[$i] = (int)$user[$i];
    foreach ($user_fields_ar_float as $i) $user[$i] = (float)$user[$i];
    foreach ($user_fields_ar_str as $i) $user[$i] = $user[$i];
    $mc1->cache_value('user' . $id, $user, $INSTALLER09['expires']['user_cache']);
}
if ($user["status"] == "pending") stderr($lang['userdetails_error'], $lang['userdetails_pending']);
// user stats
$What_Cache = (OCELOT_TRACKER == true ? 'user_stats_ocelot_' : 'user_stats_');
if (($user_stats = $mc1->get_value($What_Cache.$id)) === false) {
    $What_Expire = (OCELOT_TRACKER == true ? $INSTALLER09['expires']['user_stats_ocelot'] : $INSTALLER09['expires']['user_stats']);
    $stats_fields_ar_int = array(
            'uploaded',
            'downloaded'
        );
        $stats_fields_ar_float = array(
            'seedbonus'
        );
        $stats_fields_ar_str = array(
            'modcomment',
            'bonuscomment'
        );
        $stats_fields = implode(', ', array_merge($stats_fields_ar_int, $stats_fields_ar_float, $stats_fields_ar_str));
    $sql_1 = sql_query('SELECT ' . $stats_fields . ' FROM users WHERE id= ' . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $user_stats = mysqli_fetch_assoc($sql_1);
    foreach ($stats_fields_ar_int as $i) $user_stats[$i] = (int)$user_stats[$i];
    foreach ($stats_fields_ar_float as $i) $user_stats[$i] = (float)$user_stats[$i];
    foreach ($stats_fields_ar_str as $i) $user_stats[$i] = $user_stats[$i];
    $mc1->cache_value($What_Cache.$id, $user_stats, $What_Expire); // 5 mins
}
if (($user_status = $mc1->get_value('user_status_' . $id)) === false) {
    $sql_2 = sql_query('SELECT * FROM ustatus WHERE userid = ' . sqlesc($id));
    if (mysqli_num_rows($sql_2)) $user_status = mysqli_fetch_assoc($sql_2);
    else $user_status = array(
        'last_status' => '',
        'last_update' => 0,
        'archive' => ''
    );
    $mc1->add_value('user_status_' . $id, $user_status, $INSTALLER09['expires']['user_status']); // 30 days
    
}
//===  paranoid settings
if ($user['paranoia'] == 3 && $CURUSER['class'] < UC_STAFF && $CURUSER['id'] <> $id) {
    stderr($lang['userdetails_error'], '<span style="font-weight: bold; text-align: center;"><img src="pic/smilies/tinfoilhat.gif" alt="'.$lang['userdetails_tinfoil'].'" title="'.$lang['userdetails_tinfoil'].'" />
       '.$lang['userdetails_tinfoil2'].' <img src="pic/smilies/tinfoilhat.gif" alt="'.$lang['userdetails_tinfoil'].'" title="'.$lang['userdetails_tinfoil'].'"></span>');
    die();
}
//=== delete H&R
if (isset($_GET['delete_hit_and_run']) && $CURUSER['class'] >= UC_STAFF) {
    $delete_me = isset($_GET['delete_hit_and_run']) ? intval($_GET['delete_hit_and_run']) : 0;
    if (!is_valid_id($delete_me)) stderr($lang['userdetails_error'], $lang['userdetails_bad_id']);
    if(OCELOT_TRACKER === false) {
    sql_query('UPDATE snatched SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE id = ' . sqlesc($delete_me)) or sqlerr(__FILE__, __LINE__);
    } else {
    sql_query('UPDATE xbt_files_users SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE fid = ' . sqlesc($delete_me)) or sqlerr(__FILE__, __LINE__);
    }
    if (@mysqli_affected_rows($GLOBALS["___mysqli_ston"]) === 0) {
        stderr($lang['userdetails_error'], $lang['userdetails_notdeleted']);
    }
    header('Location: ?id=' . $id . '&completed=1');
    die();
}
if ($user['ip'] && ($CURUSER['class'] >= UC_STAFF || $user['id'] == $CURUSER['id'])) {
    $dom = @gethostbyaddr($user['ip']);
    $addr = ($dom == $user['ip'] || @gethostbyname($dom) != $user['ip']) ? $user['ip'] : $user['ip'] . ' (' . $dom . ')';
}

/** #$^$&%$&@ invincible! NO IP LOGGING..pdq **/
if ((($user['class'] == UC_MAX OR $user['id'] == $CURUSER['id']) || ($user['class'] < UC_MAX) && $CURUSER['class'] == UC_MAX) && isset($_GET['invincible'])) {
    require_once (INCL_DIR . 'invincible.php');
    if ($_GET['invincible'] == 'yes') $HTMLOUT.= "<div class='row'><div class='large-4 columns'>". invincible($id). "</div></div";
    elseif ($_GET['invincible'] == 'remove_bypass') $HTMLOUT.= "<div class='row'><div class='large-4 columns'>".invincible($id, true, false);
    else $HTMLOUT.= invincible($id, false);
} // End

/** #$^$&%$&@ stealth!..pdq **/
if ((($user['class'] >= UC_STAFF OR $user['id'] == $CURUSER['id']) || ($user['class'] < UC_STAFF) && $CURUSER['class'] >= UC_STAFF) && isset($_GET['stealth'])) {
    require_once (INCL_DIR . 'stealth.php');
    if ($_GET['stealth'] == 'yes') $HTMLOUT.= "<div class='row'><div class'col-md-4'>". stealth($id). "</div></div";
    elseif ($_GET['stealth'] == 'no') $HTMLOUT.= "<div class='row'><div class'col-md-4'>". stealth($id, false). "</div></div";
} // End

if (OCELOT_TRACKER == true) {
    $res = sql_query("SELECT x.fid, x.uploaded, x.downloaded, x.active, x.left, t.added, t.name as torrentname, t.size, t.category, t.seeders, t.leechers, c.name as catname, c.image FROM xbt_files_users x LEFT JOIN torrents t ON x.fid = t.id LEFT JOIN categories c ON t.category = c.id WHERE x.uid=" . sqlesc($id) . " AND active = 1") or sqlerr(__FILE__, __LINE__);
    while ($arr = mysqli_fetch_assoc($res)) {
        if ($arr['left'] == '0') $seeding[] = $arr;
        else $leeching[] = $arr;
    }
} else {
    $res = sql_query("SELECT p.torrent, p.uploaded, p.downloaded, p.seeder, t.added, t.name as torrentname, t.size, t.category, t.seeders, t.leechers, c.name as catname, c.image FROM peers p LEFT JOIN torrents t ON p.torrent = t.id LEFT JOIN categories c ON t.category = c.id WHERE p.userid=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    while ($arr = mysqli_fetch_assoc($res)) {
        if ($arr['seeder'] == 'yes') $seeding[] = $arr;
        else $leeching[] = $arr;
    }
}
//==userhits update by pdq
if (!(isset($_GET["hit"])) && $CURUSER["id"] <> $user["id"]) {
    $res = sql_query("SELECT added FROM userhits WHERE userid =" . sqlesc($CURUSER['id']) . " AND hitid = " . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
    $row = mysqli_fetch_row($res);
    if (!($row[0] > TIME_NOW - 3600)) {
        $hitnumber = $user['hits'] + 1;
        sql_query("UPDATE users SET hits = hits + 1 WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        // do update hits userdetails cache
        $update['user_hits'] = ($user['hits'] + 1);
        $mc1->begin_transaction('MyUser_' . $id);
        $mc1->update_row(false, array(
            'hits' => $update['user_hits']
        ));
        $mc1->commit_transaction($INSTALLER09['expires']['curuser']);
        $mc1->begin_transaction('user' . $id);
        $mc1->update_row(false, array(
            'hits' => $update['user_hits']
        ));
        $mc1->commit_transaction($INSTALLER09['expires']['user_cache']);
        sql_query("INSERT INTO userhits (userid, hitid, number, added) VALUES(" . sqlesc($CURUSER['id']) . ", " . sqlesc($id) . ", " . sqlesc($hitnumber) . ", " . sqlesc(TIME_NOW) . ")") or sqlerr(__FILE__, __LINE__);
    }
}
$HTMLOUT = '';
if (($user['anonymous'] == 'yes') && ($CURUSER['class'] < UC_STAFF && $user["id"] != $CURUSER["id"])) {
    $HTMLOUT.= "<table>";
    $HTMLOUT.= "<tr><td colspan='2' align='center'>{$lang['userdetails_anonymous']}</td></tr>";
    if ($user["avatar"]) $HTMLOUT.= "<tr><td colspan='2' align='center'><img src='" . htmlsafechars($user["avatar"]) . "'></td></tr>\n";
    if ($user["info"]) $HTMLOUT.= "<tr valign='top'><td align='left' colspan='2' class='text' bgcolor='#F4F4F0'>'" . format_comment($user["info"]) . "'</td></tr>\n";
    $HTMLOUT.= "<tr><td colspan='2' align='center'><form method='get' action='{$INSTALLER09['baseurl']}/pm_system.php?action=send_message'><input type='hidden' name='receiver' value='" . (int)$user["id"] . "'><input type='submit' value='{$lang['userdetails_sendmess']}' style='height: 23px'></form>";
    if ($CURUSER['class'] < UC_STAFF && $user["id"] != $CURUSER["id"]) {
        echo stdhead($lang['userdetails_anonymoususer']) . $HTMLOUT . stdfoot();
        die;
    }
    $HTMLOUT.= "</td></tr></table><br>";
}
require_once (BLOCK_DIR . 'userdetails/links.php');
require_once (BLOCK_DIR . 'userdetails/alerts.php');
require_once (BLOCK_DIR . 'userdetails/icons.php');
$HTMLOUT.= "<div class='callout'>" . "" . format_username($user, true) . "$country$perms$stealth<a class='button float-right' data-open='stuffModal'>Click me for info</a></div>";
	$HTMLOUT.= $h1_thingie;
$HTMLOUT.= "<div class='large reveal' id='stuffModal' data-reveal>
	<div class='row'>
		<div class='large-6 columns'><p>". $watched_user ."</p>"."<p>". $suspended  ."</p>". $shitty_alert . "</div>
		<div class='large-6 columns'>
		".$parked_alert . $enable_alert . $donoruntil_alert .  "
	  <button class='close-button' data-close aria-label='Close modal' type='button'>
		<span aria-hidden='true'>&times;</span>
	  </button>
	  </div>
	</div></div>";

$possible_actions = array(
    'torrents',
    'snatched',
    'general',
    'social',
    'activity',
    'comments',
    'edit',
	'links',
    'default'
);
$action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : 'torrents';
if (!in_array($action, $possible_actions)) stderr('Error','<br><div class="alert alert-error span11">Error! Change a few things up and try submitting again.</div>');
             $HTMLOUT.= "
<div class='container'>";
require_once (BLOCK_DIR . 'userdetails/tab_menu.php');
$HTMLOUT.= "<div class='row'>";
$HTMLOUT.= "<div class='tabs-content'>";
if ($action == "links") {
	$HTMLOUT.= "<div class='card'>";
	$HTMLOUT.= "<div class='vertical-menu'>".$sharemarks_link . $friends_link . $links_invincible . $links_stealth . $blocks_link . $shitty_link . $shitty_alert . $user_link;
	$HTMLOUT.= "</div></div>";
}
if ($action == "torrents") {
$HTMLOUT.= "<table class='stack striped  centered columns'>";
if (curuser::$blocks['userdetails_page'] & block_userdetails::FLUSH && $BLOCKS['userdetails_flush_on']) {
    require_once (BLOCK_DIR . 'userdetails/flush.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TRAFFIC && $BLOCKS['userdetails_traffic_on']) {
    require_once (BLOCK_DIR . 'userdetails/traffic.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHARE_RATIO && $BLOCKS['userdetails_share_ratio_on']) {
    require_once (BLOCK_DIR . 'userdetails/shareratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDTIME_RATIO && $BLOCKS['userdetails_seedtime_ratio_on']) {
    require_once (BLOCK_DIR . 'userdetails/seedtimeratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once (BLOCK_DIR . 'userdetails/torrents_block.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::COMPLETED && $BLOCKS['userdetails_completed_on']/* && OCELOT_TRACKER == false*/) {
    require_once (BLOCK_DIR . 'userdetails/completed.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::CONNECTABLE_PORT && $BLOCKS['userdetails_connectable_port_on']) {
    require_once (BLOCK_DIR . 'userdetails/connectable.php');
}
$HTMLOUT.= "</table>";
}
if ($action == "snatched") {
$HTMLOUT.= "<table class='table table-bordered'>";
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once (BLOCK_DIR . 'userdetails/snatched_block.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SNATCHED_STAFF && $BLOCKS['userdetails_snatched_staff_on']/* && OCELOT_TRACKER == false*/) {
    require_once (BLOCK_DIR . 'userdetails/snatched_staff.php');
}
$HTMLOUT.= "</table>";
}

if ($action == "general") {

$HTMLOUT.= "<table class='table table-bordered'>";
// === make sure prople can't see their own naughty history by snuggles
if (($CURUSER['id'] !== $user['id']) && ($CURUSER['class'] >= UC_STAFF)) {
	$HTMLOUT.= '<div class="row">';
	require_once (BLOCK_DIR . 'userdetails/watched_user.php');
	require_once (BLOCK_DIR . 'userdetails/staff_notes.php');
	require_once (BLOCK_DIR . 'userdetails/system_comments.php');
	$HTMLOUT.= '</div>';
}
//==Begin blocks
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHOWFRIENDS && $BLOCKS['userdetails_showfriends_on']){
require_once (BLOCK_DIR . 'userdetails/showfriends.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::LOGIN_LINK && $BLOCKS['userdetails_login_link_on']) {
    require_once (BLOCK_DIR . 'userdetails/loginlink.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::JOINED && $BLOCKS['userdetails_joined_on']) {
    require_once (BLOCK_DIR . 'userdetails/joined.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::ONLINETIME && $BLOCKS['userdetails_online_time_on']) {
    require_once (BLOCK_DIR . 'userdetails/onlinetime.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BROWSER && $BLOCKS['userdetails_browser_on']) {
    require_once (BLOCK_DIR . 'userdetails/browser.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BIRTHDAY && $BLOCKS['userdetails_birthday_on']) {
    require_once (BLOCK_DIR . 'userdetails/birthday.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::CONTACT_INFO && $BLOCKS['userdetails_contact_info_on']) {
    require_once (BLOCK_DIR . 'userdetails/contactinfo.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::IPHISTORY && $BLOCKS['userdetails_iphistory_on']) {
    require_once (BLOCK_DIR . 'userdetails/iphistory.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::AVATAR && $BLOCKS['userdetails_avatar_on']) {
    require_once (BLOCK_DIR . 'userdetails/avatar.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERCLASS && $BLOCKS['userdetails_userclass_on']) {
    require_once (BLOCK_DIR . 'userdetails/userclass.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::GENDER && $BLOCKS['userdetails_gender_on']) {
    require_once (BLOCK_DIR . 'userdetails/gender.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERINFO && $BLOCKS['userdetails_userinfo_on']) {
    require_once (BLOCK_DIR . 'userdetails/userinfo.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::REPORT_USER && $BLOCKS['userdetails_report_user_on']) {
    require_once (BLOCK_DIR . 'userdetails/report.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERSTATUS && $BLOCKS['userdetails_user_status_on']) {
    require_once (BLOCK_DIR . 'userdetails/userstatus.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHOWPM && $BLOCKS['userdetails_showpm_on']) {
    require_once (BLOCK_DIR . 'userdetails/showpm.php');
}
$HTMLOUT.= "</table>";
}
if ($action == "activity") {
$HTMLOUT.= "<table class='table table-bordered'>";
require_once (BLOCK_DIR . 'userdetails/moods.php');
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDBONUS && $BLOCKS['userdetails_seedbonus_on']) {
    require_once (BLOCK_DIR . 'userdetails/seedbonus.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::IRC_STATS && $BLOCKS['userdetails_irc_stats_on']) {
    require_once (BLOCK_DIR . 'userdetails/irc.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::REPUTATION && $BLOCKS['userdetails_reputation_on'] && $INSTALLER09['rep_sys_on']) {
    require_once (BLOCK_DIR . 'userdetails/reputation.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::PROFILE_HITS && $BLOCKS['userdetails_profile_hits_on']) {
    require_once (BLOCK_DIR . 'userdetails/userhits.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::FREESTUFFS && $BLOCKS['userdetails_freestuffs_on'] && OCELOT_TRACKER == false) {
    require_once (BLOCK_DIR . 'userdetails/freestuffs.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::COMMENTS && $BLOCKS['userdetails_comments_on']) {
    require_once (BLOCK_DIR . 'userdetails/comments.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::FORUMPOSTS && $BLOCKS['userdetails_forumposts_on']) {
    require_once (BLOCK_DIR . 'userdetails/forumposts.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::INVITEDBY && $BLOCKS['userdetails_invitedby_on']) {
    require_once (BLOCK_DIR . 'userdetails/invitedby.php');
}
$HTMLOUT.= "</table>";
}
if ($action == "comments") {
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERCOMMENTS && $BLOCKS['userdetails_user_comments_on']) {
    require_once (BLOCK_DIR . 'userdetails/usercomments.php');
}
}
if ($action == "edit") {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails.php');
}
$HTMLOUT.= "</div></div>";
echo stdhead("{$lang['userdetails_details']} " . $user["username"], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
