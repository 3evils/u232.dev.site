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
//== Best film of the week
$categorie = genrelist();
foreach ($categorie as $key => $value) $change[$value['id']] = array(
    'id' => $value['id'],
    'name' => $value['name'],
    'image' => $value['image']
);
if (($motw_cached = $mc1->get_value('top_movie_2')) === false) {
    $motw = sql_query("SELECT torrents.id, torrents.leechers, torrents.seeders, torrents.category, torrents.name, torrents.times_completed FROM torrents INNER JOIN avps ON torrents.id=avps.value_u WHERE avps.arg='bestfilmofweek' LIMIT 1") or sqlerr(__FILE__, __LINE__);
    while ($motw_cache = mysqli_fetch_assoc($motw)) $motw_cached[] = $motw_cache;
    $mc1->cache_value('top_movie_2', $motw_cached, 0);
}
if (count($motw_cached) > 0) {
    $HTMLOUT.= "<div class='card'>
	<div class='card-divider portlet-header'>{$lang['index_mow_title']}</div>
		<div class='portlet-content card-section'>
			<div class='module'><div class='tbadge tbadge-hot'></div>
                <table class='responsive-card-table striped'>
					<thead>
						<tr>
							<th>{$lang['index_mow_type']}</th>
							<th>{$lang['index_mow_name']}</th>
							<th class='col-md-2 text-center'>{$lang['index_mow_snatched']}</th>
							<th>{$lang['index_mow_seeder']}</th>
							<th>{$lang['index_mow_leecher']}</th>
						</tr>
					</thead>";
		if ($motw_cached) {
			foreach ($motw_cached as $m_w) {
				$mw['cat_name'] = htmlsafechars($change[$m_w['category']]['name']);
				$mw['cat_pic'] = htmlsafechars($change[$m_w['category']]['image']);
				$HTMLOUT.= "
					<tbody>
						<tr>
							<td data-label='{$lang['index_mow_type']}'><img src='pic/caticons/{$CURUSER['categorie_icon']}/" . htmlsafechars($mw["cat_pic"]) . "' alt='" . htmlsafechars($mw["cat_name"]) . "' title='" . htmlsafechars($mw["cat_name"]) . "' /></td>
							<td data-label='{$lang['index_mow_name']}'><a href='{$INSTALLER09['baseurl']}/details.php?id=" . (int)$m_w["id"] . "'><b>" . htmlsafechars($m_w["name"]) . "</b></a></td>
							<td data-label='{$lang['index_mow_snatched']}'><span class='badge'>" . (int)$m_w["times_completed"] . "</span></td>
							<td data-label='{$lang['index_mow_seeder']}'><span class='badge'>" . (int)$m_w["seeders"] . "</span></td>
							<td data-label='{$lang['index_mow_leecher']}'><span class='badge'>" . (int)$m_w["leechers"] . "</span> </td>
						</tr>
					</tbody>";
			}
			
    } else {
        //== If there are no movie of the week
        if (empty($motw_cached)) $HTMLOUT.= "<tr><td class='row-fluid text-left' colspan='5'>{$lang['index_mow_no']}!</td></tr>";
    }
	$HTMLOUT.= "</table></div></div></div>";
}
//==End
// End Class
// End File
