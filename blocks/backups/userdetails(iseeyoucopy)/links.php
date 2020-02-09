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
 //== links to make invincible method 1(PERMS_NO_IP/ no log ip) and 2(PERMS_BYPASS_BAN/cannot be banned)
 $links_invincible = '';
 $links_invincible.= ($CURUSER['class'] === UC_MAX ? (($user['perms'] & bt_options::PERMS_NO_IP) ? ' [<a title=' . "\n" . '"'.$lang['userdetails_invincible_def1'].' ' . "\n" . ''.$lang['userdetails_invincible_def2'].'" href="userdetails.php?id=' . $id . '&amp;invincible=no">' . "\n" . ''.$lang['userdetails_invincible_remove'].'</a>]' . (($user['perms'] & bt_options::PERMS_BYPASS_BAN) ? ' - ' . "\n" . ' [<a title="'.$lang['userdetails_invincible_def3'].'' . "\n" . ' '.$lang['userdetails_invincible_def4'].'" href="userdetails.php?id=' . $id . '&amp;' . "\n" . 'invincible=remove_bypass">'.$lang['userdetails_remove_bypass'].'</a>]' : ' - [<a title="'.$lang['userdetails_invincible_def5'].' ' . "\n" . $lang['userdetails_invincible_def6'] . "\n" . ' '.$lang['userdetails_invincible_def7'].' ' . "\n" . ''.$lang['userdetails_invincible_def8'].'" href="userdetails.php?id=' . $id . '&amp;invincible=yes">' . "\n" . ''.$lang['userdetails_add_bypass'].'</a>]') : '[<a title="'.$lang['userdetails_invincible_def9'].'' . "\n" . ' '.$lang['userdetails_invincible_def0'].'" ' . "\n" . 'href="userdetails.php?id=' . $id . '&amp;invincible=yes">'.$lang['userdetails_make_invincible'].'</a>]') : '')

 $links_stealth = '';
 $links_stealth.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_STEALTH) ? '[<a title=' . "" . '"'.$lang['userdetails_stelth_def1'].' ' . "" . ' '.$lang['userdetails_stelth_def2'].'" href="userdetails.php?id=' . $id . '&amp;stealth=no">' . "" . ''.$lang['userdetails_stelth_disable'].'</a>]' : '[<a title="'.$lang['userdetails_stelth_def1'].'' . "
               " . ' '.$lang['userdetails_stelth_def2'].'" ' . "" . 'href="userdetails.php?id=' . $id . '&amp;stealth=yes">'.$lang['userdetails_stelth_enable'].'</a>]') : '')
 //==end
// End Class
// End File