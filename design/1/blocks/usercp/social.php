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
 $HTMLOUT.= "
<div class='{$design['large_9']} {$design['columns']}'>
	<table class='table'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='social' />{$lang['usercp_soc_ial']}</td></tr>";
    //=== social stuff
    $HTMLOUT.= tr(''.$lang['usercp_soc_goo'].'', '<img src="pic/social_media/google_talk.gif" alt="Google Talk" title="Google Talk" /><input type="text" size="30" name="google_talk"  value="' . htmlsafechars($CURUSER['google_talk']) . '" />', 1);
    $HTMLOUT.= tr(''.$lang['usercp_soc_msn'].'', '<img src="pic/social_media/msn.gif" alt="Msn" title="Msn" /><input type="text" size="30" name="msn"  value="' . htmlsafechars($CURUSER['msn']) . '" />', 1);
    $HTMLOUT.= tr(''.$lang['usercp_soc_aim'].'', ' <img src="pic/social_media/aim.gif" alt="Aim" title="Aim" /><input type="text" size="30" name="aim"  value="' . htmlsafechars($CURUSER['aim']) . '" />', 1);
    $HTMLOUT.= tr(''.$lang['usercp_soc_yahoo'].'', '<img src="pic/social_media/yahoo.gif" alt="Yahoo" title="Yahoo" /><input type="text" size="30" name="yahoo"  value="' . htmlsafechars($CURUSER['yahoo']) . '" />', 1);
    $HTMLOUT.= tr(''.$lang['usercp_soc_icq'].'', '<img src="pic/social_media/icq.gif" alt="Icq" title="Icq" /><input type="text" size="30" name="icq"  value="' . htmlsafechars($CURUSER['icq']) . '" />', 1);
    $HTMLOUT.= tr(''.$lang['usercp_soc_www'].'', '<img src="pic/social_media/www.gif" alt="www" title="www" width="16px" height="16px" /><input type="text" size="30" name="website"  value="' . htmlsafechars($CURUSER['website']) . '" />', 1);
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";

$HTMLOUT.="</table></div>";
//==End
// End Class
// End File