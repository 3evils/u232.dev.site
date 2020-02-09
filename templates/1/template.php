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
 //==Template system by Terranova
function stdhead($title = "", $msgalert = true, $stdhead = false)
{
    global $CURUSER, $INSTALLER09, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $mc1, $BLOCKS, $CURBLOCK, $mood, $blocks;
    if (!$INSTALLER09['site_online']) die("Site is down for maintenance, please check back again later... thanks<br />");
    if ($title == "") $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '');
    else $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '') . " :: " . htmlsafechars($title);
    if ($CURUSER) {
        $INSTALLER09['stylesheet'] = isset($CURUSER['stylesheet']) ? "{$CURUSER['stylesheet']}.css" : $INSTALLER09['stylesheet'];
        $INSTALLER09['categorie_icon'] = isset($CURUSER['categorie_icon']) ? "{$CURUSER['categorie_icon']}" : $INSTALLER09['categorie_icon'];
        $INSTALLER09['language'] = isset($CURUSER['language']) ? "{$CURUSER['language']}" : $INSTALLER09['language'];
    }
    $salty = md5("Th15T3xtis5add3dto66uddy6he@water..." . $CURUSER['username'] . "");
    /** ZZZZZZZZZZZZZZZZZZZZZZZZZZip it! */

if (!isset($_NO_COMPRESS)) if (!ob_start('ob_gzhandler')) ob_start();
    $htmlout = '';
    //== Include js files needed only for the page being used by pdq
    $js_incl = '';
    $js_incl.= '<!-- javascript goes here or in footer -->';
    if (!empty($stdhead['js'])) {
        foreach ($stdhead['js'] as $JS) $js_incl.= "<script type='text/javascript' src='{$INSTALLER09['baseurl']}/scripts/" . $JS . ".js'></script>";
    }

    //== Include css files needed only for the page being used by pdq
    $stylez = ($CURUSER ? "{$CURUSER['stylesheet']}" : "{$INSTALLER09['stylesheet']}");
    $css_incl = '';
    $css_incl.= '<!-- css goes in header -->';
    if (!empty($stdhead['css'])) {
        foreach ($stdhead['css'] as $CSS) $css_incl.= "<link type='text/css' rel='stylesheet' href='{$INSTALLER09['baseurl']}/templates/{$stylez}/css/" . $CSS . ".css' />";
    }
$htmlout .='
<!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <!-- ####################################################### -->
        <!-- #   This website is powered by U-232 V5	           # -->
        <!-- #   Download and support at:                          # -->
        <!-- #     https://forum-u-232.servebeer.com               # -->
        <!-- #   Template Modded by U-232 Dev Team                 # -->
        <!-- ####################################################### -->
  <head>
    <!--<meta charset="'.charset().'" />-->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>'.$title.'</title>
	<!-- favicon  -->
	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- Template CSS-->
	<link rel="stylesheet" href="templates/' . $stylez . '/css/app.css" type="text/css" />
	<link rel="stylesheet" href="templates/' . $stylez . '/1.css" />
	<!-- Global CSS-->
	<link rel="stylesheet" href="css/global_media.css" type="text/css" />
	<link rel="stylesheet" href="/css/fontawesome-all.min.css" type="text/css" />	
        <link rel="alternate" type="application/rss+xml" title="Latest Torrents" href="/rss.php?torrent_pass='.$CURUSER["torrent_pass"].'" />
	    <!-- global javascript-->
	<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
	<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
	<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
	<!--[if gt IE 8]> <![endif]-->
	<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=9"><![endif]-->
    <!-- <script src="scripts/html5shiv.js"  async></script>  -->
	<script src="templates/1/js/vendor/jquery.js"></script>
    <script src="scripts/respond.min.js"  async></script> <!-- used for IE8 and below-->
    <!-- <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>  -->  ';
	$htmlout .= "{$js_incl}{$css_incl}
        </head>
