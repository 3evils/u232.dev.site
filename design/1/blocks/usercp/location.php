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
<div class='{$design['large_9']} {$design['columns']}s'>
	<table class='table'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='location' />{$lang['usercp_loc_opt']}</td></tr>";
    //==Time Zone
    $HTMLOUT.= tr($lang['usercp_tz'], $time_select, 1);
    $HTMLOUT.= tr($lang['usercp_checkdst'], "<input type='checkbox' name='checkdst' id='tz-checkdst' onclick='daylight_show()' value='1' $dst_correction />&nbsp;{$lang['usercp_auto_dst']}<br />
    <div id='tz-checkmanual' style='display: none;'><input type='checkbox' name='manualdst' value='1' $dst_check />&nbsp;{$lang['usercp_is_dst']}</div>", 1);
    //==Country
    $HTMLOUT.= tr($lang['usercp_country'], "<select name='country'>\n$countries\n</select>", 1);
    //==Language
    $HTMLOUT.= tr($lang['usercp_language'], "<select name='language'>
    <option value='1'" . ($CURUSER['language'] == '1' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc1']}</option>
    <option value='2'" . ($CURUSER['language'] == '2' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc2']}</option>
    <option value='2'" . ($CURUSER['language'] == '3' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc3']}</option>
    </select>", $CURUSER['language']);
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</table></div>";
 
 //==End
// End Class
// End File