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
$HTMLOUT.= "<div class='{$design['large_9']} {$design['columns']}'>
	<table class='table table-bordered'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='default' />{$lang['usercp_pm_opt']}</td></tr>";
    $HTMLOUT.= tr($lang['usercp_accept_pm'], "<input type='radio' name='acceptpms'" . ($CURUSER["acceptpms"] == "yes" ? " checked='checked'" : "") . " value='yes' />{$lang['usercp_except_blocks']}
    <input type='radio' name='acceptpms'" . ($CURUSER["acceptpms"] == "friends" ? " checked='checked'" : "") . " value='friends' />{$lang['usercp_only_friends']}
    <input type='radio' name='acceptpms'" . ($CURUSER["acceptpms"] == "no" ? " checked='checked'" : "") . " value='no' />{$lang['usercp_only_staff']}", 1);
    $HTMLOUT.= tr($lang['usercp_delete_pms'], "<input type='checkbox' name='deletepms'".($CURUSER["deletepms"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_default_delete']}", 1);
    //$HTMLOUT.= tr($lang['usercp_delete_pms'], "<input type='checkbox' name='deletepms'" . (($CURUSER['opt1'] & user_options::DELETEPMS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_delete']}", 1);
    $HTMLOUT.= tr($lang['usercp_save_pms'], "<input type='checkbox' name='savepms'".($CURUSER["savepms"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_default_save']}", 1);
    //$HTMLOUT.= tr($lang['usercp_save_pms'], "<input type='checkbox' name='savepms'" . (($CURUSER['opt1'] & user_options::SAVEPMS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_save']}", 1);
    $HTMLOUT.= tr($lang['usercp_pm_fopm'], "<input type='radio' name='subscription_pm' ".($CURUSER["subscription_pm"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']." <input type='radio' name='subscription_pm' ".($CURUSER["subscription_pm"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."<br />".$lang['usercp_pm_pm01']."", 1);
    //$HTMLOUT.= tr("Forum Subscribe Pm", "<input type='checkbox' name='subscription_pm'" . (($CURUSER['opt1'] & user_options::SUBSCRIPTION_PM) ? " checked='checked'" : "") . " value='yes' />(When someone posts in a subscribed thread, you will be PMed)", 1);
    $HTMLOUT.= tr($lang['usercp_pm_topm'], "<input type='radio' name='pm_on_delete' ".($CURUSER["pm_on_delete"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']." <input type='radio' name='pm_on_delete' ".($CURUSER["pm_on_delete"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."<br />".$lang['usercp_pm_pm02']."", 1);
    //$HTMLOUT.= tr("Torrent deletion Pm", "<input type='checkbox' name='pm_on_delete'" . (($CURUSER['opt2'] & user_options_2::PM_ON_DELETE) ? " checked='checked'" : "") . " value='yes' />(When any of your uploaded torrents are deleted, you will be PMed)", 1);
    $HTMLOUT.= tr($lang['usercp_pm_copm'], "<input type='radio' name='commentpm' ".($CURUSER["commentpm"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']." <input type='radio' name='commentpm' ".($CURUSER["commentpm"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."<br />".$lang['usercp_pm_pm03']."", 1);
    //$HTMLOUT.= tr("Torrent comment Pm", "<input type='checkbox' name='commentpm'" . (($CURUSER['opt2'] & user_options_2::COMMENTPM) ? " checked='checked'" : "") . " value='yes' />(When any of your uploaded torrents are commented on, you will be PMed)", 1);
    $HTMLOUT.= tr($lang['usercp_pm_force'], "<input type='radio' name='pm_forced' ".($CURUSER["pm_forced"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']." <input type='radio' name='pm_forced' ".($CURUSER["pm_forced"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."<br />".$lang['usercp_pm_pm04']."", 1);
$HTMLOUT.= "<tr><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</table></div>";
	 //==End
// End Class
// End File