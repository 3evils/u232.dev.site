<?php
     /*-----------------------------------------------------------------------\
	|   https://github.com/Bigjoos/ -------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Licence Info: WTFPL  --------------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Copyright (C) 2010 U-232 V5	-------------------------------------------|
	|--------------------------------------------------------------------------|
	|   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon. --|
	|--------------------------------------------------------------------------|
	|   Project Leaders: Mindless, Autotron, whocares, Swizzles.---------------|
	\------------------------------------------------------------------------*/
 //==Template system by Terranova
 //==Template system modified by son
function StatusBar()
{
    global $CURUSER, $INSTALLER09, $lang, $rep_is_on, $mc1, $msgalert;
    if (!$CURUSER) return "";
    $upped = mksize($CURUSER['uploaded']);
    $downed = mksize($CURUSER['downloaded']);
    $connectable = "";
    if ($CURUSER['class'] < UC_VIP && $INSTALLER09['max_slots']) {
    $ratioq = (($CURUSER['downloaded'] > 0) ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : 1);
if ($ratioq < 0.95) {
	switch (true) {
		case ($ratioq < 0.5):
		$max = 2;
		break;
		case ($ratioq < 0.65):
		$max = 3;
		break;
		case ($ratioq < 0.8):
		$max = 5;
		break;
		case ($ratioq < 0.95):
		$max = 10;
		break;
		default:
	   $max = 10;
	}
 }
 else {
 switch ($CURUSER['class']) {
		case UC_USER:
		$max = 20;
		break;
		case UC_POWER_USER:
		$max = 30;
		break;
		default:
	   $max = 99;
	}	
 }   
}
else
$max = 999;
    //==Memcache unread pms
    $PMCount = 0;
    if (($unread1 = $mc1->get_value('inbox_new_sb_' . $CURUSER['id'])) === false) {
        $res1 = sql_query("SELECT COUNT(id) FROM messages WHERE receiver=" . sqlesc($CURUSER['id']) . " AND unread = 'yes' AND location = '1'") or sqlerr(__LINE__, __FILE__);
        list($PMCount) = mysqli_fetch_row($res1);
        $PMCount = (int)$PMCount;
        $unread1 = $mc1->cache_value('inbox_new_sb_' . $CURUSER['id'], $PMCount, $INSTALLER09['expires']['unread']);
    }
    $inbox = ($unread1 == 1 ? "$unread1&nbsp;{$lang['gl_msg_singular']}" : "$unread1&nbsp;{$lang['gl_msg_plural']}");
    //==Memcache peers
    if (OCELOT_TRACKER == true) {
    if (($MyPeersXbtCache = $mc1->get_value('MyPeers_Ocelot_'.$CURUSER['id'])) === false) {
        $seed['yes'] = $seed['no'] = 0;
        $seed['conn'] = 3;
        $r = sql_query("SELECT COUNT(uid) AS `count`, `left`, `active`, `connectable` FROM `xbt_files_users` WHERE uid= " . sqlesc($CURUSER['id']) . " AND `left` = 0 AND `active` = 1") or sqlerr(__LINE__, __FILE__);
        while ($a = mysqli_fetch_assoc($r)) {
            $key = $a['left'] == 0 ? 'yes' : 'no';
            $seed[$key] = number_format(0 + $a['count']);
            $seed['conn'] = $a['connectable'] == 0 ? 1 : 2;
        }
        $mc1->cache_value('MyPeers_Ocelot_'.$CURUSER['id'], $seed, $INSTALLER09['expires']['MyPeers_Ocelot_']);
        unset($r, $a);
    } else {
        $seed = $MyPeersXbtCache;
    }
} else {
    if (($MyPeersCache = $mc1->get_value('MyPeers_' . $CURUSER['id'])) === false) {
        $seed['yes'] = $seed['no'] = 0;
        $seed['conn'] = 3;
        $r = sql_query("SELECT COUNT(id) AS count, seeder, connectable FROM peers WHERE userid=" . sqlesc($CURUSER['id']) . " GROUP BY seeder");
        while ($a = mysqli_fetch_assoc($r)) {
            $key = $a['seeder'] == 'yes' ? 'yes' : 'no';
            $seed[$key] = number_format(0 + $a['count']);
            $seed['conn'] = $a['connectable'] == 'no' ? 1 : 2;
        }
        $mc1->cache_value('MyPeers_' . $CURUSER['id'], $seed, $INSTALLER09['expires']['MyPeers_']);
        unset($r, $a);
    } else {
        $seed = $MyPeersCache;
    }
   }
     // for display connectable  1 / 2 / 3
    if (!empty($seed['conn'])) {
        switch ($seed['conn']) {
        case 1:
            $connectable = "<img src='{$INSTALLER09['pic_base_url']}notcon.png' alt='Not Connectable' title='Not Connectable' />";
            break;
        case 2:
            $connectable = "<img src='{$INSTALLER09['pic_base_url']}yescon.png' alt='Connectable' title='Connectable' />";
            break;
        default:
            $connectable = "N/A";
        }
    } else $connectable = 'N/A';

    if (($Achievement_Points = $mc1->get_value('user_achievement_points_' . $CURUSER['id'])) === false) {
        $Sql = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
        $Achievement_Points = mysqli_fetch_assoc($Sql);
        $Achievement_Points['id'] = (int)$Achievement_Points['id'];
        $Achievement_Points['achpoints'] = (int)$Achievement_Points['achpoints'];
        $Achievement_Points['spentpoints'] = (int)$Achievement_Points['spentpoints'];
        $mc1->cache_value('user_achievement_points_' . $CURUSER['id'], $Achievement_Points, 0);
    }
    //$hitnruns = ($CURUSER['hit_and_run_total'] > 0 ? $CURUSER['hit_and_run_total'] : '0');
    //{$lang['gl_hnr']}: <a href='".$INSTALLER09['baseurl']."/hnr.php?id=".$CURUSER['id']."'>{$hitnruns}</a>&nbsp;
    $member_reputation = get_reputation($CURUSER);
    $usrclass = $StatusBar = "";
    if ($CURUSER['override_class'] != 255) $usrclass = "&nbsp;<b>[" . get_user_class_name($CURUSER['class']) . "]</b>&nbsp;";
    else if ($CURUSER['class'] >= UC_STAFF) $usrclass = "&nbsp;<a href='".$INSTALLER09['baseurl']."/setclass.php'><b>[" . get_user_class_name($CURUSER['class']) . "]</b></a>&nbsp;";
    $StatusBar.= "<div class='text-center'>Welcome ".format_username($CURUSER) ."".(isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "[".get_user_class_name($CURUSER['class'])."]" : $usrclass)."
    ".($INSTALLER09['max_slots'] ? "{$lang['gl_act_torrents']}:&nbsp;
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-up'></i></label>{$lang['gl_seed_torrents']}
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-up'></i></label>{$lang['gl_seed_torrents']}
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-up'></i></label>&nbsp;".intval($seed['yes'])." "."&nbsp;
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-down'></i></label> {$lang['gl_leech_torrents']}
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-down'></i></label> {$lang['gl_leech_torrents']} 
    <label class='btn btn-info btn-xs'><i class='fa fa-arrow-down'>&nbsp;".($INSTALLER09['max_slots'] ? "<a title='I have ".$max." Download Slots'>".intval($seed['no'])."/".$max."</a>" : intval($seed['no']))."
    " : "")."&nbsp;".($INSTALLER09['achieve_sys_on'] ? "
    <label class='btn btn-info btn-xs'><i class='fa fa-trophy'></i></label> {$lang['gl_achpoints']}&nbsp;<a href='".$INSTALLER09['baseurl']."/achievementhistory.php?id={$CURUSER['id']}'>" . (int)$Achievement_Points['achpoints'] . "</a>&nbsp;" : "")."
    ".($INSTALLER09['seedbonus_on'] ? " 
    <label class='btn btn-info btn-xs'><i class='fa fa-btc'></i></label> {$lang['gl_karma']}: <a href='".$INSTALLER09['baseurl']."/mybonus.php'>{$CURUSER['seedbonus']}</a>&nbsp;" : "")." 
    <label class='btn btn-info btn-xs'><i class='fa fa-info'></i></label> {$lang['gl_invites']}: <a href='".$INSTALLER09['baseurl']."/invite.php'>{$CURUSER['invites']}</a>&nbsp;
    ".($INSTALLER09['rep_sys_on'] ? "<label class='btn btn-info btn-xs'><i class='fa fa-user-plus'></i></label> {$lang['gl_rep']}:{$member_reputation}" : "")."
    <br />
    <label class='btn btn-info btn-xs'><i class='fa fa-percent'></i></label> {$lang['gl_shareratio']}&nbsp;
    ". member_ratio($CURUSER['uploaded'], $INSTALLER09['ratio_free'] ? '0' : $CURUSER['downloaded']);
 if ($INSTALLER09['ratio_free']) {
    $StatusBar .= " 
    <label class='btn btn-info btn-xs'><i class='fa fa-upload'></i></label> {$lang['gl_uploaded']}:".$upped;
    } else {
        $StatusBar .= " 
        <label class='btn btn-info btn-xs'><i class='fa fa-upload'></i></label>&nbsp;{$lang['gl_uploaded']}:{$upped} 
        <label class='btn btn-info btn-xs'><i class='fa fa-download'></i></label> {$lang['gl_downloaded']}:{$downed}
        <label class='btn btn-info btn-xs'><i class='fa fa-exchange'></i></label> &nbsp;{$lang['gl_connectable']}&nbsp;{$connectable}";
  
}
	$StatusBar .= "</div>";
    return $StatusBar;
}
?>