<body>";
if ($CURUSER) {
	$htmlout .="<div data-sticky-container>
		<div data-sticky data-options='marginTop:0;'>
			<div class='title-bar' data-top-anchor='1'>
				<div class='title-bar-left'>
					<button class='menu-icon' type='button' data-open='offCanvasLeftNav'></button>
					<a class='menu-text' href='" . $INSTALLER09['baseurl'] . "/index.php'>{$INSTALLER09['site_name']}</a>
				</div>
				<div class='title-bar-center'>
				<iframe src='{$INSTALLER09['baseurl']}/auto_shout_scroll.php' class='menu-text' width='100%' height='20px' frameborder='0' name='auto_shoutbox' marginwidth='0' marginheight='0'></iframe>
				</div>
				<div class='title-bar-right'>
				<p class='small label success'>Test links</p>
								<a class='small label' href='" . $INSTALLER09['baseurl'] . "/tv_guide.php'>Tv Guide</a>
				<a class='small label' href='" . $INSTALLER09['baseurl'] . "/tv_guide_new.php'>Tv Guide 2</a>
				<a class='small label' href='" . $INSTALLER09['baseurl'] . "/new_movie_tmdb.php?action=upcoming'>New Movies</a>
					<button class='menu-icon input-group-button' type='button' data-open='offCanvasRightNav'></button>
				</div>
			</div>
		</div>
	</div>";
	$htmlout .= "<div class='off-canvas position-left' id='offCanvasLeftNav' data-off-canvas>
		<ul class='menu vertical'>
			<a class='button small' href='#'>{$lang['gl_general']}</a>
			<li><a href='" . $INSTALLER09['baseurl'] . "/topten.php'>{$lang['gl_stats']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/faq.php'>{$lang['gl_faq']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/rules.php'>{$lang['gl_rules']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/chat.php'>{$lang['gl_irc']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/staff.php'>{$lang['gl_staff']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/wiki.php'>{$lang['gl_wiki']}</a></li>
			<li><a href='#' onclick='radio();'>{$lang['gl_radio']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/rsstfreak.php'>{$lang['gl_tfreak']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/sitepot.php'>{$lang['gl_sitepot']}</a></li>
			<a class='success button' href='#'>{$lang['gl_games']}</a>	
			" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "<li><a href='" . $INSTALLER09['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></li>" : "") . "
			" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "<li><a href='" . $INSTALLER09['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></li>" : "") . "
			<a class='button small' href='#'>{$lang['gl_torrent']}</a>
			<li><a href='" . $INSTALLER09['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/requests.php'>{$lang['gl_requests']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/offers.php'>{$lang['gl_offers']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/needseed.php?needed=seeders'>{$lang['gl_nseeds']}</a></li>" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "
			<li><a href='" . $INSTALLER09['baseurl'] . "/uploadapp.php'>{$lang['gl_uapp']}</a></li> " : "
			<li><a href='" . $INSTALLER09['baseurl'] . "/upload.php'>{$lang['gl_upload']}</a></li>") . "" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "" : "
			<li><a href='" . $INSTALLER09['baseurl'] . "/multiupload.php'>{$lang['gl_mupload']}</a></li>") . "
			<li><a href='" . $INSTALLER09['baseurl'] . "/bookmarks.php'>{$lang['gl_bookmarks']}</a></li>
			<a class='success button' href='" . $INSTALLER09['baseurl'] . "/donate.php'>{$lang['gl_donate']}</a>
			<a class='button small' href='" . $INSTALLER09['baseurl'] . "/forums.php'>{$lang['gl_forums']}</a>
			<a class='button small'  href='#'>Staff Tools</a>	
			<li>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=add'>{$lang['gl_breport']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=bugs'>{$lang['gl_brespond']}</a>") . "</li>
			<li> " . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/contactstaff.php'>{$lang['gl_cstaff']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/staffbox.php'>{$lang['gl_smessages']}</a>") . "</li>
			" . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a href='" . $INSTALLER09['baseurl'] . "/staffpanel.php'>{$lang['gl_admin']}</a></li>" : "") . "  
			<a class='button primary'  href='#'>Pers Tools</a>
			<li><a href='#' onclick='themes();'>{$lang['gl_theme']}</a></li>
			<li><a href='#' onclick='design();'>Change design framework</a></li>
			<li><a href='#' onclick='language_select();'>{$lang['gl_language_select']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/pm_system.php'>{$lang['gl_pms']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/usercp.php?action=default'>{$lang['gl_usercp']}</a></li>
			<!-- <li><a href='" . $INSTALLER09['baseurl'] . "/friends.php'>{$lang['gl_friends']}</a></li> -->
			<li class='divider'></li>
			<li>" . (isset($CURUSER) && $CURUSER['got_blocks'] == 'yes' ? "{$lang['gl_userblocks']}<a href='./user_blocks.php'>My Blocks</a>" : "") . "</li>
			<li>" . (isset($CURUSER) && $CURUSER['got_moods'] == 'yes' ? "<a href='./user_unlocks.php'>My Unlocks</a>" : "") . "</li>
			<li><a class='menu-text button small alert' href='" . $INSTALLER09['baseurl'] . "/logout.php?hash_please={$salty}'>{$lang['gl_logout']}</a></li>
		</ul>
	</div>";
	$htmlout .= "<div class='off-canvas position-right' id='offCanvasRightNav' data-off-canvas data-transition='push'>";
		if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFFTOOLS && $BLOCKS['global_staff_tools_on'] && $CURUSER['class'] >= UC_STAFF) {
			require_once (BLOCK_DIR.'global/staff_tools.php');
		}
	$htmlout .="</div>";
	$htmlout .= "<div class='off-canvas-content' data-off-canvas-content>
<div class='expanded row'>
<div class='large-6 columns'>
	<h1 class='subheader'>".TBVERSION."</h1>
	<h4 class='subheader'>Another look, same source</h4>
</div>
<div class='large-4 columns'>".StatusBar()."</div>
</div>";
}
if ($CURUSER) {
	$htmlout .="
    <!-- U-232 Source - Print Global Messages Start -->
    <div class='row'>";
		$htmlout .="<label class='text-left'><b>{$lang['gl_alerts']}</b></label>";
		$htmlout .='<div class="small button-group">';
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
			    if (OCELOT_TRACKER == true) {
        if (($ocelotcheck = $mc1->get_value('ocelotcheck')) === false) {
            require_once CLASS_DIR . 'tracker.class.php';
            $ocelotcheck = Tracker::info();
            $mc1->cache_value('ocelotcheck', $ocelotcheck, 60 * 10);
        }
        if ($ocelotcheck == false) {
            $htmlout .= "
            <button class='button small success' href='index.php#'>OCELOT IS NOT RUNNING</button>";
        }
    }
		$htmlout.= "<button class='button small float-right' href='#' data-toggle='offCanvasRightSplit2'>Statusbar</button>
	</div>";
    }
    if ($CURUSER) {
    $htmlout.= '';}
    return $htmlout;
   }

 function stdfoot($stdfoot = false)
{
    global $CURUSER, $INSTALLER09, $start, $query_stat, $mc1, $querytime, $lang, $rc;
    $debug = (SQL_DEBUG && in_array($CURUSER['id'], $INSTALLER09['allowed_staff']['id']) ? 1 : 0);
    $cachetime = ($mc1->Time / 1000);
    $seconds = microtime(true) - $start;
    $r_seconds = round($seconds, 5);
    //$phptime = $seconds - $cachetime;
    $phptime = $seconds - $querytime - $cachetime;
    $queries = count($query_stat); // sql query count by pdq
    $percentphp = number_format(($phptime / $seconds) * 100, 2);
    //$percentsql  = number_format(($querytime / $seconds) * 100, 2);
    $percentmc = number_format(($cachetime / $seconds) * 100, 2);
    define('REQUIRED_PHP_VER', 7.0);
    $MemStat = (PHP_VERSION_ID < REQUIRED_PHP_VER ? $mc1->getStats() : $mc1->getStats()["127.0.0.1:11211"]);
    if (($MemStats = $mc1->get_value('mc_hits')) === false) {
        $MemStats =  $MemStat;
        if ($MemStats['cmd_get'] != 0) {
            $MemStats['Hits'] = number_format(($MemStats['get_hits'] / $MemStats['cmd_get']) * 100, 3);
        } else {
            $MemStats['Hits'] = 0;
        }
        $mc1->cache_value('mc_hits', $MemStats, 10);
    }
    // load averages - pdq
    if ($debug) {
        if (($uptime = $mc1->get_value('uptime')) === false) {
            $uptime = `uptime`;
            $mc1->cache_value('uptime', $uptime, 25);
        }
        preg_match('/load average: (.*)$/i', $uptime, $load);
    }

    //== end class
    $header = '';
    $header = '' . $lang['gl_stdfoot_querys_mstat'] . ' ' . mksize(memory_get_peak_usage()) . ' ' . $lang['gl_stdfoot_querys_mstat1'] . ' ' . round($phptime, 2) . 's | ' . round($percentmc, 2) . '' . $lang['gl_stdfoot_querys_mstat2'] . '' . number_format($cachetime, 5) . 's ' . $lang['gl_stdfoot_querys_mstat3'] . '' . $MemStats['Hits'] . '' . $lang['gl_stdfoot_querys_mstat4'] . '' . (100 - $MemStats['Hits']) . '' . $lang['gl_stdfoot_querys_mstat5'] . '' . number_format($MemStats['curr_items']);
    $htmlfoot = '';
    //== query stats
    $htmlfoot.= '';
    if (!empty($stdfoot['js'])) {
        $htmlfoot.= '<!-- javascript goes here in footer -->';
        foreach ($stdfoot['js'] as $JS) $htmlfoot.= '
		<script src="' . $INSTALLER09['baseurl'] . '/scripts/' . $JS . '.js"></script>';
    }
    $querytime = 0;
    if ($CURUSER && $query_stat && $debug) {
        $htmlfoot.= "
<ul class='accordion' data-accordion data-allow-all-closed='true'>
  <li class='accordion-item' data-accordion-item>
  <a href='#' class='accordion-title'>{$lang['gl_stdfoot_querys']}</a>
	<div class='accordion-content' data-tab-content>
		<div class='table-scroll'>
					<table>
						<thead>
							<tr>
								<th>{$lang['gl_stdfoot_id']}</th>
								<th>{$lang['gl_stdfoot_qt']}</th>
								<th>{$lang['gl_stdfoot_qs']}</th>
							</tr>
						</thead>";
        foreach ($query_stat as $key => $value) {
            $querytime+= $value['seconds']; // query execution time
             $htmlfoot.= "
						<tbody>
							<tr>
								<td>" . ($key + 1) . "</td>
								<td>" . ($value['seconds'] > 0.01 ? "
								<span title='{$lang['gl_stdfoot_ysoq']}'>" . $value['seconds'] . "</span>" : "
								<span title='{$lang['gl_stdfoot_qg']}'>" . $value['seconds'] . "</span>") . "
								</td>
								<td>" . htmlsafechars($value['query']) . "<br /></td>
							</tr>
						</tbody>";
        }
        $htmlfoot.= '</table></div></div></li></ul>';
    }
  if ($CURUSER) {
        /** just in case **/
        $htmlfoot.= "
		<div class='table-scroll'><div class='large-9 columns'>
					<table class='table table-striped text-left'>
						<thead>
							<tr><th class='text-center'>Server Info</th></tr>
						</thead>
						<tbody>
							<tr><td>" . $INSTALLER09['site_name'] . " {$lang['gl_stdfoot_querys_page']}" . $r_seconds . " {$lang['gl_stdfoot_querys_seconds']}<br />" . "</td></tr>
							<tr><td>{$lang['gl_stdfoot_querys_server']}" . $queries . " {$lang['gl_stdfoot_querys_time']} " . ($queries != 1 ? "{$lang['gl_stdfoot_querys_times']}" : "") . "</td></tr>
							<tr><td>" . ($debug ? " " . $header . "</td></tr>
							<tr><td>{$lang['gl_stdfoot_uptime']} " . $uptime . "" : " ") . "</td></tr>
						</tbody>
					</table>
				</div>

		
				<div>
					<table class='table table-striped text-left'>
						<thead>
							<tr><th class='text-center'>Source Info</th></tr>
						</thead>
						<tbody>
							<tr><td>{$lang['gl_stdfoot_powered']}" . TBVERSION . "<br /></td></tr>
							<tr><td>{$lang['gl_stdfoot_using']}{$lang['gl_stdfoot_using1']}<br /></td></tr>
							<tr><td>{$lang['gl_stdfoot_support']}<a href='http://forum-u-232.servebeer.com/index.php'>{$lang['gl_stdfoot_here']}</a><br /></td></tr>
							<tr><td>" . ($debug ? "<a title='{$lang['gl_stdfoot_sview']}' rel='external' href='/staffpanel.php?tool=system_view'>{$lang['gl_stdfoot_sview']}</a> | " . "<a rel='external' title='OPCache' href='/staffpanel.php?tool=op'>{$lang['gl_stdfoot_opc']}</a> | " . "<a rel='external' title='Memcache' href='/staffpanel.php?tool=memcache'>{$lang['gl_stdfoot_memcache']}</a>" : "") . "";$htmlfoot.= "</td></tr>";
						$htmlfoot.= "</tbody>
					</table>
				</div>
			</div>";
    }
    $htmlfoot.='</div></div><!--  End main outer container -->
        <!-- Ends Footer -->
		<script src="templates/1/js/vendor/foundation.min.js"></script>
		<script src="templates/1/js/app.js"></script>
		<script src="templates/1/js/vendor/what-input.js"></script>
        </body></html>';
    return $htmlfoot;
}
function stdmsg($heading, $text)
{
$htmlout = "<div class='row callout small-6 medium-8 large-12 columns'>";
if ($heading) $htmlout.= "<div class='card-divider'>$heading</div>";
$htmlout.= "<div class='card-section'>{$text}</div></div>";
return $htmlout;
}
function StatusBar()
{
    global $CURUSER, $INSTALLER09, $lang, $rep_is_on, $mc1, $msgalert;
    if (!$CURUSER) return "";
    $upped = mksize($CURUSER['uploaded']);
    $downed = mksize($CURUSER['downloaded']);
    $connectable = "";
    if ($CURUSER['class'] < UC_VIP && $INSTALLER09['max_slots']) {
    $ratioq = (($CURUSER['downloaded'] > 0) ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : 1);
if ($ratioq < 0.95) {
	switch (true) {
		case ($ratioq < 0.5):
		$max = 2;
		break;
		case ($ratioq < 0.65):
		$max = 3;
		break;
		case ($ratioq < 0.8):
		$max = 5;
		break;
		case ($ratioq < 0.95):
		$max = 10;
		break;
		default:
	   $max = 10;
	}
 }
 else {
 switch ($CURUSER['class']) {
		case UC_USER:
		$max = 20;
		break;
		case UC_POWER_USER:
		$max = 30;
		break;
		default:
	   $max = 99;
	}	
 }   
}
else
$max = 999;
    //==Memcache unread pms
    $PMCount = 0;
    if (($unread1 = $mc1->get_value('inbox_new_sb_' . $CURUSER['id'])) === false) {
        $res1 = sql_query("SELECT COUNT(id) FROM messages WHERE receiver=" . sqlesc($CURUSER['id']) . " AND unread = 'yes' AND location = '1'") or sqlerr(__LINE__, __FILE__);
        list($PMCount) = mysqli_fetch_row($res1);
        $PMCount = (int)$PMCount;
        $unread1 = $mc1->cache_value('inbox_new_sb_' . $CURUSER['id'], $PMCount, $INSTALLER09['expires']['unread']);
    }
    $inbox = ($unread1 == 1 ? "$unread1&nbsp;{$lang['gl_msg_singular']}" : "$unread1&nbsp;{$lang['gl_msg_plural']}");
    //==Memcache peers
    if (OCELOT_TRACKER == true) {
    if (($MyPeersOcelotCache = $mc1->get_value('MyPeers_Ocelot_'.$CURUSER['id'])) === false) {
        $seed['yes'] = $seed['no'] = 0;
        $seed['conn'] = 3;
        $r = sql_query("SELECT COUNT(uid) AS `count`, `left`, `active`, `connectable` FROM `xbt_files_users` WHERE uid= " . sqlesc($CURUSER['id']) . " AND `active` = 1") or sqlerr(__LINE__, __FILE__);
        while ($a = mysqli_fetch_assoc($r)) {
            $key = $a['left'] == 0 ? 'yes' : 'no';
            $seed[$key] = number_format(0 + $a['count']);
            $seed['conn'] = $a['connectable'] == 0 ? 1 : 2;
        }
        $mc1->cache_value('MyPeers_Ocelot_'.$CURUSER['id'], $seed, $INSTALLER09['expires']['MyPeers_Ocelot_']);
        unset($r, $a);
    } else {
        $seed = $MyPeersOcelotCache;
    }
    } else {
    if (($MyPeersCache = $mc1->get_value('MyPeers_' . $CURUSER['id'])) === false) {
        $seed['yes'] = $seed['no'] = 0;
        $seed['conn'] = 3;
        $r = sql_query("SELECT COUNT(id) AS count, seeder, connectable FROM peers WHERE userid=" . sqlesc($CURUSER['id']) . " GROUP BY seeder");
        while ($a = mysqli_fetch_assoc($r)) {
            $key = $a['seeder'] == 'yes' ? 'yes' : 'no';
            $seed[$key] = number_format(0 + $a['count']);
            $seed['conn'] = $a['connectable'] == 'no' ? 1 : 2;
        }
        $mc1->cache_value('MyPeers_' . $CURUSER['id'], $seed, $INSTALLER09['expires']['MyPeers_']);
        unset($r, $a);
    } else {
        $seed = $MyPeersCache;
    }
   }
     // for display connectable  1 / 2 / 3
    if (!empty($seed['conn'])) {
        switch ($seed['conn']) {
        case 1:
            $connectable = "<img src='{$INSTALLER09['pic_base_url']}notcon.png' alt='Not Connectable' title='Not Connectable' />";
            break;
        case 2:
            $connectable = "<img src='{$INSTALLER09['pic_base_url']}yescon.png' alt='Connectable' title='Connectable' />";
            break;
        default:
            $connectable = "N/A";
        }
    } else $connectable = 'N/A';

    if (($Achievement_Points = $mc1->get_value('user_achievement_points_' . $CURUSER['id'])) === false) {
        $Sql = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
        $Achievement_Points = mysqli_fetch_assoc($Sql);
        $Achievement_Points['id'] = (int)$Achievement_Points['id'];
        $Achievement_Points['achpoints'] = (int)$Achievement_Points['achpoints'];
        $Achievement_Points['spentpoints'] = (int)$Achievement_Points['spentpoints'];
        $mc1->cache_value('user_achievement_points_' . $CURUSER['id'], $Achievement_Points, 0);
    }
    //$hitnruns = ($CURUSER['hit_and_run_total'] > 0 ? $CURUSER['hit_and_run_total'] : '0');
    //{$lang['gl_hnr']}: <a href='".$INSTALLER09['baseurl']."/hnr.php?id=".$CURUSER['id']."'>{$hitnruns}</a>&nbsp;
    $member_reputation = get_reputation($CURUSER);
    $usrclass = $StatusBar = "";
    if ($CURUSER['override_class'] != 255) $usrclass = "&nbsp;<b>[" . get_user_class_name($CURUSER['class']) . "]</b>&nbsp;";
    else if ($CURUSER['class'] >= UC_STAFF) $usrclass = "&nbsp;<a href='".$INSTALLER09['baseurl']."/setclass.php'><b>[" . get_user_class_name($CURUSER['class']) . "]</b></a>&nbsp;";
    $StatusBar.= "  <div class='columns'>
    <div class='off-canvas-wrapper'>
      <div class='off-canvas-absolute position-top' style='z-index: 0;' id='offCanvasRightSplit2' data-off-canvas>&nbsp;Welcome ".format_username($CURUSER) ."".(isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "[".get_user_class_name($CURUSER['class'])."]" : $usrclass)."<br />".($INSTALLER09['max_slots'] ? "{$lang['gl_act_torrents']}:&nbsp;<img alt='{$lang['gl_seed_torrents']}' title='{$lang['gl_seed_torrents']}' src='{$INSTALLER09['pic_base_url']}up.png' />&nbsp;".intval($seed['yes']).""."&nbsp;<img alt='{$lang['gl_leech_torrents']}' title='{$lang['gl_leech_torrents']}' src='{$INSTALLER09['pic_base_url']}dl.png' />&nbsp;".($INSTALLER09['max_slots'] ? "<a title='I have ".$max." Download Slots'>".intval($seed['no'])."/".$max."</a>" : intval($seed['no']))."" : "")."&nbsp;".($INSTALLER09['achieve_sys_on'] ? "<i class='fa fa-trophy' aria-hidden='true'></i>{$lang['gl_achpoints']}&nbsp;<a href='./achievementhistory.php?id={$CURUSER['id']}'>" . (int)$Achievement_Points['achpoints'] . "</a>&nbsp;" : "")."".($INSTALLER09['seedbonus_on'] ? "{$lang['gl_karma']}: <a href='".$INSTALLER09['baseurl']." <br /> mybonus.php'>{$CURUSER['seedbonus']}</a>&nbsp;" : "")."<i class='fa fa-trophy'  style='font-size:1em; color:blue'></i>{$lang['gl_invites']}: <a href='".$INSTALLER09['baseurl']."/invite.php'>{$CURUSER['invites']}</a>&nbsp;".($INSTALLER09['rep_sys_on'] ?  " <br /><i class='fa fa-camera-retro' style='font-size:1em; color:red'></i>
{$lang['gl_rep']}:{$member_reputation}&nbsp;" : "")."{$lang['gl_shareratio']}&nbsp;". member_ratio($upped, $INSTALLER09['ratio_free'] ? '0' : $CURUSER['downloaded']);
 if ($INSTALLER09['ratio_free']) {
    $StatusBar .= "<br />&nbsp;{$lang['gl_uploaded']}:".$upped;
    } else {
        $StatusBar .= " <br />&nbsp;{$lang['gl_uploaded']}:{$upped} {$lang['gl_downloaded']}:{$downed}&nbsp; <br />{$lang['gl_connectable']}&nbsp;{$connectable}";
}
	$StatusBar .= "</div>
      <div class='off-canvas-content' style='min-height: 150px;' data-off-canvas-content>
	  
      </div>
    </div>
  </div>";
    return $StatusBar;
}
?>
