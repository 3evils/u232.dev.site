<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2018 U-232 CodeName Trinity                              |
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
$HTMLOUT = "";
$id = (int)$_GET["id"];
if (!is_valid_id($id)) stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);
$res = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$arr = mysqli_fetch_assoc($res);
if (!$arr) stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);
$achpoints = (int)$arr['achpoints'];
$spentpoints = (int)$arr['spentpoints'];
$res = sql_query("SELECT COUNT(*) FROM achievements WHERE userid =" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_row($res);
$count = $row[0];
$perpage = 15;
if (!$count) stderr($lang['achievement_history_no'], "{$lang['achievement_history_err2']}<a class='altlink' href='userdetails.php?id=" . (int)$arr['id'] . "'>" . htmlsafechars($arr['username']) . "</a>{$lang['achievement_history_err3']}");
$pager = pager($perpage, $count, "?id=$id&amp;");
$HTMLOUT.= "<div class='{$card}'>
			<div class='{$card_divider}'>{$lang['achievement_history_afu']}
			<a class='altlink' href='{$INSTALLER09['baseurl']}/userdetails.php?id=" . (int)$arr['id'] . "'>" . htmlsafechars($arr['username']) . "</a><br />
			{$lang['achievement_history_c']}" . htmlsafechars($row['0']) . "{$lang['achievement_history_a']}" . ($row[0] == 1 ? "" : "s") . ".";
if ($id == $CURUSER['id']) {
    $HTMLOUT.= "
			<a class='altlink' href='achievementbonus.php'>" . htmlsafechars($achpoints) . "{$lang['achievement_history_pa']}" . htmlsafechars($spentpoints) . "{$lang['achievement_history_ps']}</a>";
}
if ($id == $CURUSER['id']) {
    $HTMLOUT.= "
		<span class='{$button}'>
		<a href='/achievementlist.php'>{$lang['achievement_history_al']}</a></span>
		<span class='{$button}'>
		<a href='/postcounter.php'>{$lang['achievement_history_fpc']}</a></span>
		<span class='{$button}'>
		<a href='/topiccounter.php'>{$lang['achievement_history_ftc']}</a></span>
		<span class='{$button}'>
		<a href='/invitecounter.php'>{$lang['achievement_history_ic']}</a></span>
	";
}
$HTMLOUT.= "</div>";
//if ($count > $perpage) $HTMLOUT.= $pager['pagertop'];
$HTMLOUT.= "<div class='row {$small_up_1} {$medium_up_2} {$large_up_3}' {$data_equalizer} data-equalize-on='medium' id='achievement_equal'>";
$res = sql_query("SELECT * FROM achievements WHERE userid=" . sqlesc($id) . " ORDER BY date DESC {$pager['limit']}") or sqlerr(__FILE__, __LINE__);
while ($arr = mysqli_fetch_assoc($res)) {
    $HTMLOUT.= "<div class='{$column} {$column_block} margin-0 {$callout}'>
			<div class='{$achievement_section}' {$data_equalizer_watch}>
				  <div class='{$achievement}'>
					<p class='{$margin_bottom_2} {$margin_left_1}'><strong>" . htmlsafechars($arr['description']) . "</strong></p>
					<img class='profile-pic {$margin_2}' src='pic/achievements/" . htmlsafechars($arr['icon']) . "' alt='" . htmlsafechars($arr['achievement']) . "' title='" . htmlsafechars($arr['achievement']) . "' />
					</div>
					<p class='{$text_center} {$margin_bottom_2}'>This achievement was earned " . get_date($arr['date'], '') . "</p></div></div>";
}
$HTMLOUT.= "</div></div>";
if ($count > $perpage) $HTMLOUT.= $pager['pagerbottom'];
?>