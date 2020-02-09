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
function linkcolor($num)
{
    if (!$num) return "red";
    return "pink";
}
function readMore($text, $char, $link)
{
    return (strlen($text) > $char ? substr(htmlsafechars($text) , 0, $char - 1) . "...<a href='$link'>Read more...</a>" : htmlsafechars($text));
}
function torrenttable($res, $variant = "index")
{
    global $INSTALLER09, $CURUSER, $lang, $free, $mc1;
    require_once (INCL_DIR . 'bbcode_functions.php');
    require_once (CLASS_DIR . 'class_user_options_2.php');
    $htmlout = $prevdate = $nuked = $free_slot = $freetorrent = $free_color = $slots_check = $double_slot = $private = $newgenre = $oldlink = $char = $description = $smalldescr_reveal = $type = $sort = $row = $youtube = $youtube_yes = $youtube_no = '';
    $count_get = $wait = 0;
    if ($CURUSER["class"] < UC_VIP && $INSTALLER09['wait_times'] == 1)
    {
      $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
      $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
      if ($ratio < 0.5 || $gigs < 5) $wait = 48;
      elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
      elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
      elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
      else $wait = 0;
    }

    /** ALL FREE/DOUBLE **/
    foreach ($free as $fl) {
        switch ($fl['modifier']) {
        case 1:
            $free_display = '[Free]';
            break;
        case 2:
            $free_display = '[Double]';
            break;
        case 3:
            $free_display = '[Free and Double]';
            break;
        case 4:
            $free_display = '[Silver]';
            break;
        }
        $slot = make_freeslots($CURUSER['id'], 'fllslot_');
        $book = make_bookmarks($CURUSER['id'], 'bookmm_');
        $all_free_tag = ($fl['modifier'] != 0 && ($fl['expires'] > TIME_NOW || $fl['expires'] == 1) ? ' <a class="info" href="#">
            <b>' . $free_display . '</b> 
            <span>' . ($fl['expires'] != 1 ? '
            Expires: ' . get_date($fl['expires'], 'DATE') . '<br />
            (' . mkprettytime($fl['expires'] - TIME_NOW) . ' to go)</span></a><br />' : 'Unlimited</span></a><br />') : '');
    }
    $oldlink = array();
    foreach ($_GET as $key => $var) {
        if (in_array($key, array(
            'sort',
            'type'
        ))) continue;
        if (is_array($var)) {
            foreach ($var as $s_var) $oldlink[] = sprintf('%s=%s', urlencode($key) . '%5B%5D', urlencode($s_var));
        } else
            $oldlink[] = sprintf('%s=%s', urlencode($key), urlencode($var));
    }
    $oldlink = !empty($oldlink) ? join('&amp;', array_map('htmlsafechars', $oldlink)) .'&amp;' : '';   
    $links = array(
        'link1',
        'link2',
        'link3',
        'link4',
        'link5',
        'link6',
        'link7',
        'link8',
        'link9',
	'link10'
    );
    $i = 1;
    foreach ($links as $link) {
        if (isset($_GET['sort']) && $_GET['sort'] == $i) $$link = (isset($_GET['type']) && $_GET['type'] == 'desc') ? 'asc' : 'desc';
        else $$link = 'desc';
        $i++;
    }
    $htmlout.= "<div class='row'>
   <table class='responsive-card-table unstriped'>
   <thead>
   <tr>
   <th>{$lang["torrenttable_type"]}</th>
   <th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=1&amp;type={$link1}'>{$lang["torrenttable_name"]}</a></th>
   <th><i class='fas fa-file-archive' aria-hidden='true'></i></th>";
    $htmlout.= ($variant == 'index' ? "<th><a href='{$INSTALLER09['baseurl']}/bookmarks.php'><i class='fa fa-bookmark' aria-hidden='true'></i></a></th>" : '');
    if ($variant == "mytorrents") {
        $htmlout.= "<th>{$lang["torrenttable_edit"]}</th>";
        $htmlout.= "<th>{$lang["torrenttable_visible"]}</th>";
    }
    $htmlout.= "<th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=2&amp;type={$link2}'><i class='fas fa-copy' aria-hidden='true'></i></a></th>
   <th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=3&amp;type={$link3}'><i class='fa fa-comments' aria-hidden='true'></i></a></th>
   <th class='text-center' aria-describedby='addedHelpText'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=4&amp;type={$link4}'><i class='far fa-clock'></i></a></th>
   ".($INSTALLER09['wait_times'] == 1 ? "<th>{$lang["torrenttable_ttl"]}</th>" : "")."
   <th class='text-center' aria-describedby='sizeHelpText'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=5&amp;type={$link5}'><i class='fas fa-chart-pie'></a></th>
   <th aria-describedby='completedHelpText'><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=6&amp;type={$link6}'><i class='fas fa-sync-alt'></i></a></th>
   <th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=7&amp;type={$link7}'><font color='#5da423'><i class='fas fa-arrow-alt-circle-up'></i></font></a></th>
   <th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=8&amp;type={$link8}'><font color='red'><i class='fas fa-arrow-alt-circle-down'></i></font></a></th>";
    if ($variant == 'index') 
		$htmlout.= "<th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=9&amp;type={$link9}'>{$lang["torrenttable_uppedby"]}</a></th>";
    if ($CURUSER['class'] >= UC_STAFF)  {
		$htmlout .= "<th>Tools</th>";
    }
    $htmlout.= "
<th><a href='{$_SERVER["PHP_SELF"]}?{$oldlink}sort=10&amp;type={$link10}'>Health</a></th></tr></thead>";
    $categories = genrelist();
    foreach ($categories as $key => $value) $change[$value['id']] = array(
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
        'min_class' => $value['min_class']
    );
    while ($row = mysqli_fetch_assoc($res)) {
    //==
	/*
        if ($CURUSER['opt2'] & user_options_2::SPLIT) {
            if (get_date($row['added'], 'DATE') == $prevdate) 
				$cleandate = '';
            else 
				$htmlout.= "<tbody><tr><td><h4 class='label label-info'><b>{$lang['torrenttable_upped']} ".get_date($row['added'], 'DATE')."</b></h4></td></tr></tbody>";
            $prevdate = get_date($row['added'], 'DATE');
        }
		*/
        $row['cat_name'] = htmlsafechars($change[$row['category']]['name']);
        $row['cat_pic'] = htmlsafechars($change[$row['category']]['image']);
        $row['min_class'] = htmlsafechars($change[$row['category']]['min_class']);
        /** Freeslot/doubleslot in Use **/
        $id = (int)$row["id"];
        foreach ($slot as $sl) $slots_check = ($sl['torrentid'] == $id && $sl['free'] == 'yes' OR $sl['doubleup'] == 'yes');
        if ($row["sticky"] == "yes") 
			$htmlout.= "";
        else 
			//$htmlout.= '<tr><p class="' . (($free_color && $all_free_tag != '') || ($row['free'] != 0) || $slots_check ? 'freeleech_color' : 'browse_color') . '"></p>';
		$htmlout.= "<tbody><tr>";
		
		$htmlout.= "<td data-label='{$lang["torrenttable_type"]}'>";
        if (isset($row["cat_name"])) {
            $htmlout.= "<a href='browse.php?cat=" . (int)$row['category'] . "'>";
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "") 
				$htmlout.= "<img border='0' src='{$INSTALLER09['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/{$row['cat_pic']}' alt='{$row['cat_name']}'>";
            else {
                $htmlout.= htmlsafechars($row["cat_name"]);
            }
            $htmlout.= "</a>";
        } else {
            $htmlout.= "-";
        }
        $htmlout.= "</td>";
        $dispname = htmlsafechars($row["name"]);
		$smalldescr_reveal = '<a data-toggle="smalldescrModal' . (int)$row["id"]. '"><i class="fas fa-plus-square" style="color:#5DA423"></i></a>
		<div class="reveal" id="smalldescrModal' . (int)$row["id"]. '" data-reveal>
			<i>[' . htmlsafechars($row['description']) . ']</i>
			<button class="close-button" data-close aria-label="Close modal" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';
        $smalldescr = (!empty($row['description']) ? $smalldescr_reveal : "");
        $checked = ((!empty($row['checked_by']) && $CURUSER['class'] >= UC_USER) ? "&nbsp;<img src='{$INSTALLER09['pic_base_url']}mod.gif' width='15' border='0' alt='Checked - by " . htmlsafechars($row['checked_by']) . "' title='Checked - by " . htmlsafechars($row['checked_by']) . "' />" : "");
        $poster = empty($row["poster"]) ? "<img src=\'{$INSTALLER09['pic_base_url']}noposter.png\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />" : "<img src=\'" . htmlsafechars($row['poster']) . "\' width=\'150\' height=\'220\' border=\'0\' alt=\'Poster\' title=\'poster\' />";
        //$rating = empty($row["rating"]) ? "No votes yet":"".ratingpic($row["rating"])."";
		$youtube_yes = '<a data-toggle="youtubeModal' . (int)$row["id"]. '"><i class="fab fa-youtube" style="color:red"></i></a>
		<div class="reveal" id="youtubeModal' . (int)$row["id"]. '" data-reveal data-reset-on-close="true">
			<div class="flex-video">
			<iframe width="854" height="480" src="' . str_replace('watch?v=', 'embed/', $row['youtube']) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</div>
			<button class="close-button" data-close aria-label="Close modal" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';
		$youtube_no = '<a data-toggle="youtubeModal' . (int)$row["id"]. '"></a><div class="reveal" id="youtubeModal' . (int)$row["id"]. '" data-reveal>
			<p>No youtube data found</p>
			  <button class="close-button" data-close aria-label="Close modal" type="button">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>';
		$youtube = (!empty($row['youtube']) ? $youtube_yes : $youtube_no);
        if (isset($row["descr"])) 
			$descr = str_replace("\"", "&quot;", readMore($row["descr"], 350, "details.php?id=" . (int)$row["id"] . "&amp;hit=1"));
        $descr = str_replace('&', '&amp;', $descr);
        $htmlout.= "<td data-label='{$lang["torrenttable_name"]}'><a href='details.php?";
        if ($variant == "mytorrents") $htmlout.= "returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;";
        $htmlout.= "id=$id";
        if ($variant == "index") $htmlout.= "&amp;hit=1";
        $newgenre = '';
        if (!empty($row['newgenre'])) {
            $newgenre = array();
            $row['newgenre'] = explode(',', $row['newgenre']);
            foreach ($row['newgenre'] as $foo) $newgenre[] = '<a href="browse.php?search=' . trim(strtolower($foo)) . '&amp;searchin=genre">' . $foo . '</a>';
            $newgenre = '<i>' . join(', ', $newgenre) . '</i>';
        }
        $sticky = ($row['sticky'] == "yes" ? "<i class='fas fa-thumb-tack' aria-hidden='true'></i>" : "");
        $nuked = ($row["nuked"] == "yes" ? "<i class='fas fa-exclamation-circle' title='Reason :'". htmlsafechars($row["nukereason"]) ."' aria-hidden='true'></i>": "");
        $release_group = ($row['release_group'] == "scene" ? "&nbsp;<img src='{$INSTALLER09['pic_base_url']}scene.gif' title='Scene' alt='Scene' style='border:none' />" : ($row['release_group'] == "p2p" ? "&nbsp;<img src='{$INSTALLER09['pic_base_url']}p2p.gif' title='P2P' alt='P2P' />" : ""));
        $viponly = ($row["vip"] == 1 ? "<i class='fas fa-star' aria-hidden='true'></i>" : "");
        $freetorrent = (OCELOT_TRACKER == true && $row["freetorrent"] >= 1 ? "<img src='{$INSTALLER09['pic_base_url']}freedownload.gif' border='0' alt='Free Torrent' title='Free Torrent' />" : "");
        $bump = ($row['bump'] == "yes" ? "<img src='{$INSTALLER09['pic_base_url']}up.gif' width='12px' alt='Re-Animated torrent' title='This torrent was ReAnimated!' />" : "");
        /** FREE Torrent **/
        $free_tag = ($row['free'] != 0 ? ' <a class="info" href="#"><b>[FREE]</b> <span>' . ($row['free'] > 1 ? 'Expires: ' . get_date($row['free'], 'DATE') . '<br />(' . mkprettytime($row['free'] - TIME_NOW) . ' to go)<br />' : 'Unlimited<br />') . '</span></a>' : $all_free_tag);
        /** Silver Torrent **/
        $silver_tag = ($row['silver'] != 0 ? ' <a class="info" href="#"><b>[SILVER]</b> <span>' . ($row['silver'] > 1 ? 'Expires: ' . get_date($row['silver'], 'DATE') . '<br />(' . mkprettytime($row['silver'] - TIME_NOW) . ' to go)<br />' : 'Unlimited<br />') . '</span></a>' : '');
        if (!empty($slot)) foreach ($slot as $sl) {
            if ($sl['torrentid'] == $id && $sl['free'] == 'yes') $free_slot = 1;
            if ($sl['torrentid'] == $id && $sl['doubleup'] == 'yes') $double_slot = 1;
            if ($free_slot && $double_slot) break;
        }
        $free_slot = ($free_slot == 1 ? '&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'freedownload.gif" width="12px" alt="Free Slot" title="Free Slot in Use" />&nbsp;<small>Free Slot</small>' : '');
        $double_slot = ($double_slot == 1 ? '&nbsp;<img src="' . $INSTALLER09['pic_base_url'] . 'doubleseed.gif" width="12px" alt="Double Upload Slot" title="Double Upload Slot in Use" />&nbsp;<small>Double Slot</small><br />' : '');
        $nuked = ($row['nuked'] != 'no' && $row['nuked'] != '' ? '&nbsp;<span title="Nuked ' . htmlsafechars($row['nuked']) . '" class="browse-icons-nuked"></span>' : '');
        //==
        $Subs = '';
        if (in_array($row["category"], $INSTALLER09['movie_cats']) && !empty($row["subs"])) {
            $subs_array = explode(",", $row["subs"]);
            foreach ($subs_array as $k => $sid) {
            require_once (CACHE_DIR . 'subs.php');
                foreach ($subs as $sub) {
                    if ($sub["id"] == $sid) $Subs = "<img border='0' width='16px' style='padding:3px;' src='".htmlsafechars($sub["pic"])."' alt='".htmlsafechars($sub["name"])."' title='".htmlsafechars($sub["name"])."' />";
                }
            }
        } else $Subs = "---";
        $htmlout.= "' onmouseover=\"Tip('<b>" . CutName($dispname, 80) . "</b><br /><b>Added:&nbsp;" . get_date($row['added'], 'DATE', 0, 1) . "</b><br /><b>Size:&nbsp;" . mksize(htmlsafechars($row["size"])) . "</b><br /><b>Subtitle:&nbsp;{$Subs}</b><br /><b>Seeders:&nbsp;" . htmlsafechars($row["seeders"]) . "</b><br /><b>Leechers:&nbsp;" . htmlsafechars($row["leechers"]) . "</b><br />$poster');\" onmouseout=\"UnTip();\">" . CutName($dispname, 45) . "&nbsp;&nbsp;$youtube&nbsp;$viponly&nbsp;$release_group&nbsp;$sticky&nbsp;" . ($row['added'] >= $CURUSER['last_browse'] ? " <img src='{$INSTALLER09['pic_base_url']}newb.png' border='0' alt='New !' title='New !' />" : "") . "$checked$freetorrent$free_tag$silver_tag$free_slot&nbsp;$double_slot$nuked&nbsp;$bump$smalldescr</td>";
        if ($variant == "mytorrents") $htmlout.= "<td data-label='Download'><a href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "\"><i class='fas fa-file-archive' aria-hidden='true'></i></a></td>";
        if ($variant == "mytorrents") $htmlout.= "<td data-label='Edit'><a href='edit.php?id=" . (int)$row['id'] . "amp;returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "'><i class='fas fa-edit' aria-hidden='true'></i></a></td>";
        $htmlout.= ($variant == "index" ? "<td data-label='Download'><a href=\"download.php?torrent={$id}" . ($CURUSER['ssluse'] == 3 ? "&amp;ssl=1" : "") . "\"><i class='fas fa-file-archive' aria-hidden='true'></i></a></td>" : "");
        if ($variant == "mytorrents") {
            $htmlout.= "<td>";
            if ($row["visible"] == "no") $htmlout.= "<b>{$lang["torrenttable_not_visible"]}</b>";
            else $htmlout.= "{$lang["torrenttable_visible"]}";
            $htmlout.= "</td>";
        }
        /** pdq bookmarks **/
        $booked = '';
        if (!empty($book)) foreach ($book as $bk) {
            if ($bk['torrentid'] == $id) $booked = 1;
        }
        $rm_status = (!$booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bm_status = ($booked ? ' style="display:none;"' : ' style="display:inline;"');
        $bookmark = '<span id="bookmark' . $id . '"' . $bm_status . '>
                    <a href="bookmark.php?torrent=' . $id . '&amp;action=add" class="bookmark" name="' . $id . '">
                    <span title="Bookmark it!" class="add_bookmark_b">
						<font color="green"><i class="fa fa-check" aria-hidden="true"></i><font>
                    </span>
                    </a>
                    </span>
                    <span id="remove' . $id . '"' . $rm_status . '>
                    <a href="bookmark.php?torrent=' . $id . '&amp;action=delete" class="remove" name="' . $id . '">
                    <span class="remove_bookmark_b">
						<font color="red"><i class="fa fa-times" aria-hidden="true"></i></font>
                    </span>
                    </a>
                    </span>';
        if ($variant == "index") $htmlout.= "<td data-label='Bookmark'>{$bookmark}</td>";
        if ($row["type"] == "single") {
            $htmlout.= "<td data-label='Files'>" . (int)$row["numfiles"] . "</td>";
        } else {
            if ($variant == "index") {
                $htmlout.= "<td data-label='Files'><b><a href='filelist.php?id=$id'>" . (int)$row["numfiles"] . "</a></b></td>";
            } else {
                $htmlout.= "<td data-label='Files'><b><a href='filelist.php?id=$id'>" . (int)$row["numfiles"] . "</a></b></td>";
            }
        }
        if (!$row["comments"]) {
            $htmlout.= "<td>" . (int)$row["comments"] . "</td>";
        } else {
            if ($variant == "index") {
                $htmlout.= "<td data-label='Comments'><b><a href='details.php?id=$id&amp;hit=1&amp;tocomm=1'>" . (int)$row["comments"] . "</a></b></td>";
            } else {
                $htmlout.= "<td data-label='Comments'><b><a href='details.php?id=$id&amp;page=0#startcomments'>" . (int)$row["comments"] . "</a></b></td>";
            }
        }
        $htmlout.= "<td data-label='{$lang["torrenttable_added"]}' class='help-text' id='addedHelpText'><span style='white-space: nowrap;'>" . str_replace(",", "<br>", get_date($row['added'], '')) . "</span></td>";
          $ttl = (28*24) - floor((TIME_NOW - $row["added"]) / 3600);
          if ($ttl == 1) 
                 $ttl .= "<br>".$lang["torrenttable_hour_singular"]; 
              else 
                 $ttl .= "<br>".$lang["torrenttable_hour_plural"];
        $htmlout .= ($INSTALLER09['wait_times'] == 1 ? "<td>$ttl</td>" : "");
        $htmlout.= "<td data-label='{$lang["torrenttable_size"]}'  class='help-text' id='sizeHelpText'>" . str_replace(" ", "<br>", mksize($row["size"])) . "</td>";
        if ($row["times_completed"] != 1) $_s = "" . $lang["torrenttable_time_plural"];
        else $_s = "" . $lang["torrenttable_time_singular"];
        $What_Script_S = (OCELOT_TRACKER == true ? 'snatches_ocelot.php?id=' : 'snatches.php?id=' );
        $htmlout.= "<td data-label='{$lang["torrenttable_snatched"]}'  class='help-text' id='completedHelpText'><a href='$What_Script_S"."$id'>" . number_format($row["times_completed"]) . "<br>$_s</a></td>";
        if ($row["seeders"]) {
            if ($variant == "index") {
                if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"];
                else $ratio = 1;
                $What_Script_P = (OCELOT_TRACKER == true ? 'peerlist_ocelot.php?id=' : 'peerlist.php?id=' );
                $htmlout.= "<td data-label='{$lang["torrenttable_seeders"]}'><b><a href='$What_Script_P"."$id#seeders'><font color='" . get_slr_color($ratio) . "'>" . (int)$row["seeders"] . "</font></a></b></td>";
            } else {
                $What_Script_P = (OCELOT_TRACKER == true ? 'peerlist_ocelot.php?id=' : 'peerlist.php?id=' );
                $htmlout.= "<td data-label='{$lang["torrenttable_seeders"]}'><b><a class='" . linkcolor($row["seeders"]) . "' href='$What_Script_P"."$id#seeders'>" . (int)$row["seeders"] . "</a></b></td>";
            }
        } else {
            $htmlout.= "<td data-label='{$lang["torrenttable_seeders"]}'><span class='" . linkcolor($row["seeders"]) . "'>" . (int)$row["seeders"] . "</span></td>";
        }
        if ($row["leechers"]) {
            $What_Script_P = (OCELOT_TRACKER == true ? 'peerlist_ocelot.php?id=' : 'peerlist.php?id=' );
            if ($variant == "index") $htmlout.= "<td data-label='{$lang["torrenttable_leechers"]}'><b><a href='$What_Script_P"."$id#leechers'>" . number_format($row["leechers"]) . "</a></b></td>";
            else $htmlout.= "<td data-label='{$lang["torrenttable_leechers"]}'><b><a class='" . linkcolor($row["leechers"]) . "' href='$What_Script_P"."$id#leechers'>" . (int)$row["leechers"] . "</a></b></td>";
        } else $htmlout.= "<td data-label='{$lang["torrenttable_leechers"]}'>0</td>";
        if ($variant == "index") {
            $htmlout.= "<td data-label='{$lang["torrenttable_uppedby"]}'>" . (isset($row["username"]) ? (($row["anonymous"] == "yes" && $CURUSER['class'] < UC_STAFF && $row['owner'] != $CURUSER['id']) ? "<i>" . $lang['torrenttable_anon'] . "</i>" : "<a href='userdetails.php?id=" . (int)$row["owner"] . "'><b>" . htmlsafechars($row["username"]) . "</b></a>") : "<i>(" . $lang["torrenttable_unknown_uploader"] . ")</i>") . "</td>";
        } 
        if ($CURUSER['class'] >= UC_STAFF)  {
                $url = "edit.php?id=" .(int)$row["id"];
        if (isset($_GET["returnto"])) {
            $addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
            $url .= $addthis;
        }

        $editlink = "a href=\"$url\" class=\"sublink\"";
        $del_link = ($CURUSER['class'] === UC_MAX ? "<a href='fastdelete.php?id=".(int)$row['id']."'><i class='fas fa-trash' aria-hidden='true'></i></a>" : "");
        $htmlout .= "<td data-label='Tools'><$editlink><i class='fas fa-edit' aria-hidden='true'></i></a>{$del_link}</td>";
        }
if (!function_exists('health')) {
    function health($leechers, $seeders)
    {
        global $INSTALLER09;
        if ($leechers > 0 && $seeders > 0) $ratio = $seeders / $leechers * 100;
        if ($leechers == 1 && $seeders == 1) $ratio = $seeders / $leechers * 1;
        if (($leechers == 0 && $seeders == 0) || ($leechers > 0 && $seeders == 0)) return "<div class='alert progress'>
				<div class='progress-meter' style='width: 5%'></div>
			</div>";
        elseif ($seeders > $leechers) return "<div class='success progress'>
				<div class='progress-meter' style='width: 100%'></div>
			</div>";
        switch ($ratio) {
        case $ratio > 0 && $ratio < 15:
            return "<div class='alert progress'>
				<div class='progress-meter' style='width: 15%'></div>
			</div>";
            break;
         case $ratio >= 15 && $ratio < 25:
            return "<div class='alert progress'>
				<div class='progress-meter' style='width: 25%'></div>
			</div>";
            break;
         case $ratio >= 25 && $ratio < 35:
            return "<div class='alert progress'>
				<div class='progress-meter' style='width: 35%'></div>
			</div>";
            break;
         case $ratio >= 35 && $ratio < 45:
            return "<div class='warning progress'>
				<div class='progress-meter' style='width: 45%'></div>
			</div>";
            break;
         case $ratio >= 45 && $ratio < 55:
            return "<div class='warning progress'>
				<div class='progress-meter' style='width: 55%'></div>
			</div>";
            break;
         case $ratio >= 55 && $ratio < 65:
            return "<div class='warning progress'>
				<div class='progress-meter' style='width: 65%'></div>
			</div>";
            break;
         case $ratio >= 65 && $ratio < 75:
            return "<div class='success progress'>
				<div class='progress-meter' style='width: 75%'></div>
			</div>";
            break;
         case $ratio >= 75 && $ratio < 85:
            return "<div class='success progress'>
				<div class='progress-meter' style='width: 85%'></div>
			</div>";
            break;
         case $ratio >= 85 && $ratio < 95:
            return "<div class='success progress'>
				<div class='progress-meter' style='width: 95%'></div>
			</div>";
            break;
        }
    }
}
$htmlout.= "<td data-label='Health'>" . health($row["leechers"], $row["seeders"]) . "</td>";
        $htmlout.= "</tr></tbody>";
        $htmlout.= "<tr id=\"kdescr" . (int)$row["id"] . "\" style=\"display:none;\"><td width=\"100%\" colspan=\"14\">" . format_comment($descr, false) . "</td></tr>";
    }
    $htmlout.= "</table></div>";
    return $htmlout;
}
?>
