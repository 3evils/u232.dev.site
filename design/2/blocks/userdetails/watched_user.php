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
    //=== watched user stuff
    $the_flip_box = '[ <a name="watched_user"></a><a class="altlink" href="#watched_user" onclick="javascript:flipBox(\'3\')" title="'.$lang['userdetails_flip1'].'">' . ($user['watched_user'] > 0 ? ''.$lang['userdetails_flip2'].' ' : ''.$lang['userdetails_flip3'].' ') . '<img onclick="javascript:flipBox(\'3\')" src="pic/panel_on.gif" name="b_3" style="vertical-align:middle;"   width="8" height="8" alt="'.$lang['userdetails_flip1'].'" title="'.$lang['userdetails_flip1'].'"></a> ]';
    $HTMLOUT.= '<tr><td class="rowhead">'.$lang['userdetails_watched'].'</td>
                            <td align="left">' . ($user['watched_user'] > 0 ? ''.$lang['userdetails_watched_since'].'  ' . get_date($user['watched_user'], '') . ' ' : ' '.$lang['userdetails_not_watched'].' ') . $the_flip_box . '
                            <div align="left" id="box_3" style="display:none">
                            <form method="post" action="member_input.php" name="notes_for_staff">
                            <input name="id" type="hidden" value="' . $id . '">
                            <input type="hidden" value="watched_user" name="action">
                            '.$lang['userdetails_add_watch'].'                  
                            <input type="radio" value="yes" name="add_to_watched_users"' . ($user['watched_user'] > 0 ? ' checked="checked"' : '') . '> '.$lang['userdetails_yes1'].'
                            <input type="radio" value="no" name="add_to_watched_users"' . ($user['watched_user'] == 0 ? ' checked="checked"' : '') . '> '.$lang['userdetails_no1'].' <br>
                            <span id="desc_text" style="color:red;font-size: xx-small;">* '.$lang['userdetails_watch_change1'].'<br>
                            '.$lang['userdetails_watch_change2'].'</span><br>
                            <textarea id="watched_reason" cols="50" rows="6" name="watched_reason">' . htmlsafechars($user['watched_user_reason']) . '</textarea><br>
                            <input id="watched_user_button" type="submit" value="'.$lang['userdetails_submit'].'" class="btn" name="watched_user_button">
                            </form></div> </td></tr>';
//==End
// End Class
// End File