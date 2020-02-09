<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2018 U-232 CodeName Trinity                              |
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
    /** include css **/
    'css' => array(
	'bbcode',
        'details',
        'rating_style'
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
        'popup',
        'jquery.thanks',
        'wz_tooltip',
        'java_klappe',
        'balloontip',
        'shout',
        'thumbs',
        'sack'
    )
);
$HTMLOUT = $torrent_cache = '';
if (!isset($_GET['id']) || !is_valid_id($_GET['id'])) stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
$id = (int)$_GET["id"];


// zokii1983 delete pictures
 if (array_key_exists('delete_file', $_POST)) {
  $filename = $_POST['delete_file'];
  if (file_exists($filename)) {
	  $end = end((explode('/', rtrim($filename, '/'))));
	  $thumb = "pictures/".$id."/thumbs/".$end."";
    unlink($filename);
	unlink($thumb);
    $HTMLOUT .= ''.$filename.' has been sucessfully deleted';
	//$HTMLOUT .= 'File '.$thumb.' has been deleted';
  } else {
    $HTMLOUT .= 'Could not delete '.$filename.', file does not exist';
  }
}
//==pdq memcache slots
$slot = make_freeslots($CURUSER['id'], 'fllslot_');
$torrent['addedfree'] = $torrent['addedup'] = $free_slot = $double_slot = '';
if (!empty($slot)) foreach ($slot as $sl) {
    if ($sl['torrentid'] == $id && $sl['free'] == 'yes') {
        $free_slot = 1;
        $torrent['addedfree'] = $sl['addedfree'];
    }
    if ($sl['torrentid'] == $id && $sl['doubleup'] == 'yes') {
        $double_slot = 1;
        $torrent['addedup'] = $sl['addedup'];
    }
    if ($free_slot && $double_slot) break;
}
$categorie = genrelist();
foreach ($categorie as $key => $value) $change[$value['id']] = array(
    'id' => $value['id'],
    'name' => $value['name'],
    'image' => $value['image'],
    'min_class' => $value['min_class']
);
if (($torrents = $mc1->get_value('torrent_details_' . $id)) === false) {
    $tor_fields_ar_int = array(
        'id',
        'leechers',
        'seeders',
        'thanks',
        'comments',
        'owner',
        'size',
        'added',
        'views',
        'hits',
        'numfiles',
        'times_completed',
        'points',
        'last_reseed',
        'category',
        'free',
        'freetorrent',
        'silver',
        'rating_sum',
	'checked_when',
        'num_ratings',
        'mtime',
        'checked_when',
        'f_points'
    );
    $tor_fields_ar_str = array(
        'banned',
        'info_hash',
        'checked_by',
        'filename',
        'search_text',
        'name',
        'save_as',
        'visible',
        'type',
        'poster',
        'url',
        'anonymous',
        'allow_comments',
        'description',
        'nuked',
        'nukereason',
        'vip',
        'subs',
        'username',
        'newgenre',
        'release_group',
        'youtube',
        'tags',
        'user_likes'
    );
    $tor_fields = implode(', ', array_merge($tor_fields_ar_int, $tor_fields_ar_str));
    $result = sql_query("SELECT " . $tor_fields . ", (SELECT MAX(id) FROM torrents ) as max_id, (SELECT MIN(id) FROM torrents) as min_id, LENGTH(nfo) AS nfosz, IF(num_ratings < {$INSTALLER09['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $torrents = mysqli_fetch_assoc($result);
    foreach ($tor_fields_ar_int as $i) $torrents[$i] = (int)$torrents[$i];
    foreach ($tor_fields_ar_str as $i) $torrents[$i] = $torrents[$i];
    $mc1->cache_value('torrent_details_' . $id, $torrents, $INSTALLER09['expires']['torrent_details']);
}
$mc1->delete_value('torrent_details_' . $id);
   if ($change[$torrents['category']]['min_class'] > $CURUSER['class']) stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
//==
if (($torrents_ocelot = $mc1->get_value('torrent_ocelot_data_' . $id)) === false && OCELOT_TRACKER == true) {
    $torrents_ocelot = mysqli_fetch_assoc(sql_query("SELECT seeders, leechers, times_completed FROM torrents WHERE id =" . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $mc1->cache_value('torrent_ocelot_data_' . $id, $torrents_ocelot, $INSTALLER09['expires']['torrent_ocelot_data']);
}
//==
if (($torrents_txt = $mc1->get_value('torrent_details_txt' . $id)) === false) {
    $torrents_txt = mysqli_fetch_assoc(sql_query("SELECT descr FROM torrents WHERE id =" . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $mc1->cache_value('torrent_details_txt' . $id, $torrents_txt, $INSTALLER09['expires']['torrent_details_text']);
}
//Memcache Pretime
if (($pretime = $mc1->get_value('torrent_pretime_'.$id)) === false) {
    $prename = htmlsafechars($torrents['name']);
    $pre_q = sql_query("SELECT time FROM releases WHERE releasename = " . sqlesc($prename)) or sqlerr(__FILE__, __LINE__);
    $pret = mysqli_fetch_assoc($pre_q);
    $pretime['time'] = strtotime($pret['time']);
    $mc1->cache_value('torrent_pretime_'.$id, $pretime, $INSTALLER09['expires']['torrent_pretime']);
}
//==
if (isset($_GET["hit"])) {
    sql_query("UPDATE torrents SET views = views + 1 WHERE id =" . sqlesc($id));
    $update['views'] = ($torrents['views'] + 1);
    $mc1->begin_transaction('torrent_details_' . $id);
    $mc1->update_row(false, array(
        'views' => $update['views']
    ));
    $mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
    header("Location: {$INSTALLER09['baseurl']}/details.php?id=$id");
    exit();
}
$What_String = (OCELOT_TRACKER == true ? 'mtime' : 'last_action');
$What_String_Key = (OCELOT_TRACKER == true ? 'last_action_xbt_' : 'last_action_');
if (($l_a = $mc1->get_value($What_String_Key.$id)) === false) {
    $l_a = mysqli_fetch_assoc(sql_query('SELECT '.$What_String.' AS lastseed ' . 'FROM torrents ' . 'WHERE id = ' . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $l_a['lastseed'] = (int)$l_a['lastseed'];
    $mc1->add_value('last_action_' . $id, $l_a, 1800);
}
/** seeders/leechers/completed caches pdq**/
$torrents['times_completed'] = ((OCELOT_TRACKER === false || $torrents_ocelot['times_completed'] === false || $torrents_ocelot['times_completed'] === 0 || $torrents_ocelot['times_completed'] === false) ? $torrents['times_completed'] : $torrents_ocelot['times_completed']);
//==slots by pdq
$torrent['addup'] = get_date($torrent['addedup'], 'DATE');
$torrent['addfree'] = get_date($torrent['addedfree'], 'DATE');
$torrent['idk'] = (TIME_NOW + 14 * 86400);
$torrent['freeimg'] = '<img src="' . $INSTALLER09['pic_base_url'] . 'freedownload.gif" alt="" />';
$torrent['doubleimg'] = '<img src="' . $INSTALLER09['pic_base_url'] . 'doubleseed.gif" alt="" />';
$torrent['free_color'] = '#FF0000';
$torrent['silver_color'] = 'silver';
//==rep user query by pdq
if (($torrent_cache['rep'] = $mc1->get_value('user_rep_' . $torrents['owner'])) === false) {
    $torrent_cache['rep'] = array();
    $us = sql_query("SELECT reputation FROM users WHERE id =" . sqlesc($torrents['owner'])) or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($us)) {
        $torrent_cache['rep'] = mysqli_fetch_assoc($us);
        $mc1->add_value('user_rep_' . $torrents['owner'], $torrent_cache['rep'], 14 * 86400);
    }
}
$HTMLOUT.= "<script type='text/javascript'>
    /*<![CDATA[*/
	//var e = new sack();
function do_rate(rate,id,what) {
		var box = document.getElementById('rate_'+id);
		e.setVar('rate',rate);
		e.setVar('id',id);
		e.setVar('ajax','1');
		e.setVar('what',what);
		e.requestFile = 'rating.php';
		e.method = 'GET';
		e.element = 'rate_'+id;
		e.onloading = function () {
			box.innerHTML = 'Loading ...'
		}
		e.onCompletion = function() {
			if(e.responseStatus)
				box.innerHTML = e.response();
		}
		e.onerror = function () {
			alert('That was something wrong with the request!');
		}
		e.runAJAX();
}
/*]]>*/
</script>";
$owned = $moderator = 0;
if ($CURUSER["class"] >= UC_STAFF) $owned = $moderator = 1;
elseif ($CURUSER["id"] == $torrents["owner"]) $owned = 1;
if ($torrents["vip"] == "1" && $CURUSER["class"] < UC_VIP) stderr("VIP Access Required", "You must be a VIP In order to view details or download this torrent! You may become a Vip By Donating to our site. Donating ensures we stay online to provide you more Vip-Only Torrents!");
if (!$torrents || ($torrents["banned"] == "yes" && !$moderator)) stderr("{$lang['details_error']}", "{$lang['details_torrent_id']}");
if ($CURUSER["id"] == $torrents["owner"] || $CURUSER["class"] >= UC_STAFF) $owned = 1;
else $owned = 0;
$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if (empty($torrents["tags"])) {
    $keywords = "No Keywords Specified.";
} else {
    $tags = explode(",", $torrents['tags']);
    $keywords = "";
    foreach ($tags as $tag) {
        $keywords.= "<a href='browse.php?search=$tag&amp;searchin=all&amp;incldead=1'>" . htmlsafechars($tag) . "</a>,";
    }
    $keywords = substr($keywords, 0, (strlen($keywords) - 1));
}
if (isset($_GET["uploaded"])) {
    $HTMLOUT.= "<div data-closable class='alert-box callout success text-center'><h6><i class='fa fa-check'></i>{$lang['details_success']}</h6><button class='close-button' aria-label='Dismiss alert' type='button' data-close>
    <span aria-hidden='true'>&CircleTimes;</span>
  </button>";
    $HTMLOUT.= "<p>{$lang['details_start_seeding']}</p></div>\n";
    $HTMLOUT.= '<meta http-equiv="refresh" content="1;url=download.php?torrent=' . $id . '' . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . '" />';
} elseif (isset($_GET["edited"])) {
    $HTMLOUT.= "<div data-closable class='alert-box callout success text-center'><h6><i class='fa fa-check'></i>{$lang['details_success_edit']}</h6>
	<button class='close-button' aria-label='Dismiss alert' type='button' data-close>
    <span aria-hidden='true'>&CircleTimes;</span>
  </button></div>";
    if (isset($_GET["returnto"])) $HTMLOUT.= "<p><b>{$lang['details_go_back']}<a href='" . htmlsafechars($_GET["returnto"]) . "'>{$lang['details_whence']}</a>.</b></p>\n";
} elseif (isset($_GET["reseed"])) {
    $HTMLOUT.= "<div data-closable class='alert-box callout success text-center'>
		<h6>
			<i class='fa fa-check'></i>PM was sent! Now wait for a seeder !
		</h6>
		<button class='close-button' aria-label='Dismiss alert' type='button' data-close>
			<span aria-hidden='true'>&CircleTimes;</span>
		</button>
	</div>";
}
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (isset($_GET["checked"]) && $_GET["checked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = " . sqlesc($CURUSER['username']) . ",checked_when = ".TIME_NOW." WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
        $mc1->begin_transaction('torrent_details_' . $id);
        $mc1->update_row(false, array(
            'checked_by' => $CURUSER['username'],
			'checked_when' => TIME_NOW
        ));
        $mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
        $mc1->delete_value('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09['baseurl']}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a> was checked by {$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&checked=done#Success");
    } elseif (isset($_GET["rechecked"]) && $_GET["rechecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = " . sqlesc($CURUSER['username']) . ",checked_when = ".TIME_NOW." WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
        $mc1->begin_transaction('torrent_details_' . $id);
        $mc1->update_row(false, array(
            'checked_by' => $CURUSER['username'],
			'checked_when' => TIME_NOW
        ));
        $mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
        $mc1->delete_value('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09['baseurl']}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a> was re-checked by {$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&rechecked=done#Success");
    } elseif (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = '', checked_when='' WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
        $mc1->begin_transaction('torrent_details_' . $id);
        $mc1->update_row(false, array(
            'checked_by' => '',
            'checked_when' => ''
        ));
        $mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
        $mc1->delete_value('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09["baseurl"]}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a> was un-checked by {$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&clearchecked=done#Success");
    }
    if (isset($_GET["checked"]) && $_GET["checked"] == 'done') $HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>Successfully checked {$CURUSER['username']}!</a></h2></div>";
    if (isset($_GET["rechecked"]) && $_GET["rechecked"] == 'done') $HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>Successfully re-checked {$CURUSER['username']}!</a></h2></div>";
    if (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 'done') $HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>Successfully un-checked {$CURUSER['username']}!</a></h2></div>";
}
// end
/** free mod pdq **/
$HTMLOUT.= '<div id="balloon1" class="balloonstyle">
			Once chosen this torrent will be Freeleech ' . $torrent['freeimg'] . ' until ' . get_date($torrent['idk'], 'DATE') . ' and can be resumed or started over using the
			regular download link. Doing so will result in one Freeleech Slot being taken away from your total.
		</div>
        <div id="balloon2" class="balloonstyle">
			Once chosen this torrent will be Doubleseed ' . $torrent['doubleimg'] . ' until ' . get_date($torrent['idk'], 'DATE') . ' and can be resumed or started over using the
			regular download link. Doing so will result in one Freeleech Slot being taken away from your total.
		</div>
		<div id="balloon3" class="balloonstyle">
			Remember to show your gratitude and Thank the Uploader. <img src="' . $INSTALLER09['pic_base_url'] . 'smilies/smile1.gif" alt="" />
		</div>';
/** end **/
$url = "edit.php?id=" . (int)$torrents["id"];
if (isset($_GET["returnto"])) {
    $addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
    $url.= $addthis;
    $keepget = $addthis;
}
$editlink = "a href=\"$url\" class=\"sublink\"";
if (!($CURUSER["downloadpos"] == 0 && $CURUSER["id"] != $torrents["owner"] OR $CURUSER["downloadpos"] > 1)) {
    /** free mod by pdq **/
    //== Display the freeslots links etc.
    if ($free_slot && !$double_slot) {
        $HTMLOUT.= '<tr>
        <td align="right" class="heading">'.$lang['details_add_slots1'].'</td>
        <td align="left">' . $torrent['freeimg'] . ' <b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots2'].'</font></b>'.$lang['details_add_slots3'].'' . $torrent['addfree'] . '</td></tr>';
        $freeslot = ((!OCELOT_TRACKER && $CURUSER['freeslots'] >= 1) ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_zip = ((!OCELOT_TRACKER && $CURUSER['freeslots'] >= 1) ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double&amp;zip=1\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_text = ((!OCELOT_TRACKER && $CURUSER['freeslots'] >= 1) ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double&amp;text=1\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
    } elseif (!$free_slot && $double_slot) {
        $HTMLOUT.= '<tr>
        <td align="right" class="heading">'.$lang['details_add_slots1'].'</td>
        <td align="left">' . $torrent['doubleimg'] . ' <b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots8'].'</font></b>'.$lang['details_add_slots3'].'' . $torrent['addup'] . '</td></tr>';
        $freeslot = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_zip = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free&amp;zip=1\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_text = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free&amp;text=1\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
    } elseif ($free_slot && $double_slot) {
        $HTMLOUT.= '<tr>
        <td align="right" class="heading">'.$lang['details_add_slots1'].'</td>
        <td align="left">' . $torrent['freeimg'] . ' ' . $torrent['doubleimg'] . ' <b><font color="' . $torrent['free_color'] . '">'.$lang['details_add_slots9'].'</font></b>'.$lang['details_add_slots10'].'' . $torrent['addfree'] . ''.$lang['details_add_slots11'].'' . $torrent['addup'] . '</p></td></tr>';
        $freeslot = $freeslot_zip = $freeslot_text = '';
    } else {
    $freeslot = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>" . (!OCELOT_TRACKER ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>" : "" ) . "&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_zip = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free&amp;zip=1\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>" . (!OCELOT_TRACKER ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double&amp;zip=1\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>" : "" ) . "&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
        $freeslot_text = ($CURUSER['freeslots'] >= 1 ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=free&amp;text=1\" rel='balloon1' onclick=\"return confirm('".$lang['details_add_slots5f']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6f']}</b></font></a>" . (!OCELOT_TRACKER ? "&nbsp;&nbsp;<b>{$lang['details_add_slots4']}</b><a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;slot=double&amp;text=1\" rel='balloon2' onclick=\"return confirm('".$lang['details_add_slots5']."')\"><font color='" . $torrent['free_color'] . "'><b>{$lang['details_add_slots6']}</b></font></a>" : "") . "&nbsp;- " . htmlsafechars($CURUSER['freeslots']) . "".$lang['details_add_slots7']."" : "");
    }
    //==
    require_once MODS_DIR . 'free_details.php';   
$HTMLOUT .="<div class='row callout'>";
$prev_id = ($id - 1);
         $next_id = ($id + 1);
$s = htmlsafechars($torrents["name"], ENT_QUOTES);
$HTMLOUT.= "<div class='row'><h4 class='subheader'>$s</h4>";
if($torrents["id"] != $torrents["min_id"])
        $HTMLOUT .= "<a class='button float-left' href='details.php?id={$prev_id}'><b>[Prev Torrent]</b></a>";
        $HTMLOUT .= "<a class='button float-left' href='browse.php'><b>  [Return]</b></a>";
        if($torrents["id"] != $torrents["max_id"])
        $HTMLOUT .= "<a class='button float-right' href='details.php?id={$next_id}'><b>  [Next Torrent]</b></a>";
		$HTMLOUT.= "<a class='button float-right' href='random.php'>" . (!isset($_GET['random']) ? '[Random Any]' : '<span style="color:#3366FF;">[Random Any]</span>') . "</a>";
//Thumbs Up
if (($thumbs = $mc1->get_value('thumbs_up_' . $id)) === false) {
    $thumbs = mysqli_num_rows(sql_query("SELECT id, type, torrentid, userid FROM thumbsup WHERE torrentid = " . sqlesc($torrents['id'])));
    $thumbs = (int)$thumbs;
    $mc1->add_value('thumbs_up_' . $id, $thumbs, 0);
}
$HTMLOUT.= "<a class='button button-badge float-right' href=\"javascript:ThumbsUp('" . (int)$torrents['id'] . "')\">
	<i class='far fa-thumbs-up'></i><span class='badgeb badge-b alert'>" . $thumbs . "</span></a>";
//==
/*Tab selector begins*/
$HTMLOUT .="</div>";
$HTMLOUT.= "<ul class='tabs' data-tabs id='example-tabs'>
  <li class='tabs-title is-active'><a href='#tab_a' aria-selected='true'>Download torrent here</a></li>
  <li class='tabs-title'><a href='#tab_b'>Description and Similar Torrents</a></li>
  <li class='tabs-title'><a href='#tab_c' data-tabs-target='tab_c'>NFO and Other Information</a></li>";
 if ($CURUSER['class'] >= UC_POWER_USER) {  $HTMLOUT .= "<li class='tabs-title'><a href='#tab_d' data-tabs-target='tab_d'>Snatches</a></li>";}
$HTMLOUT .= "<li class='tabs-title'><a href='#tab_e' data-tabs-target='tab_e'>YouTube and Imdb</a></li>
</ul>";
$HTMLOUT .="<div class='tabs-content' data-tabs-content='example-tabs'>
        <div class='tabs-panel is-active' id='tab_a'>";
$HTMLOUT.="<br>  
	<div class='row'>
	<div class='medium-4 columns'>";
    $poster_url = ((empty($torrents['poster'])) ? $INSTALLER09['pic_base_url'] .'noposter.png' : htmlsafechars($torrents["poster"]));
    $HTMLOUT .= "<div class='details-poster' style='background-image:url({$poster_url});'></div> <!-- closing details poster -->";
//==09 Poster mod
$HTMLOUT .= "<div style='display:block;height:20px'></div>";
    $Free_Slot = $freeslot;
    $Free_Slot_Zip = $freeslot_zip;
    $Free_Slot_Text = $freeslot_text;
if (!empty($torrents["description"])) {
$HTMLOUT.= "<table class='table'><th class='text-center'>{$lang['details_small_descr']}</th><tr><td class='details-text-ellipsis'><i>" . htmlsafechars($torrents['description']) . "</i></td></tr></table>";
} else {
$HTMLOUT.= "<table class='table'><th class='text-center'>{$lang['details_small_descr']}</th><tr><td><i>No small description found</i></td></tr></table>";
}   
$HTMLOUT.= "</div><!-- closing col md 4 -->";
$HTMLOUT.= "<div class='medium-8 columns'>
	<table class='table'>
			<tr>
			<td align=\"right\" class=\"heading\" width=\"3%\">{$lang['details_download']}</td>
			<td align=\"left\" class='details-text-ellipsis'>
			<a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "\">&nbsp;<u>" . htmlsafechars($torrents["filename"]) . "</u></a>{$Free_Slot}
			</td>
			</tr>";
    /** end **/
    //==Torrent as zip by putyn
    $HTMLOUT.= "<tr>
		<td>{$lang['details_zip']}</td>
		<td align=\"left\" class='details-text-ellipsis'>
		<a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;zip=1\">&nbsp;<u>" . htmlsafechars($torrents["filename"]) . "</u></a>{$Free_Slot_Zip}</td></tr>";
    //==Torrent as text by putyn
    $HTMLOUT.= "<tr>
		<td>{$lang['details_text']}</td>
		<td align=\"left\" class='details-text-ellipsis'>
		<a class=\"index\" href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "&amp;text=1\">&nbsp;<u>" . htmlsafechars($torrents["filename"]) . "</u></a>{$Free_Slot_Text}</td></tr></table>";
    $HTMLOUT.= "
	<table class='table'>
		<tr>
		<td>{$lang['details_tags']}</td>
		<td align=\"left\">" . $keywords . "</td>
		</tr>";
    /**  Mod by dokty, rewrote by pdq  **/    
$my_points = 0;
    if (($torrent['torrent_points_'] = $mc1->get_value('coin_points_' . $id)) === false) {
        $sql_points = sql_query('SELECT userid, points FROM coins WHERE torrentid=' . sqlesc($id));
        $torrent['torrent_points_'] = array();
        if (mysqli_num_rows($sql_points) !== 0) {
            while ($points_cache = mysqli_fetch_assoc($sql_points)) $torrent['torrent_points_'][$points_cache['userid']] = $points_cache['points'];
        }
        $mc1->add_value('coin_points_' . $id, $torrent['torrent_points_'], 0);
    }
    $my_points = (isset($torrent['torrent_points_'][$CURUSER['id']]) ? (int)$torrent['torrent_points_'][$CURUSER['id']] : 0);
    $HTMLOUT.= '<tr>
		<td class="heading" valign="top" align="right">Karma Points</td>
		<td valign="top" align="left"><b>In total ' . (int)$torrents['points'] . ' Karma Points given to this torrent of which ' . $my_points . ' from you.<br /><br />
		<a href="coins.php?id=' . $id . '&amp;points=10"><img src="' . $INSTALLER09['pic_base_url'] . '10coin.png" alt="10" title="10 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=20"><img src="' . $INSTALLER09['pic_base_url'] . '20coin.png" alt="20" title="20 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=50"><img src="' . $INSTALLER09['pic_base_url'] . '50coin.png" alt="50" title="50 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=100"><img src="' . $INSTALLER09['pic_base_url'] . '100coin.png" alt="100" title="100 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=200"><img src="' . $INSTALLER09['pic_base_url'] . '200coin.png" alt="200" title="200 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=500"><img src="' . $INSTALLER09['pic_base_url'] . '500coin.png" alt="500" title="500 Points" /></a>&nbsp;&nbsp;
		<a href="coins.php?id=' . $id . '&amp;points=1000"><img src="' . $INSTALLER09['pic_base_url'] . '1000coin.png" alt="1000" title="1000 Points" /></a></b>&nbsp;&nbsp;
		<br />By clicking on the coins you can give Karma Points to the uploader of this torrent.</td></tr>';
//=== Torrent free-o-meter (.) (.) == set the target amount for free leech
//=== get total points
//$mc1->delete_value('free_for_day_counter_'.$id);
//$mc1->delete_value('torrent_free_points_' . $id);
    if(($tor_free_counter = $mc1->get_value('free_for_day_counter_'.$id)) === false) {
    $total = sql_query('SELECT f_points FROM torrents WHERE id=' . sqlesc($id));
    $total_row = mysqli_fetch_assoc($total);
    $percent = number_format($total_row['f_points'] / $INSTALLER09['torrent']['free_target'] * 100, 2);
    $mc1->cache_value('free_for_day_counter_'.$id, $percent, $INSTALLER09['expires']['free_for_day_counter']);
    } else
    $percent = $tor_free_counter;
        
			switch ($percent)
			{
	   			case $percent >= 100:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_12.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break; 
				   case $percent >= 80:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_10.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
				   case $percent >= 70:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_8.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
				   case $percent >= 50:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_6.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
				   case $percent >= 40:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_5.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
				   case $percent >= 30:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_4.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
				   case $percent >= 20:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_3.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;				
				   case $percent >= 10:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_2.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
				break;
                                   default:
			$image_to_use = '<img src="'.$INSTALLER09['pic_base_url'].'bar_0.png" alt="'.$percent.'% so far!" title="Torrent free-o-meter '.$percent.'% full!!!" align="middle" />';
                                break;
			}   
$i_gave_points = 0;
    if (($torrent['torrent_ffd_points_'] = $mc1->get_value('torrent_free_points_' . $id)) === false) {
        $sql_ffd_points = sql_query('SELECT userid, tf_points FROM coins WHERE torrentid=' . sqlesc($id));
        $torrent['torrent_ffd_points_'] = array();
        if (mysqli_num_rows($sql_ffd_points) !== 0) {
            while ($ffd_cache = mysqli_fetch_assoc($sql_ffd_points)) $torrent['torrent_ffd_points_'][$ffd_cache['userid']] = $ffd_cache['tf_points'];
        }
        $mc1->add_value('torrent_free_points_' . $id, $torrent['torrent_ffd_points_'], $INSTALLER09['expires']['torrent_free_points']);
    }
    $i_gave_points = (isset($torrent['torrent_ffd_points_'][$CURUSER['id']]) ? (int)$torrent['torrent_ffd_points_'][$CURUSER['id']] : 0);
    $HTMLOUT.= '<tr>
		<td class="heading" valign="top" align="right"><i><u>Free for one day</u></i></td><br /><br />
                <td class="heading" valign="top" align="left"><b>'.$INSTALLER09['torrent']['free_target'].'</b> points required to make this torrent free for 24 hours !<br /><br />'.$image_to_use.'<br /><br />
		<b>In total ' . (int)$torrents['f_points'] . ' Karma Points have been given to this torrent for 24 hours freeleech of which ' . $i_gave_points . ' from you.</b><br /><br />
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=10">10</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=20">20</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=50">50</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=100">100</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=200">200</a>&nbsp;&nbsp;
		<a href="tf_points.php?id=' . $id . '&amp;tf_points=500">500</a>&nbsp;&nbsp;
		<br /><br />By clicking on the amounts you can give Karma Points making this torrent free for one day.<br /><br /></td></tr>';
// end
//==Vote for freeleech
/*
        $elapsed = $wait1 = 0;
        $freepollby = $freepollby1 = '';
        if ($CURUSER["class"] < UC_VIP) {
        $ratio1 = member_ratio($CURUSER['uploaded'], $CURUSER['downloaded']);
        if ($ratio1 < 0.55) $wait1 = 5;
        elseif ($ratio1 < 0.45) $wait1 = 10;
        elseif ($ratio1 < 0.35) $wait1 = 15;
        elseif ($ratio1 < 0.25) $wait1 = 20;
        elseif ($ratio1 < 0.15) $wait1 = 25;
        else $wait1 = 0;
        }
        $elapsed = floor((TIME_NOW - $torrents["added"]) / 3600);
        $torrentid = (int)$torrents["id"];
        $freepoll_sql = sql_query("SELECT userid FROM freepoll where torrentid=".sqlesc($torrentid)) or sqlerr(__FILE__, __LINE__);
        $freepoll_all = mysqli_num_rows($freepoll_sql);
        if ($freepoll_all) {
        while ($rows_t = mysqli_fetch_array($freepoll_sql)) {
        $freepoll_userid = (int)$rows_t["userid"];
        $user_sql = sql_query("SELECT id, username FROM users where id=".sqlesc($freepoll_userid)) or sqlerr(__FILE__, __LINE__);
        $rows_a = mysqli_fetch_array($user_sql);
        $username_t = htmlsafechars($rows_a["username"]);
        $freepollby1 = $freepollby1 . "<a href='{$INSTALLER09['baseurl']}/userdetails.php?id={$freepoll_userid}'>{$username_t}</a>, ";
        }
        $t_userid = (int)$CURUSER["id"];
        $tsqlcount = sql_query("SELECT COUNT(id) as tcount FROM freepoll where torrentid=".sqlesc($torrentid)) or sqlerr(__FILE__, __LINE__);
        $tass = mysqli_fetch_assoc($tsqlcount);
        $freepollcount = (int)$tass["tcount"];
        $tsql = sql_query("SELECT COUNT(id) FROM freepoll where torrentid=".sqlesc($torrentid)." and userid=".sqlesc($t_userid)) or sqlerr(__FILE__, __LINE__);
        $trows = mysqli_fetch_array($tsql);
        $t_ab = $trows[0];
        if ($t_ab == 0) {
        $freepollby = $freepollby . " <form action=\"freepoll.php\" method=\"post\">
        <input type=\"submit\" name=\"submit\" value=\"{$lang['details_freepoll_vote']}\" />
        <input type=\"hidden\" name=\"torrentid\" value=\"{$torrentid}\" />
        </form>";
        } else {
        $t_userid == (int)$torrents["owner"];
        $freepollby = $freepollby . " <form action=\"freepoll.php\" method=\"post\">
        <input type=\"submit\" name=\"submit\" value=\"{$lang['details_freepoll_voted']}\" disabled=\"disabled\" />
        <input type=\"hidden\" name=\"torrentid\" value=\"{$torrentid}\" />
        </form>";
        }
        } else {
        $freepollcount = 0;
        $freepollby = "
        <form action=\"freepoll.php\" method=\"post\">
        <input type=\"submit\" name=\"submit\" value=\"{$lang['details_freepoll_vote']}\" />
        <input type=\"hidden\" name=\"torrentid\" value=\"{$torrentid}\" />
        </form>";
        }
        $count = $INSTALLER09['votes_required'] - $freepollcount;
        if ($torrents["free"] == 0) {
        $HTMLOUT .= tr("{$lang['details_freepoll_poll']}", "".htmlsafechars($freepollcount)." {$lang['details_freepoll_mess1']}".htmlsafechars($count) . " {$lang['details_freepoll_mess2']}", 1);
        }
        if ($elapsed < $wait1 AND ($torrents["free"]) == 0)
        if ($t_ab >= 1 AND ($torrents["free"]) == 0) {
        if ($freepollcount < $INSTALLER09['votes_required'])
        $HTMLOUT .="<tr><td class='rowhead'><div align='right'>{$lang['details_freepoll_fvote']}</div></td><td align='left'>{$freepollby}</td></tr>";
        } 
        else
        $HTMLOUT .="<tr><td class='rowhead'><div align='right'>{$lang['details_freepoll_sorry']}</div></td><td align='left'>{$lang['details_freepoll_mess3']} <a href='rules.php'><font color='red'><b>".number_format($wait1 - $elapsed)." {$lang['details_freepoll_hours']}</b></font></a> {$lang['details_freepoll_mess4']}</td></tr>";
        elseif ($torrents["free"] == 0)
        $HTMLOUT .="<tr><td class='rowhead'><div align='right'>{$lang['details_freepoll_vote']}</div></td><td align='left'>{$freepollby}Each vote costs <b>{$INSTALLER09['torrent']['freepoll_points']}</b> karma points</td></tr>";
        $tid = (int)$torrents["id"];
        $Free_Dur = (TIME_NOW + 604800); //== 1 Week
        if ($freepollcount == $INSTALLER09['votes_required'] && $torrents["free"] >= 1) {
        $HTMLOUT .="<tr><td class='rowhead'><div align='right'>{$lang['details_freepoll_poll']}</div></td><td align='left'>{$lang['details_freepoll_mess5']}</td></tr>";
        }
        if ($freepollcount == $INSTALLER09['votes_required'] && $torrents["free"] == 0) {
        sql_query("UPDATE torrents SET free = ".($Free_Dur)." WHERE torrents.id=" .sqlesc($tid)) or sqlerr(__FILE__, __LINE__);
        $mc1->begin_transaction('torrent_details_' . $torrentid);
$mc1->update_row(false, array(
    'free' => $Free_Dur
));
        $mc1->commit_transaction($INSTALLER09['expires']['torrent_details']);
        //$mc1->delete_value('torrent_details_' . $torrentid);
        }
        //if ($freepollcount < $INSTALLER09['votes_required'] AND $torrents["free"] == 0)
        //$HTMLOUT .="<tr><td class='rowhead'><div align='right'>{$lang['details_freepoll_poll']}</div></td><td align='left'>{$lang['details_freepoll_mess6']}</td></tr>";
*/
        //==End
      /** pdq's ratio afer d/load **/
    $downl = ($CURUSER["downloaded"] + $torrents["size"]);
    $sr = $CURUSER["uploaded"] / $downl;
    switch (true) {
    case ($sr >= 4):
        $s = "w00t";
        break;

    case ($sr >= 2):
        $s = "grin";
        break;

    case ($sr >= 1):
        $s = "smile1";
        break;

    case ($sr >= 0.5):
        $s = "noexpression";
        break;

    case ($sr >= 0.25):
        $s = "sad";
        break;

    case ($sr > 0.00):
        $s = "cry";
        break;

    default;
    $s = "w00t";
    break;
}
$sr = floor($sr * 1000) / 1000;
$sr = "<font color='" . get_ratio_color($sr) . "'>" . number_format($sr, 3) . "</font>&nbsp;&nbsp;<img src=\"pic/smilies/{$s}.gif\" alt=\"\" />";
if ($torrents['free'] >= 1 || $torrents['freetorrent'] >= 1 || $isfree['yep'] || $free_slot OR $double_slot != 0 || $CURUSER['free_switch'] != 0) {
    $HTMLOUT.= "<tr>
		<td align='right' class='heading'>Ratio After Download</td>
		<td class='details-text-ellipsis'><del>{$sr}&nbsp;&nbsp;Your new ratio if you download this torrent.</del> <b><font size='' color='#FF0000'>[FREE]</font></b>&nbsp;(Only upload stats are recorded)</td></tr>";
} else {
    $HTMLOUT.= "<tr>
		<td align='right' class='heading'>Ratio After Download</td>
		<td>{$sr}&nbsp;&nbsp;Your new ratio if you download this torrent.</td></tr>";
}
//==End
function hex_esc($matches) {
	return sprintf("%02x", ord($matches[0]));
}
$HTMLOUT .= tr("{$lang['details_info_hash']}", '<div class="details-text-ellipsis">' .preg_replace_callback('/./s', "hex_esc", hash_pad($torrents["info_hash"])) . '</div>',true);
    $HTMLOUT.= "</table>\n";
} else {
    $HTMLOUT.="<div><div class='container-fluid'><table class='table'><tr><td align='right' class='heading'>Download Disabled!!</td><td>Your not allowed to download presently !!</td></tr></table></div></div>";
}
$HTMLOUT.= "</div><!-- closnig col-md-8 --> </div><!-- closing row -->";
$HTMLOUT .="</div><!-- closing tab pane -->";
$HTMLOUT .="<div class='tabs-panel' id='tab_b'>";
$HTMLOUT.= "<br><div class='row'>
<div class='large-12 columns'>";
if (!empty($torrents_txt["descr"])) {
    $HTMLOUT.= "
	<table class='table'>
	<tr><td><b>{$lang['details_description']}</b></td></tr>
	<tr>
	<td>
	" . str_replace(array("\n","  ") , array("<br />\n","&nbsp; ") , format_comment($torrents_txt["descr"])) . "<!--</div>--></td></tr></table>";
}

$HTMLOUT .= '</div><!-- closing col md 12 --></div><!-- closing row -->';
$HTMLOUT.= "<div class='row'>
<div class='large-12 columns'>";
//== Similar Torrents mod
$searchname = substr($torrents['name'], 0, 6);
$query1 = str_replace(" ", ".", sqlesc("%" . $searchname . "%"));
$query2 = str_replace(".", " ", sqlesc("%" . $searchname . "%"));
if (($sim_torrents = $mc1->get_value('similiar_tor_' . $id)) === false) {
    $r = sql_query("SELECT id, name, size, added, seeders, leechers, category FROM torrents WHERE name LIKE {$query1} AND id <> " . sqlesc($id) . " OR name LIKE {$query2} AND id <> " . sqlesc($id) . " ORDER BY name") or sqlerr(__FILE__, __LINE__);
    while ($sim_torrent = mysqli_fetch_assoc($r)) $sim_torrents[] = $sim_torrent;
    $mc1->cache_value('similiar_tor_' . $id, $sim_torrents, 86400);
}
if (count($sim_torrents) > 0) {
    $sim_torrent = "<table class='table'>\n" . "
		<thead>
		<tr>
		<th>Type</th>
		<th>Name</th>
		<th>Size</th>
		<th>Added</th>
		<th>Seeders</th>
		<th>Leechers</th>
		</tr>
		</thead>\n";
    if ($sim_torrents) {
        foreach ($sim_torrents as $a) {
            $sim_tor['cat_name'] = htmlsafechars($change[$a['category']]['name']);
            $sim_tor['cat_pic'] = htmlsafechars($change[$a['category']]['image']);
            $cat = "<img src=\"pic/caticons/{$CURUSER['categorie_icon']}/{$sim_tor['cat_pic']}\" alt=\"{$sim_tor['cat_name']}\" title=\"{$sim_tor['cat_name']}\" />";
            $name = htmlsafechars(CutName($a["name"]));
            $seeders = (int)$a["seeders"];
            $leechers = (int)$a["leechers"];
            $added = get_date($a["added"], 'DATE', 0, 1);
            $sim_torrent.= "<tr>
			<td class='one' style='padding: 0px; border: none' width='40px'>{$cat}</td>
			<td class='one'><a href='details.php?id=" . (int)$a["id"] . "&amp;hit=1'><b>{$name}</b></a></td>
			<td class='one' style='padding: 1px' align='center'>" . mksize($a['size']) . "</td>
			<td class='one' style='padding: 1px' align='center'>{$added}</td>
			<td class='one' style='padding: 1px' align='center'>{$seeders}</td>
			<td class='one' style='padding: 1px' align='center'>{$leechers}</td></tr>\n";
        }
        $sim_torrent.= "</table>";
 $HTMLOUT.= "<table class='table'><tr><td align='right' class='heading'>{$lang['details_similiar']}<a href=\"javascript: klappe_news('a5')\"><img border=\"0\" src=\"pic/plus.png\" id=\"pica5".(int)$a['id']."\" alt=\"[Hide/Show]\" title=\"[Hide/Show]\" /></a><div id=\"ka5\" style=\"display: none;\"><br />$sim_torrent</div></td></tr></table>";
    } else {
        if (empty($sim_torrents)) $HTMLOUT.= "
		<table class='table'>\n
		<tr>
		<td>Nothing similiar to " . htmlsafechars($torrents["name"]) . " found.</td>
		</tr></table>";
    }
}
$HTMLOUT .= '</div><!-- closing col md 12 --></div><!-- closing row -->';
$HTMLOUT.= "</div><!-- closing tab pane -->";
$HTMLOUT .="<div class='tabs-panel' id='tab_c'>";
$HTMLOUT.= "<br>
<div class='row'>
<div class='large-4 columns'>
<table align='center' class='table table-bordered span3'>\n";
//==subs by putyn
if (in_array($torrents["category"], $INSTALLER09['movie_cats']) && !empty($torrents["subs"])) {
    $HTMLOUT.= "<tr>
		<td class='rowhead'>Subtitles</td>
		<td align='left'>";
    $subs_array = explode(",", $torrents["subs"]);
    foreach ($subs_array as $k => $sid) {
        require_once (CACHE_DIR . 'subs.php');
        foreach ($subs as $sub) {
            if ($sub["id"] == $sid) $HTMLOUT.= "<img border=\"0\" width=\"25px\" style=\"padding:3px;\"src=\"" . htmlsafechars($sub["pic"]) . "\" alt=\"" . htmlsafechars($sub["name"]) . "\" title=\"" . htmlsafechars($sub["name"]) . "\" />";
        }
    }
    $HTMLOUT.= "</td></tr>\n";
}
if ($CURUSER["class"] >= UC_POWER_USER && $torrents["nfosz"] > 0) $HTMLOUT.= "<tr><td class='rowhead'>{$lang['details_nfo']}</td><td align='left'><a href='viewnfo.php?id=" . (int)$torrents['id'] . "'><b>{$lang['details_view_nfo']}</b></a> (" . mksize($torrents["nfosz"]) . ")</td></tr>\n";
if ($torrents["visible"] == "no") $HTMLOUT.= tr("{$lang['details_visible']}", "<b>{$lang['details_no']}</b>{$lang['details_dead']}", 1);
if ($moderator) $HTMLOUT.= tr("{$lang['details_banned']}", $torrents["banned"]);
if ($torrents["nuked"] == "yes") $HTMLOUT.= "<tr><td class='rowhead'><b>Nuked</b></td><td align='left'><img src='{$INSTALLER09['pic_base_url']}nuked.gif' alt='Nuked' title='Nuked' /></td></tr>\n";
if (!empty($torrents["nukereason"])) $HTMLOUT.= "<tr><td class='rowhead'><b>Nuke-Reason</b></td><td align='left'>" . htmlsafechars($torrents["nukereason"]) . "</td></tr>\n";
$torrents['cat_name'] = htmlsafechars($change[$torrents['category']]['name']);
if (isset($torrents["cat_name"])) $HTMLOUT.= tr("{$lang['details_type']}", htmlsafechars($torrents["cat_name"]));
else $HTMLOUT.= tr("{$lang['details_type']}", "None");
$HTMLOUT.= tr("Rating", getRate($id, "torrent") , 1);
// --------------- likes start------
        $att_str = '';
        if (!empty($torrents['user_likes'])) {
            $likes = explode(',', $torrents['user_likes']);
        } else {
            $likes = '';
        }
        if (!empty($likes) && count(array_unique($likes)) > 0) {
            if (in_array($CURUSER['id'], $likes)) {
                if (count($likes) == 1) {
                    $att_str = jq('You like this');
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = jq('You and&nbsp;') . ((count(array_unique($likes)) - 1) == '1' ? '1 other person likes this' : (count($likes) - 1) . '&nbsp;others like this');
                }
            } elseif (!(in_array($CURUSER['id'], $likes))) {
                if (count(array_unique($likes)) == 1) {
                    $att_str = '1 other person likes this';
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = (count(array_unique($likes))) . '&nbsp;others like this';
                }
            }
        }
        $wht = ((!empty($likes) && count(array_unique($likes)) > 0 && in_array($CURUSER['id'], $likes)) ? 'unlike' : 'like');

$HTMLOUT.= tr("Likes","<span id='mlike' data-com='" . (int)$torrents["id"] . "' class='details {$wht}'>[" . ucfirst($wht) . "]</span><span class='tot-" . (int)$torrents["id"] . "' data-tot='" . (!empty($likes) && count(array_unique($likes)) > 0 ? count(array_unique($likes)) : '') . "'>&nbsp;{$att_str}</span>" , 1);
$HTMLOUT.= tr("{$lang['details_last_seeder']}", "{$lang['details_last_activity']}" . get_date($l_a['lastseed'], '', 0, 1));
$HTMLOUT.= tr("{$lang['details_size']}", mksize($torrents["size"]) . " (" . number_format($torrents["size"]) . " {$lang['details_bytes']})");
$HTMLOUT.= tr("{$lang['details_added']}", get_date($torrents['added'], "{$lang['details_long']}"));
//Display pretime
    if ($pretime['time'] == '0') {
    $prestatement = "No pretime found.";
    } else {
    $prestatement = get_pretime(time() -  $pretime['time']) . " ago<br />Uploaded " . get_pretime($torrents['added'] - $pretime['time']) . " after pre.";
    }
$HTMLOUT.="<tr><td align='right' class='heading'>Pre Time</td><td width='99%' align='left'>". $prestatement."</td></tr>";
$HTMLOUT.= tr("{$lang['details_views']}", (int)$torrents["views"]);
$HTMLOUT.= tr("{$lang['details_hits']}", (int)$torrents["hits"]);
$Ocelot_Or_Default = (OCELOT_TRACKER == true ? 'snatches_ocelot.php?id=' : 'snatches.php?id=');
$HTMLOUT.= tr("{$lang['details_snatched']}", ($torrents["times_completed"] > 0 ? "<a href='{$INSTALLER09["baseurl"]}/{$Ocelot_Or_Default}{$id}'>{$torrents['times_completed']} {$lang['details_times']}</a>" : "0 {$lang['details_times']}") , 1);
$HTMLOUT.= "
<script type='text/javascript'>
function showme() {
    document.getElementById('show').innerHTML = '{$CURUSER['username']} is viewing details for torrent {$INSTALLER09['baseurl']}/details.php?id=" . (int)$torrents['id'] . "\"';
}
</script>
<tr><td class='rowhead'>Status update</td><td><button type='button' class='small button'  id='show' onclick='showme()'>DO IT!</button></td></tr>";
$HTMLOUT.= "</table></div><!-- closing col md 4 -->
<div class='large-8 columns'>
<table align='center' class='table table-bordered'>";
//==Report Torrent Link
$HTMLOUT.= tr("Report Torrent", "<form action='report.php?type=Torrent&amp;id=$id' method='post'><input class='small button' type='submit' name='submit' value='Report This Torrent' />&nbsp;&nbsp;<strong><em class='label label-primary'>For breaking the&nbsp;<a href='rules.php'>rules</a></em></strong></form>", 1);
//== Tor Reputation by pdq
if ($torrent_cache['rep'] && $INSTALLER09['rep_sys_on']) {
    $torrents = array_merge($torrents, $torrent_cache['rep']);
    $member_reputation = get_reputation($torrents, 'torrents', $torrents['anonymous']);
    $HTMLOUT.= '<tr><td class="heading" valign="top" align="right" width="1%">Reputation</td>
		<td align="left" width="99%">' . $member_reputation . ' (counts towards uploaders Reputation)<br /></td></tr>';
}
//==Anonymous
$rowuser = (isset($torrents['username']) ? ("<a href='userdetails.php?id=" . (int)$torrents['owner'] . "'><b>" . htmlsafechars($torrents['username']) . "</b></a>") : "{$lang['details_unknown']}");
$uprow = (($torrents['anonymous'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $torrents['owner'] != $CURUSER['id'] ? '' : $rowuser . ' - ') . "<i>{$lang['details_anon']}</i>" : $rowuser);
if ($owned) $uprow.= " $spacer<$editlink><b>{$lang['details_edit']}</b></a>";
$HTMLOUT.= tr("Upped by", $uprow, 1);
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (!empty($torrents['checked_by'])) {
        if (($checked_by = $mc1->get_value('checked_by_' . $id)) === false) {
            $checked_by = mysqli_fetch_assoc(sql_query("SELECT id FROM users WHERE username=" . sqlesc($torrents['checked_by']))) or sqlerr(__FILE__, __LINE__);
            $mc1->add_value('checked_by_' . $id, $checked_by, 30 * 86400);
        }
        $HTMLOUT.= "<tr>
	<td class='rowhead'>Checked by</td>
	<td align='left'>
<p><a class='label label-primary' href='{$INSTALLER09["baseurl"]}/userdetails.php?id=" . (int)$checked_by['id'] . "'>
	<strong>" . htmlsafechars($torrents['checked_by']) . "</strong></a></p>
	<p><a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;rechecked=1'>
        <small><em class='label label-primary'><strong>[Re-Check this torrent]</strong></em></small></a> 
	<a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;clearchecked=1'>
	<small><em class='label label-primary'><strong>[Un-Check this torrent]</strong></em></small></a></p>
	&nbsp;<p><em class=label label-primary'>* STAFF Eyes Only *</em>
	".(isset($torrents["checked_when"]) && $torrents["checked_when"] > 0 ? "<strong>Checked When : ".get_date($torrents["checked_when"],'DATE',0,1)."</strong>":'' )."</td></tr>";
    } else {
        $HTMLOUT.= "<tr><td class='rowhead'>Checked by</td><td align='left'><em class='label label-primary'><strong>NOT CHECKED!</strong></em> 
       <a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;checked=1'>
       <em class='label label-primary'><small><strong>[Check this torrent]</strong></small></em></a>&nbsp;<em class='label label-primary'><strong>* STAFF Eyes Only *</strong></em></p></td></tr>";
    }
}
// end
if ($torrents["type"] == "multi") {
    if (!isset($_GET["filelist"])) $HTMLOUT.= tr("{$lang['details_num_files']}<a href=\"./filelist.php?id=$id\" class=\"sublink\"><br />{$lang['details_list']}</a>", (int)$torrents["numfiles"] . " files", 1);
    else {
        $HTMLOUT.= tr("{$lang['details_num-files']}", (int)$torrents["numfiles"] . "{$lang['details_files']}", 1);
    }
}

if(OCELOT_TRACKER == true) {
$HTMLOUT.= tr("{$lang['details_peers']}<br /><a href=\"./peerlist_ocelot.php?id=$id#seeders\" class=\"sublink\">{$lang['details_list']}</a>", (int)$torrents_ocelot["seeders"] . "{$lang['details_add_sd']}" . (int)$torrents_ocelot["leechers"] . "{$lang['details_add_lc']}" . ((int)$torrents_ocelot["seeders"] + (int)$torrents_ocelot["leechers"]) . "{$lang['details_peer_total']}", 1);
} else {
$HTMLOUT.= tr("{$lang['details_peers']}<br /><a href=\"./peerlist.php?id=$id#seeders\" class=\"sublink\">{$lang['details_list']}</a>", (int)$torrents["seeders"] . "{$lang['details_add_sd']}" . (int)$torrents["leechers"] . "{$lang['details_add_lc']}" . ((int)$torrents["seeders"] + (int)$torrents["leechers"]) . "{$lang['details_peer_total']}", 1);
}
//==putyns thanks mod
$HTMLOUT.= tr($lang['details_thanks'], '
	  <script type="text/javascript">
		/*<![CDATA[*/
		$(document).ready(function() {
			var tid = '.$id.';
			show_thanks(tid);
		});
		
		/*]]>*/
		
		</script>
		<noscript><iframe id="thanked" src ="thanks.php?torrentid='.$id.'" style="width:500px;height:50px;border:none;overflow:auto;">
	  <p>Your browser does not support iframes. And it has Javascript disabled!</p>
	  
	  </iframe></noscript>
	  <div id="thanks_holder"></div>', 1);
//==End
//==09 Reseed by putyn
$next_reseed = 0;
if ($torrents["last_reseed"] > 0) $next_reseed = ($torrents["last_reseed"] + 172800); //add 2 days
$reseed = "<form method=\"post\" action=\"./takereseed.php\">
	  <select name=\"pm_what\">
	  <option value=\"last10\">last10</option>
	  <option value=\"owner\">uploader</option>
	  </select>
	  <input class\"button\" type=\"submit\"  " . (($next_reseed > TIME_NOW) ? "disabled='disabled'" : "") . " value=\"SendPM\" />
	  <input type=\"hidden\" name=\"uploader\" value=\"" . (int)$torrents["owner"] . "\" />
	  <input type=\"hidden\" name=\"reseedid\" value=\"$id\" />
	  </form>";
$HTMLOUT.= tr("Request reseed", $reseed, 1);
//==End
$HTMLOUT.= "</table></div><!-- closing col md 8 --></div><!-- closing row -->";
$HTMLOUT.= "</div> <!-- closing tab pane -->";
if ($CURUSER['class'] >= UC_POWER_USER) { 
$HTMLOUT .="<div class='tabs-panel' id='tab_d'>";
//== Snatched Torrents mod
$What_Table = (OCELOT_TRACKER == true ? 'xbt_snatched' : 'snatched');
$What_cache = (OCELOT_TRACKER == true ? 'snatched_tor_xbt_' : 'snatched_tor_');
$What_Value = (OCELOT_TRACKER == true ? 'WHERE tstamp != "0"' : 'WHERE complete_date != "0"');
$Which_ID = (OCELOT_TRACKER == true ? 'fid' : 'id');
$Which_T_ID = (OCELOT_TRACKER == true ? 'fid' : 'torrentid');
$Which_Key_ID = (OCELOT_TRACKER == true ? 'snatched_count_xbt_' : 'snatched_count_');
$keys['Snatched_Count'] = $Which_Key_ID . $id;

    if (($Row_Count = $mc1->get_value($keys['Snatched_Count'])) === false) {
$Count_Q = sql_query("SELECT COUNT($Which_ID) FROM $What_Table $What_Value AND $Which_T_ID =" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$Row_Count = mysqli_fetch_row($Count_Q);
$mc1->cache_value($keys['Snatched_Count'], $Row_Count, $INSTALLER09['expires']['details_snatchlist']);
}
$Count = $Row_Count[0];
$perpage = 15;
$pager = pager($perpage, $Count, "details.php?id=$id&amp;");
$HTMLOUT.= "
<h3 class='text-center'>{$lang['details_add_snatch1']}<a href='{$INSTALLER09['baseurl']}/details.php?id=" . (int)$torrents['id'] . "'>" . htmlsafechars($torrents['name']) . "</a><br />{$lang['details_add_snatch2']}{$Row_Count['0']}{$lang['details_add_snatch3']}" . ($Row_Count[0] == 1 ? "" : "es") . "</h3>\n";

if (($Detail_Snatch = $mc1->get_value($What_cache . $id)) === false) {
    if (OCELOT_TRACKER == true) {
     //== \\0//
      $Main_Q = sql_query("SELECT x.*, x.uid AS xu, torrents.username as username1, users.username as username2, users.paranoia, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, x.uid, s.* FROM xbt_files_users AS x INNER JOIN users ON x.uid = users.id INNER JOIN torrents ON x.fid = torrents.id INNER JOIN xbt_snatched AS s ON s.fid=x.fid WHERE s.uid = x.uid AND s.fid = " . sqlesc($id) . " ORDER BY s.fid DESC " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
} else {
      $Main_Q = sql_query("SELECT s.*, s.userid AS su, torrents.username as username1, users.username as username2, users.paranoia, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, timesann, owner FROM snatched AS s INNER JOIN users ON s.userid = users.id INNER JOIN torrents ON s.torrentid = torrents.id WHERE complete_date !=0 AND torrentid = " . sqlesc($id) . " ORDER BY complete_date DESC " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
}
    while ($snatched_torrent = mysqli_fetch_assoc($Main_Q)) $Detail_Snatch[] = $snatched_torrent;
    $mc1->cache_value($What_cache . $id, $Detail_Snatch, $INSTALLER09['expires']['details_snatchlist']);
}

if ((count($Detail_Snatch) > 0 && $CURUSER['class'] >= UC_STAFF)) {
    if ($Count > $perpage) $HTMLOUT.= $pager['pagertop'];
 //== \\0//
 if (OCELOT_TRACKER == true) {
    $snatched_torrent = "
<table class='table'>
<tr>
<td class='colhead' align='left'>{$lang['details_snatches_username']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_uploaded']}</td>
" . ($INSTALLER09['ratio_free'] ? "" : "<td class='colhead' align='right'>{$lang['details_snatches_downloaded']}</td>") . "
<td class='colhead' align='right'>{$lang['details_snatches_ratio']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_seedtime']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_leechtime']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_lastaction']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_completedat']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_announced']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_active']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_completed']}</td>
</tr>\n";
    } else {
    $snatched_torrent = "
<table class='table'>
<tr>
<td class='colhead' align='left'>{$lang['details_snatches_username']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_connectable']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_uploaded']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_upspeed']}</td>
" . ($INSTALLER09['ratio_free'] ? "" : "<td class='colhead' align='right'>{$lang['details_snatches_downloaded']}</td>") . "
" . ($INSTALLER09['ratio_free'] ? "" : "<td class='colhead' align='right'>{$lang['details_snatches_downspeed']}</td>") . "
<td class='colhead' align='right'>{$lang['details_snatches_ratio']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_completed']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_seedtime']}</td>
<td class='colhead' align='right'>{$lang['details_snatches_leechtime']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_lastaction']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_completedat']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_client']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_port']}</td>
<td class='colhead' align='center'>{$lang['details_snatches_announced']}</td>
</tr>\n";
}

    if ($Detail_Snatch) {
        foreach ($Detail_Snatch as $D_S) {
          
if (OCELOT_TRACKER == true) {
           //== \\0//
           $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"], 3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
           $active = ($D_S['active'] == 1 ? $active = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_tick.gif' alt='Yes' title='Yes' />" : $active = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_cross.gif' alt='No' title='No' />");
           $completed = ($D_S['tstamp'] >= 1 ? $completed = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_tick.gif' alt='Yes' title='Yes' />" : $completed = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_cross.gif' alt='No' title='No' />");
           $snatchuserocelot = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=" . (int)$D_S['uid'] . "'><b>" . htmlsafechars($D_S['username2']) . "</b></a>") : "{$lang['details_snatches_unknown']}");
           $username_ocelot = (($D_S['anonymous2'] == 'yes' OR $D_S['paranoia'] >= 2) ? ($CURUSER['class'] < UC_STAFF && $D_S['uid'] != $CURUSER['id'] ? '' : $snatchuserocelot . ' - ') . "<i>{$lang['details_snatches_anon']}</i>" : $snatchuserocelot);
           $snatched_torrent.= "<tr>
                                 <td align='left'><font size='2%'>{$username_ocelot}</font></td>
                                 <td align='right'><font size='2%'>" . mksize($D_S["uploaded"]) . "</font></td>
  " . ($INSTALLER09['ratio_free'] ? "" : "<td align='right'><font size='2%'>" . mksize($D_S["downloaded"]) . "</font></td>") . "
                                 <td align='right'><font size='2%'>" . htmlsafechars($ratio) . "</font></td>
                                 <td align='right'><font size='2%'>" . mkprettytime($D_S["seedtime"]) . "</font></td>
                                 <td align='right'><font size='2%'>" . mkprettytime($D_S["leechtime"]) . "</font></td>
                                 <td align='center'><font size='2%'>" . get_date($D_S["mtime"], '', 0, 1) . "</font></td>
                                 <td align='center'><font size='2%'>" . get_date($D_S["completedtime"], '', 0, 1) . "</font></td>
                                 <td align='center'><font size='2%'>" . (int)$D_S["announced"] . "</font></td>
                                 <td align='center'><font size='2%'>" . $active . "</font></td>
                                 <td align='center'><font size='2%'>" . $completed . "</font></td>
        </tr>\n";

} else {
 $upspeed = ($D_S["upspeed"] > 0 ? mksize($D_S["upspeed"]) : ($D_S["seedtime"] > 0 ? mksize($D_S["uploaded"] / ($D_S["seedtime"] + $D_S["leechtime"])) : mksize(0)));
           $downspeed = ($D_S["downspeed"] > 0 ? mksize($D_S["downspeed"]) : ($D_S["leechtime"] > 0 ? mksize($D_S["downloaded"] / $D_S["leechtime"]) : mksize(0)));
    $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"], 3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
           $completed = sprintf("%.2f%%", 100 * (1 - ($D_S["to_go"] / $D_S["size"])));
           $snatchuser = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=" . (int)$D_S['userid'] . "'><b>" . htmlsafechars($D_S['username2']) . "</b></a>") : "{$lang['details_snatches_unknown']}");
           $username = (($D_S['anonymous2'] == 'yes' OR $D_S['paranoia'] >= 2) ? ($CURUSER['class'] < UC_STAFF && $D_S['userid'] != $CURUSER['id'] ? '' : $snatchuser . ' - ') . "<i>{$lang['details_snatches_anon']}</i>" : $snatchuser);
$snatched_torrent.= "<tr>
                                 <td align='left'><font size='2%'>{$username}</font></td>
                                 <td align='center'><font size='2%'>" . ($D_S["connectable"] == "yes" ? "<font color='green'>{$lang['details_add_yes']}</font>" : "<font color='red'>{$lang['details_add_no']}</font>") . "</font></td>
                                 <td align='right'><font size='2%'>" . mksize($D_S["uploaded"]) . "</font></td>
                                 <td align='right'><font size='2%'>" . htmlsafechars($upspeed) . "/s</font></td>
  " . ($INSTALLER09['ratio_free'] ? "" : "<td align='right'><font size='2%'>" . mksize($D_S["downloaded"]) . "</font></td>") . "
  " . ($INSTALLER09['ratio_free'] ? "" : "<td align='right'><font size='2%'>" . htmlsafechars($downspeed) . "/s</font></td>") . "
                                 <td align='right'><font size='2%'>" . htmlsafechars($ratio) . "</font></td>
                                 <td align='right'><font size='2%'>" . htmlsafechars($completed) . "</font></td>
                                 <td align='right'><font size='2%'>" . mkprettytime($D_S["seedtime"]) . "</font></td>
                                 <td align='right'><font size='2%'>" . mkprettytime($D_S["leechtime"]) . "</font></td>
                                 <td align='center'><font size='2%'>" . get_date($D_S["last_action"], '', 0, 1) . "</font></td>
                                 <td align='center'><font size='2%'>" . get_date($D_S["complete_date"], '', 0, 1) . "</font></td>
                                 <td align='center'><font size='2%'>" . htmlsafechars($D_S["agent"]) . "</font></td>
                                 <td align='center'><font size='2%'>" . (int)$D_S["port"] . "</font></td>
                                 <td align='center'><font size='2%'>" . (int)$D_S["timesann"] . "</font></td>
        </tr>\n";
        }

}

$snatched_torrent.= "</table>";
$HTMLOUT.= "
<p class='text-center'>{$lang['details_add_snatch4']}</p>
<div class='panel-body'>
        <div class='panel-group' id='accordion'>
            <div id='collapseOne' class='panel-collapse collapse in'>
                <div class='panel'>
                    <div class='panel-body'>$snatched_torrent</div></div></div>";
} else {
 if (empty($Detail_Snatch)) $HTMLOUT.= "<p class='text-center'>{$lang['details_add_snatch4']}</p>
<div class='panel-body'>
        <div class='panel-group' id='accordion'>
            <div id='collapseOne' class='panel-collapse collapse in'>
                <div class='panel'>
                    <div class='panel-body'><h3 class=text-center'>{$lang['details_add_snatch5']}</h3></div></div></div>";
   }
}
$HTMLOUT .="</div><!-- closing panel-group --></div><!-- closing panel body -->";
$HTMLOUT.= "</div><!-- closing tab pane -->";
}
//==
$HTMLOUT .="<div class='tabs-panel' id='tab_e'>";
$HTMLOUT.= "<br />
<div class='row'>
<div class='large-12 columns'>
<table class='table'>\n";
if (!empty($torrents['youtube'])) {
$HTMLOUT.= tr($lang['details_youtube'], '<object type="application/x-shockwave-flash" style="width:560px; height:340px;" data="' . str_replace('watch?v=', 'v/', $torrents['youtube']) . '"><param name="movie" value="' . str_replace('watch?v=', 'v/', $torrents['youtube']) . '" /></object><br /><a 
href=\'' . htmlsafechars($torrents['youtube']) . '\' target=\'_blank\'>' . $lang['details_youtube_link'] . '</a>', 1);
} else {
$HTMLOUT.= "<tr><td>No youtube data found</td></tr>";
}
$HTMLOUT.= "</table>
        </div><!-- closing col md 12 -->
     </div><!-- closing row -->";
$HTMLOUT.= "<div class='row'>
<div class='large-12 columns'>
<table align='center' class='table table-bordered'>\n";
//== tvmaze by whocares converted from former tvrage functions by pdq/putyn
$torrents['tvcats'] = array(
    5
); // change these to match your TV categories
if (in_array($torrents['category'], $torrents['tvcats'])) {
    $tvmaze_info = tvmaze($torrents);
    if ($tvmaze_info) $HTMLOUT.= tr($lang['details_tvrage'], $tvmaze_info, 1);
}
if ((in_array($torrents['category'], $INSTALLER09['movie_cats'])) && $torrents['url'] != '') {
$imdb = '';
$imdb_info['id'] = $imdb_info['title'] = $imdb_info['orig_title'] = $imdb_info['year'] = $imdb_info['rating'] = $imdb_info['votes'] = $imdb_info['gen'] = $imdb_info['runtime'] = $imdb_info['country'] = $imdb_info['lanuage'] = $imdb_info['director'] = $imdb_info['produce'] = $imdb_info['write'] = $imdb_info['compose'] = $imdb_info['plotoutline'] = $imdb_info['plot'] = $imdb_info['trailers'] = $imdb_info['comment'] = "";

$imdb_info = get_imdb($torrents['url']);

//<strong><font color=\"red\">Country: </font></strong>".$imdb_info['country']."
$imdb .= "<div class='imdb'>
<div class='imdb_info'>
<strong><font color=\"red\">Year: </font></strong> ".$imdb_info['year']." 
<strong><font color=\"red\">Genre: </font></strong> ".$imdb_info['gen']."
<strong><font color=\"red\">Runtime: </font></strong> ".$imdb_info['runtime']." Mins  
  
<strong><font color=\"red\">Rating: </font></strong>".$imdb_info['rating']."  
<br />
<strong><font color=\"red\">Director: </font></strong>".$imdb_info['director']." 
<strong><font color=\"red\">Producers: </font></strong> ".$imdb_info['produce']."  
<br />
<strong><font color=\"red\">Writters: </font></strong>".$imdb_info['write']."  
<strong><font color=\"red\">Music: </font></strong>".$imdb_info['compose']." 
</div><!-- closing imdb info -->
<br />";
$imdb.= "
<div class='imdb_summary'>
<div style=\"background-color:transparent; border: none; width:100%;\"><div style=\"text-transform: uppercase; border-bottom: 1px solid #CCCCCC; margin-bottom: 3px; font-size: 0.8em; color: red; font-weight: bold; display: block;\"><span onclick=\"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; this.innerHTML = '<b>Summary: </b><a href=\'#\' onclick=\'return false;\'>hide</a>'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerHTML = '<b>Summary: </b><a href=\'#\' onclick=\'return false;\'>show</a>'; }\" ><font color='red'><b>Summary: </b></font><a href=\"#\" onclick=\"return false;\">show</a></span></div><div class=\"quotecontent\"><div style=\"display: none;\"><div style='background-color:transparent;width:100%;overflow: auto'>";
$imdb.= "".$imdb_info['plotoutline']."";
$imdb.="</div></div></div><!-- closing quote --></div></div><!-- closing imdb summary -->";

$imdb.= "<div class='imdb_plot'>
<div style=\"background-color:transparent; border: none; width:100%;\"><div style=\"text-transform: uppercase; border-bottom: 1px solid #CCCCCC; margin-bottom: 3px; font-size: 0.8em; color: red; font-weight: bold; display: block;\"><span onclick=\"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; this.innerHTML = '<b>Plot: </b><a href=\'#\' onclick=\'return false;\'>hide</a>'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerHTML = '<b>Plot: </b><a href=\'#\' onclick=\'return false;\'>show</a>'; }\" ><font color='red'><b>Plot: </b></font><a href=\"#\" onclick=\"return false;\">show</a></span></div><div class=\"quotecontent\"><div style=\"display: none;\"><div style='background-color:transparent;width:100%;overflow: auto'>";
$imdb.= "".strip_tags($imdb_info['plot'])."";
$imdb.="</div></div></div></div></div><!-- closing plot -->";

$imdb.= "<div class='imdb_trailers'>
<div style=\"background-color:transparent; border: none; width:100%;\"><div style=\"text-transform: uppercase; border-bottom: 1px solid #CCCCCC; margin-bottom: 3px; font-size: 0.8em; color: red; font-weight: bold; display: block;\"><span onclick=\"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; this.innerHTML = '<b>trailers </b><a href=\'#\' onclick=\'return false;\'>hide</a>'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerHTML = '<b>trailers </b><a href=\'#\' onclick=\'return false;\'>show</a>'; }\" ><font color='red'><b>trailers </b></font><a href=\"#\" onclick=\"return false;\">show</a></span></div><div class=\"quotecontent\"><div style=\"display: none;\"><div style='background-color:transparent;width:100%;overflow: auto'>";
$imdb.= "<a href=\"movietrailer.php?movie=".$imdb_info['title']."&amp;year=".$imdb_info['year']."\" onclick=\"return popitup('movietrailer.php?movie=".$imdb_info['title']."&amp;year=".$imdb_info['year']."')\"	><span class='imdb_titles'>View Trailer</span></a>
";
$imdb.="</div></div></div></div></div><!-- closing trailers -->";

//Below was added here, but thought better in bittorrent.php where the IMDB function run.  Making sure variables are set right there seems much more sane
//isset($imdb_info['comment']) ?: $imdb_info['comment'] = 'None Available';
$imdb.= "<div class='imdb_comments'>
<div style=\"background-color:transparent; border: none; width:100%;\"><div style=\"text-transform: uppercase; border-bottom: 1px solid #CCCCCC; margin-bottom: 3px; font-size: 0.8em; color: red; font-weight: bold; display: block;\"><span onclick=\"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; this.innerHTML = '<b>Comments: </b><a href=\'#\' onclick=\'return false;\'>hide</a>'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerHTML = '<b>Comments: </b><a href=\'#\' onclick=\'return false;\'>show</a>'; }\" ><font color='red'><b>Comments: </b></font><a href=\"#\" onclick=\"return false;\">show</a></span></div><div class=\"quotecontent\"><div style=\"display: none;\"><div style='background-color:transparent;width:100%;overflow: auto'>";
$imdb.= "".strip_tags($imdb_info['comment'])."";
$imdb.="</div></div></div></div></div><!-- closing comments -->";
$imdb .="</div><!-- closing imdb -->";
$HTMLOUT.= tr('Auto imdb', $imdb, 1);
}
if (empty($tvmaze_info) && empty($imdb) && in_array($torrents['category'], array_merge($INSTALLER09['movie_cats'], $INSTALLER09['tv_cats']))) $HTMLOUT.= "<tr><td colspan='2'>No Imdb or TVMaze info.</td></tr>";
$HTMLOUT.= "</table>
     </div><!-- closig col md 12 -->
     </div><!-- closing row -->
     </div><!-- closing tab pane -->
<div align='center'>";
$HTMLOUT .="</div><!-- closing center --></div><!-- closing tab content -->";
$HTMLOUT .="</div><!-- end of container -->";

/// zokii1983 add pictures customised bt swizzles
$HTMLOUT .= "<div class='container'><div class='row' style='background:transparent !important;'><div class='large-12 columns'>";
$HTMLOUT .= "<div class='panel panel-default'>";
$HTMLOUT .= "<div class='panel-heading'><h2 class='text-center'>SCREENSHOTS</h2></div>";
$HTMLOUT .= "<div class='panel-body'>";
if ($CURUSER['class'] >= UC_UPLOADER) {
$HTMLOUT .= '<div class="screenup_bg">
<form text-center" name="addImages" id="addImagesForm" action="imageUpload.php?id='.$id.'" method="post" enctype="multipart/form-data">   
<div class="row">
<div class="large-2 columns"><label for="images"><b>Select Images</b></label></div>
<div class="col-md-6"><input class="/*form-control*/" type="file" id="upload" name="upload[]" multiple=""></div>
<div class="col-md-3 col-md-pull-0"><button type="submit" class="small button">Upload</button></div>
</div>
</form></div><br>';
}

/// zokii1983 display pictures customised by swizzles

$HTMLOUT.="<table class='table table-bordered'><tr style='background:rgba(0,0,0,.5);'>";
$files = glob("pictures/".$id."/*.*");

for ($i=0; $i<count($files); $i++) {
$HTMLOUT.="<td class='screenup_col text-center'>";
    $image = $files[$i];
	$end = end((explode('/', rtrim($image, '/'))));

    $HTMLOUT .= '<!--<a href="'.$image .'">--><img src="' . $INSTALLER09["baseurl"] . '/pictures/'.$id.'/thumbs/'.$end.'""><!--</a>-->';
	if ($CURUSER['class'] >= UC_UPLOADER) {
$HTMLOUT .= '<form method="post">
  <input type="hidden" value="'.$image .'" name="delete_file">
  <input type="submit" value="Delete image">
</form>';
}
$HTMLOUT.="</td>";
}
$HTMLOUT.= "</tr></table>";

//Modal for all pictures
$files2 = glob("pictures/".$id."/*.*");
$image2 = $files2;
if (!empty($files2)) { 
$HTMLOUT.="<div class='row'><div class='large-12 columns text-center'><a href='#myModal1' class='small button' data-toggle='modal'>To see all the full size images click here</a></div></div><br><br>";
$HTMLOUT.='<!--begin modal window-->
<div class="modal fade" id="myModal1">
<div class="modal-dialog" style="width:95% !important;">
<div class="modal-content">
<div class="modal-header">
<div class="text-center">SCREENSHOTS</div>
</div>';

$HTMLOUT .= '<div class="modal-body">     
  <div id="lightbox" class="carousel slide" data-ride="carousel"><ul class="carousel-indicators"> ';
$count = 1;         
 foreach ($image2 as $newimage){
if ($count == 1) $HTMLOUT .= '<li class="active" data-target="#lightbox">';
else $HTMLOUT .= '<li data-target="#lightbox">';
$HTMLOUT .='</li>';     
$count++;
}
$HTMLOUT .= '</ul>';
$HTMLOUT .= '<div class="carousel-inner">';
                $count = 1;
                foreach ($image2 as $newimage){
if ($count == 1) $HTMLOUT .= '<div class="item active">';
else $HTMLOUT .= '<div class="item">';
$HTMLOUT .= '<img src="'.$newimage.'"  class="img-responsive det_carousel"></div>';     
              $count++;                  
              }
                
            $HTMLOUT .= '       
</div><!--carousel-inner-->
     <a class="left carousel-control" href="#lightbox" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="right carousel-control" href="#lightbox" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!--lightbox div-->
<br><button class="small button close" type="button" data-dismiss="modal">Close&nbsp;&times;</button> <br> 
</div>';
 $HTMLOUT .= '</div><!--end carousel-->
</div><!--end modal-body-->
</div><!--end modal-content-->
</div><!--end modal-dialogue-->
</div><!--end myModal-->';
}else{
$HTMLOUT.= '<h3 class="text-center">NO SCREENSHOTS HAVE BEEN UPLOADED</h3>';
 $HTMLOUT .= '</div><!--end carousel-->
</div><!--end modal-body-->
</div><!--end modal-content-->
</div><!--end modal-dialoge-->
</div><!--end myModal-->';
}

//== End of screenshots
$HTMLOUT.= "<hr /><br /> 
<h2 class='text-center'>{$lang['details_comments']}<a href='details.php?id=$id'>" . htmlsafechars($torrents["name"], ENT_QUOTES) . "</a></h2>";
$HTMLOUT.= "<div class='container'><div class='row'>
<div class='large-2 columns'></div>
<div class='large-12 columns'>
<p><a name='startcomments'></a></p>
    <form name='comment' method='post' action='comment.php?action=add&amp;tid=$id'>
    <table align='center'>
    <tr>
    <td align='center'><b>{$lang['details_quick_comment']}</b></td></tr>
    <tr><td align='center'>
    <textarea name='body'></textarea>
    <input type='hidden' name='tid' value='" . htmlsafechars($id) . "' /><br />
    <a href=\"javascript:SmileIT(':-)','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/smile1.gif' alt='Smile' title='Smile' /></a> 
    <a href=\"javascript:SmileIT(':smile:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/smile2.gif' alt='Smiling' title='Smiling' /></a> 
    <a href=\"javascript:SmileIT(':-D','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/grin.gif' alt='Grin' title='Grin' /></a> 
    <a href=\"javascript:SmileIT(':lol:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/laugh.gif' alt='Laughing' title='Laughing' /></a> 
    <a href=\"javascript:SmileIT(':w00t:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/w00t.gif' alt='W00t' title='W00t' /></a> 
    <a href=\"javascript:SmileIT(':blum:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/blum.gif' alt='Rasp' title='Rasp' /></a> 
    <a href=\"javascript:SmileIT(';-)','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/wink.gif' alt='Wink' title='Wink' /></a> 
    <a href=\"javascript:SmileIT(':devil:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/devil.gif' alt='Devil' title='Devil' /></a> 
    <a href=\"javascript:SmileIT(':yawn:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/yawn.gif' alt='Yawn' title='Yawn' /></a> 
    <a href=\"javascript:SmileIT(':-/','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/confused.gif' alt='Confused' title='Confused' /></a> 
    <a href=\"javascript:SmileIT(':o)','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/clown.gif' alt='Clown' title='Clown' /></a> 
    <a href=\"javascript:SmileIT(':innocent:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/innocent.gif' alt='Innocent' title='innocent' /></a> 
    <a href=\"javascript:SmileIT(':whistle:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/whistle.gif' alt='Whistle' title='Whistle' /></a> 
    <a href=\"javascript:SmileIT(':unsure:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/unsure.gif' alt='Unsure' title='Unsure' /></a> 
    <a href=\"javascript:SmileIT(':blush:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/blush.gif' alt='Blush' title='Blush' /></a> 
    <a href=\"javascript:SmileIT(':hmm:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/hmm.gif' alt='Hmm' title='Hmm' /></a> 
    <a href=\"javascript:SmileIT(':hmmm:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/hmmm.gif' alt='Hmmm' title='Hmmm' /></a> 
    <a href=\"javascript:SmileIT(':huh:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/huh.gif' alt='Huh' title='Huh' /></a> 
    <a href=\"javascript:SmileIT(':look:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/look.gif' alt='Look' title='Look' /></a> 
    <a href=\"javascript:SmileIT(':rolleyes:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes' /></a> 
    <a href=\"javascript:SmileIT(':kiss:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/kiss.gif' alt='Kiss' title='Kiss' /></a> 
    <a href=\"javascript:SmileIT(':blink:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/blink.gif' alt='Blink' title='Blink' /></a> 
    <a href=\"javascript:SmileIT(':baby:','comment','body')\"><img border='0' src='{$INSTALLER09['pic_base_url']}smilies/baby.gif' alt='Baby' title='Baby' /></a><br />
    <input class='small button' type='submit' value='Submit' /></td></tr></table></form></div><!-- closing col md 8 --></div><!-- closing row --></div><!-- closing container -->";
if ($torrents["allow_comments"] == "yes" || $CURUSER['class'] >= UC_STAFF && $CURUSER['class'] <= UC_MAX) {
    $HTMLOUT.= "\n";
} else {
    $HTMLOUT.= "
	<p><table align='center' class='table table-bordered'>
	<tr>
	<td><a name='startcomments'>&nbsp;</a><b>{$lang['details_com_disabled']}</b></td>
	</tr>
        </table></p>
     </div>
     </div><div class='row'><div class='col-md-1'></div><div class='col-md-10'>\n";
    echo stdhead("{$lang['details_details']}\"" . htmlsafechars($torrents["name"], ENT_QUOTES) . "\"", true, $stdhead) . $HTMLOUT . stdfoot(true, $stdfoot);
    die();
}
$commentbar = " 
<div class='row'>
<div class='large-2 columns'></div>
<div class='large-4 columns'>
<div class='content'><br><p align='center' ><a  class='index' href='comment.php?action=add&amp;tid=$id'>{$lang['details_add_comment']}</a>
    <br /><a class='index' href='{$INSTALLER09['baseurl']}/takethankyou.php?id=" . $id . "'>
    <img src='{$INSTALLER09['pic_base_url']}smilies/thankyou.gif' alt='Thanks' title='Thank You' border='0' /></a></p></div>
     </div>
   <div class='row'>
<div class='large-2 columns'></div>
<div class='col-md-8'>\n";
$count = (int)$torrents['comments'];
if (!$count) {
    $HTMLOUT.= "
<div class='container'>
<div class='row'>
<div class='col-md-6 col-md-offset-5'>
<h2>{$lang['details_no_comment']}</h2>\n";
} else {
    $perpage = 15;
    $pager = pager($perpage, $count, "details.php?id=$id&amp;", array(
        'lastpagedefault' => 1
    )); 
    $subres = sql_query("SELECT comments.id, comments.text, comments.user_likes, comments.user, comments.torrent, comments.added, comments.anonymous, comments.editedby, comments.editedat, comments.edit_name, users.warned, users.enabled, users.chatpost, users.leechwarn, users.pirate, users.king, users.perms, users.avatar, users.av_w, users.av_h, users.offavatar, users.warned, users.reputation, users.opt1, users.opt2, users.mood, users.username, users.title, users.class, users.donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = " . sqlesc($id) . " ORDER BY comments.id " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
    $allrows = array();
    while ($subrow = mysqli_fetch_assoc($subres)) $allrows[] = $subrow;
    $HTMLOUT.="
     </div>
     </div>
     </div>
<div class='row'>
<div class='col-md-3'></div>
<div class='col-md-8'>";
$HTMLOUT.="<br><div class='col-sm-offset-3'><div style='display:inline-block;width:0%;'></div><button type='button' class='small button' data-toggle='collapse' data-target='#dropdown'>Open/Close comments</button></div><br><div id='dropdown' class='collapse in'>";    
$HTMLOUT.= $commentbar;    
    $HTMLOUT.= $pager['pagertop'];
    $HTMLOUT.= commenttable($allrows);
    $HTMLOUT.= $pager['pagerbottom'];
    $HTMLOUT.="</div></div></div></div><br>";
}
 $HTMLOUT.="</div></div><div class='row'><div class='col-md-1'></div><div class='col-md-10'>";
//////////////////////// HTML OUTPUT ////////////////////////////
?>