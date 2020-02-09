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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (CLASS_DIR . 'page_verify.php');
require_once (CACHE_DIR . 'timezones.php');
dbconn();
global $CURUSER;
if (!$CURUSER) {
    get_template();
} else {
    header("Location: {$INSTALLER09['baseurl']}/index.php");
    exit();
}
ini_set('session.use_trans_sid', '0');
if ($INSTALLER09['captcha_on'] === true){
$stdfoot = array(
    /** include js **/
    'js' => array(
        'check',
        'jquery.pstrength-min.1.2',
        'jquery.simpleCaptcha-0.2'
    )
); } else {
$stdfoot = array(
    /** include js **/
    'js' => array(
        'check',
        'jquery.pstrength-min.1.2'
    )
); }
$lang = array_merge(load_language('global') , load_language('signup'));
if (!$INSTALLER09['openreg']) stderr($lang['stderr_errorhead'],  "{$lang['signup_inviteonly']}<a href='" . $INSTALLER09['baseurl'] . "/invite_signup.php'><b>&nbsp;{$lang['signup_here']}</b></a>");
$HTMLOUT = $year = $month = $day = $gender = '';
$HTMLOUT.= "
    <script type='text/javascript'>
    /*<![CDATA[*/
    $(function() {
    $('.password').pstrength();
    });
    /*]]>*/
    </script>";
$newpage = new page_verify();
$newpage->create('tesu');
if (get_row_count('users') >= $INSTALLER09['maxusers']) stderr($lang['stderr_errorhead'], sprintf($lang['stderr_ulimit'], $INSTALLER09['maxusers']));
//==timezone select
$offset = (string)$INSTALLER09['time_offset'];
$time_select = "<select name='user_timezone'>";
foreach ($TZ as $off => $words) {
    if (preg_match("/^time_(-?[\d\.]+)$/", $off, $match)) {
        $time_select.= $match[1] == $offset ? "<option value='{$match[1]}' selected='selected'>$words</option>\n" : "<option value='{$match[1]}'>$words</option>\n";
    }
}
$time_select.= "</select>";
//==country by pdq
function countries()
{
    global $mc1, $INSTALLER09;
    if (($ret = $mc1->get_value('countries::arr')) === false) {
        $res = sql_query("SELECT id, name, flagpic FROM countries ORDER BY name ASC") or sqlerr(__FILE__, __LINE__);
        while ($row = mysqli_fetch_assoc($res)) $ret[] = $row;
        $mc1->cache_value('countries::arr', $ret, $INSTALLER09['expires']['user_flag']);
    }
    return $ret;
}
$country = '';
$countries = countries();
foreach ($countries as $cntry) $country.= "<option value='" . (int)$cntry['id'] . "'" . ($CURUSER["country"] == $cntry['id'] ? " selected='selected'" : "") . ">" . htmlsafechars($cntry['name']) . "</option>\n";
$gender.= "<select name=\"gender\">
    <option value=\"Male\">{$lang['signup_male']}</option>
    <option value=\"Female\">{$lang['signup_female']}</option>
    <option value=\"NA\">{$lang['signup_na']}</option>
    </select>";
