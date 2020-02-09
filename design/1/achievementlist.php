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
 *///$doUpdate = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && $CURUSER['class'] >= UC_MAX) {
    $clienticon = htmlsafechars(trim($_POST["clienticon"]));
    $achievname = htmlsafechars(trim($_POST["achievname"]));
    $notes = htmlsafechars($_POST["notes"]);
    $clienticon = htmlsafechars($clienticon);
    $achievname = htmlsafechars($achievname);
    sql_query("INSERT INTO achievementist (achievname, notes, clienticon) VALUES(" . sqlesc($achievname) . ", " . sqlesc($notes) . ", " . sqlesc($clienticon) . ")") or sqlerr(__FILE__, __LINE__);
    $message = "{$lang['achlst_new_ach_been_added']}. {$lang['achlst_achievement']}: [{$achievname}]";
    //autoshout($message);
    //$doUpdate = true;
    
}

// == Query update by Putyn
$res = sql_query("SELECT a1.*, (SELECT COUNT(a2.id) FROM achievements AS a2 WHERE a2.achievement = a1.achievname) as count FROM achievementist AS a1 ORDER BY a1.id ") or sqlerr(__FILE__, __LINE__);
$HTMLOUT = '';
$HTMLOUT.= "<div class='{$design['card']}'>
<div class='{$design['card_divider']}'>{$lang['achlst_std_head']}". (isset($CURUSER) && $CURUSER['class'] >= UC_MAX ? "<span class='{$design['button']} {$design['float_right']}' data-open='achievModal'>Add</span>" : "") . "</div>";
if (mysqli_num_rows($res) == 0) {
    $HTMLOUT.= "<p class='{$design['text_center']}'><b>{$lang['achlst_there_no_ach_msg']}!<br />{$lang['achlst_staff_been_lazy']}!</b></p>\n";
} else {
	$HTMLOUT.="<div class='{$design['row']} {$design['small_up_1']} {$design['medium_up_2']} {$design['large_up_3']}' {$design['data_equalizer']} data-equalize-on='medium' id='achievement_equal'>";
    $HTMLOUT.= "";
    while ($arr = mysqli_fetch_assoc($res)) {
        $notes = htmlsafechars($arr["notes"]);
        $clienticon = '';
        if ($arr["clienticon"] != "") {
            $clienticon = "<img class='profile-pic hide-for-small-only margin-2' src='" . $INSTALLER09['pic_base_url'] . "achievements/" . htmlsafechars($arr["clienticon"]) . "' title='" . htmlsafechars($arr['achievname']) . "' alt='" . htmlsafechars($arr['achievname']) . "'>";
        }
        $HTMLOUT.= "<div class='{$design['column']} {$design['column_block']} margin-0 {$design['callout']}'>
			<div class='{$design['achievement_section']}' {$design['data_equalizer_watch']}>
				  <div class='{$design['achievement']}'>
				  <p class='margin-bottom-2 margin-left-1'><strong>$notes</strong></p>
				  $clienticon
				  </div>
				  <p class='{$design['text_center']} margin-bottom-2'>This award has been earned " . htmlsafechars($arr['count']) . " times</p>
				</div></div>";
    }
    $HTMLOUT.= "</div>";
}
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT.= "
		<div class='{$design['reveal']}' id='achievModal' {$design['data_reveal']}>
		  <h2>{$lang['achlst_add_an_ach_lst']}</h2>
			<form method='post' action='achievementlist.php'>
			<table>
			<tr>
			<td>{$lang['achlst_achievname']}</td><td><input type='text' name='achievname' size='40' /></td>
			</tr>
		  <tr>
			<td>{$lang['achlst_achievicon']}</td><td><textarea cols='60' rows='3' name='clienticon'></textarea></td>
			</tr>
			<tr>
			<td>{$lang['achlst_description']}</td><td><textarea cols='60' rows='6' name='notes'></textarea></td>
			</tr>
			<tr>
			<td colspan='2' align='center'><input type='submit' name='okay' value='{$lang['achlst_add_me']}!' class='button' /></td>
			</tr>
			</table>
			</form>
		  <button class='close-button' data-close aria-label='Close modal' type='button'>
			<span aria-hidden='true'>&times;</span>
		  </button>
		</div>";
}
$HTMLOUT.="</div>";
?>