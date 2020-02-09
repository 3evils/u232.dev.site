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
// 09 poster mod
$HTMLOUT .='
 
		<script src="/scripts/raphael-min.js"></script>
		<script src="/scripts/icarousel.js"></script>
<script src="/scripts/jquery.mousewheel.js"></script>
<!--<script src="/scripts/test.js"></script><link rel="stylesheet" href="css/icarousel.css" type="text/css" />-->';

$HTMLOUT.= '<script type="text/javascript" language="javascript">
/*<![CDATA[*/

$(document).ready(function(){ $("#icarousel").iCarousel({
easing: "ease-in-out",
			slides: 10,
			make3D: !1,
			perspective: 500,
			animationSpeed: 500,
			pauseTime: 5E3,
			startSlide: 2,
			directionNav: !0,
			autoPlay: !1,
			keyboardNav: !0,
			touchNav: !0,
			mouseWheel: true,
			pauseOnHover: !1,
			nextLabel: "Next",
			previousLabel: "Previous",
			playLabel: "Play",
			pauseLabel: "Pause",
			randomStart: !1,
			slidesSpace: "200",
			slidesTopSpace: "20",
			direction: "rtl",
			timer: "",
			timerBg: "#000",
			timerColor: "#FFF",
			timerOpacity: 0.4,
			timerDiameter: 35,
			timerPadding: 4,
			timerStroke: 3,
			timerBarStroke: 1,
			timerBarStrokeColor: "#FFF",
			timerBarStrokeStyle: "solid",
			timerBarStrokeRadius: 4,
			timerPosition: "top-right",
			timerX: 10,
			timerY: 10

}); });
/*]]>*/
</script>';
	


$HTMLOUT.= "<div class='header panel panel-default'>
  <div class='panel-heading'>
<label class='text-left'>{$lang['index_latest']}</label>
</div>
    <div class='container-fluid panel-body'>";
$HTMLOUT .='<div id="carousel-container" class="carousel-container">
<div id="icarousel" class="icarousel">';


if (($scroll_torrents = $mc1->get_value('scroll_tor_')) === false) {
    $scroll = sql_query("SELECT id, seeders, leechers, name, poster FROM torrents WHERE seeders >= '1' ORDER BY added DESC LIMIT {$INSTALLER09['latest_torrents_limit_scroll']}") or sqlerr(__FILE__, __LINE__);
    while ($scroll_torrent = mysqli_fetch_assoc($scroll)) $scroll_torrents[] = $scroll_torrent;
    $mc1->cache_value('scroll_tor_', $scroll_torrents, $INSTALLER09['expires']['scroll_torrents']);
}


if ($scroll_torrents) {
        foreach ($scroll_torrents as $s_t) {
            $i = $INSTALLER09['latest_torrents_limit_scroll'];
            $id = (int)$s_t['id'];
            $name = htmlsafechars($s_t['name']);
			$poster = ($s_t['poster'] == '' ? ''.$INSTALLER09['pic_base_url'].'noposter.png' : htmlsafechars($s_t['poster']));
            $seeders = number_format((int)$s_t['seeders']);
            $leechers = number_format((int)$s_t['leechers']);
            $name = str_replace('_', ' ', $name);
            $name = str_replace('.', ' ', $name);
            $name = substr($name, 0, 50);

            $HTMLOUT.= "<div class='slide'><a href='{$INSTALLER09['baseurl']}/details.php?id=$id'><img src='".htmlsafechars($poster)."' class='glossy tester' alt='{$name}' title='{$name} - Seeders : {$seeders} - Leechers : {$leechers}'border='0' /></a></div>";

}

}

$HTMLOUT .='
		</div>
	</div></div></div>';

//== end 09 poster mod
// End Class
// End File