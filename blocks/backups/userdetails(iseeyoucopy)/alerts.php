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
//==invincible no iplogging and ban bypass by pdq
$invincible = $mc1->get_value('display_' . $CURUSER['id']);
if ($invincible) $HTMLOUT.= '<div class="col-sm-2 col-md-push-2"><h3>' . htmlsafechars($user['username']) . ' '.$lang['userdetails_is'].' ' . $invincible . ' '.$lang['userdetails_invincible'].'</h3></div>';

//==Stealth mode
$stealth = $mc1->get_value('display_stealth' . $CURUSER['id']);
if ($stealth) $HTMLOUT.= '<div class="row"><div class="col-md-6 col-md-pull-0"><h4>' . htmlsafechars($user['username']) . '&nbsp;' . $stealth . ' '.$lang['userdetails_in_stelth'].'</h4>';

 //==end
// End Class
// End File