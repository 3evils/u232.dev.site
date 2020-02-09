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
//==Stats Begin
if (($stats_cache = $mc1->get_value('site_stats_')) === false) {
    $stats_cache = mysqli_fetch_assoc(sql_query("SELECT *, seeders + leechers AS peers, seeders / leechers AS ratio, unconnectables / (seeders + leechers) AS ratiounconn FROM stats WHERE id = '1' LIMIT 1"));
    $stats_cache['seeders'] = (int)$stats_cache['seeders'];
    $stats_cache['leechers'] = (int)$stats_cache['leechers'];
    $stats_cache['regusers'] = (int)$stats_cache['regusers'];
    $stats_cache['unconusers'] = (int)$stats_cache['unconusers'];
    $stats_cache['torrents'] = (int)$stats_cache['torrents'];
    $stats_cache['torrentstoday'] = (int)$stats_cache['torrentstoday'];
    $stats_cache['ratiounconn'] = (int)$stats_cache['ratiounconn'];
    $stats_cache['unconnectables'] = (int)$stats_cache['unconnectables'];
    $stats_cache['ratio'] = (int)$stats_cache['ratio'];
    $stats_cache['peers'] = (int)$stats_cache['peers'];
    $stats_cache['numactive'] = (int)$stats_cache['numactive'];
    $stats_cache['donors'] = (int)$stats_cache['donors'];
    $stats_cache['forumposts'] = (int)$stats_cache['forumposts'];
    $stats_cache['forumtopics'] = (int)$stats_cache['forumtopics'];
    $stats_cache['torrentsmonth'] = (int)$stats_cache['torrentsmonth'];
    $stats_cache['gender_na'] = (int)$stats_cache['gender_na'];
    $stats_cache['gender_male'] = (int)$stats_cache['gender_male'];
    $stats_cache['gender_female'] = (int)$stats_cache['gender_female'];
    $stats_cache['powerusers'] = (int)$stats_cache['powerusers'];
    $stats_cache['disabled'] = (int)$stats_cache['disabled'];
    $stats_cache['uploaders'] = (int)$stats_cache['uploaders'];
    $stats_cache['moderators'] = (int)$stats_cache['moderators'];
    $stats_cache['administrators'] = (int)$stats_cache['administrators'];
    $stats_cache['sysops'] = (int)$stats_cache['sysops'];
    $mc1->cache_value('site_stats_', $stats_cache, $INSTALLER09['expires']['site_stats']);
}
//==End
//==Installer 09 stats
$HTMLOUT.= "<div class='card'>
	<div class='card-divider portlet-header'>{$lang['index_stats_title']}</div>
  <div class='portlet-content card-section'>
                <div class='row'>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['regusers']} {$lang['index_stats_regged']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$INSTALLER09['maxusers']} {$lang['index_stats_max']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['numactive']} {$lang['index_stats_online']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['unconusers']} {$lang['index_stats_uncon']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['gender_na']} {$lang['index_stats_gender_na']}</h6>
						</a>
					</div>																	
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['gender_male']} {$lang['index_stats_gender_male']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['gender_female']} {$lang['index_stats_gender_female']}</h6>
						</a>
					</div>
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$stats_cache['powerusers']} {$lang['index_stats_powerusers']}</h6>
							</a>
						</div>
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$stats_cache['disabled']} {$lang['index_stats_banned']}</h6>
							</a>
						</div>
						<hr>
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$stats_cache['uploaders']} {$lang['index_stats_uploaders']}</h6>
							</a>
						</div>
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$lang['index_stats_moderators']} {$stats_cache['moderators']}</h6>
							</a>
						</div>																	
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$lang['index_stats_admin']} {$stats_cache['administrators']}</h6>
							</a>
						</div>
						<div class='medium-6 large-3 columns'>
							<a class='dashboard-nav-card' href='#'>
								<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
								<h6 class='dashboard-nav-card-title'>{$stats_cache['sysops']} {$lang['index_stats_sysops']}</h6>
							</a>
						</div>
					<hr>
					<b>{$lang['index_stats_finfo']}</b>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['forumtopics']} {$lang['index_stats_topics']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['forumposts']} {$lang['index_stats_posts']}</h6>
						</a>
					</div>
					<hr>
					<b>{$lang['index_stats_tinfo']}</b>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$lang['index_stats_torrents']} {$stats_cache['torrents']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['torrentstoday']} {$lang['index_stats_newtor']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['peers']} {$lang['index_stats_peers']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['unconnectables']} {$lang['index_stats_unconpeer']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['seeders']} {$lang['index_stats_seeders']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>" . round($stats_cache['ratiounconn'] * 100) . " {$lang['index_stats_unconratio']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>{$stats_cache['leechers']} {$lang['index_stats_leechers']}</h6>
						</a>
					</div>
					<div class='medium-6 large-3 columns'>
						<a class='dashboard-nav-card' href='#'>
							<i class='dashboard-nav-card-icon fa fa-users' aria-hidden='true'></i>
							<h6 class='dashboard-nav-card-title'>" . round($stats_cache['ratio'] * 100) . " {$lang['index_stats_slratio']}</h6>
						</a>
					</div>
				</div>
			</div>
</div>";
//==End
// End Class
// End File
