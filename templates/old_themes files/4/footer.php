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
    if (($MemStats = $mc1->get_value('mc_hits')) === false) {
        $MemStats = $mc1->getStats()["127.0.0.1:11211"];
        $MemStats['Hits'] = (($MemStats['get_hits'] / $MemStats['cmd_get'] < 0.7) ? '' : number_format(($MemStats['get_hits'] / $MemStats['cmd_get']) * 100, 3));
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
<div class='panel panel-default'>
	<div class='panel-heading'>{$lang['gl_stdfoot_querys']}</div>
	<div class='panel-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr>
								<th class='text-center'>{$lang['gl_stdfoot_id']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qt']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qs']}</th>
							</tr>
						</thead>";
        foreach ($query_stat as $key => $value) {
            $querytime+= $value['seconds']; // query execution time
             $htmlfoot.= "
						<tbody>
							<tr>
								<td>" . ($key + 1) . "</td>
								<td>" . ($value['seconds'] > 0.01 ? "
								<span class='text-danger' title='{$lang['gl_stdfoot_ysoq']}'>" . $value['seconds'] . "</span>" : "
								<span class='text-success' title='{$lang['gl_stdfoot_qg']}'>" . $value['seconds'] . "</span>") . "
								</td>
								<td>" . htmlsafechars($value['query']) . "<br /></td>
							</tr>
						</tbody>";
        }
        $htmlfoot.= "</table></table></div></div>";
    }
require_once (TEMPLATE_DIR.'' . DIRECTORY_SEPARATOR . ''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'footer_stats.php');
    $htmlfoot.="
        </div><!--  End main outer container -->
        ".($INSTALLER09['mods']['snow'] && $CURUSER['snow'] == 'yes' ? '</div><!--snow div-->' : '')."
        <!-- Ends Footer -->
		<!-- localStorage for collapse -->
        <script src='scripts/jquery.collapse.localstorage.js'></script>
        </body></html>";
    return $htmlfoot;
}
?>
