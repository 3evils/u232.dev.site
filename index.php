<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                			    |
 |--------------------------------------------------------------------------|
 |   Licence Info: GPL			                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5					    |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.					    |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once INCL_DIR . 'user_functions.php';
require_once INCL_DIR . 'bbcode_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once ROOT_DIR . 'polls.php';
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
dbconn(true);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php'); 

$stdhead = array(
    /** include js **/
    'js' => array(
        //'gallery'
     ),
    /** include css **/
    'css' => array(
        'bbcode',
		'index-portals'
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
     'shout',
	 'jquery.cookie',
	 'portals',
	 //'jquery-ui-i18n.min'
	 /*'jquery.bgiframe-2.1.2',*/ // this is only used for ie6, its now called edge seaching to see what the possiblity that ie6 is even used anymore. The latest version off this file 3.0.1 and that was last updated Oct.2014
	 'jquery-ui.min'
    )
);
$design = extract(load_design());
$lang = array_merge(load_language('global') , load_language('index'));

$HTMLOUT = '';
$HTMLOUT = '<div class="column-portlet" id="col1">';
//==Global blocks by elephant
//==Curuser blocks by pdq
	if (curuser::$blocks['index_page'] & block_index::IE_ALERT && $BLOCKS['ie_user_alert']) {
$HTMLOUT .="<div class='portlet' id='IE_ALERT'>";
	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/ie_user.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::ANNOUNCEMENT && $BLOCKS['announcement_on']) {
$HTMLOUT .="<div class='portlet' id='ANNOUNCEMENT'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/announcement.php');
$HTMLOUT .="</div>";
	}

	if ($CURUSER['class'] >= UC_STAFF && curuser::$blocks['index_page'] & block_index::STAFF_SHOUT && $BLOCKS['staff_shoutbox_on']) {
$HTMLOUT .="<div class='portlet' id='STAFF_SHOUT'>";
   	 require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/staff_shoutbox.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::SHOUTBOX && $BLOCKS['shoutbox_on']) {
$HTMLOUT .="<div class='portlet' id='SHOUTBOX'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/shoutbox.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::NEWS && $BLOCKS['news_on']) {
$HTMLOUT .="<div class='portlet' id='NEWS'>";
 	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/news.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::ADVERTISEMENTS && $BLOCKS['ads_on']) {
$HTMLOUT .="<div class='portlet' id='ADVERTISEMENTS'>";
   	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/advertise.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::FORUMPOSTS && $BLOCKS['forum_posts_on']) {
$HTMLOUT .="<div class='portlet' id='FORUMPOSTS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/forum_posts.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::MOVIEOFWEEK && $BLOCKS['movie_ofthe_week_on']) {
$HTMLOUT .="<div class='portlet' id='MOVIEOFWEEK'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/mow.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::LATEST_TORRENTS && $BLOCKS['latest_torrents_on']) {
$HTMLOUT .="<div class='portlet' id='LATEST_TORRENTS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/latest_torrents.php');
$HTMLOUT .="</div>";
	}

        if (curuser::$blocks['index_page'] & block_index::REQNOFF && $BLOCKS['requests_and_offers_on']) {
$HTMLOUT .="<div class='portlet' id='REQUESTS_AND_OFFERS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/req_n_off.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::LATEST_TORRENTS_SCROLL && $BLOCKS['latest_torrents_scroll_on']) {
$HTMLOUT .="<div class='portlet' id='LATEST_TORRENTS_SCROLL'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/latest_torrents_scroll.php');
$HTMLOUT .="</div>";
	}

        if (curuser::$blocks['index_page'] & block_index::RECOMMENDED && $BLOCKS['recommended_torrents_on']) {
$HTMLOUT .="<div class='portlet' id='RECOMMENDED_TORRENTS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/recommended_torrents.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::STATS && $BLOCKS['stats_on']) {
$HTMLOUT .="<div class='portlet' id='STATS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/stats.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::ACTIVE_USERS && $BLOCKS['active_users_on']) {
$HTMLOUT .="<div class='portlet' id='ACTIVE_USERS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/active_users.php');
$HTMLOUT .="</div>";
	}
	if (curuser::$blocks['index_page'] & block_index::IRC_ACTIVE_USERS && $BLOCKS['active_irc_users_on']) {
$HTMLOUT .="<div class='portlet' id='IRC_ACTIVE_USERS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/active_irc_users.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::LAST_24_ACTIVE_USERS && $BLOCKS['active_24h_users_on']) {
$HTMLOUT .="<div class='portlet' id='LAST_24_ACTIVE_USERS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/active_24h_users.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::BIRTHDAY_ACTIVE_USERS && $BLOCKS['active_birthday_users_on']) {
$HTMLOUT .="<div class='portlet' id='BIRTHDAY_ACTIVE_USERS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/active_birthday_users.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::LATEST_USER && $BLOCKS['latest_user_on']) {
$HTMLOUT .="<div class='portlet' id='LATEST_USER'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/latest_user.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::ACTIVE_POLL && $BLOCKS['active_poll_on']) {
$HTMLOUT .="<div class='portlet' id='ACTIVE_POLL'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/poll.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::XMAS_GIFT && $BLOCKS['xmas_gift_on']) {
$HTMLOUT .="<div class='portlet' id='XMAS_GIFT'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/gift.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::RADIO && $BLOCKS['radio_on']) {
$HTMLOUT .="<div class='portlet' id='RADIO'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/radio.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::TORRENTFREAK && $BLOCKS['torrentfreak_on']) {
$HTMLOUT .="<div class='portlet' id='TORRENTFREAK'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/torrentfreak.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::DISCLAIMER && $BLOCKS['disclaimer_on']) {
$HTMLOUT .="<div class='portlet' id='DISCLAIMER'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/disclaimer.php');
$HTMLOUT .="</div>";
	}

	if (curuser::$blocks['index_page'] & block_index::DONATION_PROGRESS && $BLOCKS['donation_progress_on']) {
$HTMLOUT .="<div class='portlet' id='DONATION_PROGRESS'>";
    	require_once (DESIGN_DIR.''. $CURUSER['design'].'/blocks/index/donations.php');
$HTMLOUT .="</div>";
	}
	$HTMLOUT .='</div>';
echo stdhead('Home', true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
