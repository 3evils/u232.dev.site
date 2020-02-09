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
function maketable($res)
{
    global $INSTALLER09, $lang, $CURUSER;
   
    $htmlout = '';
    $htmlout.= "<table class='responsive-card-table striped'>
	" . "<thead><tr>
		<th>{$lang['userdetails_type']}</th>
         <th>{$lang['userdetails_name']}</th>
         <th>{$lang['userdetails_size']}</th>
         <th>{$lang['userdetails_se']}</th>
         <th>{$lang['userdetails_le']}</th>
         <th>{$lang['userdetails_upl']}</th>\n" . "" . ($INSTALLER09['ratio_free'] ? "" : "<th>{$lang['userdetails_downl']}</th>") . "
         <th>{$lang['userdetails_ratio']}</th></tr></thead>";
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
		<td data-label='{$lang['userdetails_size']}'>$size</td><td data-label='{$lang['userdetails_se']}'>$seeders</td>
		<td data-label='{$lang['userdetails_le']}'>$leechers</td><td data-label='{$lang['userdetails_upl']}'>$uploaded</td>" . "" . ($INSTALLER09['ratio_free'] ? "" : "<td data-label='{$lang['userdetails_downl']}'>$downloaded</td>") . "<td data-label='{$lang['userdetails_ratio']}'>$ratio</td></tr></tbody>";
    }
    $htmlout.= "</table>\n";
    return $htmlout;
}
if ($user['paranoia'] < 2 || $user['opt1'] & user_options::HIDECUR || $CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if (isset($torrents)) 
		$HTMLOUT.= "<tr><td class=\"text-center\">{$lang['userdetails_uploaded_t']}</td><td align=\"left\" width=\"90%\"><a href=\"javascript: klappe_news('a')\"><img border=\"0\" src=\"pic/plus.png\" id=\"pica\" alt=\"Show/Hide\" /></a><div id=\"ka\" style=\"display: none;\">$torrents</div></td></tr>\n";
    /*
    if (isset($torrents)) {    
       $HTMLOUT .= "   <tr valign=\"top\">    
                        <td class=\"rowhead\" width=\"10%\">
                         {$lang['userdetails_uploaded_t']}   
                      </td>    
                      <td align=\"left\" width=\"90%\">    
                         <a href=\"#\" id=\"slick-toggle\">Show/Hide</a>       
                         <div id=\"slickbox\" style=\"display: none;\">{$torrents}</div>    
                      </td>    
                   </tr>";    
    } 
    */
    /*
    if (isset($seeding)) {    
       $HTMLOUT .= "   <tr valign=\"top\">    
                        <td class=\"rowhead\" width=\"10%\">
                         {$lang['userdetails_cur_seed']} 
                      </td>    
                      <td align=\"left\" width=\"90%\">    
                         <a href=\"#\" id=\"slick-toggle\">Show/Hide</a>       
                         <div id=\"slickbox\" style=\"display: none;\">".maketable($seeding)."</div>    
                      </td>    
                   </tr>";    
    } 
    */
    if (isset($seeding)) 
		$HTMLOUT.= "<tr>
			<td>{$lang['userdetails_cur_seed']}</td>
			<td>
			<p><button class='small button' data-open='Seeding'>Show</button></p>
				<div class='large reveal' id='Seeding' data-reveal>
					<h1>{$lang['userdetails_cur_seed']}</h1>
					" . maketable($seeding) . "
					<button class='close-button' data-close aria-label='Close modal' type='button'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div></td></tr>";
    /*
    if (isset($leeching)) {    
       $HTMLOUT .= "   <tr valign=\"top\">    
                        <td class=\"rowhead\" width=\"10%\">
                         {$lang['userdetails_cur_leech']}
                      </td>    
                      <td align=\"left\" width=\"90%\">    
                         <a href=\"#\" id=\"slick-toggle\">Show/Hide</a>       
                         <div id=\"slickbox\" style=\"display: none;\">".maketable($leeching)."</div>    
                      </td>    
                   </tr>";    
    }
    */
    if (isset($leeching)) $HTMLOUT.= "<tr><td class=\"text-center\">{$lang['userdetails_cur_leech']}</td><td align=\"left\" width=\"90%\"><a href=\"javascript: klappe_news('a2')\"><img border=\"0\" src=\"pic/plus.png\" id=\"pica2\" alt=\"Show/Hide\" /></a><div id=\"ka2\" style=\"display: none;\">" . maketable($leeching) . "</div></td></tr>\n";
}
//==End
// End Class
// End File
