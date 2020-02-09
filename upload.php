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
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'bbcode_functions.php';
require_once CLASS_DIR . 'page_verify.php';
require_once (CACHE_DIR . 'subs.php');
dbconn(true);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('upload') , load_language('ad_artefact'));
$stdhead = array(
    /** include css **/
    'css' => array(
        'forums',
        'style2',
        'bbcode'
    )
);
$stdfoot = array(
    /** include js **/
    'js' => array(
         'FormManager',
        'getname',
        'shout'
    )
);
if (function_exists('parked')) parked();
$newpage = new page_verify();
$newpage->create('taud');
$HTMLOUT = $offers = $subs_list = $request = $descr = '';
if ($CURUSER['class'] < UC_UPLOADER OR $CURUSER["uploadpos"] == 0 || $CURUSER["uploadpos"] > 1 || $CURUSER['suspended'] == 'yes') stderr($lang['upload_sorry'], $lang['upload_no_auth']);
$HTMLOUT.= "
    <script type='text/javascript'>
    window.onload = function() {
    setupDependencies('upload'); //name of form(s). Seperate each with a comma (ie: 'weboptions', 'myotherform' )
    };
    </script>
<div class='callout'> 
    <form role='form' name='upload' enctype='multipart/form-data' action='./takeupload.php' method='post'>
 <div class='small-12 columns'><input type='hidden' name='MAX_FILE_SIZE' value='{$INSTALLER09['max_torrent_size']}'></div>
<div class='input-group'>
	<span class='input-group-label'>
		{$lang['upload_announce_url']}:
	</span>
	<input  class='input-group-field' type=\"text\" value=\"" . $INSTALLER09['announce_urls'][0] . "\" onclick=\"select()\">
</div>";
$descr = strip_tags(isset($_POST['descr']) ? trim($_POST['descr']) : '');
$HTMLOUT.= "<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-imdb' aria-hidden='true'></i>
	</span>
	<input class='input-group-field' placeholder='{$lang['upload_imdb_url']}' type='text' name='url' aria-describedby='imdbHelpText'>
</div>
<p class='help-text' id='imdbHelpText'>{$lang['upload_imdb_tfi']}{$lang['upload_imdb_rfmo']}</p>
<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-picture-o' aria-hidden='true'></i>
	</span>			
	<input class='input-group-field' placeholder='{$lang['upload_poster']}' type='text' name='poster' aria-describedby='posterHelpText'>
</div>
<p class='help-text' id='posterHelpText'>{$lang['upload_poster1']}</p>
<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-youtube' aria-hidden='true'></i>
	</span>
	<input class='input-group-field' placeholder='Youtube' type='text' name='youtube' aria-describedby='youtubeHelpText'>
</div>
<p class='help-text' id='youtubeHelpText'>{$lang['upload_youtube_info']}</p>
<fieldset class='fieldset'>
<legend>{$lang['upload_bitbucket']}</legend>
<iframe class='embed-responsive1' src='imgup.html' aria-describedby='bitbucketHelpText'></iframe>
<p class='help-text' id='bitbucketHelpText'>{$lang['upload_bitbucket_1']}</p>
</fieldset>
<div class='row'>
<div class='large-6 columns'>
<fieldset class='fieldset'>
<legend>{$lang['upload_torrent']}</legend>
<input class='tiny button' type='file' name='file' id='torrent' onchange='getname()'>
</fieldset>
</div>
<div class='large-6 columns'>
<fieldset class='fieldset'>
	<legend aria-describedby='nfoHelpText'>{$lang['upload_nfo']}</legend>
	<input class='tiny button' type='file' name='nfo'>
	<p class='help-text' id='nfotHelpText'>{$lang['upload_nfo_info']}</p>
</fieldset>
</div>
</div>
<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-font' aria-hidden='true'></i>
	</span> 
<input class='input-group-field' placeholder='{$lang['upload_name']}' type='text' id='name' name='name' aria-describedby='filenameHelpText'>
</div>
<p class='help-text' id='filenameHelpText'>{$lang['upload_filename']}</p>
<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-tags' aria-hidden='true'></i>
	</span>   
	<input class='input-group-field' placeholder='{$lang['upload_tags']}' type='text' name='tags' aria-describedby='tagHelpText'>
</div>
<p class='help-text' id='tagHelpText'>{$lang['upload_tag_info']}</p>
<div class='input-group'>
	<span class='input-group-label'>
		<i class='fa fa-list-alt' aria-hidden='true'></i>
	</span> 
	<input class='input-group-field' placeholder='{$lang['upload_small_description']}' type='text' name='description' aria-describedby='smalldHelpText'>
</div>
<p class='help-text' id='smalldHelpText'>{$lang['upload_small_descr']}</p>

<fieldset class='fieldset' aria-describedby='descrHelpText'> 
	<legend>{$lang['upload_description']}</legend>
	<p>". textbbcode("upload","descr")."<p>({$lang['upload_html_bbcode']})</p>
