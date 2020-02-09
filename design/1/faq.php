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
$HTMLOUT = "<div class='callout'>";
$HTMLOUT.= "<div class='callout'>" . $lang['faq_welcome'] ."</div>";
$HTMLOUT.= "<ul class='tabs' data-active-collapse='true' data-tabs id='myTab'>";
$cats = array();
$q = sql_query("SELECT * FROM faq_cat WHERE min_view <=" . sqlesc($CURUSER['class']));
while ($item = mysqli_fetch_assoc($q)) {
    $cats[] = $item;
}
$count = 0;
foreach ($cats as $row) {
    if ($count == 0) $HTMLOUT.= "<li class='tabs-title is-active'><a href='#".htmlsafechars($row['shortcut'])."' aria-selected='true'>".htmlsafechars($row['name'])."</a></li>";
    else
    $HTMLOUT.= "<li class='tabs-title'><a href='#".htmlsafechars($row['shortcut'])."'>".htmlsafechars($row['name'])."</a></li>";
    $count++;
}
$HTMLOUT.= "</ul>";
$HTMLOUT.= "<div class='tabs-content' data-tabs-content='myTab'>";
if (($faqs = $mc1->get_value('faqs__')) === false) {
    $faqs = array();
    $q2 = sql_query("SELECT * FROM faq");
    while ($row = mysqli_fetch_assoc($q2)) $faqs[] = $row;
    $mc1->cache_value('faqs__', $faqs, $INSTALLER09['expires']['faqs']);
}
$count = 0;
foreach ($cats as $row) {
	if ($count == 0) $HTMLOUT.= "<div class='tabs-panel is-active' id='".htmlsafechars($row['shortcut'])."'>";
	else
		$HTMLOUT.= "<div class='tabs-panel' id='".htmlsafechars($row['shortcut'])."'>";
	$HTMLOUT.= " <p><h2>".htmlsafechars($row['name'])."</h2></p>";    
    foreach ($faqs as $item) {
        if($item['type'] == $row['id']){
            $item['text'] = str_replace(array(
                "SITE_NAME",
                "SITE_PIC_URL",
                "BASE_URL",
                "  "
                ) , array(
                "{$INSTALLER09['site_name']}",
                "{$INSTALLER09['pic_base_url']}",
                "{$INSTALLER09['baseurl']}",
                "&nbsp; "
                ) , $item['text']); 
        
                $HTMLOUT.= "<b>".htmlspecialchars_decode($item['title'])."</b>
            ".htmlspecialchars_decode($item['text'])."";
        }
    }
    $count++;
	$HTMLOUT.= "</div>";
}
$HTMLOUT.= "</div></div>";
 ?>