// Normal Entry Point...
//== click X by Retro
$value = array(
    '...',
    '...',
    '...',
    '...',
    '...',
    '...'
);
$value[rand(1, count($value) - 1) ] = 'X';
$HTMLOUT.= "".($INSTALLER09['captcha_on'] ? "<script type='text/javascript'>
	  /*<![CDATA[*/
	  $(document).ready(function () {
	  $('#captchasignup').simpleCaptcha();
    });
    /*]]>*/
    </script>" : "")."
	<div class='row'><div class='large-3 columns'>&nbsp;&nbsp;</div>
	<div class='large-6 columns'><div class='callout'>
    <form style='padding-top:6%;' role='form' method='post' title='signup' action='takesignup.php'>
<input  type='text' placeholder='{$lang['signup_uname']}' name='wantusername' id='wantusername' onblur='checkit();'>
<div id='namecheck'></div>
<input type='password' placeholder='{$lang['signup_pass']}' name='wantpassword'>
<input type='password' placeholder='{$lang['signup_passa']}' name='passagain'>
<input type='text' placeholder='Choose a 4 digit Pin Code' name='pin_code'><input type='text' placeholder='Repeat Pin Code' name='pin_code2'>
<input type='text' placeholder='{$lang['signup_email']}' name='email' aria-describedby='valemailHelpText'>
<p class='help-text' id='valemailHelpText'>{$lang['signup_valemail']}</p>
<label>{$lang['signup_timez']} {$time_select}</label>";


//==09 Birthday mod
$year.= "<select id='sel1' name=\"year\">";
$year.= "<option value=\"0000\">{$lang['signup_year']}</option>";
$i = "2020";
while ($i >= 1920) {
    $year.= "<option value=\"" . $i . "\">" . $i . "</option>";
    $i--;
}
$year.= "</select>";
$month.= "<select id='sel2' name=\"month\">
    <option value=\"00\">{$lang['signup_month']}</option>
    <option value=\"01\">{$lang['signup_jan']}</option>
    <option value=\"02\">{$lang['signup_feb']}</option>
    <option value=\"03\">{$lang['signup_mar']}</option>
    <option value=\"04\">{$lang['signup_apr']}</option>
    <option value=\"05\">{$lang['signup_may']}</option>
    <option value=\"06\">{$lang['signup_jun']}</option>
    <option value=\"07\">{$lang['signup_jul']}</option>
    <option value=\"08\">{$lang['signup_aug']}</option>
    <option value=\"09\">{$lang['signup_sep']}</option>
    <option value=\"10\">{$lang['signup_oct']}</option>
    <option value=\"11\">{$lang['signup_nov']}</option>
    <option value=\"12\">{$lang['signup_dec']}</option>
    </select>";
$day.= "<select id='sel3' name=\"day\">";
$day.= "<option value=\"00\">{$lang['signup_day']}</option>";
$i = 1;
while ($i <= 31) {
    if ($i < 10) {
        $day.= "<option value=\"0" . $i . "\">0" . $i . "</option>";
    } else {
        $day.= "<option value=\"" . $i . "\">" . $i . "</option>";
    }
    $i++;
}
$day.= "</select>";
$HTMLOUT.= "<div class='input-group'>{$lang['signup_birth']}<span style='color:red'>*</span></div><div class='input-group'>" . $year . $month . $day . "</div>";
//==End
//==Passhint
$passhint = "";
$questions = array(
    array(
        "id" => "1",
        "question" => "{$lang['signup_q1']}"
    ) ,
    array(
        "id" => "2",
        "question" => "{$lang['signup_q2']}"
    ) ,
    array(
        "id" => "3",
        "question" => "{$lang['signup_q3']}"
    ) ,
    array(
        "id" => "4",
        "question" => "{$lang['signup_q4']}"
    ) ,
    array(
        "id" => "5",
        "question" => "{$lang['signup_q5']}"
    ) ,
    array(
        "id" => "6",
        "question" => "{$lang['signup_q6']}"
    )
);
foreach ($questions as $sph) {
    $passhint.= "<option value='" . $sph['id'] . "'>" . $sph['question'] . "</option>\n";
}
$HTMLOUT.= "
<label>{$lang['signup_select']}
	<select name='passhint'>\n$passhint\n</select><input type='text' placeholder='{$lang['signup_hint_here']}{$lang['signup_this_answer']}{$lang['signup_this_answer1']}' name='hintanswer'></select>
</label>
<label>{$lang['signup_country']}<select name='country'>\n$country\n</select>
{$lang['signup_gender']}$gender
</label>
<fieldset class='fieldset'>
<label><input type='checkbox' name='rulesverify' value='yes'> {$lang['signup_rules']}</label>
<label><input type='checkbox' name='faqverify' value='yes'> {$lang['signup_faq']}</label>
<label><input type='checkbox' name='ageverify' value='yes'> {$lang['signup_age']}</label>
</fieldset>" . ($INSTALLER09['captcha_on'] ? "<div class='input-group float-center'><div id='captchasignup'></div></div>" : "") . "
<p class='text-center'>{$lang['signup_click']}&nbsp;<strong>{$lang['signup_x']}</strong>&nbsp;{$lang['signup_click1']}</p>
	<div class='expanded button-group'>";
for ($i = 0; $i < count($value); $i++) {
    $HTMLOUT.= "<input name=\"submitme\" type=\"submit\" value=\"" . $value[$i] . "\" class=\"button\">";
}
$HTMLOUT.= "</div></div></div><div class='large-3 columns'>&nbsp;&nbsp;</div></div></form>";
echo stdhead($lang['head_signup']) . $HTMLOUT . stdfoot($stdfoot);
?>
