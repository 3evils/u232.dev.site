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
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php');
$design = array_merge(load_design());
$lang = array_merge(load_language('global'), load_language('rules'));
$HTMLOUT = '';
$HTMLOUT.= "<div class='{$design['callout']}'>";
$HTMLOUT.= "<div class='{$design['callout']}'>" . $lang['rules_welcome']."</div>";
$HTMLOUT.= "<ul class='{$design['tabs']}' data-responsive-accordion-tabs='tabs medium-accordion small-accordion large-tabs' id='myTab'>";
$count = 0;
$rules = array();
if (($rules = $mc1->get_value('rules__')) === false) {
$q = sql_query("SELECT rules_cat.id, rules_cat.name, rules_cat.shortcut, rules_cat.min_view, rules.type, rules.title, rules.text FROM rules_cat LEFT JOIN rules ON rules.type=rules_cat.id WHERE rules_cat.min_view <=" . sqlesc($CURUSER['class']));
while ($item = mysqli_fetch_assoc($q)) $rules[] = $item;
$mc1->cache_value('rules__', $rules, $INSTALLER09['expires']['rules']);
}
foreach ($rules as $row) {
    if ($count == 0) $HTMLOUT.= tabs_title_active("<a href='#".htmlsafechars($row['shortcut'])."' data-accordion-item aria-selected='true'>".htmlsafechars($row['name'])."</a>",1);
    else
    $HTMLOUT.= tabs_title("<a href='#".htmlsafechars($row['shortcut'])."'>".htmlsafechars($row['name'])."</a>",1);
    $count++;
}
$HTMLOUT.= "</ul>";
$HTMLOUT.= "<div class='{$design['tabs_content']}' {$design['data_tabs_content']}='myTab'>";
$count = 0;
foreach ($rules as $row) {
	if ($count == 0) $HTMLOUT.= "<div class='{$design['tabs_panel']} {$design['is_active']}' id='".htmlsafechars($row['shortcut'])."'>";
	else
    $HTMLOUT.= "<div class='{$design['tabs_panel']}' id='".htmlsafechars($row['shortcut'])."'>";
    $HTMLOUT.= "<h2>".htmlsafechars($row['name'])."</h2>";
    $HTMLOUT.= "<b>".htmlsafechars($row['title'])."</b><br /><br />".htmlspecialchars_decode($row['text'])."";
	$HTMLOUT.= "</div>";
    $count++;
}
$HTMLOUT.= "</div></div>";

/////////////////////// HTML OUTPUT ///////////////////////
echo stdhead('Rules') . $HTMLOUT . stdfoot();
?>
