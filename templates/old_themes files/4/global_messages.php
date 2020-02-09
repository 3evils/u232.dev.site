<?php
     /*-----------------------------------------------------------------------\
	|   https://github.com/Bigjoos/ -------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Licence Info: WTFPL  --------------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Copyright (C) 2010 U-232 V5	-------------------------------------------|
	|--------------------------------------------------------------------------|
	|   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon. --|
	|--------------------------------------------------------------------------|
	|   Project Leaders: Mindless, Autotron, whocares, Swizzles.---------------|
	\------------------------------------------------------------------------*/
 //==Template system by Terranova
 //==Template system modified by son
{
	$htmlout .="
    <!-- U-232 Source - Print Global Messages Start -->
    <div class='collapse well' id='collapseAlerts'><div class='sa-gm_taps_left'>";
	$htmlout .="<ul class='sa-gm_taps'  style='margin-top: -23px'><li><b>{$lang['gl_alerts']}</b></li>";
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_REPORTS && $BLOCKS['global_staff_report_on']) {
    require_once (BLOCK_DIR.'global/report.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_UPLOADAPP && $BLOCKS['global_staff_uploadapp_on']) {
    require_once (BLOCK_DIR.'global/uploadapp.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_HAPPYHOUR && $BLOCKS['global_happyhour_on']) {
    require_once (BLOCK_DIR.'global/happyhour.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFF_MESSAGE && $BLOCKS['global_staff_warn_on']) {
    require_once (BLOCK_DIR.'global/staffmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_NEWPM && $BLOCKS['global_message_on']) {
    require_once (BLOCK_DIR.'global/message.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_DEMOTION && $BLOCKS['global_demotion_on']) {
    require_once (BLOCK_DIR.'global/demotion.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH && $BLOCKS['global_freeleech_on']) {
    require_once (BLOCK_DIR.'global/freeleech.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_CRAZYHOUR && $BLOCKS['global_crazyhour_on']) {
    require_once (BLOCK_DIR.'global/crazyhour.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_BUG_MESSAGE && $BLOCKS['global_bug_message_on']) {
    require_once (BLOCK_DIR.'global/bugmessages.php');
    }
   if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION && $BLOCKS['global_freeleech_contribution_on']) {
    require_once (BLOCK_DIR.'global/freeleech_contribution.php');
    }
    $htmlout.= "</ul></div></div>";
}
?>
