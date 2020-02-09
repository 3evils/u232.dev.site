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
 //==country by pdq
function countries()
{
    global $mc1, $INSTALLER09;
    if (($ret = $mc1->get_value('countries::arr')) === false) {
        $res = sql_query("SELECT id, name, flagpic FROM countries ORDER BY name ASC") or sqlerr(__FILE__, __LINE__);
        while ($row = mysqli_fetch_assoc($res)) $ret[] = $row;
        $mc1->cache_value('countries::arr', $ret, $INSTALLER09['expires']['user_flag']);
    }
    return $ret;
}
$country = '';
$countries = countries();
foreach ($countries as $cntry) if ($cntry['id'] == $user['country']) {
    $country = "<img src=\"{$INSTALLER09['pic_base_url']}flag/{$cntry['flagpic']}\" alt=\"" . htmlsafechars($cntry['name']) . "\" style='margin-left: 8pt'>";
    break;
}
$perms = '';
$perms.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_NO_IP) ? '&nbsp;&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'smilies/super.gif" alt="'.$lang['userdetails_invincible'].'"  title="'.$lang['userdetails_invincible'].'">' : '') : '');
$stealth = '';
$stealth.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_STEALTH) ? '&nbsp;&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'smilies/ninja.gif" alt="'.$lang['userdetails_stelth'].'"  title="'.$lang['userdetails_stelth'].'">' : '') : ''); 
 
  //==end
// End Class
// End File