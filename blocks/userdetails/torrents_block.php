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
 $r = sql_query("SELECT t.id, t.name, t.seeders, t.leechers, c.name AS cname, c.image FROM torrents t LEFT JOIN categories c ON t.category = c.id WHERE t.owner = " . sqlesc($id) . " ORDER BY t.name") or sqlerr(__FILE__, __LINE__);
if (mysqli_num_rows($r) > 0) {
    $torrents = "<table class='responsive-card-table'>" . "<thead><tr>
		<th><strong>{$lang['userdetails_type']}</strong></th>
		<th><strong>{$lang['userdetails_name']}</strong></th>
		<th><strong>{$lang['userdetails_seeders']}</strong></th>
		<th><strong>{$lang['userdetails_leechers']}</strong></th>
		</tr></thead>";
    while ($a = mysqli_fetch_assoc($r)) {
        $cat = "<img src=\"{$INSTALLER09['pic_base_url']}/caticons/{$CURUSER['categorie_icon']}/" . htmlsafechars($a['image']) . "\" title=\"" . htmlsafechars($a['cname']) . "\" alt=\"" . htmlsafechars($a['cname']) . "\">";
        $torrents.= "<tr>
			<td data-label='{$lang['userdetails_type']}'>$cat</td>
			<td data-label='{$lang['userdetails_name']}'><a href='details.php?id=" . (int)$a['id'] . "&amp;hit=1'><b>" . htmlsafechars($a["name"]) . "</b></a></td>" . "
			<td data-label='{$lang['userdetails_se']}'>" . (int)$a['seeders'] . "</td>
			<td data-label='{$lang['userdetails_le']}'>" . (int)$a['leechers'] . "</td>
		</tr>";
    }
    $torrents.= "</table>";
}
function maketable($res)
{
    global $INSTALLER09, $lang, $CURUSER;
   
    $htmlout = '';
    $htmlout.= "<table class='responsive-card-table striped'>
	" . "<thead><tr>
		<th><strong>{$lang['userdetails_type']}</strong></th>
         <th><strong>{$lang['userdetails_name']}</strong></th>
         <th><strong>{$lang['userdetails_size']}</strong></th>
         <th><strong>{$lang['userdetails_se']}</strong></th>
         <th><strong>{$lang['userdetails_le']}</strong></th>
         <th><strong>{$lang['userdetails_upl']}</strong></th>\n" . "" . ($INSTALLER09['ratio_free'] ? "" : "<th><strong>{$lang['userdetails_downl']}</strong></th>") . "
         <th><strong>{$lang['userdetails_ratio']}</strong></th></tr></thead>";
    foreach ($res as $arr) {
        if ($arr["downloaded"] > 0) {
            $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
            $ratio = "<font color='" . get_ratio_color($ratio) . "'>$ratio</font>";
        } else if ($arr["uploaded"] > 0) $ratio = "{$lang['userdetails_inf']}";
        else $ratio = "---";
        $catimage = "{$INSTALLER09['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/{$arr['image']}";
        $catname = "&nbsp;&nbsp;".htmlsafechars($arr["catname"]);
        $catimage = "<img src=\"" . htmlsafechars($catimage) . "\" title=\"$catname\" alt=\"$catname\" width='42' height='42' />";
        $size = str_replace(" ", "<br />", mksize($arr["size"]));
        $uploaded = str_replace(" ", "<br />", mksize($arr["uploaded"]));
        $downloaded = str_replace(" ", "<br />", mksize($arr["downloaded"]));
        $seeders = number_format($arr["seeders"]);
        $leechers = number_format($arr["leechers"]);
        $OCELOT_or_PHP = (OCELOT_TRACKER == true ? $arr['fid'] : $arr['torrent']);
        $htmlout.= "<tbody><tr>
		<td data-label='{$lang['userdetails_type']}'>$catimage</td>" . "
		<td data-label='{$lang['userdetails_name']}'><a href='details.php?id=" . (int)$OCELOT_or_PHP . "&amp;hit=1'><b>" . htmlsafechars($arr['torrentname']) . "</b></a></td>
		<td data-label='{$lang['userdetails_size']}'>$size</td>
		<td data-label='{$lang['userdetails_se']}'>$seeders</td>
		<td data-label='{$lang['userdetails_le']}'>$leechers</td>
		<td data-label='{$lang['userdetails_upl']}'>$uploaded</td>" . "" . ($INSTALLER09['ratio_free'] ? "" : "
		<td data-label='{$lang['userdetails_downl']}'>$downloaded</td>") . "
		<td data-label='{$lang['userdetails_ratio']}'>$ratio</td></tr></tbody>";
    }
    $htmlout.= "</table>\n";
    return $htmlout;
}
if ($user['paranoia'] < 2 || $user['opt1'] & user_options::HIDECUR || $CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if (isset($torrents)) 
		$HTMLOUT.= "<tr>
			<td>{$lang['userdetails_uploaded_t']}</td>
			<td>
			<a href='#' class='button small' data-open='Uploaded'>Show</a>
				<div class='large reveal' id='Uploaded' data-reveal>
					<h1>{$lang['userdetails_uploaded_t']}</h1>
					". $torrents ."
					<button class='close-button' data-close aria-label='Close modal' type='button'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div></td></tr>";
    if (isset($seeding)) 
		$HTMLOUT.= "<tr>
			<td>{$lang['userdetails_cur_seed']}</td>
			<td>
			<a href='#' class='button small' data-open='Seeding'>Show</a>
				<div class='large reveal' id='Seeding' data-reveal>
					<h1>{$lang['userdetails_cur_seed']}</h1>
					" . maketable($seeding) . "
					<button class='close-button' data-close aria-label='Close modal' type='button'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div></td></tr>";
    if (isset($leeching)) 
		$HTMLOUT.= "<tr>
			<td>{$lang['userdetails_cur_leech']}</td>
			<td>
			<a href='#' class='button small' data-open='Leeching'>Show</a>
				<div class='large reveal' id='Leeching' data-reveal>
					<h1>{$lang['userdetails_cur_leech']}</h1>
					" . maketable($leeching) . "
					<button class='close-button' data-close aria-label='Close modal' type='button'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div></td></tr>";
}
//==End
// End Class
// End File