</fieldset>";
$HTMLOUT.= "<div class='row'>";
$s = "<div class='medium-6 large-4 columns'>{$lang['upload_type']}<select name='type'>\n<option value='0'>({$lang['upload_choose_one']})</option>";       
$cats = genrelist();
foreach ($cats as $row) {
    $s.= "<option value='" . (int)$row["id"] . "'>" . htmlsafechars($row["name"]) . "</option>";
}
$s.= "</select></div>";
$rg = "<div class='medium-6 large-4 columns'>{$lang['upload_add_typ']}<select name='release_group'>
		<option value='none'>{$lang['upload_add_typnone']}</option>
		<option value='p2p'>{$lang['upload_add_typp2p']}</option>
		<option value='scene'>{$lang['upload_add_typscene']}</option>
	</select>";
$HTMLOUT.= "$s";
//==== request dropdown
$res_request = sql_query('SELECT id, request_name FROM requests WHERE filled_by_user_id = 0 ORDER BY request_name ASC');
$request ='<div class="medium-6 large-4 columns">'.$lang['gl_requests'].'<select name="request"><option class="body" value="0"></option>';

if ($res_request) {
    while ($arr_request = mysqli_fetch_assoc($res_request)) {
        $request.= '<option aria-describedby="requestHelpText" value="' . (int)$arr_request['id'] . '">' . htmlsafechars($arr_request['request_name']) . '</option>';
    }
} else {
    $request.= '<option class="body" value="0">'.$lang['upload_add_noreq'].'</option>';
}
$request.= '</select><p class="help-text" id="requestHelpText">'.$lang['upload_add_fill'].'</div> ';
$HTMLOUT.=$request;
$HTMLOUT.=$rg;
//=== offers list if member has made any offers
$res_offer = sql_query('SELECT id, offer_name FROM offers WHERE offered_by_user_id = ' . sqlesc($CURUSER['id']) . ' AND status = \'approved\' ORDER BY offer_name ASC');
if (mysqli_num_rows($res_offer) > 0) {
    $offers = '  
   <div class="row"><div class="small-12 columns"><select name="offer"><option class="body" value="0"></option>';
    $message = '<option value="0">'.$lang['upload_add_offer'].'</option>';
    while ($arr_offer = mysqli_fetch_assoc($res_offer)) {
        $offers.= '<option class="body" value="' . (int)$arr_offer['id'] . '">' . htmlsafechars($arr_offer['offer_name']) . '</option>';
    }
    $offers.= '</select></div></div>'.$lang['upload_add_offer2'].'';
}
$HTMLOUT.= $offers;
$HTMLOUT.= "</div>";
if ($CURUSER['class'] >= UC_UPLOADER AND OCELOT_TRACKER == false) {
    $HTMLOUT.= "<div class='row'>
<div class='medium-6 large-4 columns'>{$lang['upload_add_free']}  
    <select name='free_length'>
    <option value='0'>{$lang['upload_add_nofree']}</option>
    <option value='42'>{$lang['upload_add_day1']}</option>
    <option value='1'>{$lang['upload_add_week1']}</option>
    <option value='2'>{$lang['upload_add_week2']}</option>
    <option value='4'>{$lang['upload_add_week4']}</option>
    <option value='8'>{$lang['upload_add_week8']}</option>
    <option value='255'>{$lang['upload_add_unltd']}</option>
    </select></div>";
    $HTMLOUT.= "<div class='medium-6 large-4 columns'>{$lang['upload_add_silv']}   
    <select name='half_length'>
    <option value='0'>{$lang['upload_add_nosilv']}</option>
    <option value='42'>{$lang['upload_add_sday1']}</option>
    <option value='1'>{$lang['upload_add_sweek1']}</option>
    <option value='2'>{$lang['upload_add_sweek2']}</option>
    <option value='4'>{$lang['upload_add_sweek4']}</option>
    <option value='8'>{$lang['upload_add_sweek8']}</option>
    <option value='255'>{$lang['upload_add_unltd']}</option>
    </select></div></div>";
    $HTMLOUT.= "<div class='row'>
	<div class='medium-6 large-4 columns'>
	<fieldset class='fieldset'>
		<legend>{$lang['upload_add_vip']}</legend>
		<input type='checkbox' name='vip' value='1' class='label' aria-describedby='vipHelpText'>
		<p class='help-text' id='vipHelpText'>{$lang['upload_add_vipchk']}</p>
	</fieldset>
	</div>";
}
$subs_list.= "";
$i = 0;
foreach ($subs as $s) {
    $subs_list.= ($i && $i % 4 == 0) ? "" : "";
    $subs_list.= "<input class='checkbox' name='subs[]' type='checkbox' value='" . (int)$s["id"] . "' id='checkbox" . (int)$s["id"] . "'><label for='checkbox" . (int)$s["id"] ."'>" . htmlsafechars($s["name"]) . "</label>";
    ++$i;
}
$subs_list.= "";
$HTMLOUT.= "<div class='medium-6 large-4 columns'>
	<fieldset class='fieldset'><legend>{$lang['upload_add_sub']}</legend>
	$subs_list
	</fieldset>
