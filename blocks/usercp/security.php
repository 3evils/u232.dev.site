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
    $HTMLOUT.= "
<div class='col-md-10'>
	<table class='table'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='security' />{$lang['usercp_secu_opt']}</td></tr>";
    $HTMLOUT.= tr("{$lang['usercp_secu_opts']}", "<fieldset><legend><strong>{$lang['usercp_secu_ssl']}</strong></legend>
       <select name='ssluse'>
                <option value='1' " . ($CURUSER['ssluse'] == 1 ? 'selected=\'selected\'' : '') . ">{$lang['usercp_secu_nossl']}</option>
                <option value='2' " . ($CURUSER['ssluse'] == 2 ? 'selected=\'selected\'' : '') . ">{$lang['usercp_secu_site']}</option>
                <option value='3' " . ($CURUSER['ssluse'] == 3 ? 'selected=\'selected\'' : '') . ">{$lang['usercp_secu_down']}</option>
        </select>
    <br/><small>{$lang['usercp_secu_inf']}</small></fieldset>", 1);
    if (get_parked() == '1') $HTMLOUT.= tr($lang['usercp_acc_parked'], "<input type='radio' name='parked'".($CURUSER["parked"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']."
    <input type='radio' name='parked'".($CURUSER["parked"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."
    <br /><font class='small' size='1'>{$lang['usercp_acc_parked_message']}<br />{$lang['usercp_acc_parked_message1']}</font>", 1);
    /*$HTMLOUT.= tr($lang['usercp_acc_parked'], "<input type='checkbox' name='parked'" . (($CURUSER['opt1'] & user_options::PARKED) ? " checked='checked'" : "") . " value='yes' />
    <br /><font class='small' size='1'>{$lang['usercp_acc_parked_message']}<br />{$lang['usercp_acc_parked_message1']}</font>", 1);*/
    if (get_anonymous() != '0') $HTMLOUT.= tr($lang['usercp_anonymous'], "<input type='checkbox' name='anonymous'".($CURUSER["anonymous"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_default_anonymous']}", 1);
     $HTMLOUT.= tr("{$lang['usercp_secu_curr']}", "<input type='radio' name='hidecur'".($CURUSER["hidecur"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']."<input type='radio' name='hidecur'".($CURUSER["hidecur"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."", 1);
    /*$HTMLOUT.= tr($lang['usercp_anonymous'], "<input type='checkbox' name='anonymous'" . (($CURUSER['opt1'] & user_options::ANONYMOUS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_anonymous']}", 1);
    $HTMLOUT.= tr("Hide current seed and leech", "<input type='checkbox' name='hidecur'" . (($CURUSER['opt1'] & user_options::HIDECUR) ? " checked='checked'" : "") . " value='yes' />(Hide your snatch lists)", 1);*/
    //=== paranoia level sir_snugglebunny
    if ($CURUSER['class'] > UC_USER) {
        $HTMLOUT.= tr(''.$lang['usercp_parano_my'].'', "<select name='paranoia'>
	  <option value='0'" . ($CURUSER['paranoia'] == 0 ? " selected='selected'" : "") . ">{$lang['usercp_parano_mood1']}</option>
	  <option value='1'" . ($CURUSER['paranoia'] == 1 ? " selected='selected'" : "") . ">{$lang['usercp_parano_mood2']}</option>
	  <option value='2'" . ($CURUSER['paranoia'] == 2 ? " selected='selected'" : "") . ">{$lang['usercp_parano_mood3']}</option>
	  <option value='3'" . ($CURUSER['paranoia'] == 3 ? " selected='selected'" : "") . ">{$lang['usercp_parano_mood4']}</option>
	  </select> <a class='altlink'  title='Click for more info' id='paranoia_open' style='font-weight:bold;cursor:pointer;'>{$lang['usercp_parano_level']}</a> <br /><br />
	  <div id='paranoia_info' style='display:none;background-color:transparent;max-width:400px;padding: 5px 5px 5px 10px;'>
	  <span style='font-weight: bold;'>{$lang['usercp_parano_mood1']}</span><br />
	  <span style='font-size: x-small;'>{$lang['usercp_parano_level1']}</span><br /><br />
	  <span style='font-weight: bold;'>{$lang['usercp_parano_mood2']}</span><br />
	  <span style='font-size: x-small;'>{$lang['usercp_parano_level3']}</span><br /><br />
	  <span style='font-weight: bold;'>{$lang['usercp_parano_mood3']}</span><br />
	  <span style='font-size: x-small;'>{$lang['usercp_parano_level4']}</span><br /><br />
	  <span style='font-weight: bold;'>{$lang['usercp_parano_mood4']}</span><br />
	  <span style='font-size: x-small;'>{$lang['usercp_parano_level5']}</span><br /><br />
	  <span style='font-weight: bold;'>{$lang['usercp_parano_level6']}</span><br />
	  {$lang['usercp_parano_level7']}<br />{$lang['usercp_parano_level8']}<br /></div>", 1);
    }
    $HTMLOUT.= tr($lang['usercp_email'], "<input type='text' name='email' size='50' value='" . htmlsafechars($CURUSER["email"]) . "' /><br />{$lang['usercp_email_pass']}<br /><input type='password' name='chmailpass' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $HTMLOUT.= "<tr><td colspan='2' align='left'>{$lang['usercp_note']}</td></tr>\n";
    //=== email forum stuff
    $HTMLOUT.= tr($lang['usercp_email_shw'], '<input type="radio" name="show_email" '.($CURUSER['show_email'] == 'yes' ? ' checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes1'].'
    <input type="radio" name="show_email" '.($CURUSER['show_email'] == 'no' ? ' checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'<br />
    '.$lang['usercp_email_visi'].'', 1);
    /*$HTMLOUT.= tr('Show Email', '<input class="styled" type="checkbox" name="show_email"' . (($CURUSER['opt1'] & user_options::SHOW_EMAIL) ? ' checked="checked"' : '') . ' value="yes" /> Yes<br />
	  Do you wish to have your email address visible on the forums?', 1);*/
    $HTMLOUT.= tr($lang['usercp_chpass'], "<input type='password' name='chpassword' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $HTMLOUT.= tr($lang['usercp_pass_again'], "<input type='password' name='passagain' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $secretqs = "<option value='0'>{$lang['usercp_none_select']}</option>\n";
    $questions = array(
        array(
            "id" => "1",
            "question" => "{$lang['usercp_q1']}"
        ) ,
        array(
            "id" => "2",
            "question" => "{$lang['usercp_q2']}"
        ) ,
        array(
            "id" => "3",
            "question" => "{$lang['usercp_q3']}"
        ) ,
        array(
            "id" => "4",
            "question" => "{$lang['usercp_q4']}"
        ) ,
        array(
            "id" => "5",
            "question" => "{$lang['usercp_q5']}"
        ) ,
        array(
            "id" => "6",
            "question" => "{$lang['usercp_q6']}"
        )
    );
    foreach ($questions as $sctq) {
        $secretqs.= "<option value='" . $sctq['id'] . "'" . ($CURUSER["passhint"] == $sctq['id'] ? " selected='selected'" : "") . ">" . $sctq['question'] . "</option>\n";
    }
    $HTMLOUT.= tr($lang['usercp_question'], "<select name='changeq'>\n$secretqs\n</select>", 1);
    $HTMLOUT.= tr($lang['usercp_sec_answer'], "<input type='text' name='secretanswer' size='40' />", 1);
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</div>";
 
 //==End
// End Class
// End File