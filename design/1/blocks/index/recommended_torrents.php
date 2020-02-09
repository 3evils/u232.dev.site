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
//== Staff recommended torrents
//$mc1->delete_value('rec_tor_');
    if(($rec_torrents = $mc1->get_value('rec_tor_')) === false) {
    $rec = sql_query("SELECT id, seeders, free, leechers, descr, name, poster FROM torrents WHERE visible = 'yes' AND recommended = 'yes' ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
    while($rec_torrent = mysqli_fetch_assoc($rec))
    $rec_torrents[] = $rec_torrent;
    $mc1->cache_value('rec_tor_', $rec_torrents, 0);
    }
    if (count($rec_torrents) > 0)
    {
    $HTMLOUT .= "<div class='card'>
<div class='card-divider portlet-header'><label class='text-left'>{$lang['index_rec_tor']}</label></div>

       <div class='content portlet-content card-section'><div style='height:300px;width:100%;overflow-y:scroll;overflow-x:hidden;'><table><tr>";
    if ($rec_torrents)
    {
    foreach($rec_torrents as $r_t) {
    $poster = ($r_t['poster'] == '' ? $INSTALLER09['pic_base_url'].'noposter.png' : htmlsafechars( $r_t['poster'] ));
    $HTMLOUT .= "
   <td class='rec_col '> 

				<div class='squashprec text-center img-rounded' ".($r_t['free'] != 0 ? " style='background-color:green'" : ($r_t['free'] == 0 ? " style='background-color:red'" : "" ))."><br />
					<a href='".$INSTALLER09["baseurl"]."/details.php?id=".(int)$r_t['id']."' title='".htmlsafechars($r_t['name'])."'><img src='".$poster."' style='width:110px;height:140px;' alt='Poster' /></a>
					<br />".CutName(htmlsafechars($r_t['name']),45)." <br /><b>".(int)$r_t['seeders']." {$lang['index_rec_tor_seed']}".($r_t['seeders'] > 1 ? "s" : "")." 
					<br /> ".(int)$r_t['leechers']." {$lang['index_rec_tor_leech']}".($r_t['leechers'] > 1 ? "s" : "")."</b>
				</div></td><br /> ";
    }
    $HTMLOUT .= "</tr></table></div></div></div>";
    } else {
    //== If there are no recommended torrents
    if (empty($rec_torrents))
    $HTMLOUT .= "<tbody><tr><td>No torrents here yet !!</td></tr></tbody></table></div></div>";
    }
    }

//==End
// End Class
// End File
