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
    $stats_cache['vip'] = (int)$stats_cache['vip'];
    $mc1->cache_value('site_stats_', $stats_cache, $INSTALLER09['expires']['site_stats']);
}
//==End
//==Installer 09 stats
$HTMLOUT.= "
<style type='text/css'>
.quick-btn {
  background: #EEEEEE;
  box-shadow: 0 0 0 1px #F8F8F8 inset, 0 0 0 1px #CCCCCC;
  color: #444444;
  display: inline-block;
  height: 65px;
  margin: 10px;
  padding-top: 16px;
  text-align: center;
  text-decoration: none;
  width: 210px;
  position: relative;
}
.quick-btn span {
  display: block;
}
.quick-btn .label {
  position: absolute;
  right: -5px;
  top: -5px;
}
.quick-btn:hover {
  text-decoration: none;
  color: #fff;
  background-color: #4d7589;
}
.quick-btn.small {
  width: 40px;
  height: 30px;
  padding-top: 6px;
}
</style>
<div class='header panel panel-default'>
<div class='panel-heading'><strong>{$lang['index_stats_title']}</strong></div>
<div class='container-fluid panel-body'>


<strong data-toggle='collapse' href='#uinfo'>{$lang['index_stats_uinfo']}</strong>
<div class='text-left collapse'  id='uinfo'>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_regged']}</span>
<span class='label label-info'>{$stats_cache['regusers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_max']}</span>
<span class='label label-info'>{$INSTALLER09['maxusers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_online']}</span>
<span class='label label-info'>{$stats_cache['numactive']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_uncon']}</span>
<span class='label label-info'>{$stats_cache['unconusers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_gender_na']}</span>
<span class='label label-info'>{$stats_cache['gender_na']}</span>
</a>
<a class='quick-btn'><i class='fa fa-male'></i><span>{$lang['index_stats_gender_male']}</span>
<span class='label label-info'>{$stats_cache['gender_male']}</span>
</a>
<a class='quick-btn'><i class='fa fa-female' style='font-size:20px;color:red;'></i><span>{$lang['index_stats_gender_female']}</span>
<span class='label label-info'>{$stats_cache['gender_female']}</span>
</a>
</div>
<hr />
<strong data-toggle='collapse' href='#cinfo'>{$lang['index_stats_cinfo']}</strong>
<div class='text-left collapse'  id='cinfo'>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_powerusers']}</span>
<span class='label label-info'>{$stats_cache['powerusers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_banned']}</span>
<span class='label label-info'>{$stats_cache['disabled']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_uploaders']}</span>
<span class='label label-info'>{$stats_cache['uploaders']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-plus' style='font-size:20px;color:blue;'></i><span>{$lang['index_stats_vips']}</span>
<span class='label label-info'>{$stats_cache['vip']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_moderators']}</span>
<span class='label label-info'>{$stats_cache['moderators']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_admin']}</span>
<span class='label label-info'>{$stats_cache['administrators']}</span>
</a>
<a class='quick-btn'><i class='fa fa-superpowers fa-spin' style='font-size:20px;color:orange;'></i><span>{$lang['index_stats_sysops']}</span>
<span class='label label-info'>{$stats_cache['sysops']}</span>
</a>
</div>
<hr />
<strong data-toggle='collapse' href='#finfo'>{$lang['index_stats_finfo']}</strong>
<div class='text-left collapse'  id='finfo'>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_topics']}</span>
<span class='label label-info'>{$stats_cache['forumtopics']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_posts']}</span>
<span class='label label-info'>{$stats_cache['forumposts']}</span>
</a>
</div>
<hr />
<strong data-toggle='collapse' href='#tinfo'>{$lang['index_stats_tinfo']}</strong>
<div class='text-left collapse'  id='tinfo'>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_torrents']}</span>
<span class='label label-info'>{$stats_cache['torrents']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_newtor']}</span>
<span class='label label-info'>{$stats_cache['torrentstoday']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_peers']}</span>
<span class='label label-info'>{$stats_cache['peers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_unconpeer']}</span>
<span class='label label-info'>{$stats_cache['unconnectables']}</span>
</a>
<a class='quick-btn'><i class='fa fa-arrow-up'></i><span>{$lang['index_stats_seeders']}</span>
<span class='label label-info'>{$stats_cache['seeders']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle'></i><span>{$lang['index_stats_unconratio']}</span>
<span class='label label-info'>" . round($stats_cache['ratiounconn'] * 100) . "</span>
</a>
<a class='quick-btn'><i class='fa fa-arrow-down'></i><span>{$lang['index_stats_leechers']}</span>
<span class='label label-info'>{$stats_cache['leechers']}</span>
</a>
<a class='quick-btn'><i class='fa fa-user-circle' style='font-size:20px;color:green;'></i><span>{$lang['index_stats_slratio']}</span>
<span class='label label-info'>" . round($stats_cache['ratio'] * 100) . "</span>
</a>
</div>
<hr />
</div>
</div>";
//==End
// End Class
// End File