</div>";
//== 09 Genre mod no mysql by Traffic
$HTMLOUT.= "<div class='medium-6 large-4 columns'>
<fieldset class='fieldset'>
	<legend>{$lang['upload_add_genre']}</legend><p>
    <input type='radio' name='genre' value='movie' id='genreMovie'>
		<label for='genreMovie'>{$lang['upload_add_movie']}</label>
    <input type='radio' name='genre' value='music' id='genreMusic'>
		<label for='genreMusic'>{$lang['upload_add_music']}</label>
    <input type='radio' name='genre' value='game' id='genreGame'>
		<label for='genreGame'>{$lang['upload_add_game']}</label>
    <input type='radio' name='genre' value='apps' id='genreApps'>
		<label for='genreApps'>{$lang['upload_add_apps']}</label>
    <input type='radio' name='genre' value='' checked='checked'>{$lang['upload_add_none']}
   </p>
    
    <p>
    <label>
    <input type='hidden' class='Depends on genre being movie or genre being music'></label>";
$movie = array(
    $lang['movie_mv1'],
    $lang['movie_mv2'],
    $lang['movie_mv3'],
    $lang['movie_mv4'],
    $lang['movie_mv5'],
    $lang['movie_mv6'],
    $lang['movie_mv7'],
);
for ($x = 0; $x < count($movie); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$movie[$x]'  name='movie[]' class='DEPENDS ON genre BEING movie'>$movie[$x]</label>";
}
$music = array(
    $lang['music_m1'],
    $lang['music_m2'],
    $lang['music_m3'],
    $lang['music_m4'],
    $lang['music_m5'],
    $lang['music_m6'],
);
for ($x = 0; $x < count($music); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$music[$x]' name='music[]' class='DEPENDS ON genre BEING music'>$music[$x]</label>";
}
$game = array(
    $lang['game_g1'],
    $lang['game_g2'],
    $lang['game_g3'],
    $lang['game_g4'],
    $lang['game_g5'],
);
for ($x = 0; $x < count($game); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$game[$x]' name='game[]' class='DEPENDS ON genre BEING game'>$game[$x]</label>";
}
$apps = array(
    $lang['app_mv1'],
    $lang['app_mv2'],
    $lang['app_mv3'],
    $lang['app_mv4'],
    $lang['app_mv5'],
    $lang['app_mv6'],
    $lang['app_mv7'],
);
for ($x = 0; $x < count($apps); $x++) {
    $HTMLOUT.= "<label><input type='checkbox' value='$apps[$x]' name='apps[]' class='DEPENDS ON genre BEING apps'>$apps[$x]</label>";
}
$HTMLOUT.= "</p></fieldset></div></div>";
//== End
$HTMLOUT.="<div class='row'>";
$HTMLOUT.= "<div class='medium-6 large-4 columns'>
	<fieldset class='fieldset'>
		<legend>{$lang['upload_anonymous']}</legend>
		<input type='checkbox' name='uplver' value='yes' id='chk1' aria-describedby='anonymousHelpText'>
		<p class='help-text' id='anonymousHelpText'>{$lang['upload_anonymous1']}</p>
	</fieldset>
</div>";
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT.= "<div class='medium-6 large-4 columns'>
	<fieldset class='fieldset'>
		<legend>{$lang['upload_comment']}</legend>
		<input type='checkbox' name='allow_commentd' value='yes'  aria-describedby='commentHelpText'>
		<p class='help-text' id='commentHelpText'>{$lang['upload_discom1']}</p>
	</fieldset>
</div>";
}
$HTMLOUT.= "<div class='medium-6 large-4 columns'>
	<fieldset class='fieldset'>
		<legend>{$lang['upload_add_ascii']}</legend>
		<input type='checkbox' name='strip' value='strip' checked='checked' aria-describedby='asciiHelpText'>
		<a href='http://en.wikipedia.org/wiki/ASCII_art' target='_blank'>
			<p class='help-text' id='asciiHelpText'>{$lang['upload_add_wascii']}</p>
		</a>
	</fieldset>
</div></div>";

if (OCELOT_TRACKER == true) {
        $HTMLOUT.= "<fieldset class='fieldset'>{$lang['upload_add_free']}<input type='checkbox' name='freetorrent' value='1'>{$lang['upload_add_freeinf']}</div>";
    }
$HTMLOUT.= "<div class='row'><div class='small-12 columns'><input type='submit' class='button float-right' value='{$lang['upload_submit']}'></div></div>";
$HTMLOUT.= "</form>";
////////////////////////// HTML OUTPUT //////////////////////////
echo stdhead($lang['upload_stdhead'], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
