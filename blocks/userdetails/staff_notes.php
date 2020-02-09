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
 //=== staff Notes
$the_flip_box_4 = '[ <a name="staff_notes"></a><a class="altlink" href="#staff_notes" onclick="javascript:flipBox(\'4\')" name="b_4" title="'.$lang['userdetails_open_staff'].'">view <img onclick="javascript:flipBox(\'4\')" src="pic/panel_on.gif" name="b_4" style="vertical-align:middle;" width="8" height="8" alt="'.$lang['userdetails_open_staff'].'" title="'.$lang['userdetails_open_staff'].'"></a> ]';
    $HTMLOUT.= '<tr><td class="rowhead">'.$lang['userdetails_staffnotes'].'</td><td align="left">           
                            <a class="altlink" href="#staff_notes" onclick="javascript:flipBox(\'6\')" name="b_6" title="'.$lang['userdetails_aev_staffnote'].'">' . ($user['staff_notes'] !== '' ? ''.$lang['userdetails_vae'].' ' : ''.$lang['userdetails_add'].' ') . '<img onclick="javascript:flipBox(\'6\')" src="pic/panel_on.gif" name="b_6" style="vertical-align:middle;" width="8" height="8" alt="'.$lang['userdetails_aev_staffnote'].'" title="'.$lang['userdetails_aev_staffnote'].'"></a>
                            <div align="left" id="box_6" style="display:none">
                            <form method="post" action="member_input.php" name="notes_for_staff">
                            <input name="id" type="hidden" value="' . (int)$user['id'] . '">
                            <input type="hidden" value="staff_notes" name="action" id="action">
                            <textarea id="new_staff_note" cols="50" rows="6" name="new_staff_note">' . htmlsafechars($user['staff_notes']) . '</textarea>
                            <br><input id="staff_notes_button" type="submit" value="'.$lang['userdetails_submit'].'" class="btn" name="staff_notes_button"/>
                            </form>
                            </div> </td></tr>';
//==End
// End Class
// End File