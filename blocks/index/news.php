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
//==Installer09 MemCached News
$adminbutton = '';
if ($CURUSER['class'] >= UC_STAFF) {
    $adminbutton = "<a class='tiny button float-right' href='staffpanel.php?tool=news&amp;mode=news'>{$lang['index_news_title']}</a>";
}
$HTMLOUT.= "<div class='card'>
	<div class='card-divider portlet-header'><b>{$lang['news_title']}</b></div>
<div class='portlet-content card-section'>";
$prefix = 'min5l3ss';
$news = $mc1->get_value('latest_news_');
if ($news === false) {
    $res = sql_query("SELECT " . $prefix . ".id AS nid, " . $prefix . ".userid, " . $prefix . ".added, " . $prefix . ".title, " . $prefix . ".body, " . $prefix . ".sticky, " . $prefix . ".anonymous, u.username, u.id, u.class, u.warned, u.chatpost, u.pirate, u.king, u.leechwarn, u.enabled, u.donor FROM news AS " . $prefix . " LEFT JOIN users AS u ON u.id = " . $prefix . ".userid WHERE " . $prefix . ".added + ( 3600 *24 *45 ) > " . TIME_NOW . " ORDER BY sticky, " . $prefix . ".added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
    while ($array = mysqli_fetch_assoc($res)) $news[] = $array;
    $mc1->cache_value('latest_news_', $news, $INSTALLER09['expires']['latest_news']);
}
$news_flag = 0;
if ($news) {
    foreach ($news as $array) {
        $button = '';
        if ($CURUSER['class'] >= UC_STAFF) {
            $hash = md5('the@@saltto66??' . $array['nid'] . 'add' . '@##mu55y==');
            $button = "<p class='text-right'>
    <a href='staffpanel.php?tool=news&amp;mode=edit&amp;newsid=" . (int)$array['nid'] . "'>
    <i class='icon-edit' title='{$lang['index_news_ed']}' ></i></a>
    <a href='staffpanel.php?tool=news&amp;mode=delete&amp;newsid=" . (int)$array['nid'] . "&amp;h={$hash}'>
    <i class='icon-remove' title='{$lang['index_news_del']}' ></i></a>
    </p>";
        }
        $HTMLOUT.= "";
        if ($news_flag < 2) {
            $HTMLOUT.= "<div class='callout'>
  <h3>{$lang['index_news_txt']}" . htmlsafechars($array['title']) . "</h3>";
$HTMLOUT.= "<blockquote><div id=\"ka" . (int)$array['nid'] . "\" style=\"display:" . ($array['sticky'] == "yes" ? "" : "none") . ";\"> " . format_comment($array['body'], 0) . "</div></blockquote>";
$HTMLOUT.= "<p class='text-right'>{$lang['index_news_added']}" . "<b>" . (($array["anonymous"] == "yes" && $CURUSER['class'] < UC_STAFF && $array['userid'] != $CURUSER['id']) ? "<i>{$lang['index_news_anon']}</i>" : format_username($array)) . "</b>" . get_date($array['added'], 'DATE') . "{$lang['index_news_txt']}" . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "{$adminbutton}" : "") . "</p></div>";
            $news_flag = ($news_flag + 1);
        } else {
            $HTMLOUT.= "<div class='callout'>
			<h3>{$lang['index_news_txt']}" . htmlsafechars($array['title']) . "</h3>";
		$HTMLOUT.= "<blockquote><div id=\"ka" . (int)$array['nid'] . "\" style=\"display:" . ($array['sticky'] == "yes" ? "" : "none") . ";\"> " . format_comment($array['body'], 0) . "</div></blockquote>";
		$HTMLOUT.= "<hr> {$lang['index_news_added']} <b>" . (($array["anonymous"] == "yes" && $CURUSER['class'] < UC_STAFF && $array['userid'] != $CURUSER['id']) ? "<i>{$lang['index_news_anon']}</i>" : format_username($array)) . "</b>" . get_date($array['added'], 'DATE') ."{$button}</div>";
        }
    }
}
if (empty($news)) 
	$HTMLOUT.= "{$lang['index_news_not']}";
$HTMLOUT.= "</div></div>";
//==End
// End Class
// End File
