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
$inbox = '0';
    if (($inbox = $mc1->get_value('inbox_new_' . $CURUSER['id'])) === false) {
        $res = sql_query('SELECT count(id) FROM messages WHERE receiver=' . sqlesc($CURUSER['id']) . ' && unread="yes" AND location = "1"') or sqlerr(__FILE__, __LINE__);
        $arr = mysqli_fetch_row($res);
        $inbox = (int)$arr[0];
        $mc1->cache_value('inbox_new_' . $CURUSER['id'], $inbox, $INSTALLER09['expires']['unread']);
    }
$htmlout .="
  <nav class='navbar navbar-default navbar-static'>
    <div class='navbar-header'>
    	<button class='navbar-toggle' type='button' data-toggle='collapse' data-target='.js-navbar-collapse'>
			<span class='sr-only'>Toggle navigation</span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		</button>
		<a class='navbar-brand' href='" . $INSTALLER09['baseurl'] . "/index.php'>U-232 Source Code</a>
	</div>
	<div class='collapse navbar-collapse js-navbar-collapse'>
		<ul class='nav navbar-nav'>
			<li class='dropdown mega-dropdown'>
				<a href='#' class='dropdown-toggle' data-toggle='dropdown'>Main Menu <span class='caret'></span></a>				
				<ul class='dropdown-menu mega-dropdown-menu'>
		<div class='col-lg-3'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr><th class='text-center' style='background:rgba(0, 0, 0, 0.1)''>{$lang['gl_general']}</th></tr>
						</thead>
						<tbody>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/announcement.php'>{$lang['gl_announcements']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/topten.php'>{$lang['gl_stats']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/faq.php'>{$lang['gl_faq']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/rules.php'>{$lang['gl_rules']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/chat.php'>{$lang['gl_irc']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/staff.php'>{$lang['gl_staff']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/wiki.php'>{$lang['gl_wiki']}</a></td></tr>
							<tr><td><a href='#' onclick='radio();'>{$lang['gl_radio']}</a></td></tr>
							<tr><td><a href='".$INSTALLER09['baseurl']."/rsstfreak.php'>{$lang['gl_tfreak']}</a></td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='col-lg-3'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr><th class='text-center' style='background:rgba(0, 0, 0, 0.1)''>{$lang['gl_torrent']}</th></tr>
						</thead>
						<tbody>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/requests.php'>{$lang['gl_requests']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/offers.php'>{$lang['gl_offers']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/needseed.php?needed=seeders'>{$lang['gl_nseeds']}</a></td></tr>
							" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/uploadapp.php'>{$lang['gl_uapp']}</a></td></tr>
							" : " ") . "
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/upload.php'>{$lang['gl_upload']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/wiki.php'>{$lang['gl_wiki']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/bookmarks.php'>{$lang['gl_bookmarks']}</a></td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='col-lg-3'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr><th class='text-center alert' style='background:rgba(0, 0, 0, 0.1)''>Staff Tools</th></tr>
						</thead>
						<tbody>
							<tr><td> " . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=add'>{$lang['gl_breport']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=bugs'>{$lang['gl_brespond']}</a>") . "</td></tr>
							<tr><td>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/contactstaff.php'>{$lang['gl_cstaff']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/staffbox.php'>{$lang['gl_smessages']}</a>") . "</td></tr>
							" . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<tr><td><a href='" . $INSTALLER09['baseurl'] . "/staffpanel.php'>{$lang['gl_admin']}</a></td></tr>" : "") . "
						</tbody>
						" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "
						<thead>
							<tr><th class='text-center' style='background:rgba(0, 0, 0, 0.1)''>{$lang['gl_games']}</th></tr>
						</thead>
						" : "") . "
						<tbody>
							" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></td></tr>
							" : "") . "
							" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></td></tr>
							" : "") . "
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='col-lg-3'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr><th class='text-center' style='background:rgba(0, 0, 0, 0.1)''>Pers Tools</th></tr>
						</thead>
						<tbody>
							<tr><td><a href='#' onclick='themes();'>{$lang['gl_theme']}</a></td></tr>
							<tr><td><a href='#' onclick='language_select();'>{$lang['gl_language_select']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/pm_system.php'>{$lang['gl_pms']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/usercp.php?action=default'>{$lang['gl_usercp']}</a></td></tr>
							<tr><td><a href='" . $INSTALLER09['baseurl'] . "/friends.php'>{$lang['gl_friends']}</a></td></tr>
						<tbody>
						</thead>
							<tr><th class='text-center' style='background:rgba(0, 0, 0, 0.1)''>" . (isset($CURUSER) && $CURUSER['got_blocks'] == 'yes' ? "{$lang['gl_userblocks']} </th></tr>
						</thead>
						<tbody>
							<tr><td><a href='".$INSTALLER09['baseurl']."/user_blocks.php'>My Blocks</a></td></tr>
							<!--<tr><td>" : "") . " " . (isset($CURUSER) && $CURUSER['got_moods'] == 'yes' ? "</td></tr> -->
							<!--<tr><td><a href='".$INSTALLER09['baseurl']."/user_unlocks.php'>My Unlocks</a>" : "") . "</td></tr> -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
		</ul>
		<div class='pull-right text-right'>
			<li><a href='" . $INSTALLER09['baseurl'] . "/donate.php'>{$lang['gl_donate']}</a></li>
			<li><a href='" . $INSTALLER09['baseurl'] . "/forums.php'>{$lang['gl_forums']}</a></li>
 			&nbsp;<span>
				" . (isset($CURUSER) && $CURUSER['class'] > UC_STAFF ? "<button type='button' href='#modal' data-toggle='modal' class='btn btn-success navbar-btn' data-target='#staff'><i class='fa fa-user-md' aria-hidden='true'></i></button>" : "") . "
				<button type='button' href='#modal' data-toggle='modal' class='btn btn-info navbar-btn' data-target='#messages'>
				<a href='".$INSTALLER09['baseurl']."/pm_system.php'><i class='fa fa-envelope' aria-hidden='true'></i>  <span class='badge'>$inbox</span></a>
				</button>
				<button type='button' class='btn btn-info navbar-btn' href='#collapseStatusBar' data-toggle='collapse' aria-expanded='false' aria-controls='collapseStatusBar'>
				<i class='fa fa-user-circle' aria-hidden='true'></i>
				</button>
				<button type='button' class='btn btn-warning navbar-btn' href='#collapseAlerts' data-toggle='collapse' role='button' aria-expanded='false' aria-controls='collapseAlerts'>
				<i class='fa fa-bell-o' aria-hidden='true'></i>
				</button>
				<a class='btn btn-danger navbar-btn' href='" . $INSTALLER09['baseurl'] . "/logout.php?hash_please={$salty}'><i class='fa fa-sign-out' aria-hidden='true'></i></a>
			</span>
		</div>
	</nav><!-- /.nav-collapse -->";
}

?>
