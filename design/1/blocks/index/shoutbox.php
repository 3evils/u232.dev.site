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
//==Start activeusers - pdq & shoutbox 09
$keys['activeusers'] = 'activeusers';
if (($active_users_cache = $mc1->get_value($keys['activeusers'])) === false) {
    $dt = $_SERVER['REQUEST_TIME'] -  180;
    $activeusers = '';
    $active_users_cache = array();
    $res = sql_query('SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, perms ' . 'FROM users WHERE last_access >= ' . $dt . ' ' . 'AND perms < ' . bt_options::PERMS_STEALTH . ' ORDER BY last_access DESC') or sqlerr(__FILE__, __LINE__);
    $actcount = mysqli_num_rows($res);
    $v = ($actcount != 1 ? 's' : '');
    while ($arr = mysqli_fetch_assoc($res)) {
        if ($activeusers) $activeusers.= "<br>";
        $activeusers.= '<b>' . format_username($arr) . '</b>';
    }
    $active_users_cache['activeusers'] = $activeusers;
    $active_users_cache['actcount'] = $actcount;
    $active_users_cache['au'] = number_format($actcount);
    $last24_cache['v'] = $v;
    $mc1->cache_value($keys['activeusers'], $active_users_cache, $INSTALLER09['expires']['activeusers']);
}
if (!$active_users_cache['activeusers']) $active_users_cache['activeusers'] = $lang['index_active_users_no'];

if ($CURUSER['opt1'] & user_options::SHOW_SHOUT) {
$commandbutton = $refreshbutton = $smilebutton = $custombutton = $staffsmiliebutton = '';
if ($CURUSER['class'] >= UC_STAFF) {
$staffsmiliebutton.= "<a class='tiny button' href=\"javascript:PopStaffSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_ssmilies']}</a>";
}
if (get_smile() != 0) $custombutton.= "
<a class='tiny button' href=\"javascript:PopCustomSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_csmilies']}</a>";
if ($CURUSER['class'] >= UC_STAFF) {
$commandbutton = "<a class='tiny button' href=\"javascript:popUp('shoutbox_commands.php')\">{$lang['index_shoutbox_commands']}</a>\n";
}
$refreshbutton = "<a class='tiny button' href='shoutbox.php' target='shoutbox'>{$lang['index_shoutbox_refresh']}</a>\n";
$smilebutton = "<a class='tiny button' href=\"javascript:PopMoreSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_smilies']}</a>\n";

$HTMLOUT .= "<div class='card'>
	<div class='card-divider portlet-header'>{$lang['index_shoutbox_general']}</div>
  <div class='portlet-content card-section'>";
$HTMLOUT.= "
<div class='large-9 columns'>
<form action='shoutbox.php' method='get' target='shoutbox' name='shbox' onsubmit='mysubmit()'>
<iframe src='{$INSTALLER09['baseurl']}/shoutbox.php' class='shout-table' name='shoutbox'></iframe>
		<div class='input-group'>
			<input type='text' class='input-group-field' name='shbox_text' placeholder='Shout Text'>
			<div class='input-group-button'>
			<input class='button' type='submit' value='{$lang['index_shoutbox_send']}'>
			<input type='hidden' name='sent' value='yes'>
			</div>
		</div>
</div>
<div class='large-3 columns'>
	<label class='text-left'>" . $lang['index_active'] . "&nbsp;&nbsp;<span class='badge success disabled' style='color:#fff'>" . $active_users_cache['actcount'] . "</span></label>
  <div class='callout' style='overflow:auto; height:250px;'><p>" . $active_users_cache['activeusers'] . "</p></div></div>
<div class='large-12 columns'>
<a href=\"javascript:SmileIT(':-)','shbox','shbox_text')\"><i class='em em-laughing'></i></a>
<a href=\"javascript:SmileIT(':smile:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/smile2.gif' alt='Smiling' title='Smiling' /></a>
<a href=\"javascript:SmileIT(':-D','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/grin.gif' alt='Grin' title='Grin' /></a>
<a href=\"javascript:SmileIT(':lol:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/laugh.gif' alt='Laughing' title='Laughing' /></a>
<a href=\"javascript:SmileIT(':w00t:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/w00t.gif' alt='W00t' title='W00t' /></a>
<a href=\"javascript:SmileIT(':blum:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/blum.gif' alt='Rasp' title='Rasp' /></a>
<a href=\"javascript:SmileIT(';-)','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/wink.gif' alt='Wink' title='Wink' /></a>
<a href=\"javascript:SmileIT(':devil:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/devil.gif' alt='Devil' title='Devil' /></a>
<a href=\"javascript:SmileIT(':yawn:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/yawn.gif' alt='Yawn' title='Yawn' /></a>
<a href=\"javascript:SmileIT(':-/','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/confused.gif' alt='Confused' title='Confused' /></a>
<a href=\"javascript:SmileIT(':o)','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/clown.gif' alt='Clown' title='Clown' /></a>
<a href=\"javascript:SmileIT(':innocent:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/innocent.gif' alt='Innocent' title='innocent' /></a>
<a href=\"javascript:SmileIT(':whistle:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/whistle.gif' alt='Whistle' title='Whistle' /></a>
<a href=\"javascript:SmileIT(':unsure:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/unsure.gif' alt='Unsure' title='Unsure' /></a>
<a href=\"javascript:SmileIT(':blush:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/blush.gif' alt='Blush' title='Blush' /></a>
<a href=\"javascript:SmileIT(':hmm:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/hmm.gif' alt='Hmm' title='Hmm' /></a>
<a href=\"javascript:SmileIT(':hmmm:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/hmmm.gif' alt='Hmmm' title='Hmmm' /></a>
<a href=\"javascript:SmileIT(':huh:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/huh.gif' alt='Huh' title='Huh' /></a>
<a href=\"javascript:SmileIT(':look:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/look.gif' alt='Look' title='Look' /></a>
<a href=\"javascript:SmileIT(':rolleyes:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes' /></a>
<a href=\"javascript:SmileIT(':kiss:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/kiss.gif' alt='Kiss' title='Kiss' /></a>
<a href=\"javascript:SmileIT(':blink:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/blink.gif' alt='Blink' title='Blink' /></a>
<a href=\"javascript:SmileIT(':baby:','shbox','shbox_text')\"><img src='{$INSTALLER09['pic_base_url']}smilies/baby.gif' alt='Baby' title='Baby' /></a>";
if ($CURUSER['class'] >= UC_STAFF)
{
$HTMLOUT.= '<a class="tiny button" href="'.$INSTALLER09['baseurl'].'/staffpanel.php?tool=shistory">'.$lang['index_shoutbox_history'].'</a>';
}
$HTMLOUT.= "{$commandbutton}
{$staffsmiliebutton}
{$smilebutton}
{$custombutton}
{$refreshbutton}</div></form>";
$HTMLOUT.= "</div></div>";
}
if (!($CURUSER['opt1'] & user_options::SHOW_SHOUT)) {
   $HTMLOUT.= "<div class='row'><ul class='accordion' data-accordion data-multi-expand='true'>
  <li class='accordion-item is-active' data-accordion-item><b><a href='#' class='accordion-title'>{$lang['index_shoutbox']}</b></a><a class='button' type='button' href='{$INSTALLER09['baseurl']}/shoutbox.php?show_shout=1&amp;show=yes'><div class='accordion-content' data-tab-content>{$lang['index_shoutbox_open']}&nbsp;</a></div></li></ul></div>";
}
//==end 09 shoutbox
//==End
// End Class
// End File
