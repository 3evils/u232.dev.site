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
$HTMLOUT.= "";
$HTMLOUT .='<script type="text/javascript">
			$(document).ready(function () {
				$("#browser").treeview();
			});
		</script>';

if ($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class']) {
    //$HTMLOUT .= begin_frame("Edit User", true);
    $HTMLOUT.= "<form method='post' action='staffpanel.php?tool=modtask'>";
    require_once CLASS_DIR . 'validator.php';
    $HTMLOUT.= validatorForm('ModTask_' . $user['id']);
    $postkey = PostKey(array(
        $user['id'],
        $CURUSER['id']
    ));
    $HTMLOUT.= "<input type='hidden' name='action' value='edituser'>";
    $HTMLOUT.= "<input type='hidden' name='userid' value='$id'>";
    $HTMLOUT.= "<input type='hidden' name='postkey' value='$postkey'>";
    $HTMLOUT.= "<input type='hidden' name='returnto' value='userdetails.php?id=$id'>";
    $HTMLOUT.= "<div class='input-group'>
        <span class='input-group-label'><i class='fa fa-user' aria-hidden='true'></i></span>
        <input class='input-group-field' placeholder='{$lang['userdetails_title']}' type='text' name='title' value='" . htmlsafechars($user['title']) . "'>
      </div>";
    $avatar = htmlsafechars($user["avatar"]);
    $HTMLOUT.= "<div class='input-group'>
        <span class='input-group-label'><i class='fa fa-id-card-o' aria-hidden='true'></i></span>
		<input class='input-group-field' placeholder='{$lang['userdetails_avatar_url']}' type='text' name='avatar' value='$avatar'>
	</div>";
	$HTMLOUT.="<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-google-plus' aria-hidden='true'></i>
		</span>
		<input class='input-group-field' placeholder='{$lang['userdetails_gtalk']}' type='text' name='google_talk' value='" . htmlsafechars($user['google_talk']) . "'>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-windows' aria-hidden='true'></i>
		</span>
		<input class='input-group-field' placeholder='{$lang['userdetails_msn']}' type='text' name='msn' value='" . htmlsafechars($user['msn']) . "'>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-yahoo' aria-hidden='true'></i>
		</span>
		<input class='input-group-field' placeholder='{$lang['userdetails_yahoo']}' type='text' name='yahoo' value='" . htmlsafechars($user['yahoo']) . "'>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-bars' aria-hidden='true'></i>
		</span>
		<input class='input-group-field' placeholder='{$lang['userdetails_icq']}' type='text' name='icq' value='" . htmlsafechars($user['icq']) . "'>
	</div>
	<div class='input-group'>
		<span class='input-group-label'>
			<i class='fa fa-link' aria-hidden='true'></i>
		</span>
		<input class='input-group-field' placeholder='{$lang['userdetails_website']}' type='text' name='website' value='" . htmlsafechars($user['website']) . "'>
	</div>";
	$HTMLOUT.="<textarea placeholder='{$lang['userdetails_signature']}' cols='60' rows='2' name='signature'>" . htmlsafechars($user['signature']) . "</textarea>";
	$HTMLOUT.= "
		<div class='row callout primary'>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_enabled']}</span>
				<p><input name='enabled' value='yes' type='radio'" . ($enabled ? " checked='checked'" : "") . ">{$lang['userdetails_yes']} <input name='enabled' value='no' type='radio'" . (!$enabled ? " checked='checked'" : "") . ">{$lang['userdetails_no']}</p>
			</div>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_signature_rights']}</span>
				<input name='signature_post' value='yes' type='radio'".($user['signature_post'] == "yes" ? "    checked='checked'" : "").">{$lang['userdetails_yes']}
				<input name='signature_post' value='no' type='radio'".($user['signature_post'] == "no" ? " checked='checked'" : "").">No
			</div>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_view_offensive']}</span>
			<p><input name='view_offensive_avatar' value='yes' type='radio'".($user['view_offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input name='view_offensive_avatar' value='no' type='radio'".($user['view_offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
			</div>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_offensive']}</span>
			<p><input name='offensive_avatar' value='yes' type='radio'".($user['offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input name='offensive_avatar' value='no' type='radio'".($user['offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
			</div>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_avatar_rights']}</span>
			<p><input name='avatar_rights' value='yes' type='radio'".($user['avatar_rights'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input name='avatar_rights' value='no' type='radio'".($user['avatar_rights'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
			</div>";
			//==High speed php announce
			if ($CURUSER["class"] == UC_MAX && OCELOT_TRACKER == false) {
				 $HTMLOUT.= "<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_highspeed']}</span>
				 <p><input type='radio' name='highspeed' value='yes' ".($user["highspeed"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
				 <input type='radio' name='highspeed' value='no' ".($user["highspeed"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
				 </div>";
			}
			//==Invites
			$HTMLOUT.= "<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_invright']}</span>
			<p><input type='radio' name='invite_on' value='yes'".($user["invite_on"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input type='radio' name='invite_on' value='no'".($user["invite_on"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
			</div>";
			//==end invites
			//==OCELOT - Can Leech
			if (OCELOT_TRACKER == true) {
				$HTMLOUT.= "<div class='small-6 medium-4 large-2 columns'>
				<span class='primary label'>{$lang['userdetails_canleech']}</span>
					<p><input type='radio' name='can_leech' value='1' " . ($user["can_leech"] == 1 ? " checked='checked'" : "") . ">{$lang['userdetails_yes']}
					<input type='radio' name='can_leech' value='0' " . ($user["can_leech"] == 0 ? " checked='checked'" : "") . ">{$lang['userdetails_no']}</p>
				</div>";
			}
			//users parked
			$HTMLOUT.= "<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_park']}</span>
			<p><input name='parked' value='yes' type='radio'".($user["parked"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']} <input name='parked' value='no' type='radio'".($user["parked"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
			</div>";
			//end users parked 
			$HTMLOUT.= "<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>{$lang['userdetails_forum_rights']}</span>
			<p><input name='forum_post' value='yes' type='radio'".($user['forum_post'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input name='forum_post' value='no' type='radio'".($user['forum_post'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_forums_no']}</p></div>
			<div class='small-6 medium-4 large-2 columns'>
			<span class='primary label'>Forum Moderator</span>
			<p><input name=\"forum_mod\" value=\"yes\" type=radio " . ($user["forum_mod"]=="yes" ? "checked=\"checked\"" : "") . ">Yes 
			<input name=\"forum_mod\" value=\"no\" type=\"radio\" " . ($user["forum_mod"]=="no" ? "checked=\"checked\"" : "") . ">No</p></div>
			<input type='submit' class='button' value='{$lang['userdetails_okay']}'>"; 
			$HTMLOUT .="</div>";
	//== we do not want mods to be able to change user classes or amount donated...
	$HTMLOUT.="<div class='row callout primary'>
		<div class='medium-6 large-3 columns'>";
    // === Donor mod time based by snuggles
    if ($CURUSER["class"] == UC_MAX) {
        $donor = $user["donor"] == "yes";
        $HTMLOUT.= "<span class='label primary'>{$lang['userdetails_donor']}</span>";
        if ($donor) {
            $donoruntil = (int)$user['donoruntil'];
            if ($donoruntil == '0') 
				$HTMLOUT.= $lang['userdetails_arbitrary'];
            else {
                $HTMLOUT.= "<div class='columns'><p><b>" . $lang['userdetails_donor2'] . "</b> " . get_date($user['donoruntil'], 'DATE') . " ";
                $HTMLOUT.= " [ " . mkprettytime($donoruntil - TIME_NOW) . " ] {$lang['userdetails_togo']}</p></div>";
            }
        } else {
            $HTMLOUT.= "<div class='input-group'>
				<span class='input-group-label'><b>{$lang['userdetails_dfor']}</b></span>
				<select class='input-group-field' name='donorlength'>
					<option value='0'>------</option>
					<option value='4'>1 {$lang['userdetails_month']}</option>" . "
					<option value='6'>6 {$lang['userdetails_weeks']}</option>
					<option value='8'>2 {$lang['userdetails_months']}</option>
					<option value='10'>10 {$lang['userdetails_weeks']}</option>" . "
					<option value='12'>3 {$lang['userdetails_months']}</option>
					<option value='255'>{$lang['userdetails_unlimited']}</option>
				</select>
			</div>";
        }
        $HTMLOUT.= "<div class='input-group'>
			<span class='input-group-label'><b>{$lang['userdetails_cdonation']}</b></span>
			<input class='input-group-field' placeholder='{$lang['userdetails_cdonation']}' type='text' name='donated' value=\"" . htmlsafechars($user["donated"]) . "\">" . "
		</div>
		<p><b>{$lang['userdetails_tdonations']}</b>" . htmlsafechars($user["total_donated"]) . "</p>";
        if ($donor) {
            $HTMLOUT.= "<div class='input-group'>
				<span class='input-group-label'><b>{$lang['userdetails_adonor']}</b></span>
				<select class='input-group-field' name='donorlengthadd'>
					<option value='0'>------</option>
					<option value='4'>1 {$lang['userdetails_month']}</option>" . "
					<option value='6'>6 {$lang['userdetails_weeks']}</option>
					<option value='8'>2 {$lang['userdetails_months']}</option>
					<option value='10'>10 {$lang['userdetails_weeks']}</option>" . "
					<option value='12'>3 {$lang['userdetails_months']}</option>
					<option value='255'>{$lang['userdetails_unlimited']}</option>
				</select>
			</div>";
            $HTMLOUT.= "<b>{$lang['userdetails_rdonor']}</b><input name='donor' value='no' type='checkbox'> [ {$lang['userdetails_bad']} ]";
        }
    }
	$HTMLOUT.="</div>";
	$HTMLOUT.= "<div class='medium-6 large-6 columns'>";
    // ====End
    if ($CURUSER['class'] == UC_STAFF && $user["class"] > UC_VIP) 
		$HTMLOUT.= "<input type='hidden' name='class' value='{$user['class']}'>";
    else {
        $HTMLOUT.= "Class<select name='class'>";
        if ($CURUSER['class'] == UC_STAFF) $maxclass = UC_VIP;
        else $maxclass = $CURUSER['class'] - 1;
        for ($i = 0; $i <= $maxclass; ++$i) $HTMLOUT.= "<option value='$i'" . ($user["class"] == $i ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>";
        $HTMLOUT.= "</select>";
    }
	$HTMLOUT.= "</div></div>";
	
    $supportfor = htmlsafechars($user["supportfor"]);
 	$HTMLOUT.= "<div class='row callout primary'>
		<div class='medium-6 large-6 columns'>
			<span class='primary label'>{$lang['userdetails_support']}</span>
			<p><input type='radio' name='support' value='yes'".($user["support"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
			<input type='radio' name='support' value='no'".($user["support"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</p>
		</div>
		<div class='medium-6 large-6 columns'>
			<textarea placeholder='{$lang['userdetails_supportfor']}' rows='2' name='supportfor'>{$supportfor}</textarea>
		</div>
	</div>";
	$HTMLOUT.= "<div class='row callout primary'>";
    $modcomment = htmlsafechars($user_stats["modcomment"]);
	$HTMLOUT.="<div class='medium-6 large-6 columns'>";
    if ($CURUSER["class"] < UC_SYSOP) {
        $HTMLOUT.= "<p>{$lang['userdetails_comment']}</p><textarea  placeholder='{$lang['userdetails_comment']}' rows='6' name='modcomment' readonly='readonly'>$modcomment</textarea>";
    } else {
        $HTMLOUT.= "<p>{$lang['userdetails_comment']}</p><textarea placeholder='{$lang['userdetails_comment']}' rows='6' name='modcomment'>$modcomment</textarea>";
    }
	$HTMLOUT.="</div>";
    $HTMLOUT.= "<div class='medium-6 large-6 columns'>
		<p>{$lang['userdetails_add_comment']}</p><textarea placeholder='{$lang['userdetails_add_comment']}' rows='6' name='addcomment'></textarea>
	</div>";
	$HTMLOUT.= "</div>";
    //=== bonus comment
	$HTMLOUT.="<div class='row callout primary'>";
    $bonuscomment = htmlsafechars($user_stats["bonuscomment"]);
    $HTMLOUT.= "<div class='medium-6 large-6 columns'>
		<p>{$lang['userdetails_bonus_comment']}</p>
		<textarea placeholder='{$lang['userdetails_bonus_comment']}' rows='6' name='bonuscomment' readonly='readonly' style='background:purple;color:yellow;'>$bonuscomment</textarea>
	</div>";
    //==end
	$HTMLOUT.= "<div class='medium-6 large-6 columns'>";
	if ($CURUSER['class'] >= UC_STAFF && OCELOT_TRACKER == false) 
		$HTMLOUT.= "<p class='text-left'>{$lang['userdetails_freeleech_slots']}</p>
			<input type='text' name='freeslots' value='" . (int)$user['freeslots'] . "'>";
	$HTMLOUT.="</div>
	</div>";
	//==Avatar disable
	$HTMLOUT.="<div class='row callout primary'>";///==Start row for avatar and imunity disable==//
	$HTMLOUT.="<div class='medium-6 large-6 columns'>";
    if ($CURUSER['class'] >= UC_STAFF) {
        $avatarpos = $user['avatarpos'] != 1;
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_avatarpos']}</b></span>" . ($avatarpos ? "
				<input name='avatarpos' value='42' type='hidden'>
				<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_avatar_d']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>");
        if ($avatarpos) {
            if ($user['avatarpos'] == 0) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].'</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='avatardisable_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['avatarpos'], 'DATE') . " (" . mkprettytime($user['avatarpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
		$HTMLOUT.= '<div class="input-group">
			<span class="input-group-label">'.$lang['userdetails_disable_for'].'</span>
			<select class="input-group-field"  name="avatarpos" onchange="this.form.submit()">
				<option value="0">------</option>
				<option value="1">1 '.$lang['userdetails_week'].'</option>
				<option value="2">2 '.$lang['userdetails_weeks'].'</option>
				<option value="4">4 '.$lang['userdetails_weeks'].'</option>
				<option value="8">8 '.$lang['userdetails_weeks'].'</option>
				<option value="90">'.$lang['userdetails_unlimited'].'</option>
			</select>
		</div>
		<div class="input-group">
			<span class="input-group-label">
				<i class="fa fa-commenting" aria-hidden="true"></i>
			</span>			
			<input class="input-group-field" placeholder="Comments" type="text" name="avatardisable_pm">
		</div>';
        }
    }
	$HTMLOUT.="</div>";
	//==Immunity
	$HTMLOUT.="<div class='medium-6 large-6 columns'>";
    if ($CURUSER['class'] >= UC_STAFF) {
        $immunity = $user['immunity'] != 0;
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_immunity']}</b></span>" . ($immunity ? "<input name='immunity' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_immunity']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_immunity']. "</span>");
        if ($immunity) {
            if ($user['immunity'] == 1) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].'</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text'  name='immunity_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['immunity'], 'DATE') . " (" . mkprettytime($user['immunity'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_immunity_for'].'</span>
				<select class="input-group-field" name="immunity" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<input class="input-group-field" placeholder="Comments" type="text"  name="immunity_pm">
			</div>';
        }
    }
	$HTMLOUT.="</div>";
	$HTMLOUT.="</div>";///==End row for avatar and imunity disable==//
	///==Start row for download and upload disable==//
	$HTMLOUT.="<div class='row callout primary'>";
    //==Download disable== editted for announce======//
    if ($CURUSER['class'] >= UC_STAFF && OCELOT_TRACKER == false) {
        $downloadpos = $user['downloadpos'] != 1;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_dpos']}</b></span>" . ($downloadpos ? "
		<input name='downloadpos' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_download_d']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>") . "";
        if ($downloadpos) {
            if ($user['downloadpos'] == 0) 
				$HTMLOUT.= '('.$lang['userdetails_unlimited_d'].')';
            else 
			$HTMLOUT.= "
			<div class='input-group'>
				<span class='input-group-label'>
					<i class='fa fa-commenting' aria-hidden='true'></i>
				</span>
				<input class='input-group-field' placeholder='Comments' type='text' name='disable_pm'>
			</div>
			<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['downloadpos'], 'DATE') . " (" . mkprettytime($user['downloadpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_disable_for'].'</span>
				<select class="input-group-field" name="downloadpos" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>	
				<input class="input-group-field" placeholder="Comments" type="text" size="60" name="disable_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
	//==Upload disable
    if ($CURUSER['class'] >= UC_STAFF) {
        $uploadpos = $user['uploadpos'] != 1;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_upos']}</b></span>" . ($uploadpos ? "<input name='uploadpos' value='42' type='hidden'><button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_upload_d']}</button" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>");
        if ($uploadpos) {
            if ($user['uploadpos'] == 0) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].'</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>	
					<input placeholder='Comments' type='text' name='updisable_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['uploadpos'], 'DATE') . " (" . mkprettytime($user['uploadpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">' . $lang['userdetails_disable_for'] .'</span>
				<select class="input-group-field" name="uploadpos" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>	
				<input class="input-group-field" placeholder="Comment" type="text" name="updisable_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
   $HTMLOUT.="</div>";
   ///==End row for download and upload disable==//
   ///==Start row for pm and shoutbox disable==//
   $HTMLOUT.="<div class='row callout primary'>";
    //==Pm disable
    if ($CURUSER['class'] >= UC_STAFF) {
        $sendpmpos = $user['sendpmpos'] != 1;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_pmpos']}</b></span>" . ($sendpmpos ? "<input name='sendpmpos' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_pm_d']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>");
        if ($sendpmpos) {
            if ($user['sendpmpos'] == 0) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].'</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>			
					<input class='input-group-field' placeholder='Comments' type='text' name='pmdisable_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['sendpmpos'], 'DATE') . " (" . mkprettytime($user['sendpmpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_disable_for'].'</span>
				<select class="input-group-field"  name="sendpmpos" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
			<span class="input-group-label">
				<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
			<input class="input-group-field" placeholder="Comments" type="text" name="pmdisable_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
	//==Shoutbox disable
    if ($CURUSER['class'] >= UC_STAFF) {
        $chatpost = $user['chatpost'] != 1;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_chatpos']}</b></span>" . ($chatpost ? "<input name='chatpost' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_shout_d']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>");
        if ($chatpost) {
            if ($user['chatpost'] == 0) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].')</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='chatdisable_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['chatpost'], 'DATE') . " (" . mkprettytime($user['chatpost'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_disable_for'].'</span>
			<select class="input-group-field" name="chatpost" onchange="this.form.submit()">
				<option value="0">------</option>
				<option value="1">1 '.$lang['userdetails_week'].'</option>
				<option value="2">2 '.$lang['userdetails_weeks'].'</option>
				<option value="4">4 '.$lang['userdetails_weeks'].'</option>
				<option value="8">8 '.$lang['userdetails_weeks'].'</option>
				<option value="90">'.$lang['userdetails_unlimited'].'</option>
			</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>		
				<input class="input-group-field" placeholder="Comments" type="text" name="chatdisable_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
	$HTMLOUT.="</div>";
	///==end row for pm and shoutbox disable==//
	///==Start row for warnings and games disable==//
	$HTMLOUT.="<div class='row callout primary'>";
	//==Warnings
    if ($CURUSER['class'] >= UC_STAFF) {
        $warned = $user['warned'] != 0;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_warned']}</b></span>" . ($warned ? "<input name='warned' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_warned']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_warning'] . "</span>");
        if ($warned) {
            if ($user['warned'] == 1) 
				$HTMLOUT.= '<p class="help-text">'.$lang['userdetails_unlimited_d'].'</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='warned_pm'>
					</div>
					<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['warned'], 'DATE') . " (" . mkprettytime($user['warned'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">' . $lang['userdetails_warn_for'] . '</span>
				<select class="input-group-field" name="warned" onchange="this.form.submit()">
					<option value="0">' . $lang['userdetails_warn0'] . '</option>
					<option value="1">' . $lang['userdetails_warn1'] . '</option>
					<option value="2">' . $lang['userdetails_warn2'] . '</option>
					<option value="4">' . $lang['userdetails_warn4'] . '</option>
					<option value="8">' . $lang['userdetails_warn8'] . '</option>
					<option value="90">' . $lang['userdetails_warninf'] . '</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<input class="input-group-field" placeholder="Comments" type="text" name="warned_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
    //==End
	$HTMLOUT.="<div class='medium-6 large-6 columns'>";
    //==Games disable
    if ($CURUSER['class'] >= UC_STAFF) {
        $game_access = $user['game_access'] != 1;
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_games']}</b></span>" . ($game_access ? "<input name='game_access' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_game_d']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_disablement'] . "</span>");
        if ($game_access) {
            if ($user['game_access'] == 0) 
				$HTMLOUT.= '<p class="help-text">('.$lang['userdetails_unlimited_d'].')</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='game_disable_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['game_access'], 'DATE') . " (" . mkprettytime($user['game_access'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_disable_for'].'</span>
				<select class="input-group-field" name="game_access" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<input class="input-group-field" placeholder="Comments" type="text" name="game_disable_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
	$HTMLOUT.="</div>";
	///==End row for warning and warning disable==//
	///==Start row for freeleech and lech warning disable==///
	$HTMLOUT.="<div class='row callout primary'>";
	if ($CURUSER['class'] >= UC_ADMINISTRATOR && OCELOT_TRACKER == false) {
        $free_switch = $user['free_switch'] != 0;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_freeleech_status']}</b></span>" . ($free_switch ? "<input name='free_switch' value='42' type='hidden'>
		<button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_freeleech']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_freeleech'] . "</span>");
        if ($free_switch) {
            if ($user['free_switch'] == 1) 
				$HTMLOUT.= '<p class="help-text">('.$lang['userdetails_unlimited_d'].')</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='free_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['free_switch'], 'DATE') . " (" . mkprettytime($user['free_switch'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_freeleech_for'].'</span>
				<select class="input-group-field" name="free_switch" onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<input class="input-group-field" type="text" placeholder="'.$lang['userdetails_pm_comment'].'" name="free_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }
	    //==Leech Warnings
    if ($CURUSER['class'] >= UC_STAFF) {
        $leechwarn = $user['leechwarn'] != 0;
		$HTMLOUT.="<div class='medium-6 large-6 columns'>";
        $HTMLOUT.= "<span class='label float-left'><b>{$lang['userdetails_leechwarn']}</b></span>" . ($leechwarn ? "
			   <input name='leechwarn' value='42' type='hidden'>
			   <button type='submit' class='tiny warning button float-right'>{$lang['userdetails_remove_leechwarn']}</button>" : "<span class='label float-right'>" . $lang['userdetails_no_leechwarn'] . "</span>");
        if ($leechwarn) {
            if ($user['leechwarn'] == 1) 
				$HTMLOUT.= '<p class="help-text">('.$lang['userdetails_unlimited_d'].')</p>';
            else 
				$HTMLOUT.= "<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-commenting' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' placeholder='Comments' type='text' name='leechwarn_pm'>
				</div>
				<p class='help-text'>{$lang['userdetails_until']} " . get_date($user['leechwarn'], 'DATE') . " (" . mkprettytime($user['leechwarn'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fa fa-commenting' aria-hidden='true'></i>
			</span>			
			<input class='input-group-field' placeholder='Comments' type='text' size='60' name='leechwarn_pm'>
		</div>";
        } else {
            $HTMLOUT.= '<div class="input-group">
				<span class="input-group-label">'.$lang['userdetails_leechwarn_for'].'</span>
				<select class="input-group-field" name="leechwarn"  onchange="this.form.submit()">
					<option value="0">------</option>
					<option value="1">1 '.$lang['userdetails_week'].'</option>
					<option value="2">2 '.$lang['userdetails_weeks'].'</option>
					<option value="4">4 '.$lang['userdetails_weeks'].'</option>
					<option value="8">8 '.$lang['userdetails_weeks'].'</option>
					<option value="90">'.$lang['userdetails_unlimited'].'</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>
				<input class="input-group-field" placeholder="Comments" type="text" name="leechwarn_pm">
			</div>';
        }
		$HTMLOUT.="</div>";
    }	
	$HTMLOUT.="</div>";
	///==End row for freeleech and lech warning disable==///	
	$HTMLOUT.= "<b>{$lang['userdetails_invites']}</b>
		<input type='text' name='invites' value='" . htmlsafechars($user['invites']) . "'>";
	///*** Adjust up/down ***///
	if ($CURUSER['class'] >= UC_ADMINISTRATOR) {
		$HTMLOUT.="<div class='row callout primary'>
			<div class='medium-6 large-6 columns'>
				{$lang['userdetails_addupload']}
				<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-upload' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' type='number' name='amountup'>
					<select class='input-group-field' name='formatup'>
						<option value='mb'>{$lang['userdetails_MB']}</option>
						<option value='gb'>{$lang['userdetails_GB']}</option>
					</select>
					<input type='hidden' id='upchange' name='upchange' value='plus'>
				</div>
			</div>
			<div class='medium-6 large-6 columns'>
				{$lang['userdetails_adddownload']}
				<div class='input-group'>
					<span class='input-group-label'>
						<i class='fa fa-download' aria-hidden='true'></i>
					</span>
					<input class='input-group-field' type='number' name='amountdown'> 
					<select class='input-group-field' name='formatdown'>
						<option value='mb'>{$lang['userdetails_MB']}</option>
						<option value='gb'>{$lang['userdetails_GB']}</option>
					</select>
					<input type='hidden' id='downchange' name='downchange' value='plus'>
				</div>
			</div>
		</div>";
    }
	///*** ALL BITS AND BOBS START HERE ***///
	if (OCELOT_TRACKER == true) {
        // == Wait time
        if ($CURUSER['class'] >= UC_STAFF) 
			$HTMLOUT.= "<div class='small-1 columns'>{$lang['userdetails_waittime']}
				<input type='text' name='wait_time' value='" . (int)$user['wait_time'] . "'>
			</div>";
        // ==end
        // == Peers limit
        if ($CURUSER['class'] >= UC_STAFF) 
			$HTMLOUT.= "<div class='small-1 columns'>{$lang['userdetails_peerslimit']}
				<input type='text'' name='peers_limit' value='" . (int)$user['peers_limit'] . "'>
			</div>";
        // ==end
        // == Torrents limit
        if ($CURUSER['class'] >= UC_STAFF) 
			$HTMLOUT.= "<div class='small-1 columns'>{$lang['userdetails_torrentslimit']}
				<input type='text' name='torrents_limit' value='" . (int)$user['torrents_limit'] . "'>
			</div>";
        // ==end
    }
	//Start row suspended and reset
	$HTMLOUT.= '<div class="row callout primary">
		<div class="medium-6 large-6 columns">
			<div class="input-group">
				<span class="input-group-field">'.$lang['userdetails_suspended'].'</span>
					<p><input name="suspended" value="yes" type="radio"'.($user['suspended'] == 'yes' ? ' checked="checked"' : '').'>'.$lang['userdetails_yes'].'
					<input name="suspended" value="no" type="radio"'.($user['suspended'] == 'no' ? ' checked="checked"' : '').'>'.$lang['userdetails_no'].'</p>
				</div>
			<div class="input-group">
				<span class="input-group-label">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</span>	
				<input class="input-group-field" type="text" name="suspended_reason">
			</div>
		</div>';
		//reset passkey
		$HTMLOUT.= "<div class='medium-6 large-6 columns'>
			<div class='input-group'>
				<span class='input-group-field'>{$lang['userdetails_reset']}</span>
				<p><input type='checkbox' name='reset_torrent_pass' value='1'></p>
			</div>
			<p><font class='small'>{$lang['userdetails_pass_msg']}</font></p>
		</div>
	</div>";
	//end row suspended and reset
	$HTMLOUT.= "<div class='row callout primary'>";
		// == seedbonus
		if ($CURUSER['class'] >= UC_STAFF) 
			$HTMLOUT.= "<div class='medium-6 large-3 columns'>{$lang['userdetails_bonus_points']}
				<input type='text' name='seedbonus' value='" . (int)$user_stats['seedbonus'] . "'>
			</div>";
		// ==end
		// == rep
		if ($CURUSER['class'] >= UC_STAFF) 
			$HTMLOUT.= "<div class='medium-6 large-3 columns'>{$lang['userdetails_rep_points']}
				<input type='text' name='reputation' value='" . (int)$user['reputation'] . "'>
			</div>";
		// ==end
		//==new row
		if ($CURUSER['class'] >= UC_STAFF)
			$HTMLOUT.= '<div class="medium-6 large-3 columns">'.$lang['userdetails_hnr'].'
				<input type="text" name="hit_and_run_total" value="' . (int)$user['hit_and_run_total'] . '">
			</div>';
		if ($CURUSER['class'] >= UC_STAFF)
		$HTMLOUT.= "<div class='medium-6 large-3 columns'>{$lang['userdetails_paranoia']}
			<select name='paranoia'>
				<option value='0'" . ($user['paranoia'] == 0 ? " selected='selected'" : "") . ">{$lang['userdetails_paranoia_0']}</option>
				<option value='1'" . ($user['paranoia'] == 1 ? " selected='selected'" : "") . ">{$lang['userdetails_paranoia_1']}</option>
				<option value='2'" . ($user['paranoia'] == 2 ? " selected='selected'" : "") . ">{$lang['userdetails_paranoia_2']}</option>
				<option value='3'" . ($user['paranoia'] == 3 ? " selected='selected'" : "") . ">{$lang['userdetails_paranoia_3']}</option>
			</select>
		</div>";
	$HTMLOUT.= "</div>";
	$HTMLOUT.= "<div class='row callout primary'>
		<div class='large-12 columns'>";	
			$q = sql_query("SELECT o.id as oid, o.name as oname, f.id as fid, f.name as fname FROM `over_forums` as o LEFT JOIN forums as f ON f.forum_id = o.id ") or sqlerr(__FILE__, __LINE__);
				while($a = mysqli_fetch_assoc($q))
					$boo[$a['oname']][] = array($a['fid'],$a['fname']);
					$forum_list = "<ul id=\"browser\" class=\"filetree treeview-gray\" style=\"width:50%;text-align:left;\">";
					foreach($boo as $fo=>$foo) {
						$forum_list .="<ul>";
						$forum_list .="<li class=\"closed\"><span class=\"folder\">".$fo."</span>";
							foreach($foo as $fooo)
								$forum_list .= "<li><label for=\"forum_".$fooo[0]."\"><span class=\"file\" style=\"position:relative;width:200px;\"><b>".$fooo[1]."</b><div style=\"display:inline-block;width:15px;\"></div><input type=\"checkbox\" ".(stristr($user["forums_mod"],"[".$fooo[0]."]") ? "checked=\"checked\"" : "" )."style=\"right:0;top:0;position:absolute;\" name=\"forums[]\" id=\"forum_".$fooo[0]."\" value=\"".$fooo[0]."\"></span></label></li>";
						$forum_list .= "</li></ul>";	
					}
					$forum_list .= "</ul>";
			$HTMLOUT .="<b>Forums List<br>".$forum_list."</b>";
	$HTMLOUT.= "</div></div>";
	$HTMLOUT.= "<input type='submit' class='button float-right' value='{$lang['userdetails_okay']}'>";
$HTMLOUT.= "</form>";//==End edit form
}
//==end
// End Class
// End File