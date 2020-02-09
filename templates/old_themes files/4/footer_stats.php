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
  if ($CURUSER) {
        /** just in case **/
        $htmlfoot.= "
<div class='panel panel-default'>
	<div class='panel-heading'>Server Statistics</div>
	<div class='panel-body'>
		<div class='col-lg-8'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered text-left'>
						<thead>
							<tr><th class='text-center'style='background:rgba(0, 0, 0, 0.1)''>Server Info</th></tr>
						</thead>
						<tbody>
							<tr><td>" . $INSTALLER09['site_name'] . " {$lang['gl_stdfoot_querys_page']}" . $r_seconds . " {$lang['gl_stdfoot_querys_seconds']}<br />" . "</td></tr>
							<tr><td>{$lang['gl_stdfoot_querys_server']}" . $queries . " {$lang['gl_stdfoot_querys_time']} " . ($queries != 1 ? "{$lang['gl_stdfoot_querys_times']}" : "") . "</td></tr>
							<tr><td>" . ($debug ? " " . $header . "</td></tr>
							<tr><td>{$lang['gl_stdfoot_uptime']} " . $uptime . "" : " ") . "</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='col-lg-4'>
			<div class='panel panel-default'>	
				<div class='panel-body'>
					<table class='table table-hover table-bordered text-left'>
						<thead>
							<tr><th class='text-center'style='background:rgba(0, 0, 0, 0.1)''>Source Info</th></tr>
						</thead>
						<tbody>
							<tr><td>{$lang['gl_stdfoot_powered']}" . TBVERSION . "<br /></td></tr>
							<tr><td>{$lang['gl_stdfoot_using']}{$lang['gl_stdfoot_using1']}<br /></td></tr>
							<tr><td>{$lang['gl_stdfoot_support']}<a href='http://forum-u-232.servebeer.com/index.php'>{$lang['gl_stdfoot_here']}</a><br /></td></tr>
							<tr><td>" . ($debug ? "<a title='{$lang['gl_stdfoot_sview']}' rel='external' href='/staffpanel.php?tool=system_view'>{$lang['gl_stdfoot_sview']}</a> | " . "<a rel='external' title='OPCache' href='/staffpanel.php?tool=op'>{$lang['gl_stdfoot_opc']}</a> | " . "<a rel='external' title='Memcache' href='/staffpanel.php?tool=memcache'>{$lang['gl_stdfoot_memcache']}</a>" : "") . "";$htmlfoot.= "</td></tr>";
						$htmlfoot.= "</tbody>
					</table>
				</div>
			</div>
		</div>";
	$htmlfoot.= "</div>"; 

}
?>
