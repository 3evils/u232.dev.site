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
    $HTMLOUT.= "<div class='{$design['large_9']} {$design['columns']}'>
	<table class='table'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='personal' />{$lang['usercp_pers_opt']}</td></tr>";
    if ($CURUSER['class'] >= UC_VIP) $HTMLOUT.= tr($lang['usercp_title'], "<input size='50' value='" . htmlsafechars($CURUSER["title"]) . "' name='title' /><br />", 1);
    //==status mod
    $CURUSER['archive'] = unserialize($CURUSER['archive']);
    $HTMLOUT.= "<tr><td class='rowhead'><div style='float:right'>{$lang['usercp_pers_onstat']}</div></td><td><fieldset><legend><strong>{$lang['usercp_pers_upstat']}</strong></legend></fieldset>";
    if (isset($CURUSER['last_status'])) $HTMLOUT.= "<div id='current_holder'>
    <small style='font-weight:bold;'>{$lang['usercp_pers_custat']}</small>
    <h2 id='current_status' title='{$lang['usercp_pers_edit']}' onclick='status_pedit()'>" . format_urls($CURUSER["last_status"]) . "</h2></div>";
    $HTMLOUT.= "<small style='font-weight:bold;'>{$lang['usercp_pers_updt']}</small>
    <textarea name='status' id='status' onkeyup='status_count()' cols='50' rows='4'></textarea>
    <div style='width:390px;'>
    <div style='float:left;padding-left:5px;'><small style='font-weight:bold;'>{$lang['usercp_pers_nobb']}</small></div>
    <div style='float:right;font-size:12px;font-weight:bold;' id='status_count'>140</div>
    <div style='clear:both;'></div></div>";
    if (count($CURUSER['archive'])) {
        $HTMLOUT.= "<div style='width:390px'>
    <div style='float:left;padding-left:5px;'><small style='font-weight:bold;'>{$lang['usercp_pers_arcstat']}</small></div>
    <div style='float:right;cursor:pointer' id='status_archive_click' onclick='status_slide()'>+</div>
    <div style='clear:both;'></div>
    <div id='status_archive' style='padding-left:15px;display:none;'>";
        if (is_array($CURUSER['archive'])) foreach (array_reverse($CURUSER['archive'], true) as $a_id => $sa) $HTMLOUT.= '<div id="status_' . $a_id . '">
    <div style="float:left">' . htmlsafechars($sa['status']) . '
    <small>added ' . get_date($sa['date'], '', 0, 1) . '</small></div>
    <div style="float:right;cursor:pointer;"><span onclick="status_delete(' . $a_id . ')"></span></div>
    <div style="clear:both;border:1px solid #222;border-width:1px 0 0 0;margin-bottom:3px;"></div></div>';
        $HTMLOUT.= "</div></div>";
    }
    $HTMLOUT.= "</td></tr>";
    $HTMLOUT.= tr($lang['usercp_top_perpage'], "<input type='text' size='10' name='topicsperpage' value='$CURUSER[topicsperpage]' /> {$lang['usercp_default']}", 1);
    $HTMLOUT.= tr($lang['usercp_post_perpage'], "<input type='text' size='10' name='postsperpage' value='$CURUSER[postsperpage]' /> {$lang['usercp_default']}", 1);
    $HTMLOUT.= tr($lang['usercp_pers_list'], "<input type='radio' name='forum_sort' ".($CURUSER["forum_sort"] == "ASC" ? " checked='checked'" : "")." value='ASC' />{$lang['usercp_pers_bot']}<input type='radio' name='forum_sort' ".($CURUSER["forum_sort"] != "ASC" ? " checked='checked'" : "")." value='DESC' />{$lang['usercp_pers_top']}<br />{$lang['usercp_pers_order']}", 1);
    $HTMLOUT.= tr($lang['usercp_stylesheet'], "<select name='stylesheet'>\n$stylesheets\n</select>", 1);
    $HTMLOUT.= tr($lang['usercp_gender'], "<input type='radio' name='gender'" . ($CURUSER["gender"] == "Male" ? " checked='checked'" : "") . " value='Male' />{$lang['usercp_male']}
    <input type='radio' name='gender'" . ($CURUSER["gender"] == "Female" ? " checked='checked'" : "") . " value='Female' />{$lang['usercp_female']}
    <input type='radio' name='gender'" . ($CURUSER["gender"] == "N/A" ? " checked='checked'" : "") . " value='N/A' />{$lang['usercp_na']}", 1);
    $HTMLOUT.= tr($lang['usercp_shoutback'], "<input type='radio' name='shoutboxbg'" . ($CURUSER["shoutboxbg"] == "1" ? " checked='checked'" : "") . " value='1' />{$lang['usercp_shoutback_white']}
    <input type='radio' name='shoutboxbg'" . ($CURUSER["shoutboxbg"] == "2" ? " checked='checked'" : "") . " value='2' />{$lang['usercp_shoutback_grey']}<input type='radio' name='shoutboxbg'" . ($CURUSER["shoutboxbg"] == "3" ? " checked='checked'" : "") . " value='3' />{$lang['usercp_shoutback_black']}", 1);
      //== Let it snow, Let it snow, Let it snow \\0//
      $Is_It_Crimbo = date('m-d-Y', time());
      $d = date_parse_from_format("m-d-y", $Is_It_Crimbo);
      $month = intval($d["month"]);
      if ($month == 11) {
      $HTMLOUT.= tr($lang['usercp_snow'], "<input type='radio' name='snow'".($CURUSER["snow"] == "yes" ? " checked='checked'" : "")." value='yes' />Yes<input type='radio' name='snow'".($CURUSER["snow"] == "no" ? " checked='checked'" : "")." value='no' />No", 1);
      }
    //==09 Birthday
    $day = $month = $year = '';
    $birthday = $CURUSER["birthday"];
    $birthday = date("Y-m-d", strtotime($birthday));
    list($year1, $month1, $day1) = explode('-', $birthday);
    if ($CURUSER['birthday'] == '0000-00-00' OR $CURUSER['birthday'] == '1801-01-01') {
        $year.= "<select name=\"year\"><option value=\"0000\">--</option>\n";
        $i = "1920";
        while ($i <= (date('Y', TIME_NOW) - 13)) {
            $year.= "<option value=\"" . $i . "\">" . $i . "</option>\n";
            $i++;
        }
        $year.= "</select>\n";
        $birthmonths = array(
            "01" => "{$lang['usercp_pers_month1']}",
            "02" => "{$lang['usercp_pers_month2']}",
            "03" => "{$lang['usercp_pers_month3']}",
            "04" => "{$lang['usercp_pers_month4']}",
            "05" => "{$lang['usercp_pers_month5']}",
            "06" => "{$lang['usercp_pers_month6']}",
            "07" => "{$lang['usercp_pers_month7']}",
            "08" => "{$lang['usercp_pers_month8']}",
            "09" => "{$lang['usercp_pers_month9']}",
            "10" => "{$lang['usercp_pers_month10']}",
            "11" => "{$lang['usercp_pers_month11']}",
            "12" => "{$lang['usercp_pers_month12']}",
        );
        $month = "<select name=\"month\"><option value=\"00\">--</option>\n";
        foreach ($birthmonths as $month_no => $show_month) {
            $month.= "<option value=\"$month_no\">$show_month</option>\n";
        }
        $month.= "</select>\n";
        $day.= "<select name=\"day\"><option value=\"00\">--</option>\n";
        $i = 1;
        while ($i <= 31) {
            if ($i < 10) {
                $day.= "<option value=\"0" . $i . "\">0" . $i . "</option>\n";
            } else {
                $day.= "<option value=\"" . $i . "\">" . $i . "</option>\n";
            }
            $i++;
        }
        $day.= "</select>\n";
        $HTMLOUT.= tr($lang['usercp_pers_birth'], $year . $month . $day, 1);
    }
$HTMLOUT.= '<input class="btn btn-primary" type="submit" value="'.$lang['usercp_sign_sub'] .'" style="height: 40px" /></table></div>';
 //==End
// End Class
// End File