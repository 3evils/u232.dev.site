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

$stdhead = array(
    /** include js **/
    'js' => array(
        'raphael-min1',
        'jquery.mousewheel1',
        'icarousel1'
    )
);
 
$HTMLOUT.= '
<!--<script type="text/javascript" src="/scripts/raphael-min.js"></script>
<script type="text/javascript" src="/scripts/icarousel.js"></script>
<script type="text/javascript" src="/scripts/jquery.mousewheel.js"></script>

<link rel="stylesheet" href="css/icarousel.css" type="text/css" />-->

<script type="text/javascript" language="javascript">
/*<![CDATA[*/
$(document).ready(function(){ $("#icarousel1").iCarousel({
easing: "ease-in-out",
			slides: 14,
			make3D: !1,
			perspective: 5,
			animationSpeed: 500,
			pauseTime: 5E3,
			startSlide: 5,
			directionNav: !0,
			autoPlay: 0,
			keyboardNav: !0,
			touchNav: !0,
			mouseWheel: false,
			pauseOnHover: !1,
			nextLabel: "Next",
			previousLabel: "Previous",
			playLabel: "Play",
			pauseLabel: "Pause",
			randomStart: !1,
			slidesSpace: "100",
			slidesTopSpace: "0",
			direction: "rtl",
			timer: "false",
			timerBg: "#111",
			timerColor: "#111",
			timerOpacity: 0.0,
			timerDiameter: 35,
			timerPadding: 4,
			timerStroke: 3,
			timerBarStroke: 1,
			timerBarStrokeColor: "#111",
			timerBarStrokeStyle: "solid",
			timerBarStrokeRadius: 4,
			timerPosition: "top-right",
			timerX: 10,
			timerY: 10

}); });
/*]]>*/
</script>';


//== Staff recommended torrents
//$mc1->delete_value('rec_tor_');
    if(($rec_torrents = $mc1->get_value('rec_tor_')) === false) {
    $rec = sql_query("SELECT id, seeders, free, leechers, descr, name, poster FROM torrents WHERE visible = 'yes' AND recommended = 'yes' ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
  //$message = "Hi, \n A recommended torrent if you were interested in has been uploaded!!!" . htmlsafechars($rec['name']) . "!";
        

while($rec_torrent = mysqli_fetch_assoc($rec))
    $rec_torrents[] = $rec_torrent;
    $mc1->cache_value('rec_tor_', $rec_torrents, 0);
    }
    if (count($rec_torrents) > 0)
    {





    $HTMLOUT .= "<div class='header panel panel-default'>
	<div class='panel-heading'><label class='text-left'>{$lang['rec_tor']}</label></div>
        <div class='container-fluid panel-body'>";

$HTMLOUT .='<div id="carousel-container1" class="carousel-container1">
<div id="icarousel1" class="icarousel1">';


    if ($rec_torrents)
    {
    foreach($rec_torrents as $r_t) {
            //$i = $INSTALLER09['latest_torrents_limit_scroll'];
            $id = (int)$r_t ['id'];
            $name = htmlsafechars($r_t['name']);
	    $poster = ($r_t['poster'] == '' ? ''.$INSTALLER09['pic_base_url'].'noposter.png' : $r_t['poster']);
            $seeders = number_format((int)$r_t['seeders']);
            $leechers = number_format((int)$r_t['leechers']);
            $name = str_replace('_', ' ', $name);
            $name = str_replace('.', ' ', $name);
            $name = substr($name, 0, 50);
       

 $HTMLOUT.= "<div class='slide1' ".($r_t['free'] != 0 ? " id='green'" : ($r_t['free'] == 0 ? " id='red'" : "" ))."><a href='{$INSTALLER09['baseurl']}/details.php?id=$id'><img src='".htmlsafechars($poster)."' class='glossy tester1' alt='{$name}' title='{$name} - Seeders : {$seeders} - Leechers : {$leechers}'border='0' /></a></div>";
}
$HTMLOUT .='</div></div></div></div>';
if ($INSTALLER09['autoshout_on'] == 1) {
    autoshout($message);
    //ircbot($messages);
    $mc1->delete_value('shoutbox_');
}
    } else {
    //== If there are no recommended torrents
    if (empty($rec_torrents))
    $HTMLOUT .= "No torrents here yet !!</div></div></div></div><br />";
    }
    }

//==End
// End Class
// End File
