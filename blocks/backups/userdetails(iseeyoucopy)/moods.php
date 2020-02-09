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
 //==where is user now
if (!empty($user['where_is'])) $HTMLOUT.= "<tr><td class='rowhead' width='1%'>{$lang['userdetails_location']}</td><td align='left' width='99%'>" . format_urls($user['where_is']) . "</td></tr>";
//==
if ($INSTALLER09['mood_sys_on']) {
$moodname = (isset($mood['name'][$user['mood']]) ? htmlsafechars($mood['name'][$user['mood']]) : $lang['userdetails_neutral']);
$moodpic = (isset($mood['image'][$user['mood']]) ? htmlsafechars($mood['image'][$user['mood']]) : 'noexpression.gif');
$HTMLOUT.= '<tr><td class="rowhead">'.$lang['userdetails_currentmood'].'</td><td align="left"><span class="tool">
       <a href="javascript:;" onclick="PopUp(\'usermood.php\',\''.$lang['userdetails_mood'].'\',530,500,1,1);">
       <img src="' . $INSTALLER09['pic_base_url'] . 'smilies/' . $moodpic . '" alt="' . $moodname . '" border="0">
       <span class="tip">' . htmlsafechars($user['username']) . ' ' . $moodname . ' !</span></a></span></td></tr>'; 
}
//==end
// End Class
// End File
